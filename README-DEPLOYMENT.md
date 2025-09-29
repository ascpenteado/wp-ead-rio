# Docker Deployment to DigitalOcean

This project uses Docker for production deployment with a multi-stage build that includes your compiled theme assets.

## Required GitHub Secrets

Set these in your GitHub repository settings (Settings → Secrets and variables → Actions):

```
DO_REGISTRY_TOKEN=your_do_registry_token
DO_REGISTRY_NAME=your_registry_name
DO_HOST=your_droplet_ip
DO_USERNAME=your_droplet_username
DO_SSH_KEY=your_private_ssh_key
DO_PORT=22
```

## Droplet Setup

1. **Run the setup script on your droplet:**
   ```bash
   curl -sSL https://raw.githubusercontent.com/YOUR_USERNAME/wp-ead-rio/main/deploy.sh | bash
   ```

2. **Edit environment variables:**
   ```bash
   cd /opt/wp-ead-rio
   nano .env  # Update database passwords and registry path
   ```

3. **Login to DigitalOcean Container Registry:**
   ```bash
   docker login registry.digitalocean.com
   ```

4. **Start the application:**
   ```bash
   docker-compose -f docker-compose.prod.yml up -d
   ```

## Local Development vs Production

- **Development**: Use DDEV (`ddev start`)
- **Production**: Uses official WordPress Docker image
- **Assets**: Built during Docker image creation, included in `dist/` folder

## Deployment Process

1. Push to `main` branch triggers GitHub Action
2. Builds Docker image with compiled theme assets
3. Pushes to DigitalOcean Container Registry
4. SSH to droplet and updates running containers
5. Zero-downtime deployment with Docker Compose

## Manual Deployment

If needed, you can manually deploy:

```bash
# On your droplet
cd /opt/wp-ead-rio
docker-compose -f docker-compose.prod.yml pull
docker-compose -f docker-compose.prod.yml up -d --no-deps wordpress
```

## Monitoring

Check deployment status:
```bash
docker-compose -f docker-compose.prod.yml ps
docker-compose -f docker-compose.prod.yml logs wordpress
```