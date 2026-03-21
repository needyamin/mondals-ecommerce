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

## 2️⃣ Scalable Deployment (MicroK8s / K3s)
If you prefer the **Kubernetes (K8s)** manifests I've created:

1. Install a lightweight K8s on your Ubuntu server:
   ```bash
   sudo snap install microk8s --classic
   sudo usermod -aG microk8s $USER
   # Logout / Login
   microk8s status --wait-ready
   microk8s enable dns storage ingress
   ```
2. Apply the Mondal manifests:
   ```bash
   microk8s kubectl apply -f k8s/db.yaml
   microk8s kubectl apply -f k8s/deployment.yaml
   ```
3. Monitor your pods:
   ```bash
   microk8s kubectl get pods -n mondals-ecommerce
   ```

---

## 🛠️ Maintenance & Troubleshooting

### To Check Logs:
```bash
docker compose logs -f app          # Docker Compose
microk8s kubectl logs -f -l app=mondals-app -n mondals-ecommerce  # Kubernetes
```

### To Enter the Container:
```bash
docker exec -it mondals-app bash     # Docker Compose
microk8s kubectl exec -it deployment/mondals-app -n mondals-ecommerce -- bash  # K8s
```

### Force Migration:
```bash
docker exec mondals-app php artisan migrate --force
```

---
<p align="center">
  <b>Mondal's E-Commerce Architecture</b> | <i>Built for Production Stability</i>
</p>
