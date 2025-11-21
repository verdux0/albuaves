#!/bin/bash

for file in *.*; do
    # Cuenta cuántos puntos tiene el nombre
    dots=$(grep -o "\." <<< "$file" | wc -l)

    # Si solo tiene un punto, pasa al siguiente archivo
    if [ "$dots" -le 1 ]; then
        continue
    fi

    # Si tiene más de uno, quita SOLO la última extensión
    newname="${file%.*}"

    mv "$file" "$newname"
    echo "Renombrado: $file → $newname"
done
