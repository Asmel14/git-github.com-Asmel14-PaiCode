<?php

declare(strict_types=1);
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tandas</h1>
    <div>
        <button id="tan-new" class="btn btn-sm btn-primary shadow-sm" type="button"><i class="fas fa-plus fa-sm"></i> Nueva</button>
        <button id="tan-refresh" class="btn btn-sm btn-outline-primary shadow-sm" type="button"><i class="fas fa-sync-alt fa-sm"></i> Recargar</button>
    </div>
</div>

<div id="tan-alert" class="alert d-none" role="alert"></div>

<div id="tan-form-card" class="card shadow mb-4 d-none">
    <div class="card-header py-3"><h6 id="tan-form-title" class="m-0 font-weight-bold text-primary">Nueva tanda</h6></div>
    <div class="card-body">
        <input type="hidden" id="tan-id">
        <div class="form-row">
            <div class="form-group col-md-4"><label for="tan-nombre">Nombre</label><input id="tan-nombre" class="form-control" type="text" maxlength="100" placeholder="Ej: Matutino"></div>
            <div class="form-group col-md-3"><label for="tan-codigo">Codigo</label><input id="tan-codigo" class="form-control" type="text" maxlength="30" placeholder="Ej: MATUTINO"></div>
            <div class="form-group col-md-2"><label for="tan-inicio">Hora inicio</label><input id="tan-inicio" class="form-control" type="time" step="1"></div>
            <div class="form-group col-md-2"><label for="tan-fin">Hora fin</label><input id="tan-fin" class="form-control" type="time" step="1"></div>
            <div class="form-group col-md-1"><label for="tan-estado">Estado</label><select id="tan-estado" class="form-control"><option value="1">A</option><option value="0">I</option></select></div>
        </div>
        <button id="tan-save" class="btn btn-primary btn-sm" type="button">Guardar</button>
        <button id="tan-cancel" class="btn btn-secondary btn-sm" type="button">Cancelar</button>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Listado de tandas</h6></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                <thead><tr><th>ID</th><th>Nombre</th><th>Codigo</th><th>Hora inicio</th><th>Hora fin</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody id="tan-tbody"><tr><td colspan="7" class="text-center text-muted">Cargando...</td></tr></tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function () {
    const apiBase = '../';
    const state = { rows: [] };

    function setAlert(type, message) {
        const el = document.getElementById('tan-alert');
        el.className = 'alert alert-' + type;
        el.textContent = message;
        el.classList.remove('d-none');
    }

    function clearAlert() {
        const el = document.getElementById('tan-alert');
        el.className = 'alert d-none';
        el.textContent = '';
    }

    function esc(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function normalizeTime(value) {
        const trimmed = String(value || '').trim();
        if (trimmed === '') {
            return null;
        }
        return trimmed.length === 5 ? (trimmed + ':00') : trimmed;
    }

    async function req(action, method, payload) {
        const res = await fetch(apiBase + '?resource=tandas&action=' + action + '&limit=500', {
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
        const tbody = document.getElementById('tan-tbody');
        if (state.rows.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Sin registros</td></tr>';
            return;
        }

        tbody.innerHTML = state.rows.map((r) => '<tr>' +
            '<td>' + r.id + '</td>' +
            '<td>' + esc(r.nombre || '') + '</td>' +
            '<td>' + esc(r.codigo || '') + '</td>' +
            '<td>' + esc(r.hora_inicio || '') + '</td>' +
            '<td>' + esc(r.hora_fin || '') + '</td>' +
            '<td>' + (Number(r.estado) === 1 ? 'Activa' : 'Inactiva') + '</td>' +
            '<td>' +
            '<button class="btn btn-sm btn-info mr-1" onclick="TandasView.edit(' + Number(r.id) + ')">Editar</button>' +
            '<button class="btn btn-sm btn-danger" onclick="TandasView.remove(' + Number(r.id) + ')">Eliminar</button>' +
            '</td>' +
            '</tr>').join('');
    }

    function showForm(edit, row) {
        document.getElementById('tan-form-card').classList.remove('d-none');
        document.getElementById('tan-form-title').textContent = edit ? 'Editar tanda' : 'Nueva tanda';
        document.getElementById('tan-id').value = edit ? String(row.id) : '';
        document.getElementById('tan-nombre').value = edit ? (row.nombre || '') : '';
        document.getElementById('tan-codigo').value = edit ? (row.codigo || '') : '';
        document.getElementById('tan-inicio').value = edit && row.hora_inicio ? String(row.hora_inicio).slice(0, 5) : '';
        document.getElementById('tan-fin').value = edit && row.hora_fin ? String(row.hora_fin).slice(0, 5) : '';
        document.getElementById('tan-estado').value = edit ? String(Number(row.estado || 0)) : '1';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function hideForm() {
        document.getElementById('tan-form-card').classList.add('d-none');
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
        const id = document.getElementById('tan-id').value.trim();
        const data = {
            nombre: document.getElementById('tan-nombre').value.trim(),
            codigo: document.getElementById('tan-codigo').value.trim().toUpperCase(),
            hora_inicio: normalizeTime(document.getElementById('tan-inicio').value),
            hora_fin: normalizeTime(document.getElementById('tan-fin').value),
            estado: Number(document.getElementById('tan-estado').value),
        };

        try {
            if (!data.nombre || !data.codigo) {
                throw new Error('Nombre y codigo son obligatorios.');
            }
            if (data.hora_inicio && data.hora_fin && data.hora_inicio >= data.hora_fin) {
                throw new Error('Hora inicio debe ser menor que hora fin.');
            }

            if (id === '') {
                await req('store', 'POST', { data });
                setAlert('success', 'Tanda creada correctamente.');
            } else {
                await req('update', 'PUT', { criteria: { id: Number(id) }, data });
                setAlert('success', 'Tanda actualizada correctamente.');
            }

            hideForm();
            await load();
        } catch (e) {
            setAlert('danger', e.message);
        }
    }

    async function removeRow(id) {
        clearAlert();
        if (!window.confirm('Deseas eliminar la tanda #' + id + '?')) {
            return;
        }

        try {
            await req('destroy', 'DELETE', { criteria: { id: Number(id) } });
            setAlert('warning', 'Tanda eliminada.');
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

    document.getElementById('tan-new').addEventListener('click', function () { clearAlert(); showForm(false, {}); });
    document.getElementById('tan-refresh').addEventListener('click', load);
    document.getElementById('tan-save').addEventListener('click', save);
    document.getElementById('tan-cancel').addEventListener('click', function () { hideForm(); clearAlert(); });

    window.TandasView = { edit, remove: removeRow };

    load();
})();
</script>
