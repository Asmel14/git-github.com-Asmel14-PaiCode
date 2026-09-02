<?php

declare(strict_types=1);
?>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap4.min.css" rel="stylesheet">
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Modulo financiero - Historial de pagos</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">1) Filtrar estudiante</h6>
    </div>
    <div class="card-body">
        <div class="form-row align-items-end">
            <div class="form-group col-lg-6 mb-0">
                <label for="historial-estudiante">Estudiante inscrito</label>
                <select id="historial-estudiante" class="form-control">
                    <option value="">Cargando estudiantes...</option>
                </select>
            </div>
            <div class="form-group col-lg-6 mb-0">
                <div id="historial-estudiante-info" class="alert alert-info mb-0 py-2 px-3">Selecciona un estudiante para consultar su historial de pagos.</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">2) Historial de pagos</h6>
        <span id="historial-pagos-count" class="badge badge-light">0 registros</span>
    </div>
    <div class="card-body">
        <div class="form-row mb-3">
            <div class="form-group col-lg-3 mb-0">
                <label for="historial-fecha-desde">Desde</label>
                <input id="historial-fecha-desde" type="date" class="form-control">
            </div>
            <div class="form-group col-lg-3 mb-0">
                <label for="historial-fecha-hasta">Hasta</label>
                <input id="historial-fecha-hasta" type="date" class="form-control">
            </div>
            <div class="form-group col-lg-3 mb-0">
                <label for="historial-anio">Año escolar</label>
                <select id="historial-anio" class="form-control">
                    <option value="">Todos</option>
                </select>
            </div>
            <div class="form-group col-lg-3 mb-0">
                <label for="historial-periodo">Período</label>
                <select id="historial-periodo" class="form-control">
                    <option value="">Todos</option>
                </select>
            </div>
        </div>
        <div class="table-responsive mb-3">
            <table class="table table-bordered table-sm" id="tabla-historial-pagos-general" width="100%" cellspacing="0">
                <thead class="thead-light">
                <tr>
                    <th>Fecha</th>
                    <th>Periodo</th>
                    <th>Recibo</th>
                    <th>Metodo</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody id="tbody-historial-pagos-general">
                <tr>
                    <td colspan="5" class="text-center text-muted">Sin datos</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-danger" id="btn-imprimir-historial-pdf">
                <i class="fas fa-file-pdf mr-1"></i> Imprimir historial PDF
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
(function () {
    const MARCADOR_DETALLE = 'DETALLE_PAGO_JSON:';

    const estudianteSelect = document.getElementById('historial-estudiante');
    const estudianteInfo = document.getElementById('historial-estudiante-info');
    const historialCount = document.getElementById('historial-pagos-count');
    const tbodyHistorial = document.getElementById('tbody-historial-pagos-general');
    const btnImprimirHistorial = document.getElementById('btn-imprimir-historial-pdf');
    const fechaDesdeInput = document.getElementById('historial-fecha-desde');
    const fechaHastaInput = document.getElementById('historial-fecha-hasta');
    const anioSelect = document.getElementById('historial-anio');
    const periodoSelect = document.getElementById('historial-periodo');

    const state = {
        estudiantes: [],
        inscripciones: [],
        planificaciones: new Map(),
        aniosEscolares: new Map(),
        metodosPago: new Map(),
        pagos: [],
        pagosDetalleById: new Map(),
        estudiantesInscritos: [],
        tomEstudiantes: null,
        datosCentro: null,
    };

    function escHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formatMoney(value) {
        const amount = Number(value || 0);
        return 'RD$ ' + amount.toLocaleString('es-DO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function pad2(value) {
        return String(value).padStart(2, '0');
    }

    function getTodayIso() {
        const now = new Date();
        return [now.getFullYear(), pad2(now.getMonth() + 1), pad2(now.getDate())].join('-');
    }

    function getPublicBaseUrl() {
        const pathname = String(window.location.pathname || '');
        const basePath = pathname.replace(/\/admin\/index\.php$/, '/');
        return window.location.origin + basePath;
    }

    function resolveStoredAssetUrl(path) {
        const raw = String(path || '').trim().replace(/\\/g, '/');
        if (raw === '') {
            return '';
        }

        if (raw.startsWith('http://') || raw.startsWith('https://') || raw.startsWith('data:')) {
            return raw;
        }

        if (raw.startsWith('/')) {
            return window.location.origin + raw;
        }

        return getPublicBaseUrl() + raw.replace(/^\.\//, '');
    }

    function formatDateISO(value) {
        const text = String(value || '').trim();
        if (!/^\d{4}-\d{2}-\d{2}/.test(text)) {
            return '-';
        }

        return text.slice(8, 10) + '/' + text.slice(5, 7) + '/' + text.slice(0, 4);
    }

    function parseDate(value) {
        const text = String(value || '').trim();
        if (!/^\d{4}-\d{2}-\d{2}/.test(text)) {
            return null;
        }

        const dt = new Date(text.slice(0, 10) + 'T00:00:00');
        return Number.isNaN(dt.getTime()) ? null : dt;
    }

    function fullName(estudiante) {
        const parts = [
            estudiante.primer_nombre,
            estudiante.segundo_nombre,
            estudiante.primer_apellido,
            estudiante.segundo_apellido,
        ].map((item) => String(item || '').trim()).filter(Boolean);

        return parts.join(' ');
    }

    function toMap(rows, keyField) {
        const map = new Map();
        rows.forEach((row) => {
            const id = Number(row[keyField] || 0);
            if (id > 0) {
                map.set(id, row);
            }
        });
        return map;
    }

    async function apiRequest(resource, action, options) {
        const method = (options && options.method) ? options.method : 'GET';
        const payload = (options && options.payload) ? options.payload : null;
        const query = (options && options.query) ? options.query : {};

        const params = new URLSearchParams();
        params.set('resource', resource);
        if (action) {
            params.set('action', action);
        }

        Object.keys(query).forEach((key) => {
            params.set(key, String(query[key]));
        });

        const response = await fetch('../?' + params.toString(), {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: payload ? JSON.stringify(payload) : undefined,
            credentials: 'same-origin',
        });

        const raw = await response.text();
        let json;
        try {
            json = JSON.parse(raw);
        } catch (error) {
            throw new Error('Respuesta invalida del API para ' + resource + '.');
        }

        if (!response.ok || !json.success) {
            throw new Error((json && json.message) ? json.message : ('Error consultando ' + resource + '.'));
        }

        return json.data;
    }

    function parseDetallePagoFromObservaciones(value) {
        const text = String(value || '');
        const markerIndex = text.indexOf(MARCADOR_DETALLE);
        if (markerIndex < 0) {
            return null;
        }

        const jsonText = text.slice(markerIndex + MARCADOR_DETALLE.length).trim();
        if (jsonText === '') {
            return null;
        }

        try {
            const parsed = JSON.parse(jsonText);
            if (!parsed || !Array.isArray(parsed.items)) {
                return null;
            }
            return parsed;
        } catch (error) {
            return null;
        }
    }

    function selectedEstudianteId() {
        return Number(estudianteSelect.value || 0);
    }

    function getPagoInscripcionId(pago) {
        const detalle = state.pagosDetalleById.get(Number(pago.id || 0)) || null;
        return detalle ? Number(detalle.inscripcion_id || 0) : 0;
    }

    function getPagoAnioEscolarId(pago) {
        const inscripcionId = getPagoInscripcionId(pago);
        if (inscripcionId <= 0) {
            return 0;
        }

        const inscripcion = state.inscripciones.find((row) => Number(row.id || 0) === inscripcionId) || null;
        if (!inscripcion) {
            return 0;
        }

        const planificacion = state.planificaciones.get(Number(inscripcion.planificacion_academica_id || 0)) || null;
        return planificacion ? Number(planificacion.anio_escolar_id || 0) : 0;
    }

    function getPagoPeriodos(pago) {
        const detalle = state.pagosDetalleById.get(Number(pago.id || 0)) || null;
        if (!detalle || !Array.isArray(detalle.items)) {
            return [];
        }

        return Array.from(new Set(detalle.items
            .map((item) => String(item.periodo || '').trim())
            .filter((value) => value !== '')));
    }

    function getPagosByEstudiante(estudianteId) {
        return state.pagos
            .filter((pago) => Number(pago.estudiante_id || 0) === estudianteId)
            .sort((a, b) => String(b.fecha_pago || '').localeCompare(String(a.fecha_pago || '')));
    }

    function getPagosFiltrados(estudianteId) {
        const desde = String(fechaDesdeInput.value || '').trim();
        const hasta = String(fechaHastaInput.value || '').trim();
        const anioId = Number(anioSelect.value || 0);
        const periodo = String(periodoSelect.value || '').trim();

        return getPagosByEstudiante(estudianteId).filter((pago) => {
            const fechaPago = String(pago.fecha_pago || '').slice(0, 10);
            if (desde !== '' && fechaPago < desde) {
                return false;
            }
            if (hasta !== '' && fechaPago > hasta) {
                return false;
            }

            if (anioId > 0 && getPagoAnioEscolarId(pago) !== anioId) {
                return false;
            }

            if (periodo !== '') {
                const periodos = getPagoPeriodos(pago);
                if (!periodos.includes(periodo)) {
                    return false;
                }
            }

            return true;
        });
    }

    function renderEstudiantes() {
        const activos = state.inscripciones
            .filter((row) => Number(row.inscripcion_activa || 0) === 1)
            .map((row) => Number(row.estudiante_id || 0));

        const idsActivos = new Set(activos);
        const lista = state.estudiantes
            .filter((estudiante) => idsActivos.has(Number(estudiante.id || 0)))
            .sort((a, b) => fullName(a).localeCompare(fullName(b)));

        state.estudiantesInscritos = lista;
        const selectedBefore = Number(estudianteSelect.value || 0);

        if (lista.length === 0) {
            estudianteSelect.innerHTML = '<option value="">No hay estudiantes inscritos activos</option>';
            return;
        }

        let html = '<option value="">Seleccione estudiante...</option>';
        const options = [{ value: '', text: 'Seleccione estudiante...' }];
        lista.forEach((estudiante) => {
            const id = Number(estudiante.id || 0);
            const etiqueta = fullName(estudiante) + ' (' + String(estudiante.id_sigerd || ('ID ' + id)) + ')';
            html += '<option value="' + id + '">' + escHtml(etiqueta) + '</option>';
            options.push({ value: String(id), text: etiqueta });
        });

        estudianteSelect.innerHTML = html;

        if (!state.tomEstudiantes && typeof window.TomSelect === 'function') {
            state.tomEstudiantes = new window.TomSelect(estudianteSelect, {
                create: false,
                allowEmptyOption: true,
                placeholder: 'Seleccione o escriba para buscar',
                maxOptions: 500,
                searchField: ['text'],
                onChange: function () {
                    renderHistorial();
                },
            });
        } else if (state.tomEstudiantes) {
            state.tomEstudiantes.clearOptions();
            state.tomEstudiantes.addOptions(options);
            const existeSeleccion = options.some((opt) => Number(opt.value || 0) === selectedBefore);
            state.tomEstudiantes.setValue(existeSeleccion ? String(selectedBefore) : '', true);
            state.tomEstudiantes.refreshOptions(false);
        }
    }

    function renderFiltrosHistorial() {
        const estudianteId = selectedEstudianteId();
        const pagos = estudianteId > 0 ? getPagosByEstudiante(estudianteId) : [];
        const anioSeleccionado = String(anioSelect.value || '');
        const periodoSeleccionado = String(periodoSelect.value || '');

        const anioIds = Array.from(new Set(pagos.map((pago) => getPagoAnioEscolarId(pago)).filter((id) => id > 0)));
        anioIds.sort((a, b) => b - a);

        let anioHtml = '<option value="">Todos</option>';
        anioIds.forEach((id) => {
            const anio = state.aniosEscolares.get(id) || null;
            const nombre = anio ? String(anio.nombre || ('AÑO #' + id)) : ('AÑO #' + id);
            anioHtml += '<option value="' + id + '">' + escHtml(nombre) + '</option>';
        });
        anioSelect.innerHTML = anioHtml;
        if (anioSeleccionado !== '' && anioSelect.querySelector('option[value="' + anioSeleccionado + '"]')) {
            anioSelect.value = anioSeleccionado;
        }

        const periodos = Array.from(new Set(pagos.flatMap((pago) => getPagoPeriodos(pago)))).sort((a, b) => a.localeCompare(b));
        let periodoHtml = '<option value="">Todos</option>';
        periodos.forEach((nombre) => {
            periodoHtml += '<option value="' + escHtml(nombre) + '">' + escHtml(nombre) + '</option>';
        });
        periodoSelect.innerHTML = periodoHtml;
        if (periodoSeleccionado !== '' && Array.from(periodoSelect.options).some((opt) => opt.value === periodoSeleccionado)) {
            periodoSelect.value = periodoSeleccionado;
        }
    }

    function renderHistorial() {
        const estudianteId = selectedEstudianteId();
        renderFiltrosHistorial();

        if (estudianteId <= 0) {
            estudianteInfo.textContent = 'Selecciona un estudiante para consultar su historial de pagos.';
            historialCount.textContent = '0 registros';
            tbodyHistorial.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Sin datos</td></tr>';
            return;
        }

        const estudiante = state.estudiantes.find((row) => Number(row.id || 0) === estudianteId) || null;
        const pagos = getPagosFiltrados(estudianteId);

        estudianteInfo.textContent = 'Estudiante: ' + (estudiante ? fullName(estudiante) : 'N/D') + ' | ID SIGERD: ' + (estudiante ? String(estudiante.id_sigerd || '-') : '-');
        historialCount.textContent = pagos.length + ' registros';

        if (pagos.length === 0) {
            tbodyHistorial.innerHTML = '<tr><td colspan="5" class="text-center text-muted">El estudiante no tiene pagos registrados.</td></tr>';
            return;
        }

        let html = '';
        pagos.forEach((pago) => {
            const metodo = state.metodosPago.get(Number(pago.metodo_pago_id || 0)) || null;
            const detalle = state.pagosDetalleById.get(Number(pago.id || 0)) || null;
            let periodosText = '-';

            if (detalle && Array.isArray(detalle.items)) {
                const periodos = detalle.items
                    .map((item) => String(item.periodo || '').trim())
                    .filter((value) => value !== '');
                const periodosUnicos = Array.from(new Set(periodos));
                periodosText = periodosUnicos.length > 0 ? periodosUnicos.join(', ') : '-';
            }

            html += '<tr>'
                + '<td>' + escHtml(formatDateISO(pago.fecha_pago)) + '</td>'
                + '<td>' + escHtml(periodosText) + '</td>'
                + '<td>' + escHtml(String(pago.numero_recibo || '-')) + '</td>'
                + '<td>' + escHtml(metodo ? String(metodo.nombre || '-') : '-') + '</td>'
                + '<td class="text-right">' + escHtml(formatMoney(Number(pago.monto_total || 0))) + '</td>'
                + '</tr>';
        });

        tbodyHistorial.innerHTML = html;
    }

    function openPrintableHistorialPdf() {
        const estudianteId = selectedEstudianteId();
        if (estudianteId <= 0) {
            throw new Error('Selecciona un estudiante para imprimir el historial.');
        }

        const estudiante = state.estudiantes.find((row) => Number(row.id || 0) === estudianteId) || null;
        const centro = state.datosCentro || {};
        const logoUrl = resolveStoredAssetUrl(centro.logo || '');
        const pagos = getPagosFiltrados(estudianteId);
        const totalGeneral = pagos.reduce((acc, pago) => acc + Number(pago.monto_total || 0), 0);
        const anioLabel = anioSelect.value !== '' ? (anioSelect.options[anioSelect.selectedIndex] ? anioSelect.options[anioSelect.selectedIndex].textContent : 'Todos') : 'Todos';
        const periodoLabel = periodoSelect.value !== '' ? periodoSelect.value : 'Todos';
        const desdeLabel = fechaDesdeInput.value !== '' ? formatDateISO(fechaDesdeInput.value) : 'Sin límite';
        const hastaLabel = fechaHastaInput.value !== '' ? formatDateISO(fechaHastaInput.value) : 'Sin límite';

        let bodyRows = '<tr><td colspan="5" style="text-align:center;color:#6b7280;">Sin pagos registrados.</td></tr>';
        if (pagos.length > 0) {
            bodyRows = pagos.map((pago) => {
                const metodo = state.metodosPago.get(Number(pago.metodo_pago_id || 0)) || null;
                const detalle = state.pagosDetalleById.get(Number(pago.id || 0)) || null;
                let periodosText = '-';
                if (detalle && Array.isArray(detalle.items)) {
                    const periodos = detalle.items
                        .map((item) => String(item.periodo || '').trim())
                        .filter((value) => value !== '');
                    const periodosUnicos = Array.from(new Set(periodos));
                    periodosText = periodosUnicos.length > 0 ? periodosUnicos.join(', ') : '-';
                }

                return '<tr>'
                    + '<td>' + escHtml(formatDateISO(pago.fecha_pago)) + '</td>'
                    + '<td>' + escHtml(periodosText) + '</td>'
                    + '<td>' + escHtml(String(pago.numero_recibo || '-')) + '</td>'
                    + '<td>' + escHtml(metodo ? String(metodo.nombre || '-') : '-') + '</td>'
                    + '<td style="text-align:right;">' + escHtml(formatMoney(Number(pago.monto_total || 0))) + '</td>'
                    + '</tr>';
            }).join('');
        }

        const html = '<!doctype html><html lang="es"><head><meta charset="utf-8">'
            + '<title>Historial de pagos</title>'
            + '<style>'
            + 'body{font-family:Arial,Helvetica,sans-serif;margin:20px;color:#1f2933;line-height:1.35;}'
            + '.head{border:2px solid #1f2933;padding:12px 14px;margin-bottom:14px;}'
            + '.head-top{display:flex;justify-content:space-between;gap:16px;align-items:flex-start;}'
            + '.head-identidad h1{margin:0;font-size:18px;text-transform:uppercase;letter-spacing:.35px;}'
            + '.head-identidad p{margin:2px 0;font-size:12px;}'
            + '.head-documento{margin-top:8px;padding-top:8px;border-top:1px solid #b0b8c0;}'
            + '.head-documento h2{margin:0;font-size:22px;text-transform:uppercase;letter-spacing:.45px;}'
            + '.meta{margin-top:4px;font-size:12px;color:#4a5560;}'
            + '.foto{width:120px;min-width:120px;height:120px;border:1px solid #b0b8c0;display:flex;align-items:center;justify-content:center;background:#fafafa;overflow:hidden;}'
            + '.foto img{width:100%;height:100%;object-fit:cover;}'
            + '.foto-empty{font-size:11px;color:#66717d;text-align:center;padding:8px;}'
            + '.section{margin-bottom:14px;page-break-inside:avoid;}'
            + '.section h3{margin:0 0 8px 0;font-size:14px;background:#eef2f6;padding:7px 9px;border:1px solid #c8d1db;text-transform:uppercase;letter-spacing:.3px;}'
            + 'table{width:100%;border-collapse:collapse;}'
            + 'th,td{border:1px solid #d3dae2;padding:6px 8px;font-size:12px;vertical-align:top;}'
            + 'th{text-align:left;background:#f7f9fb;font-weight:700;}'
            + '.actions{margin-bottom:12px;}'
            + '.actions button{padding:6px 12px;font-size:12px;}'
            + '.actions .btn-volver{margin-left:6px;background:#f1f3f5;border:1px solid #b5bcc3;}'
            + '@media print {.actions{display:none;} body{margin:8mm;} .section{break-inside:avoid;}}'
            + '</style></head><body>'
            + '<div class="actions"><button onclick="window.print()">Imprimir</button><button class="btn-volver" onclick="window.close()">Volver</button></div>'
            + '<div class="head">'
            + '<div class="head-top">'
            + '<div class="head-identidad">'
            + '<h1>' + escHtml(String(centro.nombre_centro || 'Centro educativo').trim()) + '</h1>'
            + '<p>Codigo de centro: ' + escHtml(String(centro.codigo_centro || '-').trim()) + ' | RNC: ' + escHtml(String(centro.rnc || '-').trim()) + '</p>'
            + '<p>Direccion: ' + escHtml(String(centro.direccion || '-').trim()) + '</p>'
            + '<p>Telefono: ' + escHtml(String(centro.telefono || '-').trim()) + ' | Celular: ' + escHtml(String(centro.celular || '-').trim()) + '</p>'
            + '<p>Correo: ' + escHtml(String(centro.correo_electronico || '-').trim()) + '</p>'
            + (String(centro.lema || '').trim() !== '' ? '<p><strong>Lema:</strong> ' + escHtml(String(centro.lema || '').trim()) + '</p>' : '')
            + '<div class="head-documento">'
            + '<h2>Historial de pagos</h2>'
            + '<div class="meta">Fecha de impresion: ' + escHtml(formatDateISO(getTodayIso())) + '</div>'
            + '</div>'
            + '</div>'
            + '<div class="foto">' + (logoUrl !== '' ? '<img src="' + escHtml(logoUrl) + '" alt="Logo del centro">' : '<div class="foto-empty">Sin logo</div>') + '</div>'
            + '</div>'
            + '</div>'
            + '<section class="section">'
            + '<h3>Datos del estudiante</h3>'
            + '<table>'
            + '<tr><th style="width:30%;">Estudiante</th><td>' + escHtml(estudiante ? fullName(estudiante) : '-') + '</td></tr>'
            + '<tr><th>ID SIGERD</th><td>' + escHtml(estudiante ? String(estudiante.id_sigerd || '-') : '-') + '</td></tr>'
            + '<tr><th>Desde</th><td>' + escHtml(desdeLabel) + '</td></tr>'
            + '<tr><th>Hasta</th><td>' + escHtml(hastaLabel) + '</td></tr>'
            + '<tr><th>Año escolar</th><td>' + escHtml(String(anioLabel || 'Todos')) + '</td></tr>'
            + '<tr><th>Período</th><td>' + escHtml(periodoLabel) + '</td></tr>'
            + '</table>'
            + '</section>'
            + '<section class="section">'
            + '<h3>Detalle del historial</h3>'
            + '<table>'
            + '<thead><tr><th>Fecha</th><th>Periodo</th><th>Recibo</th><th>Metodo</th><th>Total</th></tr></thead>'
            + '<tbody>' + bodyRows + '</tbody>'
            + '</table>'
            + '</section>'
            + '<section class="section">'
            + '<h3>Resumen</h3>'
            + '<table>'
            + '<tr><th style="width:30%;">Total acumulado</th><td>' + escHtml(formatMoney(Number(totalGeneral.toFixed(2)))) + '</td></tr>'
            + '<tr><th>Cantidad de pagos</th><td>' + escHtml(String(pagos.length)) + '</td></tr>'
            + '</table>'
            + '</section>'
            + '<script>window.onload=function(){setTimeout(function(){window.print();},300);};<' + '/script>'
            + '</body></html>';

        const win = window.open('', '_blank');
        if (!win) {
            throw new Error('No se pudo abrir la ventana de impresion. Habilita pop-ups para este sitio.');
        }

        win.document.open();
        win.document.write(html);
        win.document.close();
    }

    async function init() {
        try {
            const [
                estudiantes,
                inscripciones,
                planificaciones,
                anios,
                metodos,
                pagos,
                datosCentro,
            ] = await Promise.all([
                apiRequest('estudiantes', 'index', { method: 'GET', query: { limit: 3000, offset: 0 } }),
                apiRequest('inscripciones', 'index', { method: 'GET', query: { limit: 3000, offset: 0 } }),
                apiRequest('planificaciones_academicas', 'index', { method: 'GET', query: { limit: 3000, offset: 0 } }),
                apiRequest('anios_escolares', 'index', { method: 'GET', query: { limit: 200, offset: 0 } }),
                apiRequest('metodos_pago', 'index', { method: 'GET', query: { limit: 200, offset: 0 } }),
                apiRequest('pagos', 'index', { method: 'GET', query: { limit: 3000, offset: 0 } }),
                apiRequest('datos_centro_educativo', 'index', { method: 'GET', query: { limit: 50, offset: 0 } }),
            ]);

            state.estudiantes = Array.isArray(estudiantes) ? estudiantes : [];
            state.inscripciones = Array.isArray(inscripciones) ? inscripciones : [];
            state.planificaciones = toMap(Array.isArray(planificaciones) ? planificaciones : [], 'id');
            state.aniosEscolares = toMap(Array.isArray(anios) ? anios : [], 'id');
            state.metodosPago = toMap(Array.isArray(metodos) ? metodos : [], 'id');
            state.pagos = Array.isArray(pagos) ? pagos : [];
            state.datosCentro = (Array.isArray(datosCentro) ? datosCentro : []).find((row) => Number(row.estado ?? 1) === 1)
                || (Array.isArray(datosCentro) ? datosCentro[0] : null)
                || null;

            state.pagos.forEach((pago) => {
                state.pagosDetalleById.set(Number(pago.id || 0), parseDetallePagoFromObservaciones(pago.observaciones));
            });

            renderEstudiantes();
            renderHistorial();
        } catch (error) {
            estudianteInfo.textContent = error instanceof Error ? error.message : 'No se pudo cargar el historial de pagos.';
            historialCount.textContent = '0 registros';
            tbodyHistorial.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Sin datos</td></tr>';
        }
    }

    estudianteSelect.addEventListener('change', renderHistorial);
    fechaDesdeInput.addEventListener('change', renderHistorial);
    fechaHastaInput.addEventListener('change', renderHistorial);
    anioSelect.addEventListener('change', renderHistorial);
    periodoSelect.addEventListener('change', renderHistorial);
    btnImprimirHistorial.addEventListener('click', function () {
        try {
            openPrintableHistorialPdf();
        } catch (error) {
            estudianteInfo.textContent = error instanceof Error ? error.message : 'No se pudo imprimir el historial.';
        }
    });

    init();
})();
</script>