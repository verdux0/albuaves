# Albuaves

Aplicación full-stack ligera para catalogar aves que habitan la Albufera de Valencia. Combina un backend PHP + SQLite que expone operaciones CRUD para la tabla `birds` y un frontend Bootstrap con JavaScript vanilla que consume la API vía `fetch` para renderizar, crear, editar y eliminar fichas de aves.

## Arquitectura en un vistazo
- **Frontend estático (`frontend/`)**: HTML5 con Bootstrap 5 e iconos oficiales, lógica en `js/api.js` y `index.html`. Se sirve como SPA ligera mediante un servidor estático.
- **Backend ligero (`backend/api/api.php`)**: Endpoint único RESTful sobre PHP 8 y SQLite3. Gestiona cabeceras CORS, valida parámetros y persiste en `backend/db/albuaves.db`.
- **Base de datos (`backend/db/`)**: Ficheros `.sql` para creación/población y binario SQLite con los datos reales.
- **Scripts**: `run.sh` levanta el servidor PHP y gestiona logs/PID; `run-client.sh` sirve el frontend con `python3 -m http.server`.

## Tecnologías, licencias y motivaciones

| Componente | Sitio oficial | Licencia | ¿Por qué aquí? |
| --- | --- | --- | --- |
| HTML5 + JavaScript (ES2022) | https://developer.mozilla.org/ | [W3C](https://www.w3.org/Consortium/Legal/2015/doc-license) | Máxima compatibilidad con navegadores y aprendizaje directo sin frameworks pesados. |
| Bootstrap 5.3 | https://getbootstrap.com | [MIT](https://github.com/twbs/bootstrap/blob/main/LICENSE) | Proporciona diseño responsive inmediato y sistema de componentes accesibles. |
| Bootstrap Icons 1.11 | https://icons.getbootstrap.com | [MIT](https://github.com/twbs/icons/blob/main/LICENSE.md) | Iconografía consistente sin dependencias adicionales. |
| PHP 8 servidor embebido | https://www.php.net/manual/en/features.commandline.webserver.php | [PHP License](https://www.php.net/license/) | Permite levantar la API con un único comando/script sin instalar Apache o Nginx. |
| SQLite 3 | https://www.sqlite.org | Dominio público | Base de datos embebida, cero configuración y fichero único fácil de versionar. |
| Fetch API | https://developer.mozilla.org/docs/Web/API/Fetch_API | [CC0 docs](https://developer.mozilla.org/docs/MDN/About#copyrights) | API nativa del navegador para llamadas HTTP modernas con soporte para Promises. |
| Python `http.server` | https://docs.python.org/3/library/http.server.html | [PSF License](https://docs.python.org/3/license.html) | Servidor estándar disponible en cualquier distro; evita instalar servidores extra para el cliente. |

> El código propio del repositorio permanece sin una licencia explícita; aplica la política de la asignatura hasta que se decida lo contrario.

## Demostración: llamada a la API en el navegador
- El frontend consume `API_URL = http://127.0.0.1:9191/api.php` definido en `frontend/js/api.js`. Cada acción (listar, crear, actualizar, borrar) invoca `fetch` con el método HTTP correspondiente.
- Se incluye la captura `docs/screenshots/frontend-api-call.png` donde se aprecia la respuesta JSON mostrada en DevTools tras crear un ave.
- Cómo reproducirlo:
  1. Arranca backend y frontend siguiendo la guía de más abajo.
  2. Abre el navegador en `http://127.0.0.1:4173` y crea una nueva ave desde el modal.
  3. Abre DevTools → pestaña *Network*, selecciona la petición `api.php` y comprueba la respuesta JSON.

```154:191:frontend/js/api.js
const api = {
    async obtenerAves() {
        const res = await fetch(API_URL);
        if (!res.ok) throw new Error('API error');
        return res.json();
    },
    async agregarAve(ave) {
        const res = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(ave),
        });
        if (!res.ok) throw new Error('Error creando ave');
        return res.json();
    },
    // ...
}
```

## Tutorial / How-to

### 1. Prerrequisitos
- GNU/Linux, macOS o WSL con bash.
- PHP ≥ 8.1 (incluye `php -S` y SQLite3 por defecto).
- Python ≥ 3.8 (para el servidor estático del cliente). Alternativa: cualquier servidor HTTP estático que pueda apuntar a `frontend/`.

### 2. Script de servidor (`run.sh`)
1. Desde la raíz del repo ejecutar:
   ```bash
   ./run.sh
   ```
2. El script:
   - Cambia a `backend/api/`.
   - Lanza `php -S 127.0.0.1:9191 api.php` en segundo plano.
   - Guarda el PID en `backend/api/logs/php-server.pid` y el log completo en `backend/api/logs/php-server.log`.
3. Para detenerlo: `kill $(cat backend/api/logs/php-server.pid)` o `pkill -F backend/api/logs/php-server.pid`.
4. Si necesitas recrear la base de datos, utiliza los scripts `backend/db/albuaves-db-create.sql` y `backend/db/albuaves-tables-population.sql` con `sqlite3 backend/db/albuaves.db < script.sql`.

### 3. Script del cliente (`run-client.sh`)
1. Ejecuta:
   ```bash
   ./run-client.sh 4173
   ```
   El puerto es opcional; por defecto usa `4173`.
2. El script se coloca en `frontend/` y lanza `python3 -m http.server <puerto>`, dejando disponible la SPA en `http://127.0.0.1:<puerto>`.
3. No existe fase de compilación: el frontend es HTML/CSS/JS plano. El script cumple el requisito de “puesta en marcha” envolviendo la orden recomendada para servirlo de forma consistente.

### 4. Pruebas manuales recomendadas
| Prueba | Pasos | Resultado esperado |
| --- | --- | --- |
| Listado inicial | Abrir la web; observar la tabla | Tabla con aves precargadas desde SQLite. |
| Alta | Click en “Añadir ave”, completar campos reales, enviar | Toast verde “Ave creada correctamente”, fila nueva y petición `POST` 201 en la red. |
| Edición | Icono lápiz → modificar descripción → guardar | Modal se cierra, toast amarillo, petición `PUT`. |
| Borrado | Icono papelera → confirmar en alert del navegador | Ave desaparece y petición `DELETE` con `{"message":"Ave eliminada correctamente"}`. |
| API directa | `curl http://127.0.0.1:9191/api.php?bird_id=1` | JSON con la fila solicitada. |

## Referencia rápida de la API

| Método | Ruta | Parámetros | Descripción |
| --- | --- | --- | --- |
| `GET` | `/api.php` | `bird_id` opcional (query) | Lista todas las aves o una sola si se indica `bird_id`. |
| `POST` | `/api.php` | Cuerpo JSON (`common_name`, `scientific_name`, `description`, `img_url`) | Inserta una nueva ave. |
| `PUT` | `/api.php` | Cuerpo JSON con `bird_id` y los campos editados | Actualiza la ave indicada. |
| `DELETE` | `/api.php?bird_id={id}` | Query `bird_id` obligatorio | Elimina la ave solicitada. |

## Sitios web del proyecto en local
- Backend: `http://127.0.0.1:9191/api.php`
- Frontend: `http://127.0.0.1:4173` (o el puerto indicado al script)

Estos endpoints son los que debes capturar o enlazar en el informe para evidenciar el funcionamiento del código.


