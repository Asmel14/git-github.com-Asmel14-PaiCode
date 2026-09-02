<?php

declare(strict_types=1);
?>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap4.min.css" rel="stylesheet">
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Modulo financiero - Pagos</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">1) Seleccionar estudiante</h6>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-lg-5">
                <label for="pago-estudiante">Estudiante inscrito</label>
                <select id="pago-estudiante" class="form-control">
                    <option value="">Cargando estudiantes...</option>
                </select>
            </div>
            <div class="form-group col-lg-3">
                <label for="pago-fecha">Fecha de pago</label>
                <input id="pago-fecha" type="date" class="form-control">
            </div>
            <div class="form-group col-lg-4 d-flex align-items-end">
                <div id="pago-regla-resumen" class="alert alert-info w-100 mb-0 py-2 px-3">Selecciona un estudiante para ver su regla de cobro.</div>
            </div>
        </div>
        <div id="pago-estudiante-info" class="small text-muted">Sin estudiante seleccionado.</div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">2) Historial de pagos</h6>
                <span id="pago-historial-count" class="badge badge-light">0 registros</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="tabla-historial-pagos" width="100%" cellspacing="0">
                        <thead class="thead-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Periodo</th>
                            <th>Recibo</th>
                            <th>Metodo</th>
                            <th>Total</th>
                            <th>Accion</th>
                        </tr>
                        </thead>
                        <tbody id="tbody-historial-pagos">
                        <tr>
                            <td colspan="6" class="text-center text-muted">Sin datos</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">3) Pendientes de pago</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-seleccionar-todo">Seleccionar todo</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="tabla-pendientes-pago" width="100%" cellspacing="0">
                        <thead class="thead-light">
                        <tr>
                            <th style="width:50px;">Ok</th>
                            <th>Concepto</th>
                            <th>Periodo</th>
                            <th>Base</th>
                            <th>Mora</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody id="tbody-pendientes-pago">
                        <tr>
                            <td colspan="6" class="text-center text-muted">Sin estudiante seleccionado.</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">4) Registrar pago</h6>
    </div>
    <div class="card-body">
        <div id="pago-estado" class="alert alert-info py-2">Cargando informacion financiera...</div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="pago-metodo">Metodo de pago</label>
                <select id="pago-metodo" class="form-control">
                    <option value="">Cargando metodos...</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="pago-referencia">Referencia</label>
                <input id="pago-referencia" type="text" class="form-control" maxlength="150" placeholder="Ej: TXN-12345">
                <small id="pago-referencia-ayuda" class="form-text text-muted">Opcional.</small>
            </div>
            <div class="form-group col-md-4">
                <label>Total seleccionado</label>
                <div class="form-control bg-light font-weight-bold" id="pago-total-seleccionado">RD$ 0.00</div>
            </div>
        </div>
        <div class="form-group">
            <label for="pago-observaciones">Observaciones</label>
            <textarea id="pago-observaciones" class="form-control" rows="2" maxlength="1000" placeholder="Comentario interno del pago"></textarea>
        </div>
        <button type="button" id="btn-registrar-pago" class="btn btn-success">
            <i class="fas fa-save mr-1"></i> Registrar pago
        </button>
    </div>
</div>

