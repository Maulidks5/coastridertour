#!/usr/bin/env bash

set -Eeuo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
ACCOUNT_HOME="${CPANEL_ACCOUNT_HOME:-$(getent passwd "$(id -un)" | cut -d: -f6)}"
PUBLIC_ROOT="${ACCOUNT_HOME}/public_html"
SECRETS_FILE="${ACCOUNT_HOME}/.coastrider-tour.env"

if [[ -z "${ACCOUNT_HOME}" || "${ACCOUNT_HOME}" == "/" ]]; then
    echo "Unable to determine the cPanel account home directory." >&2
    exit 1
fi

if [[ ! -d "${PUBLIC_ROOT}" ]]; then
    echo "Expected public directory not found: ${PUBLIC_ROOT}" >&2
    exit 1
fi

if [[ ! -f "${SECRETS_FILE}" ]]; then
    echo "Missing production environment file: ${SECRETS_FILE}" >&2
    echo "Copy deploy/env.production.example there, fill its secrets, then deploy again." >&2
    exit 1
fi

PHP_BIN="/opt/cpanel/ea-php83/root/usr/bin/php"
if [[ ! -x "${PHP_BIN}" ]]; then
    PHP_BIN="$(command -v php || true)"
fi

if [[ -z "${PHP_BIN}" ]]; then
    echo "PHP CLI was not found." >&2
    exit 1
fi

COMPOSER_BIN="$(command -v composer || true)"
if [[ -z "${COMPOSER_BIN}" && -f "${ACCOUNT_HOME}/bin/composer" ]]; then
    COMPOSER_BIN="${ACCOUNT_HOME}/bin/composer"
fi
if [[ -z "${COMPOSER_BIN}" && -x "/opt/cpanel/composer/bin/composer" ]]; then
    COMPOSER_BIN="/opt/cpanel/composer/bin/composer"
fi

if [[ -z "${COMPOSER_BIN}" ]]; then
    echo "Composer was not found. Ask the host to enable Composer for this account." >&2
    exit 1
fi

cd "${PROJECT_ROOT}"
install -m 600 "${SECRETS_FILE}" .env
if grep -q '^PUBLIC_STORAGE_ROOT=' .env; then
    sed -i "s|^PUBLIC_STORAGE_ROOT=.*|PUBLIC_STORAGE_ROOT=${PUBLIC_ROOT}/storage|" .env
else
    printf '\nPUBLIC_STORAGE_ROOT=%s/storage\n' "${PUBLIC_ROOT}" >> .env
fi
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

if [[ -f vendor/autoload.php ]]; then
    "${PHP_BIN}" artisan down --retry=30 --refresh=15 || true
fi

finish_deployment() {
    if [[ -f "${PROJECT_ROOT}/vendor/autoload.php" ]]; then
        "${PHP_BIN}" "${PROJECT_ROOT}/artisan" up >/dev/null 2>&1 || true
    fi
}
trap finish_deployment EXIT

"${PHP_BIN}" "${COMPOSER_BIN}" install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

"${PHP_BIN}" artisan migrate --force
"${PHP_BIN}" artisan optimize:clear
"${PHP_BIN}" artisan optimize

rsync -a \
    --exclude='index.php' \
    --exclude='storage' \
    "${PROJECT_ROOT}/public/" "${PUBLIC_ROOT}/"

sed "s|__APP_ROOT__|${PROJECT_ROOT}|g" \
    "${PROJECT_ROOT}/deploy/public-index.php" > "${PUBLIC_ROOT}/index.php.tmp"
chmod 644 "${PUBLIC_ROOT}/index.php.tmp"
mv -f "${PUBLIC_ROOT}/index.php.tmp" "${PUBLIC_ROOT}/index.php"

if [[ -L "${PUBLIC_ROOT}/storage" ]]; then
    rm "${PUBLIC_ROOT}/storage"
fi

mkdir -p "${PUBLIC_ROOT}/storage"
rsync -a "${PROJECT_ROOT}/storage/app/public/" "${PUBLIC_ROOT}/storage/"
find "${PUBLIC_ROOT}/storage" -type d -exec chmod 755 {} +
find "${PUBLIC_ROOT}/storage" -type f -exec chmod 644 {} +
chmod 644 "${PUBLIC_ROOT}/.htaccess" "${PUBLIC_ROOT}/index.php"

"${PHP_BIN}" artisan up
trap - EXIT

echo "Coast Rider deployed successfully to ${PUBLIC_ROOT}."
