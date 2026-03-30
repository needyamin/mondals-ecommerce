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

k3s_admin="/etc/rancher/k3s/k3s.yaml"
kube_conf="${HOME}/.kube/config"

have_kubectl() { command -v kubectl >/dev/null 2>&1; }
have_k3s_kubectl() { sudo k3s kubectl version --client >/dev/null 2>&1; }

if ! have_kubectl && ! have_k3s_kubectl && ! command -v microk8s >/dev/null 2>&1; then
  echo "❌ No kubectl. Install kubectl, K3s, or MicroK8s."
  exit 1
fi

if have_kubectl && ! kubectl cluster-info >/dev/null 2>&1; then
  if [ -f "$k3s_admin" ] && [ "${MONDALS_K8S_NO_KUBECONFIG_SYNC:-}" != "1" ]; then
    echo "🔑 Syncing K3s admin kubeconfig → $kube_conf (sudo)..."
    mkdir -p "${HOME}/.kube"
    sudo cp -f "$k3s_admin" "$kube_conf"
    sudo chown "$(id -u):$(id -g)" "$kube_conf"
  fi
fi

# Pick a client that can talk to the API (plain kubectl often breaks with K3s TLS/certs)
KUBECTL=(kubectl)
if have_kubectl && kubectl cluster-info >/dev/null 2>&1; then
  :
elif have_k3s_kubectl && sudo k3s kubectl cluster-info >/dev/null 2>&1; then
  KUBECTL=(sudo k3s kubectl)
  echo "ℹ️  Using: sudo k3s kubectl"
elif command -v microk8s >/dev/null 2>&1 && microk8s kubectl cluster-info >/dev/null 2>&1; then
  KUBECTL=(microk8s kubectl)
  echo "ℹ️  Using: microk8s kubectl"
else
  echo "❌ Cannot reach cluster API."
  if [ -f "$k3s_admin" ]; then
    echo "   Try: sudo k3s kubectl get nodes"
    echo "   Or fix kubeconfig: sudo cp -f $k3s_admin $kube_conf && sudo chown \"\$USER:\$USER\" $kube_conf"
  fi
  exit 1
fi

k() { "${KUBECTL[@]}" "$@"; }

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

echo "📦 apply -k k8s/"
k apply -k k8s/

echo "⏳ Waiting for MySQL StatefulSet..."
k rollout status "statefulset/mondals-db" -n "$NS" --timeout=300s

if [ "$SKIP_MIGRATE" = false ]; then
  echo "🛠️  Running migrations (Job)..."
  k delete job mondals-migrate -n "$NS" --ignore-not-found=true
  k apply -f k8s/migrate-job.yaml
  JOB_WAIT=600
  if ! k wait --for=condition=complete "job/mondals-migrate" -n "$NS" --timeout="${JOB_WAIT}s"; then
    echo ""
    echo "❌ Migrate job did not finish. Check image + APP_KEY in k8s/config-secret.yaml"
    echo "   Pods:"
    k get pods -n "$NS" -l job-name=mondals-migrate -o wide 2>/dev/null || true
    echo "   Logs (migrate):"
    k logs -n "$NS" -l job-name=mondals-migrate -c migrate --tail=100 2>/dev/null || true
    echo "   Logs (init):"
    k logs -n "$NS" -l job-name=mondals-migrate -c wait-for-mysql --tail=50 2>/dev/null || true
    echo "   Describe job:"
    k describe job mondals-migrate -n "$NS" | tail -35
    echo ""
    echo "   Load image on K3s: docker build -t mondals-app:latest . && docker save mondals-app:latest | sudo k3s ctr images import -"
    echo "   Or one-shot: MONDALS_K8S_BUILD_IMAGE=1 ./scripts/k8s-up.sh"
    exit 1
  fi
else
  echo "⏭️  Skipping migrate job (--skip-migrate)"
fi

echo "⏳ Waiting for app Deployment..."
k rollout status "deployment/mondals-app" -n "$NS" --timeout=300s

echo ""
echo "============================================"
echo "  ✅ K8s stack is up"
echo "============================================"
echo "  ${KUBECTL[*]} get pods -n $NS"
echo "  ${KUBECTL[*]} logs -f deployment/mondals-app -n $NS"
echo "============================================"
k get pods -n "$NS"
