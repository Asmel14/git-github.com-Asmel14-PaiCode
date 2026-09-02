<?php

declare(strict_types=1);
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Niveles</h1>
    <div>
        <button id="niv-new" class="btn btn-sm btn-primary shadow-sm" type="button"><i class="fas fa-plus fa-sm"></i> Nuevo</button>
        <button id="niv-refresh" class="btn btn-sm btn-outline-primary shadow-sm" type="button"><i class="fas fa-sync-alt fa-sm"></i> Recargar</button>
    </div>
</div>

<div id="niv-alert" class="alert d-none" role="alert"></div>

<div id="niv-form-card" class="card shadow mb-4 d-none">
    <div class="card-header py-3"><h6 id="niv-form-title" class="m-0 font-weight-bold text-primary">Nuevo nivel</h6></div>
    <div class="card-body">
        <input type="hidden" id="niv-id">
        <div class="form-group"><label for="niv-nombre">Nivel</label><input id="niv-nombre" class="form-control" type="text" maxlength="100" placeholder="Ej: Primaria"></div>
        <button id="niv-save" class="btn btn-primary btn-sm" type="button">Guardar</button>
        <button id="niv-cancel" class="btn btn-secondary btn-sm" type="button">Cancelar</button>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Listado de niveles</h6></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                <thead><tr><th>ID</th><th>Nivel</th><th>Acciones</th></tr></thead>
                <tbody id="niv-tbody"><tr><td colspan="3" class="text-center text-muted">Cargando...</td></tr></tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function () {
    const apiBase = '../';
    const state = { rows: [] };

    function setAlert(type, message) { const el = document.getElementById('niv-alert'); el.className = 'alert alert-' + type; el.textContent = message; el.classList.remove('d-none'); }
    function clearAlert() { const el = document.getElementById('niv-alert'); el.className = 'alert d-none'; el.textContent = ''; }

    async function req(action, method, payload) {
        const res = await fetch(apiBase + '?resource=niveles&action=' + action + '&limit=500', {
            method,
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: payload ? JSON.stringify(payload) : undefined,
        });
        const json = await res.json();
        if (!json.success) throw new Error(json.message || 'Operacion fallida.');
        return json;
    }

    function render() {
        const tbody = document.getElementById('niv-tbody');
        if (state.rows.length === 0) { tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Sin registros</td></tr>'; return; }
        tbody.innerHTML = state.rows.map((r) => '<tr><td>' + r.id + '</td><td>' + esc(r.nivel || '') + '</td><td><button class="btn btn-sm btn-info mr-1" onclick="NivelesView.edit(' + Number(r.id) + ')">Editar</button><button class="btn btn-sm btn-danger" onclick="NivelesView.remove(' + Number(r.id) + ')">Eliminar</button></td></tr>').join('');
    }

    function esc(v) { return String(v).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;'); }

    function showForm(edit, row) {
        document.getElementById('niv-form-card').classList.remove('d-none');
        document.getElementById('niv-form-title').textContent = edit ? 'Editar nivel' : 'Nuevo nivel';
        document.getElementById('niv-id').value = edit ? String(row.id) : '';
        document.getElementById('niv-nombre').value = edit ? (row.nivel || '') : '';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function hideForm() { document.getElementById('niv-form-card').classList.add('d-none'); }

    async function load() {
        clearAlert();
        try { const json = await req('index', 'GET'); state.rows = Array.isArray(json.data) ? json.data : []; render(); }
        catch (e) { setAlert('danger', e.message); }
    }

    async function save() {
        clearAlert();
        const id = document.getElementById('niv-id').value.trim();
        const nivel = document.getElementById('niv-nombre').value.trim();
        try {
            if (!nivel) throw new Error('Nivel es obligatorio.');
            const data = { nivel };
            if (id === '') { await req('store', 'POST', { data }); setAlert('success', 'Nivel creado correctamente.'); }
            else { await req('update', 'PUT', { criteria: { id: Number(id) }, data }); setAlert('success', 'Nivel actualizado correctamente.'); }
            hideForm();
            await load();
        } catch (e) { setAlert('danger', e.message); }
    }

    async function removeRow(id) {
        clearAlert();
        if (!window.confirm('Deseas eliminar el nivel #' + id + '?')) return;
        try { await req('destroy', 'DELETE', { criteria: { id: Number(id) } }); setAlert('warning', 'Nivel eliminado.'); await load(); }
        catch (e) { setAlert('danger', e.message); }
    }

    function edit(id) {
        clearAlert();
        const row = state.rows.find((r) => Number(r.id) === Number(id));
        if (!row) { setAlert('danger', 'No se encontro el registro.'); return; }
        showForm(true, row);
    }

    document.getElementById('niv-new').addEventListener('click', function () { clearAlert(); showForm(false, {}); });
    document.getElementById('niv-refresh').addEventListener('click', load);
    document.getElementById('niv-save').addEventListener('click', save);
    document.getElementById('niv-cancel').addEventListener('click', function () { hideForm(); clearAlert(); });

    window.NivelesView = { edit, remove: removeRow };

    load();
})();
</script>
