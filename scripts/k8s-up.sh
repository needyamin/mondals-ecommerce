#!/bin/bash
# Apply all k8s manifests, wait for MySQL + app, run migrate Job (optional skip).
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
NS="mondals-ecommerce"
SKIP_MIGRATE=false

for arg in "$@"; do
  case "$arg" in
    --skip-migrate) SKIP_MIGRATE=true ;;
  esac
done

cd "$ROOT"

echo ""
echo "☸️  Mondals K8s — apply stack (namespace: $NS)"
echo "   repo: $ROOT"
echo ""

if ! command -v kubectl >/dev/null 2>&1; then
  echo "❌ kubectl not found. Install K3s/MicroK8s or kubectl."
  exit 1
fi

k3s_admin="/etc/rancher/k3s/k3s.yaml"
kube_conf="${HOME}/.kube/config"

if ! kubectl cluster-info >/dev/null 2>&1; then
  if [ -f "$k3s_admin" ] && [ "${MONDALS_K8S_NO_KUBECONFIG_SYNC:-}" != "1" ]; then
    echo "🔑 Syncing K3s admin kubeconfig → $kube_conf (sudo)..."
    mkdir -p "${HOME}/.kube"
    sudo cp -f "$k3s_admin" "$kube_conf"
    sudo chown "$(id -u):$(id -g)" "$kube_conf"
  fi
fi

if ! kubectl cluster-info >/dev/null 2>&1; then
  echo "❌ Cannot reach cluster."
  if [ -f "$k3s_admin" ]; then
    echo "   sudo cp -f $k3s_admin $kube_conf && sudo chown \"\$USER:\$USER\" $kube_conf"
    echo "   (skip auto-sync: MONDALS_K8S_NO_KUBECONFIG_SYNC=1 ./scripts/k8s-up.sh)"
  fi
  if command -v microk8s >/dev/null 2>&1; then
    echo "   MicroK8s: microk8s kubectl apply -k k8s/"
  fi
  exit 1
fi

if [ "${MONDALS_K8S_BUILD_IMAGE:-}" = "1" ]; then
  echo "🐳 docker build -t mondals-app:latest + import into cluster..."
  docker build -t mondals-app:latest "$ROOT"
  if sudo k3s ctr version >/dev/null 2>&1; then
    docker save mondals-app:latest | sudo k3s ctr images import -
  elif command -v microk8s >/dev/null 2>&1; then
    docker save mondals-app:latest | microk8s ctr images import -
  else
    echo "❌ No k3s/microk8s ctr; import manually after docker save."
    exit 1
  fi
fi

echo "📦 kubectl apply -k k8s/"
kubectl apply -k k8s/

echo "⏳ Waiting for MySQL StatefulSet..."
kubectl rollout status "statefulset/mondals-db" -n "$NS" --timeout=300s

if [ "$SKIP_MIGRATE" = false ]; then
  echo "🛠️  Running migrations (Job)..."
  kubectl delete job mondals-migrate -n "$NS" --ignore-not-found=true
  kubectl apply -f k8s/migrate-job.yaml
  JOB_WAIT=600
  if ! kubectl wait --for=condition=complete "job/mondals-migrate" -n "$NS" --timeout="${JOB_WAIT}s"; then
    echo ""
    echo "❌ Migrate job did not finish. Check image + APP_KEY in k8s/config-secret.yaml"
    echo "   Pods:"
    kubectl get pods -n "$NS" -l job-name=mondals-migrate -o wide 2>/dev/null || true
    echo "   Logs (migrate):"
    kubectl logs -n "$NS" -l job-name=mondals-migrate -c migrate --tail=100 2>/dev/null || true
    echo "   Logs (init):"
    kubectl logs -n "$NS" -l job-name=mondals-migrate -c wait-for-mysql --tail=50 2>/dev/null || true
    echo "   Describe job:"
    kubectl describe job mondals-migrate -n "$NS" | tail -35
    echo ""
    echo "   Load image on K3s: docker build -t mondals-app:latest . && docker save mondals-app:latest | sudo k3s ctr images import -"
    echo "   Or one-shot: MONDALS_K8S_BUILD_IMAGE=1 ./scripts/k8s-up.sh"
    exit 1
  fi
else
  echo "⏭️  Skipping migrate job (--skip-migrate)"
fi

echo "⏳ Waiting for app Deployment..."
kubectl rollout status "deployment/mondals-app" -n "$NS" --timeout=300s

echo ""
echo "============================================"
echo "  ✅ K8s stack is up"
echo "============================================"
echo "  kubectl get pods -n $NS"
echo "  kubectl logs -f deployment/mondals-app -n $NS"
echo "============================================"
kubectl get pods -n "$NS"
