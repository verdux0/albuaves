const API_URL = "http://127.0.0.1:9191/api.php";

// Envoltorio API Fetch para CRUD
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
    async actualizarAve(ave) {
        const res = await fetch(API_URL, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(ave),
        });
        if (!res.ok) throw new Error('Error actualizando ave');
        return res.json();
    },
    async eliminarAve(bird_id) {
        const url = `${API_URL}?bird_id=${bird_id}`;
        const res = await fetch(url, { method: 'DELETE' });
        if (!res.ok) throw new Error('Error eliminando ave');
        return res.json();
    }
};