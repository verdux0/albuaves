#!/bin/bash

# Comprobación de argumentos
if [ $# -ne 2 ]; then
    echo "Uso: $0 <carpeta_local> <url_repo>"
    exit 1
fi

CARPETA_LOCAL="$1"
URL_REPO="$2"

# Ir a la carpeta
cd "$CARPETA_LOCAL" || { echo "Carpeta no encontrada"; exit 1; }

# Inicializar git si no existe
if [ ! -d ".git" ]; then
    git init
    git remote add origin "$URL_REPO"
fi

# Añadir todos los archivos
git add .

# Crear commit con fecha automática
COMMIT_MSG="Auto commit $(date +"%Y-%m-%d %H:%M:%S")"
git commit -m "$COMMIT_MSG"

# Empujar (main por defecto, si tu repo usa master puedes cambiarlo)
git branch -M main
git push -u origin main
