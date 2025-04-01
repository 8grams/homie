#!/bin/bash

# run cron
printenv | sed 's/^\(.*\)\=\(.*\)$/export \1\="\2"/g' > /root/project_env.sh
source /root/project_env.sh
cron

# Set default values for environment variables if not set
export APP_URL=${APP_URL:-":80"}

# Process Caddyfile with environment variables
envsubst < /etc/caddy/Caddyfile > /etc/caddy/Caddyfile.tmp
mv /etc/caddy/Caddyfile.tmp /etc/caddy/Caddyfile

# run frankenphp
frankenphp run --config /etc/caddy/Caddyfile --adapter caddyfile