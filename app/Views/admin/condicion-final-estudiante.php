<?php

declare(strict_types=1);
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Condicion final de estudiante</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-flex flex-column" style="gap: 12px;">
            <h6 class="m-0 font-weight-bold text-primary">Filtros academicos</h6>
            <div class="d-flex flex-column flex-lg-row" style="gap: 12px; flex-wrap: wrap;">
                <div style="min-width: 230px;">
                    <label for="filtro-cond-anio" class="small font-weight-bold text-muted mb-1 d-block">Ano academico</label>
                    <select id="filtro-cond-anio" class="form-control">
                        <option value="">Selecciona</option>
                    </select>
                </div>
                <div style="min-width: 210px;">
                    <label for="filtro-cond-nivel" class="small font-weight-bold text-muted mb-1 d-block">Nivel</label>
                    <select id="filtro-cond-nivel" class="form-control">
                        <option value="">Selecciona</option>
                    </select>
                </div>
                <div style="min-width: 210px;">
                    <label for="filtro-cond-grado" class="small font-weight-bold text-muted mb-1 d-block">Grado</label>
                    <select id="filtro-cond-grado" class="form-control">
                        <option value="">Selecciona</option>
                    </select>
                </div>
                <div style="min-width: 210px;">
                    <label for="filtro-cond-seccion" class="small font-weight-bold text-muted mb-1 d-block">Seccion</label>
                    <select id="filtro-cond-seccion" class="form-control">
                        <option value="">Selecciona</option>
                    </select>
                </div>
                <div class="align-self-end">
                    <button type="button" class="btn btn-primary" id="btn-cargar-condiciones">
                        <i class="fas fa-search mr-1"></i> Cargar estudiantes
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="estado-condicion-final" class="alert alert-info mb-3">Selecciona ano, nivel, grado y seccion para cargar estudiantes.</div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Estudiante</th>
                    <th>ID SIGERD</th>
                    <th>Condicion final</th>
                    <th style="min-width: 200px;">Accion</th>
                </tr>
                </thead>
                <tbody id="tbody-condicion-final">
                <tr>
                    <td colspan="5" class="text-center text-muted">Sin datos</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function () {
    const apiBase = '../';

    const filtroAnio = document.getElementById('filtro-cond-anio');
    const filtroNivel = document.getElementById('filtro-cond-nivel');
    const filtroGrado = document.getElementById('filtro-cond-grado');
    const filtroSeccion = document.getElementById('filtro-cond-seccion');
    const btnCargar = document.getElementById('btn-cargar-condiciones');
    const estado = document.getElementById('estado-condicion-final');
    const tbody = document.getElementById('tbody-condicion-final');

    const UI_NO_DEFINIDO = 'NO_DEFINIDO';
    const UI_PROMOVIDO = 'PROMOVIDO';
    const UI_ABANDONO = 'ABANDONO';
    const UI_TRANSFERIDO = 'TRANSFERIDO';
    const UI_REPROBADO = 'REPROBADO';

    const DB_CURSANDO = 'CURSANDO';
    const DB_PROMOVIDO = 'PROMOVIDO';
    const DB_REPROBADO = 'REPROBADO';
    const DB_RETIRADO = 'RETIRADO';
    const DB_TRASLADADO = 'TRASLADADO';

    const state = {
        aniosMap: new Map(),
        nivelesMap: new Map(),
        gradosMap: new Map(),
        seccionesMap: new Map(),
        tandasMap: new Map(),
        planificacionesMap: new Map(),
        inscripciones: [],
        estudiantesMap: new Map(),
        historialRows: [],
        loadedRows: [],
    };

    function escHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function setStatus(message, type) {
        const classes = {
            info: 'alert alert-info mb-3',
            success: 'alert alert-success mb-3',
            warning: 'alert alert-warning mb-3',
            danger: 'alert alert-danger mb-3',
        };

        estado.className = classes[type] || classes.info;
        estado.textContent = message;
    }

    function toMap(rows, keyField) {
        const map = new Map();
        rows.forEach((row) => {
            const key = Number(row[keyField] || 0);
            if (key > 0) {
                map.set(key, row);
            }
        });
        return map;
    }

    function normalizeStudentName(estudiante) {
        if (!estudiante) {
            return '-';
        }

        const parts = [
            estudiante.primer_nombre,
            estudiante.segundo_nombre,
            estudiante.primer_apellido,
            estudiante.segundo_apellido,
        ].map((part) => String(part || '').trim()).filter(Boolean);

        return parts.length > 0 ? parts.join(' ') : '-';
    }

    function getTodayLocalISO() {
        const now = new Date();
        const offsetMs = now.getTimezoneOffset() * 60000;
        return new Date(now.getTime() - offsetMs).toISOString().slice(0, 10);
    }

    function getPlanifJornada(planif) {
        const explicit = String(planif.jornada || '').trim().toUpperCase();
        if (explicit === 'MATUTINO' || explicit === 'VESPERTINO') {
            return explicit;
        }

        const tanda = state.tandasMap.get(Number(planif.tanda_id || 0)) || null;
        if (!tanda) {
            return 'MATUTINO';
        }

        const byCodigo = String(tanda.codigo || '').trim().toUpperCase();
        if (byCodigo === 'MATUTINO' || byCodigo === 'VESPERTINO') {
            return byCodigo;
        }

        const byNombre = String(tanda.nombre || '').trim().toUpperCase();
        if (byNombre.includes('VESPERT')) {
            return 'VESPERTINO';
        }

        return 'MATUTINO';
    }

    function mapDbEstadoToUi(dbEstado) {
        const estado = String(dbEstado || '').trim().toUpperCase();
        if (estado === DB_PROMOVIDO) {
            return UI_PROMOVIDO;
        }

        if (estado === DB_REPROBADO) {
            return UI_REPROBADO;
        }

        if (estado === DB_RETIRADO) {
            return UI_ABANDONO;
        }

        if (estado === DB_TRASLADADO) {
            return UI_TRANSFERIDO;
        }

        return UI_NO_DEFINIDO;
    }

    function mapUiEstadoToDb(uiEstado) {
        const estado = String(uiEstado || '').trim().toUpperCase();
        if (estado === UI_PROMOVIDO) {
            return DB_PROMOVIDO;
        }
        if (estado === UI_REPROBADO) {
            return DB_REPROBADO;
        }
        if (estado === UI_ABANDONO) {
            return DB_RETIRADO;
        }
        if (estado === UI_TRANSFERIDO) {
            return DB_TRASLADADO;
        }
        return DB_CURSANDO;
    }

    async function apiGet(resource) {
        const response = await fetch(apiBase + '?resource=' + resource + '&action=index&limit=5000', {
            method: 'GET',
            headers: { 'Accept': 'application/json' },
        });

        const json = await response.json();
        if (!json.success) {
            throw new Error(json.message || ('No se pudo cargar ' + resource));
        }

        return Array.isArray(json.data) ? json.data : [];
    }

    async function apiStore(resource, data) {
        const response = await fetch(apiBase + '?resource=' + resource + '&action=store', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ data }),
        });

        const json = await response.json();
        if (!json.success) {
            throw new Error(json.message || ('No se pudo guardar en ' + resource));
        }

        return json.data || {};
    }

    async function apiUpdate(resource, criteria, data) {
        const response = await fetch(apiBase + '?resource=' + resource + '&action=update', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ criteria, data }),
        });

        const json = await response.json();
        if (!json.success) {
            throw new Error(json.message || ('No se pudo actualizar en ' + resource));
        }

        return json.data || {};
    }

    function fillSelect(selectEl, values, labelFactory) {
        const options = ['<option value="">Selecciona</option>'];
        values.forEach((value) => {
            options.push('<option value="' + value + '">' + escHtml(labelFactory(value)) + '</option>');
        });
        selectEl.innerHTML = options.join('');
    }

    function getActivePlanificaciones() {
        return Array.from(state.planificacionesMap.values()).filter((row) => Number(row.estado ?? 1) === 1);
    }

    function renderAnios() {
        const ids = Array.from(new Set(
            getActivePlanificaciones().map((row) => Number(row.anio_escolar_id || 0)).filter((id) => id > 0)
        )).sort((a, b) => b - a);

        fillSelect(filtroAnio, ids, function (id) {
            const anio = state.aniosMap.get(id);
            return String((anio && anio.nombre) || ('ANO #' + id)).trim();
        });

        if (ids.length > 0) {
            filtroAnio.value = String(ids[0]);
        }
    }

    function renderNiveles() {
        const anioId = Number(filtroAnio.value || 0);
        const ids = Array.from(new Set(
            getActivePlanificaciones()
                .filter((row) => anioId > 0 ? Number(row.anio_escolar_id || 0) === anioId : true)
                .map((row) => Number(row.nivel_id || 0))
                .filter((id) => id > 0)
        )).sort((a, b) => a - b);

        fillSelect(filtroNivel, ids, function (id) {
            const nivel = state.nivelesMap.get(id);
            return String((nivel && nivel.nivel) || ('NIVEL #' + id)).trim();
        });
    }

    function renderGrados() {
        const anioId = Number(filtroAnio.value || 0);
        const nivelId = Number(filtroNivel.value || 0);
        const ids = Array.from(new Set(
            getActivePlanificaciones()
                .filter((row) => anioId > 0 ? Number(row.anio_escolar_id || 0) === anioId : true)
                .filter((row) => nivelId > 0 ? Number(row.nivel_id || 0) === nivelId : true)
                .map((row) => Number(row.grado_id || 0))
                .filter((id) => id > 0)
        )).sort((a, b) => a - b);

        fillSelect(filtroGrado, ids, function (id) {
            const grado = state.gradosMap.get(id);
            return String((grado && grado.grado) || ('GRADO #' + id)).trim();
        });
    }

    function renderSecciones() {
        const anioId = Number(filtroAnio.value || 0);
        const nivelId = Number(filtroNivel.value || 0);
        const gradoId = Number(filtroGrado.value || 0);
        const ids = Array.from(new Set(
            getActivePlanificaciones()
                .filter((row) => anioId > 0 ? Number(row.anio_escolar_id || 0) === anioId : true)
                .filter((row) => nivelId > 0 ? Number(row.nivel_id || 0) === nivelId : true)
                .filter((row) => gradoId > 0 ? Number(row.grado_id || 0) === gradoId : true)
                .map((row) => Number(row.seccion_id || 0))
                .filter((id) => id > 0)
        )).sort((a, b) => a - b);

        fillSelect(filtroSeccion, ids, function (id) {
            const seccion = state.seccionesMap.get(id);
            return String((seccion && seccion.seccion) || ('SECCION #' + id)).trim();
        });
    }

    function getSelectedPlanifIds() {
        const anioId = Number(filtroAnio.value || 0);
        const nivelId = Number(filtroNivel.value || 0);
        const gradoId = Number(filtroGrado.value || 0);
        const seccionId = Number(filtroSeccion.value || 0);

        return getActivePlanificaciones()
            .filter((row) => anioId > 0 ? Number(row.anio_escolar_id || 0) === anioId : false)
            .filter((row) => nivelId > 0 ? Number(row.nivel_id || 0) === nivelId : false)
            .filter((row) => gradoId > 0 ? Number(row.grado_id || 0) === gradoId : false)
            .filter((row) => seccionId > 0 ? Number(row.seccion_id || 0) === seccionId : false)
            .map((row) => Number(row.id || 0))
            .filter((id) => id > 0);
    }

    function findHistorialRow(estudianteId, anioId, nivelId, gradoId, seccionId) {
        const rows = state.historialRows.filter((item) =>
            Number(item.estudiante_id || 0) === estudianteId
            && Number(item.anio_escolar_id || 0) === anioId
            && Number(item.nivel_id || 0) === nivelId
            && Number(item.grado_id || 0) === gradoId
            && Number(item.seccion_id || 0) === seccionId
        );

        if (rows.length === 0) {
            return null;
        }

        rows.sort((a, b) => Number(b.id || 0) - Number(a.id || 0));
        return rows[0];
    }

    function buildLoadedRows() {
        const planifIds = getSelectedPlanifIds();
        if (planifIds.length === 0) {
            return [];
        }

        const byStudent = new Map();
        state.inscripciones.forEach((inscripcion) => {
            const planifId = Number(inscripcion.planificacion_academica_id || 0);
            if (!planifIds.includes(planifId)) {
                return;
            }

            const estudianteId = Number(inscripcion.estudiante_id || 0);
            if (estudianteId <= 0) {
                return;
            }

            const current = byStudent.get(estudianteId) || null;
            if (!current) {
                byStudent.set(estudianteId, inscripcion);
                return;
            }

            const a = String(inscripcion.fecha_inscripcion || '') + '-' + String(inscripcion.id || 0);
            const b = String(current.fecha_inscripcion || '') + '-' + String(current.id || 0);
            if (a > b) {
                byStudent.set(estudianteId, inscripcion);
            }
        });

        return Array.from(byStudent.values()).map((inscripcion) => {
            const estudianteId = Number(inscripcion.estudiante_id || 0);
            const planif = state.planificacionesMap.get(Number(inscripcion.planificacion_academica_id || 0)) || null;
            const estudiante = state.estudiantesMap.get(estudianteId) || null;
            if (!planif || !estudiante) {
                return null;
            }

            const anioId = Number(planif.anio_escolar_id || 0);
            const nivelId = Number(planif.nivel_id || 0);
            const gradoId = Number(planif.grado_id || 0);
            const seccionId = Number(planif.seccion_id || 0);
            const historial = findHistorialRow(estudianteId, anioId, nivelId, gradoId, seccionId);

            return {
                estudianteId,
                estudianteNombre: normalizeStudentName(estudiante),
                idSigerd: String(estudiante.id_sigerd || '-').trim() || '-',
                inscripcion,
                planif,
                historial,
                uiCondicion: mapDbEstadoToUi(historial ? historial.estado : ''),
            };
        }).filter(Boolean).sort((a, b) => a.estudianteNombre.localeCompare(b.estudianteNombre));
    }

    function rowSelectHtml(selected) {
        const values = [
            [UI_NO_DEFINIDO, 'NO DEFINIDO'],
            [UI_PROMOVIDO, 'PROMOVIDO'],
            [UI_ABANDONO, 'ABANDONO'],
            [UI_TRANSFERIDO, 'TRANSFERIDO'],
            [UI_REPROBADO, 'REPROBADO'],
        ];

        return '<select class="form-control form-control-sm condicion-final-select">'
            + values.map((item) => {
                return '<option value="' + item[0] + '"' + (selected === item[0] ? ' selected' : '') + '>' + item[1] + '</option>';
            }).join('')
            + '</select>';
    }

    function renderLoadedRows() {
        if (state.loadedRows.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No hay estudiantes inscritos para la combinacion seleccionada.</td></tr>';
            setStatus('No hay estudiantes para los filtros seleccionados.', 'warning');
            return;
        }

        tbody.innerHTML = state.loadedRows.map((row, idx) => {
            return ''
                + '<tr data-estudiante-id="' + row.estudianteId + '">'
                + '<td>' + (idx + 1) + '</td>'
                + '<td>' + escHtml(row.estudianteNombre) + '</td>'
                + '<td>' + escHtml(row.idSigerd) + '</td>'
                + '<td>' + rowSelectHtml(row.uiCondicion) + '</td>'
                + '<td><button type="button" class="btn btn-primary btn-sm btn-guardar-condicion" data-estudiante-id="' + row.estudianteId + '"><i class="fas fa-save mr-1"></i> Guardar</button></td>'
                + '</tr>';
        }).join('');

        setStatus('Condiciones cargadas. Este modulo actualiza historial academico para mantener consistencia.', 'success');
    }

    async function guardarCondicion(estudianteId, uiCondicion) {
        const row = state.loadedRows.find((item) => item.estudianteId === estudianteId) || null;
        if (!row) {
            throw new Error('No se encontro el estudiante en la grilla cargada.');
        }

        const dbEstado = mapUiEstadoToDb(uiCondicion);
        const planif = row.planif;
        const historial = row.historial;
        const jornada = getPlanifJornada(planif);

        if (!historial) {
            if (uiCondicion === UI_NO_DEFINIDO) {
                return;
            }

            await apiStore('historial_academico', {
                estudiante_id: row.estudianteId,
                anio_escolar_id: Number(planif.anio_escolar_id || 0),
                nivel_id: Number(planif.nivel_id || 0),
                grado_id: Number(planif.grado_id || 0),
                seccion_id: Number(planif.seccion_id || 0),
                jornada: jornada,
                fecha_inicio: String(row.inscripcion.fecha_inscripcion || getTodayLocalISO()).trim(),
                fecha_fin: getTodayLocalISO(),
                estado: dbEstado,
                observaciones: 'Condicion final actualizada desde modulo Condicion final de estudiante.',
            });

            return;
        }

        if (uiCondicion === UI_NO_DEFINIDO) {
            await apiUpdate('historial_academico', { id: Number(historial.id || 0) }, {
                estado: DB_CURSANDO,
                fecha_fin: null,
            });
            return;
        }

        await apiUpdate('historial_academico', { id: Number(historial.id || 0) }, {
            estado: dbEstado,
            fecha_fin: getTodayLocalISO(),
        });
    }

    async function loadBaseData() {
        const [
            anios,
            niveles,
            grados,
            secciones,
            tandas,
            planificaciones,
            inscripciones,
            estudiantes,
            historial,
        ] = await Promise.all([
            apiGet('anios_escolares'),
            apiGet('niveles'),
            apiGet('grados'),
            apiGet('secciones'),
            apiGet('tandas'),
            apiGet('planificaciones_academicas'),
            apiGet('inscripciones'),
            apiGet('estudiantes'),
            apiGet('historial_academico'),
        ]);

        state.aniosMap = toMap(anios, 'id');
        state.nivelesMap = toMap(niveles, 'id');
        state.gradosMap = toMap(grados, 'id');
        state.seccionesMap = toMap(secciones, 'id');
        state.tandasMap = toMap(tandas, 'id');
        state.planificacionesMap = toMap(planificaciones, 'id');
        state.inscripciones = inscripciones;
        state.estudiantesMap = toMap(estudiantes, 'id');
        state.historialRows = historial;

        renderAnios();
        renderNiveles();
        renderGrados();
        renderSecciones();
    }

    async function recargarHistorial() {
        state.historialRows = await apiGet('historial_academico');
    }

    async function cargarEstudiantes() {
        const anioId = Number(filtroAnio.value || 0);
        const nivelId = Number(filtroNivel.value || 0);
        const gradoId = Number(filtroGrado.value || 0);
        const seccionId = Number(filtroSeccion.value || 0);

        if (anioId <= 0 || nivelId <= 0 || gradoId <= 0 || seccionId <= 0) {
            window.alert('Debes seleccionar ano academico, nivel, grado y seccion.');
            return;
        }

        setStatus('Cargando estudiantes e historial academico para condicion final...', 'info');
        await recargarHistorial();
        state.loadedRows = buildLoadedRows();
        renderLoadedRows();
    }

    async function init() {
        setStatus('Cargando catalogos academicos...', 'info');

        try {
            await loadBaseData();
            setStatus('Selecciona filtros y pulsa Cargar estudiantes para definir condicion final.', 'success');
        } catch (error) {
            setStatus(error.message || 'No se pudieron cargar los datos de condicion final.', 'danger');
        }
    }

    btnCargar.addEventListener('click', async function () {
        try {
            await cargarEstudiantes();
        } catch (error) {
            setStatus(error.message || 'No se pudieron cargar los estudiantes para condicion final.', 'danger');
        }
    });

    filtroAnio.addEventListener('change', function () {
        renderNiveles();
        renderGrados();
        renderSecciones();
    });

    filtroNivel.addEventListener('change', function () {
        renderGrados();
        renderSecciones();
    });

    filtroGrado.addEventListener('change', function () {
        renderSecciones();
    });

    tbody.addEventListener('click', async function (event) {
        const target = event.target instanceof HTMLElement ? event.target.closest('.btn-guardar-condicion') : null;
        if (!(target instanceof HTMLElement)) {
            return;
        }

        const estudianteId = Number(target.dataset.estudianteId || 0);
        if (estudianteId <= 0) {
            return;
        }

        const tr = target.closest('tr');
        const select = tr ? tr.querySelector('.condicion-final-select') : null;
        const uiCondicion = select instanceof HTMLSelectElement ? String(select.value || UI_NO_DEFINIDO) : UI_NO_DEFINIDO;

        const originalHtml = target.innerHTML;
        target.disabled = true;
        target.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...';

        try {
            await guardarCondicion(estudianteId, uiCondicion);
            await recargarHistorial();
            state.loadedRows = buildLoadedRows();
            renderLoadedRows();
            setStatus('Condicion final guardada en historial academico correctamente.', 'success');
        } catch (error) {
            setStatus(error.message || 'No se pudo guardar la condicion final.', 'danger');
        } finally {
            target.disabled = false;
            target.innerHTML = originalHtml;
        }
    });

    init();
})();
</script>
