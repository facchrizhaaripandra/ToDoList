# Railway Deployment Setup

## Prerequisites

-   Railway account
-   MySQL plugin linked to your Railway project
-   GitHub repository connected

## Environment Variables Required

Set these in **Railway Dashboard → Variables** tab:

```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:NbRDebOtjJ3U9eegYMXBb77MSlAE/U54U9lkDeCPJng=
LOG_LEVEL=debug
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local
```

**Important Notes:**

-   `DATABASE_URL` will be **automatically injected** by Railway MySQL plugin (don't set manually)
-   `APP_KEY` must be the same value used in local development
-   Keep `APP_DEBUG=false` for production

## Database Connection

1. In Railway, add **MySQL** service to your project
2. MySQL plugin will automatically provide `DATABASE_URL` environment variable
3. App uses this URL to connect to database

## Deployment Process

When you push to GitHub:

1. Railway automatically detects changes
2. Runs `Procfile` release command:
    - Clears caches
    - Runs migrations
    - Links storage
3. Starts web server

## Troubleshooting 500 Errors

If you see 500 errors:

1. **Check Railway Logs:**

    - Open Railway Dashboard → Deployments → View Logs
    - Look for error messages

2. **Enable Debug Mode (temporary):**

    - Set `APP_DEBUG=true` in Railway Variables
    - Redeploy
    - Check logs for detailed error

3. **Common Issues:**
    - Missing `DATABASE_URL` → Check MySQL plugin is linked
    - Database migrations failed → Check logs
    - Permission issues → Check storage folder permissions

## Testing Locally

```bash
# Local development uses .env file
php artisan serve

# To simulate production locally:
# 1. Update .env with APP_ENV=production, APP_DEBUG=false
# 2. Run: php artisan config:cache && php artisan migrate
```

## After Deployment

1. Visit your Railway project URL
2. Test core features:
    - Create tasks
    - Drag & drop tasks
    - Mark complete
    - Delete tasks
3. Check database persists data
