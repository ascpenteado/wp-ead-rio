# Multi-stage build for WordPress production deployment
FROM node:20-alpine AS builder

# Set working directory for theme build
WORKDIR /app/theme

# Copy theme files
COPY web/wp-content/themes/ead-rio/package.json web/wp-content/themes/ead-rio/pnpm-lock.yaml ./
COPY web/wp-content/themes/ead-rio/src ./src/
COPY web/wp-content/themes/ead-rio/tsconfig.json ./

# Install pnpm and dependencies
RUN npm install -g pnpm
RUN pnpm install --frozen-lockfile

# Build production assets
RUN pnpm run build

# Production stage
FROM wordpress:6.6-php8.3-apache

# Copy WordPress files
COPY web/ /var/www/html/

# Copy built theme from builder stage (includes compiled assets + PHP files)
COPY --from=builder /app/theme/dist/ /var/www/html/wp-content/themes/ead-rio/dist/

# Remove development files that shouldn't be in production
RUN rm -rf /var/www/html/wp-content/themes/ead-rio/src \
    /var/www/html/wp-content/themes/ead-rio/node_modules \
    /var/www/html/wp-content/themes/ead-rio/package.json \
    /var/www/html/wp-content/themes/ead-rio/pnpm-lock.yaml \
    /var/www/html/wp-content/themes/ead-rio/tsconfig.json \
    /var/www/html/wp-content/themes/ead-rio/*.md

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

# Configure Apache to respect the PORT provided by the platform (Railway)
ENV PORT=8080
COPY docker/railway-entrypoint.sh /usr/local/bin/railway-entrypoint.sh
RUN chmod +x /usr/local/bin/railway-entrypoint.sh
ENTRYPOINT ["/usr/local/bin/railway-entrypoint.sh"]
CMD ["apache2-foreground"]

# Expose default port (overridden by PORT at runtime)
EXPOSE 8080
