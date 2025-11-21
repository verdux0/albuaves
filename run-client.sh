#!/bin/bash
PORT="${1:-4173}"

cd frontend || exit 1

printf "Servidor estático del cliente disponible en http://127.0.0.1:%s\n" "$PORT"
python3 -m http.server "$PORT"

