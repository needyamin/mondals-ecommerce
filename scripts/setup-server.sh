#!/bin/bash
# ⚡ Mondal's E-Commerce Server Setup Script ⚡
# Automates Docker & Docker-Compose installation on Ubuntu 20.04/22.04/24.04

set -e

echo "🚀 Starting Mondal's Server Setup..."

# 1. Update system packages
sudo apt-get update && sudo apt-get upgrade -y

# 2. Install essential dependencies
sudo apt-get install -y \
    ca-certificates \
    curl \
    gnupg \
    lsb-release \
    git \
    ufw

# 3. Setup Docker Official repository
sudo mkdir -p /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
$(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# 4. Install Docker Engine and Compose plugin
sudo apt-get update
sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# 5. Fix potential "masked service" issue and Enable Docker to start on boot
sudo systemctl unmask docker.service || true
sudo systemctl unmask docker.socket || true
sudo systemctl enable --now docker

# 6. Add current user to Docker group (requires logout/login)
sudo usermod -aG docker $USER

# 7. Configure Uncomplicated Firewall (UFW)
sudo ufw allow ssh
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw allow 8000/tcp
sudo ufw --force enable

echo "✅ Docker and Docker Compose installed successfully."
echo "⚠️ IMPORTANT: Please log out and log back in to use Docker without 'sudo'!"
