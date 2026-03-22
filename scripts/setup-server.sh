#!/bin/bash
# ⚡ Mondal's E-Commerce Server Setup Script ⚡
# Automates Docker & Docker-Compose installation on Ubuntu 20.04/22.04/24.04

set -euo pipefail

echo "🚀 Starting Mondal's Server Setup..."

# ── 1. Update system packages ──
echo "📦 Updating system packages..."
sudo apt-get update && sudo apt-get upgrade -y

# ── 2. Install essential dependencies ──
echo "🔧 Installing dependencies..."
sudo apt-get install -y \
    ca-certificates \
    curl \
    gnupg \
    lsb-release \
    git \
    ufw \
    mysql-client \
    jq

# ── 3. Setup Docker Official repository ──
echo "🐳 Setting up Docker repository..."
sudo mkdir -p /etc/apt/keyrings

# Remove existing GPG key to avoid interactive "Overwrite?" prompt
if [ -f /etc/apt/keyrings/docker.gpg ]; then
    sudo rm -f /etc/apt/keyrings/docker.gpg
fi

curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
$(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# ── 4. Install Docker Engine and Compose plugin ──
echo "📥 Installing Docker Engine..."
sudo apt-get update
sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# ── 5. Configure Docker daemon (prevent storage-driver issues) ──
echo "⚙️  Configuring Docker daemon..."
sudo mkdir -p /etc/docker
if [ ! -f /etc/docker/daemon.json ]; then
    sudo tee /etc/docker/daemon.json > /dev/null <<EOF
{
    "storage-driver": "overlay2",
    "log-driver": "json-file",
    "log-opts": {
        "max-size": "10m",
        "max-file": "3"
    }
}
EOF
fi

# ── 6. Fix potential masked service issue and start Docker ──
echo "🔄 Starting Docker service..."
sudo systemctl unmask docker.service 2>/dev/null || true
sudo systemctl unmask docker.socket 2>/dev/null || true

# Stop any existing broken Docker processes
sudo systemctl stop docker 2>/dev/null || true
sudo rm -f /var/run/docker.pid 2>/dev/null || true

# Reload and restart cleanly
sudo systemctl daemon-reload
sudo systemctl enable docker
sudo systemctl start docker

# ── 7. Verify Docker is running ──
echo "🔍 Verifying Docker installation..."
MAX_RETRIES=5
RETRY_COUNT=0
while ! sudo docker info > /dev/null 2>&1; do
    RETRY_COUNT=$((RETRY_COUNT + 1))
    if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
        echo "❌ Docker failed to start after $MAX_RETRIES attempts."
        echo "🔍 Diagnostic information:"
        echo "---"
        sudo systemctl status docker.service --no-pager || true
        echo "---"
        sudo journalctl -xeu docker.service --no-pager | tail -30 || true
        echo "---"
        echo "💡 Try: sudo rm -rf /var/lib/docker && sudo systemctl restart docker"
        exit 1
    fi
    echo "   Waiting for Docker to start... (attempt $RETRY_COUNT/$MAX_RETRIES)"
    sleep 3
done

echo "✅ Docker is running!"
sudo docker --version
sudo docker compose version

# ── 8. Add current user to Docker group ──
if ! groups "$USER" | grep -q docker; then
    sudo usermod -aG docker "$USER"
    echo "👤 Added $USER to docker group."
fi

# ── 9. Configure Firewall (UFW) ──
echo "🔒 Configuring firewall..."
sudo ufw allow ssh
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw allow 8000/tcp
sudo ufw --force enable

echo ""
echo "============================================"
echo "  ✅ SERVER SETUP COMPLETE!"
echo "============================================"
echo ""
echo "  Docker:  $(sudo docker --version)"
echo "  Compose: $(sudo docker compose version)"
echo ""
echo "  ⚠️  IMPORTANT: Log out and log back in"
echo "     to use Docker without 'sudo'."
echo ""
echo "  Next step: ./scripts/deploy.sh"
echo "============================================"
