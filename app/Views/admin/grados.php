<?php

declare(strict_types=1);
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Grados</h1>
    <div>
        <button id="gra-new" class="btn btn-sm btn-primary shadow-sm" type="button"><i class="fas fa-plus fa-sm"></i> Nuevo</button>
        <button id="gra-refresh" class="btn btn-sm btn-outline-primary shadow-sm" type="button"><i class="fas fa-sync-alt fa-sm"></i> Recargar</button>
    </div>
</div>

<div id="gra-alert" class="alert d-none" role="alert"></div>

<div id="gra-form-card" class="card shadow mb-4 d-none">
    <div class="card-header py-3"><h6 id="gra-form-title" class="m-0 font-weight-bold text-primary">Nuevo grado</h6></div>
    <div class="card-body">
        <input type="hidden" id="gra-id">
        <div class="form-group"><label for="gra-nombre">Grado</label><input id="gra-nombre" class="form-control" type="text" maxlength="100" placeholder="Ej: 1ro de primaria"></div>

        <div class="custom-control custom-switch mb-3">
            <input type="checkbox" class="custom-control-input" id="gra-relacionar" checked>
            <label class="custom-control-label" for="gra-relacionar">Crear relacion academica al crear grado</label>
        </div>

        <div id="gra-relacion-card" class="border rounded p-3 mb-3">
            <input type="hidden" id="gra-planif-id">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="gra-anio-id">Ano escolar</label>
                    <select id="gra-anio-id" class="form-control"></select>
                </div>
                <div class="form-group col-md-3">
                    <label for="gra-nivel-id">Nivel</label>
                    <select id="gra-nivel-id" class="form-control"></select>
                </div>
                <div class="form-group col-md-3">
                    <label for="gra-seccion-id">Seccion</label>
                    <select id="gra-seccion-id" class="form-control"></select>
                </div>
                <div class="form-group col-md-3">
                    <label for="gra-tanda-id">Tanda</label>
                    <select id="gra-tanda-id" class="form-control"></select>
                </div>
            </div>
            <small class="text-muted">Si esta opcion esta activa al crear, tambien se registra en planificaciones_academicas.</small>
        </div>

        <button id="gra-save" class="btn btn-primary btn-sm" type="button">Guardar</button>
        <button id="gra-cancel" class="btn btn-secondary btn-sm" type="button">Cancelar</button>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Listado de grados</h6></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                <thead><tr><th>ID</th><th>Grado</th><th>Nivel</th><th>Seccion</th><th>Tanda</th><th>Acciones</th></tr></thead>
                <tbody id="gra-tbody"><tr><td colspan="6" class="text-center text-muted">Cargando...</td></tr></tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function () {
    const apiBase = '../';
    const state = {
        rows: [],
        anios: [],
        niveles: [],
        secciones: [],
        tandas: [],
        planificaciones: [],
    };

    function setAlert(type, message) { const el = document.getElementById('gra-alert'); el.className = 'alert alert-' + type; el.textContent = message; el.classList.remove('d-none'); }
    function clearAlert() { const el = document.getElementById('gra-alert'); el.className = 'alert d-none'; el.textContent = ''; }

    async function req(resource, action, method, payload) {
        const res = await fetch(apiBase + '?resource=' + resource + '&action=' + action + '&limit=500', {
            method,
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: payload ? JSON.stringify(payload) : undefined,
        });
        const json = await res.json();
        if (!json.success) throw new Error(json.message || 'Operacion fallida.');
        return json;
    }

    function render() {
        const tbody = document.getElementById('gra-tbody');
        if (state.rows.length === 0) { tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Sin registros</td></tr>'; return; }

        const nivelMap = Object.fromEntries(state.niveles.map((n) => [Number(n.id), String(n.nivel || ('Nivel #' + n.id))]));
        const seccionMap = Object.fromEntries(state.secciones.map((s) => [Number(s.id), String(s.seccion || ('Seccion #' + s.id))]));
        const tandaMap = Object.fromEntries(state.tandas.map((t) => [Number(t.id), String(t.nombre || ('Tanda #' + t.id))]));

        tbody.innerHTML = state.rows.map((r) => {
            const rels = state.planificaciones.filter((p) => Number(p.grado_id) === Number(r.id));

            const nivelesText = rels.length > 0
                ? Array.from(new Set(rels.map((p) => nivelMap[Number(p.nivel_id)] || ('Nivel #' + p.nivel_id)))).join(', ')
                : '-';

            const seccionesText = rels.length > 0
                ? Array.from(new Set(rels.map((p) => seccionMap[Number(p.seccion_id)] || ('Seccion #' + p.seccion_id)))).join(', ')
                : '-';

            const tandasText = rels.length > 0
                ? Array.from(new Set(rels.map((p) => tandaMap[Number(p.tanda_id)] || String(p.jornada || ('Tanda #' + p.tanda_id))))).join(', ')
                : '-';

            return '<tr>' +
                '<td>' + r.id + '</td>' +
                '<td>' + esc(r.grado || '') + '</td>' +
                '<td>' + esc(nivelesText) + '</td>' +
                '<td>' + esc(seccionesText) + '</td>' +
                '<td>' + esc(tandasText) + '</td>' +
                '<td><button class="btn btn-sm btn-info mr-1" onclick="GradosView.edit(' + Number(r.id) + ')">Editar</button><button class="btn btn-sm btn-danger" onclick="GradosView.remove(' + Number(r.id) + ')">Eliminar</button></td>' +
                '</tr>';
        }).join('');
    }

    function esc(v) { return String(v).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;'); }

    function showForm(edit, row) {
        document.getElementById('gra-form-card').classList.remove('d-none');
        document.getElementById('gra-form-title').textContent = edit ? 'Editar grado' : 'Nuevo grado';
        document.getElementById('gra-id').value = edit ? String(row.id) : '';
        document.getElementById('gra-nombre').value = edit ? (row.grado || '') : '';

        const related = edit
            ? state.planificaciones.find((p) => Number(p.grado_id) === Number(row.id)) || null
            : null;

        document.getElementById('gra-relacionar').checked = true;
        document.getElementById('gra-relacion-card').classList.remove('d-none');
        document.getElementById('gra-planif-id').value = related ? String(related.id) : '';

        if (related) {
            document.getElementById('gra-anio-id').value = String(related.anio_escolar_id || '');
            document.getElementById('gra-nivel-id').value = String(related.nivel_id || '');
            document.getElementById('gra-seccion-id').value = String(related.seccion_id || '');
            document.getElementById('gra-tanda-id').value = String(related.tanda_id || '');
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function hideForm() { document.getElementById('gra-form-card').classList.add('d-none'); }

    function fillSelect(id, rows, valueKey, textFn) {
        const select = document.getElementById(id);
        if (rows.length === 0) {
            select.innerHTML = '<option value="">Sin datos</option>';
            return;
        }

        select.innerHTML = rows.map((row) => {
            const value = Number(row[valueKey]);
            const text = textFn(row);
            return '<option value="' + value + '">' + esc(text) + '</option>';
        }).join('');
    }

    function renderRelacionCatalogos() {
        fillSelect('gra-anio-id', state.anios, 'id', (r) => (r.nombre || ('Ano #' + r.id)));
        fillSelect('gra-nivel-id', state.niveles, 'id', (r) => (r.nivel || ('Nivel #' + r.id)));
        fillSelect('gra-seccion-id', state.secciones, 'id', (r) => (r.seccion || ('Seccion #' + r.id)));
        fillSelect('gra-tanda-id', state.tandas, 'id', (r) => ((r.nombre || 'Tanda') + ' [' + (r.codigo || '') + ']'));

        if (state.anios.length > 0) {
            document.getElementById('gra-anio-id').value = String(state.anios[0].id);
        }
        if (state.niveles.length > 0) {
            document.getElementById('gra-nivel-id').value = String(state.niveles[0].id);
        }
        if (state.secciones.length > 0) {
            document.getElementById('gra-seccion-id').value = String(state.secciones[0].id);
        }
        if (state.tandas.length > 0) {
            document.getElementById('gra-tanda-id').value = String(state.tandas[0].id);
        }
    }

    async function load() {
        clearAlert();
        try {
            const [gradosRes, aniosRes, nivelesRes, seccionesRes, tandasRes, planifRes] = await Promise.all([
                req('grados', 'index', 'GET'),
                req('anios_escolares', 'index', 'GET'),
                req('niveles', 'index', 'GET'),
                req('secciones', 'index', 'GET'),
                req('tandas', 'index', 'GET'),
                req('planificaciones_academicas', 'index', 'GET'),
            ]);

            state.rows = Array.isArray(gradosRes.data)
                ? gradosRes.data.sort((a, b) => Number(a.id) - Number(b.id))
                : [];
            state.anios = Array.isArray(aniosRes.data) ? aniosRes.data : [];
            state.niveles = Array.isArray(nivelesRes.data) ? nivelesRes.data : [];
            state.secciones = Array.isArray(seccionesRes.data) ? seccionesRes.data : [];
            state.tandas = Array.isArray(tandasRes.data) ? tandasRes.data : [];
            state.planificaciones = Array.isArray(planifRes.data) ? planifRes.data : [];

            render();
            renderRelacionCatalogos();
        }
        catch (e) { setAlert('danger', e.message); }
    }

    async function createRelacionAcademica(gradoId) {
        const anioId = Number(document.getElementById('gra-anio-id').value || 0);
        const nivelId = Number(document.getElementById('gra-nivel-id').value || 0);
        const seccionId = Number(document.getElementById('gra-seccion-id').value || 0);
        const tandaId = Number(document.getElementById('gra-tanda-id').value || 0);
        const planifId = Number(document.getElementById('gra-planif-id').value || 0);

        if (anioId <= 0 || nivelId <= 0 || seccionId <= 0 || tandaId <= 0) {
            throw new Error('Para relacionar el grado debes seleccionar ano, nivel, seccion y tanda.');
        }

        const tanda = state.tandas.find((t) => Number(t.id) === tandaId) || null;
        const jornada = tanda && tanda.codigo ? String(tanda.codigo) : null;

        const data = {
            anio_escolar_id: anioId,
            nivel_id: nivelId,
            grado_id: Number(gradoId),
            seccion_id: seccionId,
            tanda_id: tandaId,
            jornada: jornada,
            estado: 1,
        };

        if (planifId > 0) {
            await req('planificaciones_academicas', 'update', 'PUT', {
                criteria: { id: planifId },
                data,
            });
            return;
        }

        const existing = state.planificaciones.find((p) => Number(p.grado_id) === Number(gradoId)) || null;
        if (existing && Number(existing.id) > 0) {
            await req('planificaciones_academicas', 'update', 'PUT', {
                criteria: { id: Number(existing.id) },
                data,
            });
            return;
        }

        await req('planificaciones_academicas', 'store', 'POST', { data });
    }

    async function save() {
        clearAlert();
        const id = document.getElementById('gra-id').value.trim();
        const grado = document.getElementById('gra-nombre').value.trim();
        try {
            if (!grado) throw new Error('Grado es obligatorio.');
            const data = { grado };

            if (id === '') {
                const created = await req('grados', 'store', 'POST', { data });
                const newId = Number((created.data || {}).id || 0);

                if (newId <= 0) {
                    throw new Error('No se pudo identificar el grado creado.');
                }

                if (document.getElementById('gra-relacionar').checked) {
                    await createRelacionAcademica(newId);
                    setAlert('success', 'Grado creado y relacionado correctamente.');
                } else {
                    setAlert('success', 'Grado creado correctamente.');
                }
            } else {
                await req('grados', 'update', 'PUT', { criteria: { id: Number(id) }, data });

                if (document.getElementById('gra-relacionar').checked) {
                    await createRelacionAcademica(Number(id));
                    setAlert('success', 'Grado y relacion academica actualizados correctamente.');
                } else {
                    setAlert('success', 'Grado actualizado correctamente.');
                }
            }

            hideForm();
            await load();
        } catch (e) { setAlert('danger', e.message); }
    }

    async function removeRow(id) {
        clearAlert();
        if (!window.confirm('Deseas eliminar el grado #' + id + '?')) return;
        try { await req('grados', 'destroy', 'DELETE', { criteria: { id: Number(id) } }); setAlert('warning', 'Grado eliminado.'); await load(); }
        catch (e) { setAlert('danger', e.message); }
    }

    function edit(id) {
        clearAlert();
        const row = state.rows.find((r) => Number(r.id) === Number(id));
        if (!row) { setAlert('danger', 'No se encontro el registro.'); return; }
        showForm(true, row);
    }

    document.getElementById('gra-new').addEventListener('click', function () { clearAlert(); showForm(false, {}); });
    document.getElementById('gra-refresh').addEventListener('click', load);
    document.getElementById('gra-save').addEventListener('click', save);
    document.getElementById('gra-cancel').addEventListener('click', function () { hideForm(); clearAlert(); });
    document.getElementById('gra-relacionar').addEventListener('change', function (e) {
        document.getElementById('gra-relacion-card').classList.toggle('d-none', !e.target.checked);
    });

    window.GradosView = { edit, remove: removeRow };

    load();
})();
</script>
