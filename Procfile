web: php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
release: php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan storage:link && php artisan migrate --force