<div class="modal fade" id="modal-detalle-pago" tabindex="-1" role="dialog" aria-labelledby="modal-detalle-pago-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-detalle-pago-title">Detalle del pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detalle-pago-content">
                <div class="text-muted">Sin datos.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-imprimir-pago">
                    <i class="fas fa-file-pdf mr-1"></i> Imprimir PDF
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
(function () {
    const MARCADOR_DETALLE = 'DETALLE_PAGO_JSON:';

    const estudiantesSelect = document.getElementById('pago-estudiante');
    const fechaPagoInput = document.getElementById('pago-fecha');
    const reglaResumen = document.getElementById('pago-regla-resumen');
    const estudianteInfo = document.getElementById('pago-estudiante-info');
    const modalDetallePago = document.getElementById('modal-detalle-pago');
    const detallePagoContent = document.getElementById('detalle-pago-content');
    const btnImprimirPago = document.getElementById('btn-imprimir-pago');

    const historialCount = document.getElementById('pago-historial-count');
    const tbodyHistorial = document.getElementById('tbody-historial-pagos');

    const tbodyPendientes = document.getElementById('tbody-pendientes-pago');
    const btnSeleccionarTodo = document.getElementById('btn-seleccionar-todo');

    const estadoPago = document.getElementById('pago-estado');
    const metodoPagoSelect = document.getElementById('pago-metodo');
    const referenciaInput = document.getElementById('pago-referencia');
    const referenciaAyuda = document.getElementById('pago-referencia-ayuda');
    const totalSeleccionado = document.getElementById('pago-total-seleccionado');
    const observacionesInput = document.getElementById('pago-observaciones');
    const btnRegistrarPago = document.getElementById('btn-registrar-pago');

    const state = {
        estudiantes: [],
        inscripciones: [],
        planificaciones: new Map(),
        aniosEscolares: new Map(),
        metodosPago: new Map(),
        parametrosByAnio: new Map(),
        pagos: [],
        pendientes: [],
        pagosDetalleById: new Map(),
        estudiantesInscritos: [],
        tomEstudiantes: null,
        datosCentro: null,
        pagoDetalleActualId: 0,
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

    function getTodayIso() {
        const now = new Date();
        return [now.getFullYear(), pad2(now.getMonth() + 1), pad2(now.getDate())].join('-');
    }

    function syncFechaPagoActual() {
        const hoy = getTodayIso();
        fechaPagoInput.value = hoy;
        return hoy;
    }

    function getFechaPagoSeleccionada() {
        const value = String(fechaPagoInput.value || '').trim();
        if (/^\d{4}-\d{2}-\d{2}$/.test(value)) {
            return value;
        }

        return syncFechaPagoActual();
    }

    function formatDateISO(value) {
        const text = String(value || '').trim();
        if (!/^\d{4}-\d{2}-\d{2}/.test(text)) {
            return '-';
        }

        return text.slice(8, 10) + '/' + text.slice(5, 7) + '/' + text.slice(0, 4);
    }

    function formatDateTimeISO(value) {
        const text = String(value || '').trim();
        if (!/^\d{4}-\d{2}-\d{2}/.test(text)) {
            return '-';
        }

        const fecha = formatDateISO(text);
        const hora = text.length >= 16 ? text.slice(11, 16) : '';
        return hora !== '' ? (fecha + ' ' + hora) : fecha;
    }

    function monthName(numero) {
        const meses = {
            1: 'Enero',
            2: 'Febrero',
            3: 'Marzo',
            4: 'Abril',
            5: 'Mayo',
            6: 'Junio',
            7: 'Julio',
            8: 'Agosto',
            9: 'Septiembre',
            10: 'Octubre',
            11: 'Noviembre',
            12: 'Diciembre',
        };

        return meses[numero] || 'Mes';
    }

    function parseDate(value) {
        const text = String(value || '').trim();
        if (!/^\d{4}-\d{2}-\d{2}$/.test(text)) {
            return null;
        }

        const dt = new Date(text + 'T00:00:00');
        return Number.isNaN(dt.getTime()) ? null : dt;
    }

    function sameOrBefore(a, b) {
        if (!(a instanceof Date) || !(b instanceof Date)) {
            return false;
        }
        return a.getTime() <= b.getTime();
    }

    function daysInMonth(year, month) {
        return new Date(year, month, 0).getDate();
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

    function setStatus(message, type) {
        const classMap = {
            info: 'alert alert-info py-2',
            success: 'alert alert-success py-2',
            warning: 'alert alert-warning py-2',
            danger: 'alert alert-danger py-2',
        };

        estadoPago.className = classMap[type] || classMap.info;
        estadoPago.textContent = message;
    }

    function showModal(element) {
        if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.modal === 'function') {
            window.jQuery(element).modal('show');
        }
    }

    function hideModal(element) {
        if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.modal === 'function') {
            window.jQuery(element).modal('hide');
        }
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

    function observacionVisible(value) {
        const text = String(value || '');
        const markerIndex = text.indexOf(MARCADOR_DETALLE);
        if (markerIndex < 0) {
            return text.trim();
        }
        return text.slice(0, markerIndex).trim();
    }

    function selectedEstudianteId() {
        return Number(estudiantesSelect.value || 0);
    }

    function getInscripcionActivaByEstudiante(estudianteId) {
        const rows = state.inscripciones
            .filter((row) => Number(row.estudiante_id || 0) === estudianteId && Number(row.inscripcion_activa || 0) === 1)
            .sort((a, b) => String(b.fecha_inscripcion || '').localeCompare(String(a.fecha_inscripcion || '')));

        return rows.length > 0 ? rows[0] : null;
    }

    function getReglaPagoText(inscripcion) {
        const fecha = parseDate(inscripcion.fecha_inscripcion);
        if (!(fecha instanceof Date)) {
            return 'Regla: 1 inscripcion + 10 cuotas. No se pudo validar junio por fecha invalida.';
        }

        const mes = fecha.getMonth() + 1;
        if (mes < 8) {
            return 'Regla aplicada: Inscripcion antes de agosto -> se cobra agosto a mayo (junio libre).';
        }

        return 'Regla aplicada: Inscripcion despues de agosto -> se cobra septiembre a junio (incluye junio).';
    }

    function buildPaidKeysFromHistorial(inscripcionId) {
        const keys = new Set();

        state.pagos.forEach((pago) => {
            const detalle = state.pagosDetalleById.get(Number(pago.id || 0));
            if (!detalle || Number(detalle.inscripcion_id || 0) !== inscripcionId) {
                return;
            }

            detalle.items.forEach((item) => {
                const key = String(item.key || '').trim();
                if (key !== '') {
                    keys.add(key);
                }
            });
        });

        return keys;
    }

    function buildPendientes(inscripcion, fechaPagoISO) {
        const pendientes = [];
        const inscripcionId = Number(inscripcion.id || 0);
        const planificacion = state.planificaciones.get(Number(inscripcion.planificacion_academica_id || 0)) || null;
        const anio = planificacion ? (state.aniosEscolares.get(Number(planificacion.anio_escolar_id || 0)) || null) : null;
        const parametros = anio ? (state.parametrosByAnio.get(Number(anio.id || 0)) || null) : null;

        const fechaInscripcion = parseDate(inscripcion.fecha_inscripcion);
        const fechaPago = parseDate(fechaPagoISO);

        const paidKeys = buildPaidKeysFromHistorial(inscripcionId);

        const inscripcionMonto = Number(inscripcion.tarifa_inscripcion || 0);
        const keyInscripcion = 'INSCRIPCION:' + String(inscripcionId);
        if (!paidKeys.has(keyInscripcion) && inscripcionMonto > 0) {
            pendientes.push({
                key: keyInscripcion,
                concepto: 'INSCRIPCION',
                periodo: formatDateISO(inscripcion.fecha_inscripcion),
                monto_base: Number(inscripcionMonto.toFixed(2)),
                mora: 0,
                monto_total: Number(inscripcionMonto.toFixed(2)),
                orden_secuencial: 0,
            });
        }

        const mensualidad = Number(inscripcion.mensualidad || 0);
        if (mensualidad <= 0 || !(fechaInscripcion instanceof Date)) {
            return pendientes;
        }

        const mesInscripcion = fechaInscripcion.getMonth() + 1;
        const mesInicio = mesInscripcion < 8 ? 8 : 9;

        const anioInicio = anio && /^\d{4}-\d{2}-\d{2}$/.test(String(anio.fecha_inicio || ''))
            ? Number(String(anio.fecha_inicio).slice(0, 4))
            : fechaInscripcion.getFullYear();

        const anioFin = anio && /^\d{4}-\d{2}-\d{2}$/.test(String(anio.fecha_fin || ''))
            ? Number(String(anio.fecha_fin).slice(0, 4))
            : (anioInicio + 1);

        const diaVencimiento = Math.max(1, Math.min(31, Number(parametros && parametros.dia_vencimiento_mensual ? parametros.dia_vencimiento_mensual : 25)));
        const moraMensual = Number(parametros && parametros.mora_mensual ? parametros.mora_mensual : 0);

        for (let index = 0; index < 10; index += 1) {
            const corrido = mesInicio + index;
            const mes = ((corrido - 1) % 12) + 1;
            const anioCuota = corrido <= 12 ? anioInicio : anioFin;

            const key = 'CUOTA:' + String(anioCuota) + '-' + pad2(mes);
            if (paidKeys.has(key)) {
                continue;
            }

            const diaLimite = Math.min(diaVencimiento, daysInMonth(anioCuota, mes));
            const fechaLimite = new Date(anioCuota + '-' + pad2(mes) + '-' + pad2(diaLimite) + 'T23:59:59');

            let mora = 0;
            if (fechaPago instanceof Date && !sameOrBefore(fechaPago, fechaLimite) && moraMensual > 0) {
                mora = Number(moraMensual.toFixed(2));
            }

            const base = Number(mensualidad.toFixed(2));
            const total = Number((base + mora).toFixed(2));

            pendientes.push({
                key: key,
                concepto: 'CUOTA',
                periodo: monthName(mes) + ' ' + String(anioCuota),
                monto_base: base,
                mora: mora,
                monto_total: total,
                orden_secuencial: index + 1,
            });
        }

        return pendientes;
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
        const selectedBefore = Number(estudiantesSelect.value || 0);

        if (state.estudiantesInscritos.length === 0) {
            estudiantesSelect.innerHTML = '<option value="">No hay estudiantes inscritos activos</option>';
            if (state.tomEstudiantes) {
                state.tomEstudiantes.clearOptions();
                state.tomEstudiantes.addOption({ value: '', text: 'No hay estudiantes inscritos activos' });
                state.tomEstudiantes.setValue('', true);
                state.tomEstudiantes.refreshOptions(false);
            }
            return;
        }

        let html = '<option value="">Seleccione estudiante...</option>';
        const options = [{ value: '', text: 'Seleccione estudiante...' }];
        state.estudiantesInscritos.forEach((estudiante) => {
            const id = Number(estudiante.id || 0);
            const etiqueta = fullName(estudiante) + ' (' + String(estudiante.id_sigerd || ('ID ' + id)) + ')';
            html += '<option value="' + id + '">' + escHtml(etiqueta) + '</option>';
            options.push({ value: String(id), text: etiqueta });
        });

        estudiantesSelect.innerHTML = html;

        if (!state.tomEstudiantes && typeof window.TomSelect === 'function') {
            state.tomEstudiantes = new window.TomSelect(estudiantesSelect, {
                create: false,
                allowEmptyOption: true,
                placeholder: 'Seleccione o escriba para buscar',
                maxOptions: 500,
                searchField: ['text'],
                onChange: function () {
                    refreshByEstudiante();
                },
            });
        } else if (state.tomEstudiantes) {
            state.tomEstudiantes.clearOptions();
            state.tomEstudiantes.addOptions(options);
            const existeSeleccion = options.some((opt) => Number(opt.value || 0) === selectedBefore);
            state.tomEstudiantes.setValue(existeSeleccion ? String(selectedBefore) : '', true);
            state.tomEstudiantes.refreshOptions(false);
        } else {
            estudiantesSelect.value = state.estudiantesInscritos.some((row) => Number(row.id || 0) === selectedBefore)
                ? String(selectedBefore)
                : '';
        }
    }

    function renderMetodosPago() {
        const metodos = Array.from(state.metodosPago.values())
            .filter((item) => Number(item.activo || 0) === 1)
            .sort((a, b) => String(a.nombre || '').localeCompare(String(b.nombre || '')));

        if (metodos.length === 0) {
            metodoPagoSelect.innerHTML = '<option value="">No hay metodos activos</option>';
            return;
        }

        let html = '<option value="">Selecciona metodo</option>';
        metodos.forEach((metodo) => {
            const id = Number(metodo.id || 0);
            html += '<option value="' + id + '">'
                + escHtml(String(metodo.nombre || 'Metodo ' + id))
                + '</option>';
        });

        metodoPagoSelect.innerHTML = html;
    }

    function updateReferenciaHint() {
        const metodoId = Number(metodoPagoSelect.value || 0);
        const metodo = state.metodosPago.get(metodoId) || null;
        const requiere = metodo ? Number(metodo.requiere_referencia || 0) === 1 : false;

        referenciaAyuda.textContent = requiere
            ? 'Este metodo requiere referencia.'
            : 'Opcional.';
    }

    function renderHistorial(estudianteId) {
        const rows = state.pagos
            .filter((pago) => Number(pago.estudiante_id || 0) === estudianteId)
            .sort((a, b) => String(b.fecha_pago || '').localeCompare(String(a.fecha_pago || '')));

        historialCount.textContent = rows.length + ' registros';

        if (rows.length === 0) {
            tbodyHistorial.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Sin pagos registrados para este estudiante.</td></tr>';
            return;
        }

        let html = '';
        rows.forEach((pago) => {
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
                + '<td class="text-center">'
                + '<button type="button" class="btn btn-sm btn-info mr-1 btn-ver-pago" data-pago-id="' + Number(pago.id || 0) + '"><i class="fas fa-eye"></i></button>'
                + '<button type="button" class="btn btn-sm btn-danger btn-eliminar-pago" data-pago-id="' + Number(pago.id || 0) + '"><i class="fas fa-trash"></i></button>'
                + '</td>'
                + '</tr>';
        });

        tbodyHistorial.innerHTML = html;
    }

    function buildDetallePagoHtml(pago) {
        const metodo = state.metodosPago.get(Number(pago.metodo_pago_id || 0)) || null;
        const detalle = state.pagosDetalleById.get(Number(pago.id || 0)) || null;
        const referencia = String(pago.referencia || '').trim();
        const observacion = observacionVisible(pago.observaciones);

        let itemsHtml = '<tr><td colspan="5" class="text-center text-muted">Sin detalle registrado.</td></tr>';
        if (detalle && Array.isArray(detalle.items) && detalle.items.length > 0) {
            itemsHtml = detalle.items.map((item) => {
                return '<tr>'
                    + '<td>' + escHtml(String(item.concepto || '-')) + '</td>'
                    + '<td>' + escHtml(String(item.periodo || '-')) + '</td>'
                    + '<td class="text-right">' + escHtml(formatMoney(Number(item.monto_base || 0))) + '</td>'
                    + '<td class="text-right">' + escHtml(formatMoney(Number(item.mora || 0))) + '</td>'
                    + '<td class="text-right">' + escHtml(formatMoney(Number(item.monto_total || 0))) + '</td>'
                    + '</tr>';
            }).join('');
        }

        return ''
            + '<div class="row">'
            + '<div class="col-md-6">'
            + '<table class="table table-sm table-bordered">'
            + '<tr><th style="width:40%;">Fecha</th><td>' + escHtml(formatDateISO(pago.fecha_pago)) + '</td></tr>'
            + '<tr><th>Recibo</th><td>' + escHtml(String(pago.numero_recibo || '-')) + '</td></tr>'
            + '<tr><th>Metodo</th><td>' + escHtml(metodo ? String(metodo.nombre || '-') : '-') + '</td></tr>'
            + '</table>'
            + '</div>'
            + '<div class="col-md-6">'
            + '<table class="table table-sm table-bordered">'
            + '<tr><th style="width:40%;">Referencia</th><td>' + escHtml(referencia !== '' ? referencia : '-') + '</td></tr>'
            + '<tr><th>Total</th><td>' + escHtml(formatMoney(Number(pago.monto_total || 0))) + '</td></tr>'
            + '<tr><th>Observaciones</th><td>' + escHtml(observacion !== '' ? observacion : '-') + '</td></tr>'
            + '</table>'
            + '</div>'
            + '</div>'
            + '<div class="table-responsive">'
            + '<table class="table table-bordered table-sm mb-0">'
            + '<thead class="thead-light"><tr><th>Concepto</th><th>Periodo</th><th>Base</th><th>Mora</th><th>Total</th></tr></thead>'
            + '<tbody>' + itemsHtml + '</tbody>'
            + '</table>'
            + '</div>';
    }

    function verPago(pagoId) {
        const pago = state.pagos.find((row) => Number(row.id || 0) === pagoId) || null;
        if (!pago) {
            setStatus('No se encontro el pago seleccionado.', 'warning');
            return;
        }

        state.pagoDetalleActualId = pagoId;
        detallePagoContent.innerHTML = buildDetallePagoHtml(pago);
        showModal(modalDetallePago);
    }

    async function eliminarPago(pagoId) {
        const pago = state.pagos.find((row) => Number(row.id || 0) === pagoId) || null;
        if (!pago) {
            setStatus('No se encontro el pago seleccionado.', 'warning');
            return;
        }

        const confirmado = window.confirm('Se eliminara el recibo ' + String(pago.numero_recibo || pagoId) + '. Esta accion no se puede deshacer.');
        if (!confirmado) {
            return;
        }

        setStatus('Eliminando pago...', 'info');
        hideModal(modalDetallePago);

        try {
            await apiRequest('pagos', 'destroy', {
                method: 'POST',
                payload: {
                    criteria: {
                        id: pagoId,
                    },
                },
            });

            state.pagos = await apiRequest('pagos', 'index', {
                method: 'GET',
                query: { limit: 3000, offset: 0 },
            });

            state.pagosDetalleById.clear();
            state.pagos.forEach((row) => {
                state.pagosDetalleById.set(Number(row.id || 0), parseDetallePagoFromObservaciones(row.observaciones));
            });

            await refreshByEstudiante();
            setStatus('Pago eliminado correctamente.', 'success');
        } catch (error) {
            setStatus(error instanceof Error ? error.message : 'No se pudo eliminar el pago.', 'danger');
        }
    }

    function renderPendientes() {
        if (state.pendientes.length === 0) {
            tbodyPendientes.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No hay pendientes para el estudiante seleccionado.</td></tr>';
            updateTotalSeleccionado();
            return;
        }

        let html = '';
        state.pendientes.forEach((item, index) => {
            const esCuota = item.concepto === 'CUOTA';
            html += '<tr>'
                + '<td class="text-center"><input type="checkbox" class="pago-pendiente-check" data-index="' + index + '" data-concepto="' + escHtml(item.concepto) + '"' + (esCuota ? ' data-secuencial="' + Number(item.orden_secuencial || 0) + '"' : '') + '></td>'
                + '<td>' + escHtml(item.concepto) + '</td>'
                + '<td>' + escHtml(item.periodo) + '</td>'
                + '<td class="text-right">' + escHtml(formatMoney(item.monto_base)) + '</td>'
                + '<td class="text-right">' + escHtml(formatMoney(item.mora)) + '</td>'
                + '<td class="text-right">' + escHtml(formatMoney(item.monto_total)) + '</td>'
                + '</tr>';
        });

        tbodyPendientes.innerHTML = html;
        applyPendingSelectionRules();
        updateTotalSeleccionado();
    }

    function applyPendingSelectionRules() {
        const checks = Array.from(document.querySelectorAll('.pago-pendiente-check'));
        let cuotaAnteriorCumplida = true;

        checks.forEach((check) => {
            if (!(check instanceof HTMLInputElement)) {
                return;
            }

            const concepto = String(check.getAttribute('data-concepto') || '');
            const row = check.closest('tr');
            if (concepto !== 'CUOTA') {
                check.disabled = false;
                check.title = '';
                if (row) {
                    row.classList.remove('table-secondary');
                }
                return;
            }

            if (cuotaAnteriorCumplida) {
                check.disabled = false;
                check.title = '';
                if (row) {
                    row.classList.remove('table-secondary');
                }
            } else {
                check.checked = false;
                check.disabled = true;
                check.title = 'Debes seleccionar primero la cuota anterior pendiente.';
                if (row) {
                    row.classList.add('table-secondary');
                }
            }

            cuotaAnteriorCumplida = cuotaAnteriorCumplida && check.checked;
        });
    }

    function getSelectedPendientes() {
        const checks = Array.from(document.querySelectorAll('.pago-pendiente-check'));
        const seleccion = [];

        checks.forEach((check) => {
            if (!check.checked) {
                return;
            }

            const index = Number(check.getAttribute('data-index') || -1);
            if (index >= 0 && index < state.pendientes.length) {
                seleccion.push(state.pendientes[index]);
            }
        });

        return seleccion;
    }

    function updateTotalSeleccionado() {
        const seleccion = getSelectedPendientes();
        const total = seleccion.reduce((acc, item) => acc + Number(item.monto_total || 0), 0);
        totalSeleccionado.textContent = formatMoney(Number(total.toFixed(2)));
    }

    function buildNumeroRecibo(estudianteId) {
        const now = new Date();
        const y = now.getFullYear();
        const m = pad2(now.getMonth() + 1);
        const d = pad2(now.getDate());
        const h = pad2(now.getHours());
        const i = pad2(now.getMinutes());
        const s = pad2(now.getSeconds());
        const rnd = String(Math.floor(100 + Math.random() * 900));

        return ('REC-' + y + m + d + h + i + s + '-' + estudianteId + '-' + rnd).slice(0, 50);
    }

    function openPrintablePagoPdf(pago) {
        const metodo = state.metodosPago.get(Number(pago.metodo_pago_id || 0)) || null;
        const detalle = state.pagosDetalleById.get(Number(pago.id || 0)) || null;
        const estudiante = state.estudiantes.find((row) => Number(row.id || 0) === Number(pago.estudiante_id || 0)) || null;
        const centro = state.datosCentro || {};
        const referencia = String(pago.referencia || '').trim();
        const observacion = observacionVisible(pago.observaciones);
        const logoUrl = resolveStoredAssetUrl(centro.logo || '');

        let detalleRows = '<tr><td colspan="5" style="text-align:center;color:#6b7280;">Sin detalle registrado.</td></tr>';
        if (detalle && Array.isArray(detalle.items) && detalle.items.length > 0) {
            detalleRows = detalle.items.map((item) => {
                return '<tr>'
                    + '<td>' + escHtml(String(item.concepto || '-')) + '</td>'
                    + '<td>' + escHtml(String(item.periodo || '-')) + '</td>'
                    + '<td style="text-align:right;">' + escHtml(formatMoney(Number(item.monto_base || 0))) + '</td>'
                    + '<td style="text-align:right;">' + escHtml(formatMoney(Number(item.mora || 0))) + '</td>'
                    + '<td style="text-align:right;">' + escHtml(formatMoney(Number(item.monto_total || 0))) + '</td>'
                    + '</tr>';
            }).join('');
        }

        const html = '<!doctype html><html lang="es"><head><meta charset="utf-8">'
            + '<title>Recibo de pago</title>'
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
            + '.grid{display:flex;gap:14px;margin-bottom:14px;}'
            + '.grid .card{flex:1;}'
            + '.section{margin-bottom:14px;page-break-inside:avoid;}'
            + '.section h3{margin:0 0 8px 0;font-size:14px;background:#eef2f6;padding:7px 9px;border:1px solid #c8d1db;text-transform:uppercase;letter-spacing:.3px;}'
            + 'table{width:100%;border-collapse:collapse;}'
            + 'th,td{border:1px solid #d3dae2;padding:6px 8px;font-size:12px;vertical-align:top;}'
            + 'th{width:36%;text-align:left;background:#f7f9fb;font-weight:700;}'
            + '.actions{margin-bottom:12px;}'
            + '.actions button{padding:6px 12px;font-size:12px;}'
            + '.actions .btn-volver{margin-left:6px;background:#f1f3f5;border:1px solid #b5bcc3;}'
            + '.firmas{margin-top:45px;display:flex;justify-content:space-between;gap:24px;page-break-inside:avoid;}'
            + '.firma{flex:1;text-align:center;}'
            + '.firma-espacio{height:48px;}'
            + '.firma-linea{margin:0 auto 10px auto;width:86%;border-top:1px solid #1f2933;height:18px;}'
            + '.firma-label{font-size:12px;color:#333;}'
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
            + '<h2>Recibo de pago</h2>'
            + '<div class="meta">Fecha de impresion: ' + escHtml(formatDateISO(getTodayIso())) + '</div>'
            + '</div>'
            + '</div>'
            + '<div class="foto">' + (logoUrl !== '' ? '<img src="' + escHtml(logoUrl) + '" alt="Logo del centro">' : '<div class="foto-empty">Sin logo</div>') + '</div>'
            + '</div>'
            + '</div>'
            + '<div class="grid">'
            + '<div class="card"><table>'
            + '<tr><th>Estudiante</th><td>' + escHtml(estudiante ? fullName(estudiante) : '-') + '</td></tr>'
            + '<tr><th>ID SIGERD</th><td>' + escHtml(estudiante ? String(estudiante.id_sigerd || '-') : '-') + '</td></tr>'
            + '<tr><th>Recibo</th><td>' + escHtml(String(pago.numero_recibo || '-')) + '</td></tr>'
            + '</table></div>'
            + '<div class="card"><table>'
            + '<tr><th>Fecha de pago</th><td>' + escHtml(formatDateTimeISO(pago.fecha_pago)) + '</td></tr>'
            + '<tr><th>Metodo</th><td>' + escHtml(metodo ? String(metodo.nombre || '-') : '-') + '</td></tr>'
            + '<tr><th>Referencia</th><td>' + escHtml(referencia !== '' ? referencia : '-') + '</td></tr>'
            + '</table></div>'
            + '</div>'
            + '<section class="section">'
            + '<h3>Detalle del pago</h3>'
            + '<table>'
            + '<thead><tr><th>Concepto</th><th>Periodo</th><th>Base</th><th>Mora</th><th>Total</th></tr></thead>'
            + '<tbody>' + detalleRows + '</tbody>'
            + '</table>'
            + '</section>'
            + '<section class="section">'
            + '<h3>Resumen</h3>'
            + '<table>'
            + '<tr><th>Total pagado</th><td>' + escHtml(formatMoney(Number(pago.monto_total || 0))) + '</td></tr>'
            + '<tr><th>Observaciones</th><td>' + escHtml(observacion !== '' ? observacion : '-') + '</td></tr>'
            + '</table>'
            + '</section>'
            + '<section class="firmas">'
            + '<div class="firma"><div class="firma-espacio"></div><div class="firma-linea"></div><div class="firma-label">Firma del padre, madre o tutor</div></div>'
            + '<div class="firma"><div class="firma-espacio"></div><div class="firma-linea"></div><div class="firma-label">Firma del encargado de caja</div></div>'
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

    function buildObservacionesFinal(obsUsuario, inscripcionId, items) {
        const payload = {
            inscripcion_id: inscripcionId,
            items: items.map((item) => ({
                key: item.key,
                concepto: item.concepto,
                periodo: item.periodo,
                monto_base: item.monto_base,
                mora: item.mora,
                monto_total: item.monto_total,
            })),
        };

        const markerText = MARCADOR_DETALLE + JSON.stringify(payload);
        const userText = String(obsUsuario || '').trim();

        if (userText === '') {
            return markerText;
        }

        const merged = userText + '\n' + markerText;
        if (merged.length <= 65000) {
            return merged;
        }

        return merged.slice(0, 65000);
    }

    async function refreshByEstudiante() {
        const estudianteId = selectedEstudianteId();
        const fechaPago = getFechaPagoSeleccionada();

        if (estudianteId <= 0) {
            reglaResumen.textContent = 'Selecciona un estudiante para ver su regla de cobro.';
            estudianteInfo.textContent = 'Sin estudiante seleccionado.';
            state.pendientes = [];
            tbodyHistorial.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Sin datos</td></tr>';
            historialCount.textContent = '0 registros';
            renderPendientes();
            return;
        }

        const estudiante = state.estudiantes.find((row) => Number(row.id || 0) === estudianteId) || null;
        const inscripcion = getInscripcionActivaByEstudiante(estudianteId);

        if (!inscripcion) {
            reglaResumen.textContent = 'El estudiante no tiene inscripcion activa.';
            estudianteInfo.textContent = estudiante ? (fullName(estudiante) + ' sin inscripcion activa.') : 'Sin estudiante.';
            state.pendientes = [];
            renderHistorial(estudianteId);
            renderPendientes();
            return;
        }

        const planificacion = state.planificaciones.get(Number(inscripcion.planificacion_academica_id || 0)) || null;
        const anio = planificacion ? (state.aniosEscolares.get(Number(planificacion.anio_escolar_id || 0)) || null) : null;
        const parametros = anio ? (state.parametrosByAnio.get(Number(anio.id || 0)) || null) : null;

        reglaResumen.textContent = getReglaPagoText(inscripcion);

        estudianteInfo.textContent = [
            'Estudiante: ' + (estudiante ? fullName(estudiante) : 'N/D'),
            'ID SIGERD: ' + (estudiante ? (estudiante.id_sigerd || '-') : '-'),
            'Fecha inscripcion: ' + formatDateISO(inscripcion.fecha_inscripcion),
            'Mensualidad: ' + formatMoney(inscripcion.mensualidad || 0),
            'Inscripcion: ' + formatMoney(inscripcion.tarifa_inscripcion || 0),
            'Vencimiento: dia ' + String(parametros && parametros.dia_vencimiento_mensual ? parametros.dia_vencimiento_mensual : 25),
            'Mora mensual: ' + formatMoney(parametros && parametros.mora_mensual ? parametros.mora_mensual : 0),
            'Ano escolar: ' + (anio ? String(anio.nombre || '-') : '-'),
        ].join(' | ');

        renderHistorial(estudianteId);
        state.pendientes = buildPendientes(inscripcion, fechaPago);
        renderPendientes();
    }

    async function registrarPago() {
        const estudianteId = selectedEstudianteId();
        const fechaPago = getFechaPagoSeleccionada();
        const metodoId = Number(metodoPagoSelect.value || 0);
        const referencia = String(referenciaInput.value || '').trim();
        const observaciones = String(observacionesInput.value || '').trim();

        if (estudianteId <= 0) {
            setStatus('Selecciona un estudiante.', 'warning');
            return;
        }

        const inscripcion = getInscripcionActivaByEstudiante(estudianteId);
        if (!inscripcion) {
            setStatus('El estudiante seleccionado no tiene inscripcion activa.', 'warning');
            return;
        }

        if (!/^\d{4}-\d{2}-\d{2}$/.test(fechaPago)) {
            setStatus('Selecciona una fecha de pago valida.', 'warning');
            return;
        }

        if (metodoId <= 0) {
            setStatus('Selecciona un metodo de pago.', 'warning');
            return;
        }

        const metodo = state.metodosPago.get(metodoId) || null;
        if (metodo && Number(metodo.requiere_referencia || 0) === 1 && referencia === '') {
            setStatus('El metodo seleccionado requiere referencia.', 'warning');
            return;
        }

        const itemsSeleccionados = getSelectedPendientes();
        if (itemsSeleccionados.length === 0) {
            setStatus('Selecciona por lo menos un pendiente para registrar el pago.', 'warning');
            return;
        }

        const total = Number(itemsSeleccionados.reduce((acc, item) => acc + Number(item.monto_total || 0), 0).toFixed(2));
        if (total <= 0) {
            setStatus('El total del pago no es valido.', 'warning');
            return;
        }

        const numeroRecibo = buildNumeroRecibo(estudianteId);
        const observacionesFinal = buildObservacionesFinal(observaciones, Number(inscripcion.id || 0), itemsSeleccionados);

        btnRegistrarPago.disabled = true;
        setStatus('Registrando pago...', 'info');

        try {
            const createResult = await apiRequest('pagos', 'store', {
                method: 'POST',
                payload: {
                    data: {
                        estudiante_id: estudianteId,
                        numero_recibo: numeroRecibo,
                        fecha_pago: fechaPago + ' 12:00:00',
                        metodo_pago_id: metodoId,
                        referencia: referencia === '' ? null : referencia,
                        monto_total: total,
                        estado: 'APLICADO',
                        observaciones: observacionesFinal,
                    },
                },
            });

            const pagoCreadoId = Number(createResult && createResult.id ? createResult.id : 0);

            state.pagos = await apiRequest('pagos', 'index', {
                method: 'GET',
                query: { limit: 3000, offset: 0 },
            });

            state.pagosDetalleById.clear();
            state.pagos.forEach((pago) => {
                state.pagosDetalleById.set(Number(pago.id || 0), parseDetallePagoFromObservaciones(pago.observaciones));
            });

            observacionesInput.value = '';
            referenciaInput.value = '';

            await refreshByEstudiante();
            setStatus('Pago registrado correctamente.', 'success');

            if (pagoCreadoId > 0) {
                verPago(pagoCreadoId);
            } else {
                const pagoReciente = state.pagos.find((row) => String(row.numero_recibo || '') === numeroRecibo) || null;
                if (pagoReciente) {
                    verPago(Number(pagoReciente.id || 0));
                }
            }
        } catch (error) {
            setStatus(error instanceof Error ? error.message : 'No se pudo registrar el pago.', 'danger');
        } finally {
            btnRegistrarPago.disabled = false;
        }
    }

    async function init() {
        syncFechaPagoActual();

        try {
            const [
                estudiantes,
                inscripciones,
                planificaciones,
                anios,
                metodos,
                parametros,
                pagos,
                datosCentro,
            ] = await Promise.all([
                apiRequest('estudiantes', 'index', { method: 'GET', query: { limit: 3000, offset: 0 } }),
                apiRequest('inscripciones', 'index', { method: 'GET', query: { limit: 3000, offset: 0 } }),
                apiRequest('planificaciones_academicas', 'index', { method: 'GET', query: { limit: 3000, offset: 0 } }),
                apiRequest('anios_escolares', 'index', { method: 'GET', query: { limit: 200, offset: 0 } }),
                apiRequest('metodos_pago', 'index', { method: 'GET', query: { limit: 200, offset: 0 } }),
                apiRequest('parametros_financieros', 'index', { method: 'GET', query: { limit: 200, offset: 0 } }),
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

            (Array.isArray(parametros) ? parametros : []).forEach((item) => {
                const anioId = Number(item.anio_escolar_id || 0);
                if (anioId <= 0) {
                    return;
                }

                const existing = state.parametrosByAnio.get(anioId) || null;
                if (!existing || Number(item.id || 0) > Number(existing.id || 0)) {
                    state.parametrosByAnio.set(anioId, item);
                }
            });

            state.pagos.forEach((pago) => {
                state.pagosDetalleById.set(Number(pago.id || 0), parseDetallePagoFromObservaciones(pago.observaciones));
            });

            renderEstudiantes();
            renderMetodosPago();
            updateReferenciaHint();
            await refreshByEstudiante();

            setStatus('Listo para registrar pagos.', 'success');
        } catch (error) {
            setStatus(error instanceof Error ? error.message : 'Error cargando la vista de pagos.', 'danger');
        }
    }

    estudiantesSelect.addEventListener('change', refreshByEstudiante);
    fechaPagoInput.addEventListener('change', refreshByEstudiante);
    metodoPagoSelect.addEventListener('change', updateReferenciaHint);

    tbodyPendientes.addEventListener('change', (event) => {
        const target = event.target;
        if (target instanceof HTMLInputElement && target.classList.contains('pago-pendiente-check')) {
            applyPendingSelectionRules();
            updateTotalSeleccionado();
        }
    });

    tbodyHistorial.addEventListener('click', (event) => {
        const target = event.target;
        if (!(target instanceof Element)) {
            return;
        }

        const buttonVer = target.closest('.btn-ver-pago');
        if (buttonVer) {
            verPago(Number(buttonVer.getAttribute('data-pago-id') || 0));
            return;
        }

        const buttonEliminar = target.closest('.btn-eliminar-pago');
        if (buttonEliminar) {
            eliminarPago(Number(buttonEliminar.getAttribute('data-pago-id') || 0));
        }
    });

    btnSeleccionarTodo.addEventListener('click', () => {
        const checks = Array.from(document.querySelectorAll('.pago-pendiente-check'));
        if (checks.length === 0) {
            return;
        }

        const allChecked = checks.every((check) => check.checked);
        checks.forEach((check) => {
            check.checked = !allChecked;
        });

        btnSeleccionarTodo.textContent = allChecked ? 'Seleccionar todo' : 'Quitar seleccion';
        updateTotalSeleccionado();
    });

    btnRegistrarPago.addEventListener('click', registrarPago);
    btnImprimirPago.addEventListener('click', function () {
        const pagoId = Number(state.pagoDetalleActualId || 0);
        const pago = state.pagos.find((row) => Number(row.id || 0) === pagoId) || null;
        if (!pago) {
            setStatus('Selecciona un pago valido para imprimir.', 'warning');
            return;
        }

        try {
            openPrintablePagoPdf(pago);
        } catch (error) {
            setStatus(error instanceof Error ? error.message : 'No se pudo generar el PDF del pago.', 'danger');
        }
    });

    init();
})();
</script>
