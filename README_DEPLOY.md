# Ubuntu Server Deployment Guide 🚀
Mondal's E-Commerce Platform

This guide covers the deployment of the Mondal's E-Commerce engine to a **fresh Ubuntu server** using the Docker/Kubernetes files I've already created.

---

## 1️⃣ Quick Deployment (Docker Compose)
This is the **fastest** and most reliable way to host on a single Ubuntu server.

### A. Automatic Server Setup
1. Copy the project folder to your server (`/var/www/mondals-ecommerce`).
2. Run the server setup script:
   ```bash
   chmod +x scripts/setup-server.sh
   ./scripts/setup-server.sh
   ```
3. **Logout and log back in** (to enable your user for Docker).

### B. Deployment
1. Navigate to the project folder.
2. Initialize your production environment:
   ```bash
   cp .env.example .env
   # Update .env with your production values (APP_URL, DB_PASSWORD, etc.)
   ```
3. Run the automated deployment script:
   ```bash
   chmod +x scripts/deploy.sh
   ./scripts/deploy.sh
   ```

---

## 2️⃣ Scalable deployment (Kubernetes)

Edit **`k8s/config-secret.yaml`**: set a real **`APP_KEY`** (not the `CHANGE_ME` placeholder) and **`DB_PASSWORD`** if needed.

**Image:** manifests use **`mondals-app:latest`**. On the K3s node, load it into containerd once (or push to your registry and change the image name):

```bash
cd /path/to/mondals-ecommerce
docker build -t mondals-app:latest .
docker save mondals-app:latest | sudo k3s ctr images import -
```

Or **`MONDALS_K8S_BUILD_IMAGE=1 ./scripts/k8s-up.sh`** to build and import before apply.

**One command** (from repo root or `scripts/`: finds repo root automatically):

```bash
chmod +x scripts/k8s-up.sh
./scripts/k8s-up.sh
```

This runs **`kubectl apply -k k8s/`**, waits for MySQL + migrate Job + app, then prints pod status. Infra-only refresh: **`./scripts/k8s-up.sh --skip-migrate`**.

Manual steps (same as the script):

```bash
kubectl apply -k k8s/
kubectl apply -f k8s/migrate-job.yaml   # delete job mondals-migrate first if it already ran
kubectl get pods -n mondals-ecommerce
```

### MicroK8s
```bash
sudo snap install microk8s --classic
sudo usermod -aG microk8s $USER
# Log out and back in
microk8s status --wait-ready
microk8s enable dns storage ingress
```
Use **`microk8s kubectl`** instead of **`kubectl`** in the commands above (or `alias kubectl=microk8s kubectl`).

### K3s
```bash
curl -sfL https://get.k3s.io | sh -
```
If **`kubectl cluster-info`** fails but **`sudo k3s kubectl get nodes`** works, **`scripts/k8s-up.sh`** will use **`sudo k3s kubectl`** automatically.

By default **`/etc/rancher/k3s/k3s.yaml`** is root-only. Use a copy you own (fixes *permission denied* / *Unable to read k3s.yaml*):

```bash
mkdir -p ~/.kube
sudo cp /etc/rancher/k3s/k3s.yaml ~/.kube/config
sudo chown "$USER:$USER" ~/.kube/config
```

Optional (new installs): allow group read on the server config — install with  
`curl -sfL https://get.k3s.io | INSTALL_K3S_EXEC="server --write-kubeconfig-mode 644" sh -`  
or add your user to a group via **`--write-kubeconfig-group`** (see [K3s kubeconfig](https://docs.k3s.io/cluster-access)).

---

## 🛠️ Maintenance & Troubleshooting

### To Check Logs:
```bash
docker compose logs -f app          # Docker Compose
kubectl logs -f -l app=mondals-app -n mondals-ecommerce   # K8s (after kubeconfig fix above)
```

### To Enter the Container:
```bash
docker exec -it mondals-app bash     # Docker Compose
kubectl exec -it deployment/mondals-app -n mondals-ecommerce -- bash   # K8s
```

### Force Migration:
```bash
docker exec mondals-app php artisan migrate --force
```

---
<p align="center">
  <b>Mondal's E-Commerce Architecture</b> | <i>Built for Production Stability</i>
</p>
