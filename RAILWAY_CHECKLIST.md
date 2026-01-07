# Railway Setup Checklist - IMPORTANT

Jika mendapat error "Connection refused", ikuti checklist ini:

## Step 1: Add MySQL Service

-   [ ] Di Railway Dashboard, buka Project
-   [ ] Click **+ New Service**
-   [ ] Pilih **MySQL**
-   [ ] Wait sampai MySQL service online (status: green)

## Step 2: Link MySQL to Web Service

-   [ ] Di MySQL service, click **Settings** (gear icon)
-   [ ] Copy DATABASE_URL (format: `mysql://user:pass@host:port/db`)
-   [ ] Go ke **Web** service → **Settings** → **Environment** tab
-   [ ] Add variable: `DATABASE_URL` = paste value

**OR** (Automatic linking):

-   [ ] Right-click MySQL service
-   [ ] Select "Connect"
-   [ ] Railway otomatis add DATABASE_URL ke web service

## Step 3: Set Required Variables

Di **Web Service → Variables** tab, add:

-   [ ] `APP_ENV=production`
-   [ ] `APP_DEBUG=false`
-   [ ] `APP_KEY=base64:NbRDebOtjJ3U9eegYMXBb77MSlAE/U54U9lkDeCPJng=`

## Step 4: Verify Environment

-   [ ] Di **Variables** tab, pastikan **DATABASE_URL** ada
-   [ ] Click **Redeploy** button
-   [ ] Go ke **Deployments** → View Logs
-   [ ] Look untuk "Migrations complete!" message

## Troubleshooting

**Jika masih Connection refused:**

1. Check MySQL service status (harus green/running)
2. Check DATABASE_URL ada di variables
3. Redeploy lagi
4. Check deployment logs untuk error message

**Jika migration gagal:**

1. Check log message, catat error
2. Bisa jadi database sudah ada table, try manual migration
3. Atau drop semua table di MySQL, redeploy

## Test di Local

```bash
# Pastikan lokal .env punya:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_DATABASE=todo_app

php artisan migrate
php artisan serve
```

Jika lokal berhasil tapi Railway tidak, berarti issuenya di Railway setup/variables.
