#!/bin/bash
IPAPI="127.0.0.1"
PORTAPI="9191"

# Entramos en php/
cd backend/api/ || exit 1

# Iniciar servidor y guardar PID en logs/php-server.pid
php -S "${IPAPI}:${PORTAPI}" > logs/php-server.log 2>&1 &
echo $! > logs/php-server.pid

# Volver al directorio raíz para mostrar rutas correctas
cd ..

PID=$(cat backend/api/logs/php-server.pid)
LOG_PATH="$(pwd)/backend/api/logs/php-server.log"

printf "Servidor PHP iniciado en http://%s:%s (PID %s)\nLog: %s\n" "$IPAPI" "$PORTAPI" "$PID" "$LOG_PATH"
