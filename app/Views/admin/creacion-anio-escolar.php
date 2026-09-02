<?php

declare(strict_types=1);
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Creacion de ano escolar</h1>
    <div>
        <button id="anio-new" class="btn btn-sm btn-primary shadow-sm" type="button"><i class="fas fa-plus fa-sm"></i> Nuevo</button>
        <button id="anio-refresh" class="btn btn-sm btn-outline-primary shadow-sm" type="button"><i class="fas fa-sync-alt fa-sm"></i> Recargar</button>
    </div>
</div>

<div id="anio-alert" class="alert d-none" role="alert"></div>

<div id="anio-form-card" class="card shadow mb-4 d-none">
    <div class="card-header py-3"><h6 id="anio-form-title" class="m-0 font-weight-bold text-primary">Nuevo ano escolar</h6></div>
    <div class="card-body">
        <input type="hidden" id="anio-id">
        <div class="form-row">
            <div class="form-group col-md-4"><label for="anio-nombre">Nombre</label><input id="anio-nombre" class="form-control" type="text" maxlength="255" placeholder="Ej: 2026-2027"></div>
            <div class="form-group col-md-4"><label for="anio-inicio">Fecha inicio</label><input id="anio-inicio" class="form-control" type="date"></div>
            <div class="form-group col-md-4"><label for="anio-fin">Fecha fin</label><input id="anio-fin" class="form-control" type="date"></div>
        </div>
        <button id="anio-save" class="btn btn-primary btn-sm" type="button">Guardar</button>
        <button id="anio-cancel" class="btn btn-secondary btn-sm" type="button">Cancelar</button>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Listado de anos escolares</h6></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                <thead><tr><th>ID</th><th>Nombre</th><th>Fecha inicio</th><th>Fecha fin</th><th>Acciones</th></tr></thead>
                <tbody id="anio-tbody"><tr><td colspan="5" class="text-center text-muted">Cargando...</td></tr></tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function () {
    const apiBase = '../';
    const state = { rows: [] };

    function setAlert(type, message) {
        const el = document.getElementById('anio-alert');
        el.className = 'alert alert-' + type;
        el.textContent = message;
        el.classList.remove('d-none');
    }

    function clearAlert() {
        const el = document.getElementById('anio-alert');
        el.className = 'alert d-none';
        el.textContent = '';
    }

    async function req(action, method, payload) {
        const res = await fetch(apiBase + '?resource=anios_escolares&action=' + action + '&limit=500', {
            method,
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: payload ? JSON.stringify(payload) : undefined,
        });
        const json = await res.json();
        if (!json.success) {
            throw new Error(json.message || 'Operacion fallida.');
        }
        return json;
    }

    function render() {
        const tbody = document.getElementById('anio-tbody');
        if (state.rows.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Sin registros</td></tr>';
            return;
        }

        tbody.innerHTML = state.rows.map((r) => '<tr>' +
            '<td>' + r.id + '</td>' +
            '<td>' + esc(r.nombre || '') + '</td>' +
            '<td>' + esc(r.fecha_inicio || '') + '</td>' +
            '<td>' + esc(r.fecha_fin || '') + '</td>' +
            '<td>' +
            '<button class="btn btn-sm btn-info mr-1" onclick="AnioView.edit(' + Number(r.id) + ')">Editar</button>' +
            '<button class="btn btn-sm btn-danger" onclick="AnioView.remove(' + Number(r.id) + ')">Eliminar</button>' +
            '</td>' +
            '</tr>').join('');
    }

    function esc(value) {
        return String(value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function formData() {
        return {
            nombre: document.getElementById('anio-nombre').value.trim(),
            fecha_inicio: document.getElementById('anio-inicio').value,
            fecha_fin: document.getElementById('anio-fin').value,
        };
    }

    function showForm(edit, row) {
        document.getElementById('anio-form-card').classList.remove('d-none');
        document.getElementById('anio-form-title').textContent = edit ? 'Editar ano escolar' : 'Nuevo ano escolar';
        document.getElementById('anio-id').value = edit ? String(row.id) : '';
        document.getElementById('anio-nombre').value = edit ? (row.nombre || '') : '';
        document.getElementById('anio-inicio').value = edit ? (row.fecha_inicio || '') : '';
        document.getElementById('anio-fin').value = edit ? (row.fecha_fin || '') : '';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function hideForm() {
        document.getElementById('anio-form-card').classList.add('d-none');
    }

    async function load() {
        clearAlert();
        try {
            const json = await req('index', 'GET');
            state.rows = Array.isArray(json.data) ? json.data : [];
            render();
        } catch (e) {
            setAlert('danger', e.message);
        }
    }

    async function save() {
        clearAlert();
        const id = document.getElementById('anio-id').value.trim();
        const data = formData();
        try {
            if (!data.nombre || !data.fecha_inicio || !data.fecha_fin) {
                throw new Error('Completa nombre, fecha inicio y fecha fin.');
            }
            if (id === '') {
                await req('store', 'POST', { data });
                setAlert('success', 'Ano escolar creado correctamente.');
            } else {
                await req('update', 'PUT', { criteria: { id: Number(id) }, data });
                setAlert('success', 'Ano escolar actualizado correctamente.');
            }
            hideForm();
            await load();
        } catch (e) {
            setAlert('danger', e.message);
        }
    }

    async function removeRow(id) {
        clearAlert();
        if (!window.confirm('Deseas eliminar el ano escolar #' + id + '?')) {
            return;
        }
        try {
            await req('destroy', 'DELETE', { criteria: { id: Number(id) } });
            setAlert('warning', 'Ano escolar eliminado.');
            await load();
        } catch (e) {
            setAlert('danger', e.message);
        }
    }

    function edit(id) {
        clearAlert();
        const row = state.rows.find((r) => Number(r.id) === Number(id));
        if (!row) {
            setAlert('danger', 'No se encontro el registro.');
            return;
        }
        showForm(true, row);
    }

    document.getElementById('anio-new').addEventListener('click', function () { clearAlert(); showForm(false, {}); });
    document.getElementById('anio-refresh').addEventListener('click', load);
    document.getElementById('anio-save').addEventListener('click', save);
    document.getElementById('anio-cancel').addEventListener('click', function () { hideForm(); clearAlert(); });

    window.AnioView = { edit, remove: removeRow };

    load();
})();
</script>
