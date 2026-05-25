#!/bin/bash
set -e

# ==============================================
# Server Setup Script for LeadData Application
# Jalankan: bash server-setup.sh
# ==============================================

RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; NC='\033[0m'
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN} LeadData - Server Setup Script${NC}"
echo -e "${GREEN}========================================${NC}"

# --- Cek OS ---
if [ -f /etc/os-release ]; then
    . /etc/os-release
    OS=$ID
else
    echo -e "${RED}Tidak bisa deteksi OS${NC}"; exit 1
fi

if [ "$OS" != "ubuntu" ] && [ "$OS" != "debian" ]; then
    echo -e "${RED}Script ini hanya untuk Ubuntu/Debian. OS terdeteksi: $OS${NC}"
    exit 1
fi
echo -e "${GREEN}OS terdeteksi: $OS $VERSION_ID${NC}"

# --- Konfigurasi (ubah sesuai kebutuhan) ---
APP_PATH="/var/www/lead-data-app"
DB_NAME="lead_data"
DB_USER="lead_data_user"
DB_PASS="$(openssl rand -base64 16)"
APP_DOMAIN="10.10.10.106"
GIT_REPO="https://github.com/marketingbranding/lead-data-app.git"

echo ""
echo -e "${YELLOW}Konfigurasi:${NC}"
echo "  App path : $APP_PATH"
echo "  Domain   : $APP_DOMAIN"
echo "  Database : $DB_NAME"
echo "  DB User  : $DB_USER"
echo "  DB Pass  : $DB_PASS"
echo "  Git Repo : $GIT_REPO"
echo ""

# --- 1. Update System ---
echo -e "${YELLOW}[1/12] Update system packages...${NC}"
apt update && apt upgrade -y

# --- 2. Install Nginx ---
echo -e "${YELLOW}[2/12] Install Nginx...${NC}"
apt install -y nginx
systemctl enable --now nginx

# --- 3. Install PHP 8.3 ---
echo -e "${YELLOW}[3/12] Install PHP 8.3 + extensions...${NC}"
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y php8.3-fpm php8.3-cli php8.3-common php8.3-mysql \
    php8.3-mbstring php8.3-xml php8.3-bcmath php8.3-gd php8.3-intl \
    php8.3-zip php8.3-curl php8.3-fileinfo
systemctl enable --now php8.3-fpm

# --- 4. Install Composer ---
echo -e "${YELLOW}[4/12] Install Composer...${NC}"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"

# --- 5. Install Node.js 20.x ---
echo -e "${YELLOW}[5/12] Install Node.js 20.x...${NC}"
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# --- 6. Install MariaDB ---
echo -e "${YELLOW}[6/12] Install MariaDB...${NC}"
apt install -y mariadb-server
systemctl enable --now mariadb

# --- 7. Setup Database ---
echo -e "${YELLOW}[7/12] Setup database...${NC}"
mysql -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';"
mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# --- 8. Install Supervisor ---
echo -e "${YELLOW}[8/12] Install Supervisor...${NC}"
apt install -y supervisor

# --- 9. Clone Repository ---
echo -e "${YELLOW}[9/12] Clone repository...${NC}"
rm -rf "$APP_PATH"
if [ -n "$GIT_TOKEN" ]; then
    REPO_AUTH="${GIT_REPO/https:\/\//https:\/\/${GIT_TOKEN}@}"
    git clone "$REPO_AUTH" "$APP_PATH"
else
    git clone "$GIT_REPO" "$APP_PATH"
fi
cd "$APP_PATH"

# --- 10. Setup Environment ---
echo -e "${YELLOW}[10/12] Setup environment...${NC}"
cp .env.example .env
sed -i "s/APP_ENV=.*/APP_ENV=production/" .env
sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/" .env
sed -i "s|APP_URL=.*|APP_URL=http://$APP_DOMAIN|" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASS/" .env

php artisan key:generate --force

# --- 11. Setup Laravel ---
echo -e "${YELLOW}[11/12] Setup Laravel...${NC}"
composer install --no-dev --optimize-autoloader --no-interaction
npm ci --no-optional
npm run build
php artisan storage:link
php artisan migrate --force
php artisan db:seed --force

# --- 12. Setup Nginx & Supervisor ---
echo -e "${YELLOW}[12/12] Setup Nginx & Supervisor...${NC}"

# Nginx config
cat > /etc/nginx/sites-available/lead-data <<NGINX
server {
    listen 80;
    server_name $APP_DOMAIN;
    root $APP_PATH/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php\$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
NGINX

ln -sf /etc/nginx/sites-available/lead-data /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx

# Supervisor config for queue worker
cat > /etc/supervisor/conf.d/laravel-worker.conf <<SUPERVISOR
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php $APP_PATH/artisan queue:listen --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=$APP_PATH/storage/logs/queue-worker.log
stopwaitsecs=3600
SUPERVISOR

supervisorctl reread
supervisorctl update
supervisorctl start laravel-worker:* 2>/dev/null || true

# Permissions
chown -R www-data:www-data "$APP_PATH/storage" "$APP_PATH/bootstrap/cache"
chmod -R 775 "$APP_PATH/storage" "$APP_PATH/bootstrap/cache"

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN} ✅ Setup selesai!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "Akses: ${YELLOW}http://$APP_DOMAIN${NC}"
echo ""
echo -e "${YELLOW}Database Credentials (simpan baik-baik):${NC}"
echo "  Database: $DB_NAME"
echo "  User    : $DB_USER"
echo "  Password: $DB_PASS"
echo ""
echo -e "${YELLOW}Langkah selanjutnya:${NC}"
echo "  1. Buat admin user: php artisan make:filament-user"
echo "     (atau jalankan dari SSH: cd $APP_PATH && php artisan make:filament-user)"
echo "  2. Setup GitHub Actions secrets (lihat .github/workflows/deploy.yml)"
echo ""
