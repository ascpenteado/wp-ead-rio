# Railway Deployment Guide

This project is ready to be deployed on [Railway](https://railway.app/).

## Prerequisites

1.  A Railway account.
2.  The Railway CLI installed (optional, but recommended).

## Deployment Steps

1.  **New Project**: Create a new project on Railway.
2.  **Database**: Add a MySQL (or MariaDB) service to your project.
3.  **Deploy Code**: Deploy this repository to Railway.

## Environment Variables

You **MUST** configure the following environment variables in your Railway service settings for the WordPress application.

### Database Connection

Use the variables provided by your Railway MySQL service:

- `WORDPRESS_DB_HOST`: `${{MySQL.MYSQLHOST}}:${{MySQL.MYSQLPORT}}`
- `WORDPRESS_DB_USER`: `${{MySQL.MYSQLUSER}}`
- `WORDPRESS_DB_PASSWORD`: `${{MySQL.MYSQLPASSWORD}}`
- `WORDPRESS_DB_NAME`: `${{MySQL.MYSQLDATABASE}}`

### SSL Termination (CRITICAL)

Railway terminates SSL at the load balancer. To prevent infinite redirect loops, you must add this variable:

- `WORDPRESS_CONFIG_EXTRA`:
  ```php
  define('DOMAIN_CURRENT_SITE','${{RAILWAY_PUBLIC_DOMAIN}}');
  define('WP_HOME','https://${{RAILWAY_PUBLIC_DOMAIN}}');
  define('WP_SITEURL','https://${{RAILWAY_PUBLIC_DOMAIN}}');
  if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) { $_SERVER['HTTPS'] = 'on'; }
  ```
  _Note: Copy the entire PHP snippet above into the value field._

### Other Settings

- `WORDPRESS_TABLE_PREFIX`: `wp_` (or your preferred prefix)
- `WORDPRESS_DEBUG`: `false`

## Build & Deploy

Railway will automatically detect the `Dockerfile` and build your application.

- The build process includes compiling the theme assets (Node.js build).
- The final image is based on the official WordPress image.
