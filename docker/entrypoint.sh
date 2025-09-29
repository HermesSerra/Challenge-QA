#!/bin/bash
set -e

echo "🚀 Starting Challenge QA Application..."

# Create logs directory if not exists
mkdir -p /var/www/html/logs

# Set proper permissions
chown -R www-data:www-data /var/www/html/logs
chmod -R 777 /var/www/html/logs

# Install/update composer dependencies if composer.json exists
if [ -f "/var/www/html/composer.json" ]; then
    echo "📦 Installing/updating Composer dependencies..."
    composer install --optimize-autoloader
fi

php /var/www/html/vendor/bin/doctrine-migrations migrations:migrate --no-interaction

echo "🎯 Application ready! Access at http://localhost:8080"
echo "📊 Available endpoints:"
echo "  - POST /api/user/register - User registration"
echo "  - POST /api/user/login - User authentication"
echo "  - POST /api/calculator/simple-interest - Simple interest calculation"
echo "  - POST /api/calculator/compound-interest - Compound interest calculation"
echo "  - POST /api/calculator/installment - Installment simulation"

# Start Apache
exec "$@"