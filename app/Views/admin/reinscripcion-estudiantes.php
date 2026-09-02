<?php

declare(strict_types=1);
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Reinscripcion de estudiantes</h1>
    <a id="btn-volver-relacion" href="index.php?view=relacion-estudiantes" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Volver a Relacion
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-flex flex-column" style="gap: 12px;">
            <h6 class="m-0 font-weight-bold text-primary">Filtros de busqueda</h6>
            <div class="d-flex flex-column flex-lg-row" style="gap: 12px;">
                <div style="min-width: 260px;">
                    <label for="filtro-reinscripcion-nombre" class="small font-weight-bold text-muted mb-1 d-block">Filtrar por nombre</label>
                    <input id="filtro-reinscripcion-nombre" type="text" class="form-control" placeholder="Escribe nombre del estudiante">
                </div>
                <div style="min-width: 220px;">
                    <label for="filtro-reinscripcion-sigerd" class="small font-weight-bold text-muted mb-1 d-block">Filtrar por ID SIGERD</label>
                    <input id="filtro-reinscripcion-sigerd" type="text" class="form-control" placeholder="Ej: SIG-001">
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="estado-reinscripcion" class="alert alert-info mb-3">Cargando estudiantes registrados...</div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Estudiante</th>
                    <th>ID SIGERD</th>
                    <th>Ultimo ano escolar</th>
                    <th>Ultimo grado</th>
                    <th>Ultima inscripcion</th>
                    <th style="min-width: 170px;">Accion</th>
                </tr>
                </thead>
                <tbody id="tbody-reinscripciones">
                <tr>
                    <td colspan="7" class="text-center text-muted">Sin datos</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-reinscribir-estudiante" tabindex="-1" role="dialog" aria-labelledby="modal-reinscribir-estudiante-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <form id="form-reinscribir-estudiante">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-reinscribir-estudiante-title">Reinscribir estudiante</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="reinscripcion-estudiante-id">

                    <div id="resumen-estudiante-reinscripcion" class="alert alert-light border mb-3"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="reinscripcion-anio">Ano escolar *</label>
                            <select id="reinscripcion-anio" class="form-control" required>
                                <option value="">Selecciona</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="reinscripcion-planificacion">Oferta academica (grado/seccion/tanda) *</label>
                            <select id="reinscripcion-planificacion" class="form-control" required>
                                <option value="">Selecciona una oferta academica</option>
                            </select>
                            <small id="reinscripcion-oferta-ayuda" class="form-text text-muted">Selecciona un ano para cargar la oferta academica.</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="reinscripcion-fecha">Fecha de reinscripcion *</label>
                            <input id="reinscripcion-fecha" type="date" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="reinscripcion-tarifa">Tarifa inscripcion *</label>
                            <input id="reinscripcion-tarifa" type="number" class="form-control" min="0" step="0.01" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="reinscripcion-mensualidad">Mensualidad *</label>
                            <input id="reinscripcion-mensualidad" type="number" class="form-control" min="0" step="0.01" required>
                        </div>
                    </div>
                    <small id="reinscripcion-tarifa-ayuda" class="form-text text-muted mb-3">La tarifa se intenta cargar automaticamente desde Configuracion del sistema.</small>

                    <div class="form-group">
                        <label for="reinscripcion-observaciones">Observaciones</label>
                        <textarea id="reinscripcion-observaciones" rows="3" class="form-control" maxlength="2000"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="reinscripcion-acepta-terminos" checked>
                                <label class="custom-control-label" for="reinscripcion-acepta-terminos">Acepta terminos y condiciones</label>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="reinscripcion-activa" checked>
                                <label class="custom-control-label" for="reinscripcion-activa">Inscripcion activa</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btn-guardar-reinscripcion">
                        <i class="fas fa-save mr-1"></i> Guardar reinscripcion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    const apiBase = '../';
    const estado = document.getElementById('estado-reinscripcion');
    const tbody = document.getElementById('tbody-reinscripciones');
    const filtroNombre = document.getElementById('filtro-reinscripcion-nombre');
    const filtroSigerd = document.getElementById('filtro-reinscripcion-sigerd');

    const modal = document.getElementById('modal-reinscribir-estudiante');
    const form = document.getElementById('form-reinscribir-estudiante');
    const estudianteIdInput = document.getElementById('reinscripcion-estudiante-id');
    const resumenEstudiante = document.getElementById('resumen-estudiante-reinscripcion');
    const anioSelect = document.getElementById('reinscripcion-anio');
    const planifSelect = document.getElementById('reinscripcion-planificacion');
    const ofertaAyuda = document.getElementById('reinscripcion-oferta-ayuda');
    const fechaInput = document.getElementById('reinscripcion-fecha');
    const tarifaInput = document.getElementById('reinscripcion-tarifa');
    const mensualidadInput = document.getElementById('reinscripcion-mensualidad');
    const tarifaAyuda = document.getElementById('reinscripcion-tarifa-ayuda');
    const observacionesInput = document.getElementById('reinscripcion-observaciones');
    const aceptaTerminosInput = document.getElementById('reinscripcion-acepta-terminos');
    const activaInput = document.getElementById('reinscripcion-activa');
    const btnGuardar = document.getElementById('btn-guardar-reinscripcion');
    const btnVolverRelacion = document.getElementById('btn-volver-relacion');

    const state = {
        estudiantesMap: new Map(),
        inscripciones: [],
        planificacionesMap: new Map(),
        aniosMap: new Map(),
        nivelesMap: new Map(),
        gradosMap: new Map(),
        seccionesMap: new Map(),
        tandasMap: new Map(),
        tarifarios: [],
        tarifasGrados: [],
        defaultTarifarioId: 0,
        rows: [],
        returnTo: 'index.php?view=relacion-estudiantes',
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

    function normalizeText(value) {
        return String(value || '')
            .toUpperCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .trim();
    }

    function formatDateIso(value) {
        const text = String(value || '').trim();
        if (!/^\d{4}-\d{2}-\d{2}$/.test(text)) {
            return '-';
        }

        const parts = text.split('-');
        return parts[2] + '/' + parts[1] + '/' + parts[0];
    }

    function getTodayLocalISO() {
        const now = new Date();
        const offsetMs = now.getTimezoneOffset() * 60000;
        return new Date(now.getTime() - offsetMs).toISOString().slice(0, 10);
    }

    function getQueryParam(name) {
        const params = new URLSearchParams(window.location.search || '');
        return String(params.get(name) || '').trim();
    }

    function resolveReturnToUrl() {
        const raw = getQueryParam('return_to');
        if (raw === '') {
            return 'index.php?view=relacion-estudiantes';
        }

        // Evita redirecciones externas: solo rutas internas del admin.
        if (raw.startsWith('index.php?')) {
            return raw;
        }

        return 'index.php?view=relacion-estudiantes';
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

    function buildTurnoLabel(planif) {
        const tandaId = Number(planif.tanda_id || 0);
        if (tandaId > 0 && state.tandasMap.has(tandaId)) {
            const tanda = state.tandasMap.get(tandaId);
            const nombre = String(tanda.nombre || tanda.codigo || '').trim();
            if (nombre !== '') {
                return nombre.toUpperCase();
            }
        }

        const jornada = String(planif.jornada || '').trim();
        return jornada !== '' ? jornada.toUpperCase() : '-';
    }

    function getPlanifJornada(planif) {
        const explicit = String(planif.jornada || '').trim().toUpperCase();
        if (explicit === 'MATUTINO' || explicit === 'VESPERTINO') {
            return explicit;
        }

        const tandaId = Number(planif.tanda_id || 0);
        if (tandaId > 0 && state.tandasMap.has(tandaId)) {
            const tanda = state.tandasMap.get(tandaId);
            const codigo = String(tanda.codigo || '').trim().toUpperCase();
            if (codigo === 'MATUTINO' || codigo === 'VESPERTINO') {
                return codigo;
            }
        }

        return '';
    }

    function resolveDefaultTarifarioId() {
        const activo = state.tarifarios.find((row) => Number(row.estado ?? 1) === 1) || state.tarifarios[0] || null;
        state.defaultTarifarioId = activo ? Number(activo.id || 0) : 0;
    }

    function nombreEstudiante(estudiante) {
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

    function setTarifaAyuda(message, type) {
        const classes = {
            info: 'form-text text-muted mb-3',
            success: 'form-text text-success mb-3',
            warning: 'form-text text-warning mb-3',
        };

        tarifaAyuda.className = classes[type] || classes.info;
        tarifaAyuda.textContent = message;
    }

    function clearTarifaAuto() {
        tarifaInput.value = '0';
        mensualidadInput.value = '0';
        setTarifaAyuda('La tarifa se intenta cargar automaticamente desde Configuracion del sistema.', 'info');
    }

    function applyTarifaByPlanifId(planifId) {
        const planif = state.planificacionesMap.get(Number(planifId || 0)) || null;
        if (!planif) {
            clearTarifaAuto();
            return;
        }

        const nivelId = Number(planif.nivel_id || 0);
        const gradoId = Number(planif.grado_id || 0);
        const jornada = getPlanifJornada(planif);

        let candidates = state.tarifasGrados.filter((row) =>
            Number(row.nivel_id || 0) === nivelId
            && Number(row.grado_id || 0) === gradoId
            && String(row.jornada || '').toUpperCase() === jornada
            && Number(row.activo ?? 1) === 1
        );

        if (state.defaultTarifarioId > 0) {
            const filtered = candidates.filter((row) => Number(row.tarifario_id || 0) === state.defaultTarifarioId);
            if (filtered.length > 0) {
                candidates = filtered;
            }
        }

        if (candidates.length === 0) {
            clearTarifaAuto();
            setTarifaAyuda('No se encontro tarifa configurada para esta oferta academica.', 'warning');
            return;
        }

        candidates.sort((a, b) => Number(b.id || 0) - Number(a.id || 0));
        const tarifa = candidates[0];
        tarifaInput.value = String(tarifa.tarifa_inscripcion ?? 0);
        mensualidadInput.value = String(tarifa.mensualidad ?? 0);
        setTarifaAyuda('Tarifa cargada automaticamente desde Configuracion del sistema.', 'success');
    }

    function showModal(element) {
        if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.modal === 'function') {
            window.jQuery(element).modal('show');
            return;
        }

        element.style.display = 'block';
        element.classList.add('show');
        element.setAttribute('aria-modal', 'true');
        element.removeAttribute('aria-hidden');
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';

        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.setAttribute('data-fallback-backdrop', '1');
        document.body.appendChild(backdrop);
    }

    function hideModal(element) {
        if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.modal === 'function') {
            window.jQuery(element).modal('hide');
            return;
        }

        element.classList.remove('show');
        element.setAttribute('aria-hidden', 'true');
        element.removeAttribute('aria-modal');
        element.style.display = 'none';

        document.querySelectorAll('[data-fallback-backdrop="1"]').forEach((node) => node.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
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

    function buildRows() {
        const latestByStudent = new Map();
        state.inscripciones.forEach((item) => {
            const estudianteId = Number(item.estudiante_id || 0);
            if (estudianteId <= 0) {
                return;
            }

            const current = latestByStudent.get(estudianteId) || null;
            if (!current) {
                latestByStudent.set(estudianteId, item);
                return;
            }

            const a = String(item.fecha_inscripcion || '') + '-' + String(item.id || 0);
            const b = String(current.fecha_inscripcion || '') + '-' + String(current.id || 0);
            if (a > b) {
                latestByStudent.set(estudianteId, item);
            }
        });

        state.rows = Array.from(state.estudiantesMap.values()).map((estudiante) => {
            const estudianteId = Number(estudiante.id || 0);
            const latestInscripcion = latestByStudent.get(estudianteId) || null;
            const planif = latestInscripcion ? state.planificacionesMap.get(Number(latestInscripcion.planificacion_academica_id || 0)) : null;
            const anio = planif ? state.aniosMap.get(Number(planif.anio_escolar_id || 0)) : null;
            const grado = planif ? state.gradosMap.get(Number(planif.grado_id || 0)) : null;

            const nombre = nombreEstudiante(estudiante);
            return {
                estudianteId,
                estudianteRaw: estudiante,
                nombre,
                nombreBusqueda: normalizeText(nombre),
                idSigerd: String(estudiante.id_sigerd || '-').trim() || '-',
                idSigerdBusqueda: normalizeText(String(estudiante.id_sigerd || '')),
                ultimoAnio: anio ? String(anio.nombre || '-').trim() || '-' : '-',
                ultimoGrado: grado ? String(grado.grado || '-').trim() || '-' : '-',
                ultimaFecha: latestInscripcion ? formatDateIso(latestInscripcion.fecha_inscripcion || '') : '-',
            };
        }).sort((a, b) => a.nombre.localeCompare(b.nombre));
    }

    function getFilteredRows() {
        const nombreTerm = normalizeText(filtroNombre.value || '');
        const sigerdTerm = normalizeText(filtroSigerd.value || '');

        return state.rows.filter((row) => {
            const nombreOk = nombreTerm === '' || row.nombreBusqueda.includes(nombreTerm);
            const sigerdOk = sigerdTerm === '' || row.idSigerdBusqueda.includes(sigerdTerm);
            return nombreOk && sigerdOk;
        });
    }

    function renderRows() {
        const rows = getFilteredRows();
        if (rows.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No hay estudiantes para los filtros aplicados.</td></tr>';
            return;
        }

        tbody.innerHTML = rows.map((row, idx) => {
            return ''
                + '<tr>'
                + '<td>' + (idx + 1) + '</td>'
                + '<td>' + escHtml(row.nombre) + '</td>'
                + '<td>' + escHtml(row.idSigerd) + '</td>'
                + '<td>' + escHtml(row.ultimoAnio) + '</td>'
                + '<td>' + escHtml(row.ultimoGrado) + '</td>'
                + '<td>' + escHtml(row.ultimaFecha) + '</td>'
                + '<td><button type="button" class="btn btn-primary btn-sm btn-reinscribir" data-estudiante-id="' + row.estudianteId + '"><i class="fas fa-redo-alt mr-1"></i> Reinscribir</button></td>'
                + '</tr>';
        }).join('');
    }

    function renderAniosOferta() {
        const ids = Array.from(new Set(
            Array.from(state.planificacionesMap.values())
                .filter((row) => Number(row.estado ?? 1) === 1)
                .map((row) => Number(row.anio_escolar_id || 0))
                .filter((id) => id > 0)
        )).sort((a, b) => b - a);

        const options = ['<option value="">Selecciona</option>'];
        ids.forEach((id) => {
            const anio = state.aniosMap.get(id);
            const nombre = String((anio && anio.nombre) || ('ANO #' + id)).trim();
            options.push('<option value="' + id + '">' + escHtml(nombre) + '</option>');
        });

        anioSelect.innerHTML = options.join('');
        if (ids.length > 0) {
            anioSelect.value = String(ids[0]);
        }
    }

    function renderPlanificacionesOferta() {
        planifSelect.innerHTML = '<option value="">Selecciona una oferta academica</option>';
        clearTarifaAuto();

        const anioId = Number(anioSelect.value || 0);
        if (anioId <= 0) {
            ofertaAyuda.className = 'form-text text-muted';
            ofertaAyuda.textContent = 'Selecciona un ano para cargar la oferta academica.';
            return;
        }

        const ofertas = Array.from(state.planificacionesMap.values())
            .filter((row) => Number(row.estado ?? 1) === 1)
            .filter((row) => Number(row.anio_escolar_id || 0) === anioId)
            .sort((a, b) => Number(a.grado_id || 0) - Number(b.grado_id || 0));

        if (ofertas.length === 0) {
            ofertaAyuda.className = 'form-text text-warning';
            ofertaAyuda.textContent = 'No hay planificaciones academicas activas para ese ano.';
            return;
        }

        const options = ['<option value="">Selecciona una oferta academica</option>'];
        ofertas.forEach((planif) => {
            const nivel = state.nivelesMap.get(Number(planif.nivel_id || 0));
            const grado = state.gradosMap.get(Number(planif.grado_id || 0));
            const seccion = state.seccionesMap.get(Number(planif.seccion_id || 0));
            const nivelText = String((nivel && nivel.nivel) || ('NIVEL #' + planif.nivel_id)).toUpperCase();
            const gradoText = String((grado && grado.grado) || ('GRADO #' + planif.grado_id)).toUpperCase();
            const seccionText = String((seccion && seccion.seccion) || ('SECCION #' + planif.seccion_id)).toUpperCase();
            const tandaText = buildTurnoLabel(planif);

            options.push('<option value="' + Number(planif.id || 0) + '">' + escHtml(nivelText + ' - ' + gradoText + ' - Seccion ' + seccionText + ' - ' + tandaText) + '</option>');
        });

        planifSelect.innerHTML = options.join('');
        ofertaAyuda.className = 'form-text text-success';
        ofertaAyuda.textContent = 'Oferta academica cargada correctamente para reinscripcion.';
    }

    function getRowByEstudianteId(estudianteId) {
        const numericId = Number(estudianteId || 0);
        return state.rows.find((row) => row.estudianteId === numericId) || null;
    }

    function openReinscripcionModal(row) {
        estudianteIdInput.value = String(row.estudianteId);
        resumenEstudiante.innerHTML = ''
            + '<div><strong>Estudiante:</strong> ' + escHtml(row.nombre) + '</div>'
            + '<div><strong>ID SIGERD:</strong> ' + escHtml(row.idSigerd) + '</div>'
            + '<div><strong>Ultimo ano escolar:</strong> ' + escHtml(row.ultimoAnio) + '</div>'
            + '<div><strong>Ultimo grado:</strong> ' + escHtml(row.ultimoGrado) + '</div>';

        observacionesInput.value = '';
        aceptaTerminosInput.checked = true;
        activaInput.checked = true;
        fechaInput.value = getTodayLocalISO();

        renderAniosOferta();
        renderPlanificacionesOferta();
        showModal(modal);
    }

    function toNullableString(value) {
        const cleaned = String(value || '').trim();
        return cleaned === '' ? null : cleaned;
    }

    function toDecimal(value) {
        const numeric = Number(value);
        return Number.isFinite(numeric) ? numeric : 0;
    }

    function reinscripcionDuplicada(estudianteId, planifId) {
        return state.inscripciones.some((item) =>
            Number(item.estudiante_id || 0) === Number(estudianteId)
            && Number(item.planificacion_academica_id || 0) === Number(planifId)
        );
    }

    async function reloadData() {
        const [
            estudiantes,
            inscripciones,
            planificaciones,
            anios,
            niveles,
            grados,
            secciones,
            tandas,
            tarifarios,
            tarifasGrados,
        ] = await Promise.all([
            apiGet('estudiantes'),
            apiGet('inscripciones'),
            apiGet('planificaciones_academicas'),
            apiGet('anios_escolares'),
            apiGet('niveles'),
            apiGet('grados'),
            apiGet('secciones'),
            apiGet('tandas'),
            apiGet('tarifarios'),
            apiGet('tarifas_grados'),
        ]);

        state.estudiantesMap = toMap(estudiantes, 'id');
        state.inscripciones = inscripciones;
        state.planificacionesMap = toMap(planificaciones, 'id');
        state.aniosMap = toMap(anios, 'id');
        state.nivelesMap = toMap(niveles, 'id');
        state.gradosMap = toMap(grados, 'id');
        state.seccionesMap = toMap(secciones, 'id');
        state.tandasMap = toMap(tandas, 'id');
        state.tarifarios = tarifarios;
        state.tarifasGrados = tarifasGrados;
        resolveDefaultTarifarioId();

        buildRows();
        renderRows();
    }

    async function init() {
        setStatus('Cargando estudiantes registrados para reinscripcion...', 'info');
        try {
            state.returnTo = resolveReturnToUrl();
            btnVolverRelacion.setAttribute('href', state.returnTo);

            await reloadData();
            setStatus('Selecciona un estudiante para generar su reinscripcion en un nuevo ano escolar.', 'success');

            const estudianteIdFromQuery = Number(getQueryParam('estudiante_id') || 0);
            if (estudianteIdFromQuery > 0) {
                const row = getRowByEstudianteId(estudianteIdFromQuery);
                if (row) {
                    openReinscripcionModal(row);
                }
            }
        } catch (error) {
            setStatus(error.message || 'No se pudieron cargar los datos para reinscripcion.', 'danger');
        }
    }

    tbody.addEventListener('click', function (event) {
        const target = event.target instanceof HTMLElement ? event.target.closest('.btn-reinscribir') : null;
        if (!(target instanceof HTMLElement)) {
            return;
        }

        const estudianteId = Number(target.dataset.estudianteId || 0);
        const row = getRowByEstudianteId(estudianteId);
        if (!row) {
            window.alert('No se encontro el estudiante seleccionado.');
            return;
        }

        openReinscripcionModal(row);
    });

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        if (!form.reportValidity()) {
            return;
        }

        const estudianteId = Number(estudianteIdInput.value || 0);
        const planifId = Number(planifSelect.value || 0);
        if (estudianteId <= 0 || planifId <= 0) {
            window.alert('Debes seleccionar un estudiante y una oferta academica valida.');
            return;
        }

        if (reinscripcionDuplicada(estudianteId, planifId)) {
            window.alert('Este estudiante ya tiene una inscripcion para esa oferta academica.');
            return;
        }

        const originalHtml = btnGuardar.innerHTML;
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...';

        try {
            await apiStore('inscripciones', {
                estudiante_id: estudianteId,
                planificacion_academica_id: planifId,
                centro_procedencia: null,
                tarifa_inscripcion: toDecimal(tarifaInput.value),
                mensualidad: toDecimal(mensualidadInput.value),
                fecha_inscripcion: String(fechaInput.value || getTodayLocalISO()).trim(),
                acepta_terminos: aceptaTerminosInput.checked ? 1 : 0,
                inscripcion_activa: activaInput.checked ? 1 : 0,
                observaciones: toNullableString(observacionesInput.value),
            });

            hideModal(modal);
            await reloadData();
            setStatus('Reinscripcion guardada correctamente.', 'success');
            window.location.href = state.returnTo;
        } catch (error) {
            window.alert(error.message || 'No se pudo guardar la reinscripcion.');
        } finally {
            btnGuardar.disabled = false;
            btnGuardar.innerHTML = originalHtml;
        }
    });

    anioSelect.addEventListener('change', function () {
        renderPlanificacionesOferta();
    });

    planifSelect.addEventListener('change', function () {
        applyTarifaByPlanifId(planifSelect.value);
    });

    filtroNombre.addEventListener('input', renderRows);
    filtroSigerd.addEventListener('input', renderRows);

    document.addEventListener('click', function (event) {
        const target = event.target instanceof HTMLElement ? event.target : null;
        if (!target) {
            return;
        }

        const dismiss = target.closest('[data-dismiss="modal"]');
        if (!dismiss) {
            return;
        }

        const modalRoot = dismiss.closest('.modal');
        if (modalRoot instanceof HTMLElement) {
            hideModal(modalRoot);
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') {
            return;
        }

        if (modal.classList.contains('show') || modal.style.display === 'block') {
            hideModal(modal);
        }
    });

    init();
})();
</script>
