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

if ! kubectl cluster-info >/dev/null 2>&1; then
  echo "❌ Cannot reach cluster. Fix kubeconfig, e.g.:"
  echo "   mkdir -p ~/.kube && sudo cp /etc/rancher/k3s/k3s.yaml ~/.kube/config && sudo chown \"\$USER:\$USER\" ~/.kube/config"
  exit 1
fi

echo "📦 kubectl apply -k k8s/"
kubectl apply -k k8s/

echo "⏳ Waiting for MySQL StatefulSet..."
kubectl rollout status "statefulset/mondals-db" -n "$NS" --timeout=300s

if [ "$SKIP_MIGRATE" = false ]; then
  echo "🛠️  Running migrations (Job)..."
  kubectl delete job mondals-migrate -n "$NS" --ignore-not-found=true
  kubectl apply -f k8s/migrate-job.yaml
  kubectl wait --for=condition=complete "job/mondals-migrate" -n "$NS" --timeout=300s
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
