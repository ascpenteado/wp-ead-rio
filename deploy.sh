#!/bin/bash

# Deployment script for DigitalOcean droplet
# Run this script on your droplet to set up the initial deployment

set -e

# Configuration
PROJECT_DIR="/opt/wp-ead-rio"
COMPOSE_FILE="docker-compose.prod.yml"

echo "🚀 Setting up WordPress deployment on DigitalOcean droplet..."

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker not found. Installing Docker..."
    curl -fsSL https://get.docker.com -o get-docker.sh
    sh get-docker.sh
    rm get-docker.sh

    # Add current user to docker group
    sudo usermod -aG docker $USER
    echo "✅ Docker installed. Please log out and back in for group changes to take effect."
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose not found. Installing..."
    sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
    sudo chmod +x /usr/local/bin/docker-compose
    echo "✅ Docker Compose installed."
fi

# Create project directory
echo "📁 Creating project directory..."
sudo mkdir -p $PROJECT_DIR
sudo chown $USER:$USER $PROJECT_DIR
cd $PROJECT_DIR

# Download production docker-compose file
if [ ! -f $COMPOSE_FILE ]; then
    echo "📥 Downloading docker-compose.prod.yml..."
    curl -o $COMPOSE_FILE https://raw.githubusercontent.com/YOUR_USERNAME/wp-ead-rio/main/docker-compose.prod.yml
fi

# Create environment file if it doesn't exist
if [ ! -f .env ]; then
    echo "⚙️ Creating environment file..."
    cat > .env << EOF
# Production Environment Variables
DOCKER_IMAGE=registry.digitalocean.com/YOUR_REGISTRY/wp-ead-rio:latest

# Database Configuration - CHANGE THESE VALUES!
DB_NAME=wp_ead_rio
DB_USER=wp_user
DB_PASSWORD=$(openssl rand -base64 32)
DB_ROOT_PASSWORD=$(openssl rand -base64 32)
TABLE_PREFIX=wp_

# WordPress Configuration
WP_DEBUG=false
EOF
    echo "✅ Environment file created. Please edit .env with your values!"
    echo "📝 Especially update the DOCKER_IMAGE registry path and database passwords."
fi

# Login to DigitalOcean Container Registry
echo "🔐 To complete setup, login to your DigitalOcean Container Registry:"
echo "   docker login registry.digitalocean.com"
echo ""
echo "📚 Next steps:"
echo "1. Edit .env file with your configuration"
echo "2. Login to DO Container Registry: docker login registry.digitalocean.com"
echo "3. Start the application: docker-compose -f $COMPOSE_FILE up -d"
echo "4. Check status: docker-compose -f $COMPOSE_FILE ps"
echo ""
echo "🎉 Setup complete! Your project is ready at $PROJECT_DIR"