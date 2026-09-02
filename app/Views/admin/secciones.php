<?php

declare(strict_types=1);
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Secciones</h1>
    <div>
        <button id="sec-new" class="btn btn-sm btn-primary shadow-sm" type="button"><i class="fas fa-plus fa-sm"></i> Nuevo</button>
        <button id="sec-refresh" class="btn btn-sm btn-outline-primary shadow-sm" type="button"><i class="fas fa-sync-alt fa-sm"></i> Recargar</button>
    </div>
</div>

<div id="sec-alert" class="alert d-none" role="alert"></div>

<div id="sec-form-card" class="card shadow mb-4 d-none">
    <div class="card-header py-3"><h6 id="sec-form-title" class="m-0 font-weight-bold text-primary">Nueva seccion</h6></div>
    <div class="card-body">
        <input type="hidden" id="sec-id">
        <div class="form-group"><label for="sec-nombre">Seccion</label><input id="sec-nombre" class="form-control" type="text" maxlength="50" placeholder="Ej: A"></div>
        <button id="sec-save" class="btn btn-primary btn-sm" type="button">Guardar</button>
        <button id="sec-cancel" class="btn btn-secondary btn-sm" type="button">Cancelar</button>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Listado de secciones</h6></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                <thead><tr><th>ID</th><th>Seccion</th><th>Acciones</th></tr></thead>
                <tbody id="sec-tbody"><tr><td colspan="3" class="text-center text-muted">Cargando...</td></tr></tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function () {
    const apiBase = '../';
    const state = { rows: [] };

    function setAlert(type, message) { const el = document.getElementById('sec-alert'); el.className = 'alert alert-' + type; el.textContent = message; el.classList.remove('d-none'); }
    function clearAlert() { const el = document.getElementById('sec-alert'); el.className = 'alert d-none'; el.textContent = ''; }

    async function req(action, method, payload) {
        const res = await fetch(apiBase + '?resource=secciones&action=' + action + '&limit=500', {
            method,
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: payload ? JSON.stringify(payload) : undefined,
        });
        const json = await res.json();
        if (!json.success) throw new Error(json.message || 'Operacion fallida.');
        return json;
    }

    function render() {
        const tbody = document.getElementById('sec-tbody');
        if (state.rows.length === 0) { tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Sin registros</td></tr>'; return; }
        tbody.innerHTML = state.rows.map((r) => '<tr><td>' + r.id + '</td><td>' + esc(r.seccion || '') + '</td><td><button class="btn btn-sm btn-info mr-1" onclick="SeccionesView.edit(' + Number(r.id) + ')">Editar</button><button class="btn btn-sm btn-danger" onclick="SeccionesView.remove(' + Number(r.id) + ')">Eliminar</button></td></tr>').join('');
    }

    function esc(v) { return String(v).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;'); }

    function showForm(edit, row) {
        document.getElementById('sec-form-card').classList.remove('d-none');
        document.getElementById('sec-form-title').textContent = edit ? 'Editar seccion' : 'Nueva seccion';
        document.getElementById('sec-id').value = edit ? String(row.id) : '';
        document.getElementById('sec-nombre').value = edit ? (row.seccion || '') : '';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function hideForm() { document.getElementById('sec-form-card').classList.add('d-none'); }

    async function load() {
        clearAlert();
        try { const json = await req('index', 'GET'); state.rows = Array.isArray(json.data) ? json.data : []; render(); }
        catch (e) { setAlert('danger', e.message); }
    }

    async function save() {
        clearAlert();
        const id = document.getElementById('sec-id').value.trim();
        const seccion = document.getElementById('sec-nombre').value.trim();
        try {
            if (!seccion) throw new Error('Seccion es obligatoria.');
            const data = { seccion };
            if (id === '') { await req('store', 'POST', { data }); setAlert('success', 'Seccion creada correctamente.'); }
            else { await req('update', 'PUT', { criteria: { id: Number(id) }, data }); setAlert('success', 'Seccion actualizada correctamente.'); }
            hideForm();
            await load();
        } catch (e) { setAlert('danger', e.message); }
    }

    async function removeRow(id) {
        clearAlert();
        if (!window.confirm('Deseas eliminar la seccion #' + id + '?')) return;
        try { await req('destroy', 'DELETE', { criteria: { id: Number(id) } }); setAlert('warning', 'Seccion eliminada.'); await load(); }
        catch (e) { setAlert('danger', e.message); }
    }

    function edit(id) {
        clearAlert();
        const row = state.rows.find((r) => Number(r.id) === Number(id));
        if (!row) { setAlert('danger', 'No se encontro el registro.'); return; }
        showForm(true, row);
    }

    document.getElementById('sec-new').addEventListener('click', function () { clearAlert(); showForm(false, {}); });
    document.getElementById('sec-refresh').addEventListener('click', load);
    document.getElementById('sec-save').addEventListener('click', save);
    document.getElementById('sec-cancel').addEventListener('click', function () { hideForm(); clearAlert(); });

    window.SeccionesView = { edit, remove: removeRow };

    load();
})();
</script>
