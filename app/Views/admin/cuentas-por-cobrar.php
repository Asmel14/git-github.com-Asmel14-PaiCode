<?php

declare(strict_types=1);
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Modulo financiero - Cuentas por cobrar</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">1) Parametros de consulta</h6>
    </div>
    <div class="card-body">
        <div class="form-row align-items-end">
            <div class="form-group col-lg-3 mb-0">
                <label for="cxc-anio-escolar">A&ntilde;o escolar</label>
                <select id="cxc-anio-escolar" class="form-control">
                    <option value="">Todos los a&ntilde;os</option>
                </select>
            </div>
            <div class="form-group col-lg-3 mb-0">
                <label for="cxc-periodo">Periodo</label>
                <select id="cxc-periodo" class="form-control" disabled>
                    <option value="">Seleccione un a&ntilde;o escolar</option>
                </select>
            </div>
            <div class="form-group col-lg-3 mb-0">
                <label for="cxc-fecha-corte">Fecha de corte</label>
                <input id="cxc-fecha-corte" type="date" class="form-control">
            </div>
            <div class="form-group col-lg-3 mb-0 d-flex align-items-end">
                <div id="cxc-regla-resumen" class="alert alert-info mb-0 py-2 px-3">La tabla carga todos los estudiantes con deudas, incluyendo inscripcion y cuotas sin pagar.</div>
            </div>
        </div>
        <div id="cxc-estudiante-info" class="small text-muted mt-3">Cargando cuentas por cobrar.</div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="card shadow mb-4 border-left-danger">
            <div class="card-body py-3">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total pendiente</div>
                <div class="h4 mb-0 font-weight-bold text-gray-800" id="cxc-total-pendiente">RD$ 0.00</div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card shadow mb-4 border-left-warning">
            <div class="card-body py-3">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Mora acumulada</div>
                <div class="h4 mb-0 font-weight-bold text-gray-800" id="cxc-total-mora">RD$ 0.00</div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card shadow mb-4 border-left-primary">
            <div class="card-body py-3">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Cantidad de pendientes</div>
                <div class="h4 mb-0 font-weight-bold text-gray-800" id="cxc-total-items">0</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">2) Detalle de cuentas por cobrar</h6>
        <span id="cxc-historial-count" class="badge badge-light">0 pendientes</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" id="tabla-cxc" width="100%" cellspacing="0">
                <thead class="thead-light">
                <tr>
                    <th>Estudiante</th>
                    <th>ID SIGERD</th>
                    <th>Concepto</th>
                    <th>Periodo</th>
                    <th>Base</th>
                    <th>Mora</th>
                    <th>Total pendiente</th>
                    <th>Estado</th>
                </tr>
                </thead>
                <tbody id="tbody-cxc">
                <tr>
                    <td colspan="8" class="text-center text-muted">Cargando datos...</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function () {
    const MARCADOR_DETALLE = 'DETALLE_PAGO_JSON:';

    const anioEscolarInput = document.getElementById('cxc-anio-escolar');
    const periodoInput = document.getElementById('cxc-periodo');
    const fechaCorteInput = document.getElementById('cxc-fecha-corte');
    const reglaResumen = document.getElementById('cxc-regla-resumen');
    const estudianteInfo = document.getElementById('cxc-estudiante-info');
    const totalPendiente = document.getElementById('cxc-total-pendiente');
    const totalMora = document.getElementById('cxc-total-mora');
    const totalItems = document.getElementById('cxc-total-items');
    const totalBadge = document.getElementById('cxc-historial-count');
    const tbody = document.getElementById('tbody-cxc');

    const state = {
        estudiantes: [],
        inscripciones: [],
        planificaciones: new Map(),
        aniosEscolares: new Map(),
        periodosCobro: [],
        periodosCobroById: new Map(),
        periodosCobroByAnio: new Map(),
        parametrosByAnio: new Map(),
        pagos: [],
        pagosDetalleById: new Map(),
        pendientes: [],
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

    function parseDate(value) {
        const text = String(value || '').trim();
        if (!/^\d{4}-\d{2}-\d{2}$/.test(text)) {
            return null;
        }

        const dt = new Date(text + 'T00:00:00');
        return Number.isNaN(dt.getTime()) ? null : dt;
    }

    function formatDateISO(value) {
        const text = String(value || '').trim();
        if (!/^\d{4}-\d{2}-\d{2}/.test(text)) {
            return '-';
        }

        return text.slice(8, 10) + '/' + text.slice(5, 7) + '/' + text.slice(0, 4);
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

    function getPeriodoCobroByAnioYMes(anioEscolarId, numeroMes) {
        const periodos = state.periodosCobroByAnio.get(Number(anioEscolarId || 0)) || [];
        return periodos.find((periodo) => Number(periodo.numero_mes || 0) === Number(numeroMes || 0)) || null;
    }

    function renderAniosEscolares() {
        const anios = Array.from(state.aniosEscolares.values())
            .sort((a, b) => String(b.fecha_inicio || '').localeCompare(String(a.fecha_inicio || '')) || Number(b.id || 0) - Number(a.id || 0));

        let html = '<option value="">Todos los a&ntilde;os</option>';
        anios.forEach((anio) => {
            const id = Number(anio.id || 0);
            if (id <= 0) {
                return;
            }

            html += '<option value="' + id + '">' + escHtml(String(anio.nombre || ('Anio ' + id))) + '</option>';
        });

        anioEscolarInput.innerHTML = html;
    }

    function renderPeriodosForAnio(anioEscolarId) {
        const id = Number(anioEscolarId || 0);
        const periodos = id > 0 ? (state.periodosCobroByAnio.get(id) || []) : [];

        if (id <= 0) {
            periodoInput.innerHTML = '<option value="">Seleccione un a&ntilde;o escolar</option>';
            periodoInput.disabled = true;
            periodoInput.value = '';
            return;
        }

        periodoInput.disabled = false;
        let html = '<option value="">Todos los periodos</option>';
        periodos.forEach((periodo) => {
            const periodoId = Number(periodo.id || 0);
            if (periodoId <= 0) {
                return;
            }

            const label = String(periodo.nombre || ('Periodo ' + String(periodo.numero_mes || periodoId)));
            html += '<option value="' + periodoId + '">' + escHtml(label) + '</option>';
        });

        periodoInput.innerHTML = html;
        periodoInput.value = '';
    }

    function getStudentById(estudianteId) {
        return state.estudiantes.find((row) => Number(row.id || 0) === estudianteId) || null;
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

    function buildPendientes(inscripcion, fechaCorteIso) {
        const pendientes = [];
        const inscripcionId = Number(inscripcion.id || 0);
        const estudianteId = Number(inscripcion.estudiante_id || 0);
        const estudiante = getStudentById(estudianteId);
        const planificacion = state.planificaciones.get(Number(inscripcion.planificacion_academica_id || 0)) || null;
        const anio = planificacion ? (state.aniosEscolares.get(Number(planificacion.anio_escolar_id || 0)) || null) : null;
        const anioEscolarId = anio ? Number(anio.id || 0) : 0;
        const parametros = anio ? (state.parametrosByAnio.get(Number(anio.id || 0)) || null) : null;

        const fechaInscripcion = parseDate(inscripcion.fecha_inscripcion);
        const fechaCorte = parseDate(fechaCorteIso);
        const paidKeys = buildPaidKeysFromHistorial(inscripcionId);

        const inscripcionMonto = Number(inscripcion.tarifa_inscripcion || 0);
        const keyInscripcion = 'INSCRIPCION:' + String(inscripcionId);
        if (!paidKeys.has(keyInscripcion) && inscripcionMonto > 0) {
            pendientes.push({
                estudiante_id: estudianteId,
                estudiante_nombre: estudiante ? fullName(estudiante) : ('ESTUDIANTE #' + estudianteId),
                estudiante_sigerd: estudiante ? String(estudiante.id_sigerd || '-') : '-',
                anio_escolar_id: anioEscolarId,
                periodo_id: 0,
                concepto: 'INSCRIPCION',
                periodo: 'Inscripcion',
                monto_base: Number(inscripcionMonto.toFixed(2)),
                mora: 0,
                monto_total: Number(inscripcionMonto.toFixed(2)),
                estado: 'PENDIENTE',
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
            let estado = 'PENDIENTE';
            if (fechaCorte instanceof Date && fechaCorte.getTime() > fechaLimite.getTime()) {
                estado = 'VENCIDO';
                if (moraMensual > 0) {
                    mora = Number(moraMensual.toFixed(2));
                }
            }

            const base = Number(mensualidad.toFixed(2));
            const periodoCobro = getPeriodoCobroByAnioYMes(anioEscolarId, mes);
            pendientes.push({
                estudiante_id: estudianteId,
                estudiante_nombre: estudiante ? fullName(estudiante) : ('ESTUDIANTE #' + estudianteId),
                estudiante_sigerd: estudiante ? String(estudiante.id_sigerd || '-') : '-',
                anio_escolar_id: anioEscolarId,
                periodo_id: periodoCobro ? Number(periodoCobro.id || 0) : 0,
                concepto: 'CUOTA',
                periodo: periodoCobro ? String(periodoCobro.nombre || (monthName(mes) + ' ' + String(anioCuota))) : (monthName(mes) + ' ' + String(anioCuota)),
                monto_base: base,
                mora: mora,
                monto_total: Number((base + mora).toFixed(2)),
                estado: estado,
            });
        }

        return pendientes;
    }

    function getInscripcionesObjetivo() {
        const byEstudiante = new Map();

        state.inscripciones.forEach((inscripcion) => {
            const estudianteId = Number(inscripcion.estudiante_id || 0);
            if (estudianteId <= 0) {
                return;
            }

            const current = byEstudiante.get(estudianteId) || null;
            if (current === null) {
                byEstudiante.set(estudianteId, inscripcion);
                return;
            }

            const currentActiva = Number(current.inscripcion_activa || 0) === 1;
            const nuevaActiva = Number(inscripcion.inscripcion_activa || 0) === 1;
            const currentFecha = String(current.fecha_inscripcion || '');
            const nuevaFecha = String(inscripcion.fecha_inscripcion || '');

            if (nuevaActiva && !currentActiva) {
                byEstudiante.set(estudianteId, inscripcion);
                return;
            }

            if (nuevaActiva === currentActiva && nuevaFecha > currentFecha) {
                byEstudiante.set(estudianteId, inscripcion);
                return;
            }

            if (nuevaActiva === currentActiva && nuevaFecha === currentFecha && Number(inscripcion.id || 0) > Number(current.id || 0)) {
                byEstudiante.set(estudianteId, inscripcion);
            }
        });

        return Array.from(byEstudiante.values()).sort((a, b) => {
            const estudianteA = getStudentById(Number(a.estudiante_id || 0));
            const estudianteB = getStudentById(Number(b.estudiante_id || 0));
            return fullName(estudianteA || {}).localeCompare(fullName(estudianteB || {}));
        });
    }

    function renderTablaPendientes() {
        if (state.pendientes.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No hay cuentas por cobrar para los filtros seleccionados.</td></tr>';
            totalPendiente.textContent = 'RD$ 0.00';
            totalMora.textContent = 'RD$ 0.00';
            totalItems.textContent = '0';
            totalBadge.textContent = '0 pendientes';
            return;
        }

        let html = '';
        let sumaPendiente = 0;
        let sumaMora = 0;
        state.pendientes.forEach((item) => {
            sumaPendiente += Number(item.monto_total || 0);
            sumaMora += Number(item.mora || 0);
            const badge = item.estado === 'VENCIDO'
                ? '<span class="badge badge-danger">Vencido</span>'
                : '<span class="badge badge-warning">Pendiente</span>';

            html += '<tr>'
                + '<td>' + escHtml(item.estudiante_nombre || '-') + '</td>'
                + '<td>' + escHtml(item.estudiante_sigerd || '-') + '</td>'
                + '<td>' + escHtml(item.concepto) + '</td>'
                + '<td>' + escHtml(item.periodo) + '</td>'
                + '<td class="text-right">' + escHtml(formatMoney(item.monto_base)) + '</td>'
                + '<td class="text-right">' + escHtml(formatMoney(item.mora)) + '</td>'
                + '<td class="text-right">' + escHtml(formatMoney(item.monto_total)) + '</td>'
                + '<td>' + badge + '</td>'
                + '</tr>';
        });

        tbody.innerHTML = html;
        totalPendiente.textContent = formatMoney(Number(sumaPendiente.toFixed(2)));
        totalMora.textContent = formatMoney(Number(sumaMora.toFixed(2)));
        totalItems.textContent = String(state.pendientes.length);
        totalBadge.textContent = state.pendientes.length + ' pendientes';
    }

    function refreshCuentasPorCobrar() {
        const fechaCorte = String(fechaCorteInput.value || '').trim() || getTodayIso();
        fechaCorteInput.value = fechaCorte;
        const anioFiltro = Number(anioEscolarInput.value || 0);
        const periodoFiltro = Number(periodoInput.value || 0);
        const periodoSeleccionado = periodoFiltro > 0 ? (state.periodosCobroById.get(periodoFiltro) || null) : null;
        const anioFiltroEfectivo = anioFiltro > 0
            ? anioFiltro
            : (periodoSeleccionado ? Number(periodoSeleccionado.anio_escolar_id || 0) : 0);

        const inscripcionesObjetivo = getInscripcionesObjetivo();
        if (inscripcionesObjetivo.length === 0) {
            reglaResumen.textContent = 'No hay inscripciones registradas para calcular cuentas por cobrar.';
            estudianteInfo.textContent = 'Sin datos para mostrar.';
            state.pendientes = [];
            renderTablaPendientes();
            return;
        }

        const pendientes = [];
        inscripcionesObjetivo.forEach((inscripcion) => {
            const planificacion = state.planificaciones.get(Number(inscripcion.planificacion_academica_id || 0)) || null;
            const anio = planificacion ? (state.aniosEscolares.get(Number(planificacion.anio_escolar_id || 0)) || null) : null;
            const anioInscripcion = anio ? Number(anio.id || 0) : 0;

            if (anioFiltroEfectivo > 0 && anioInscripcion !== anioFiltroEfectivo) {
                return;
            }

            buildPendientes(inscripcion, fechaCorte).forEach((item) => {
                if (anioFiltroEfectivo > 0 && Number(item.anio_escolar_id || 0) !== anioFiltroEfectivo) {
                    return;
                }

                if (periodoFiltro > 0 && item.concepto === 'CUOTA' && Number(item.periodo_id || 0) !== periodoFiltro) {
                    return;
                }

                pendientes.push(item);
            });
        });

        state.pendientes = pendientes.sort((a, b) => {
            const nombreDiff = String(a.estudiante_nombre || '').localeCompare(String(b.estudiante_nombre || ''));
            if (nombreDiff !== 0) {
                return nombreDiff;
            }

            const periodoDiff = String(a.periodo || '').localeCompare(String(b.periodo || ''));
            if (periodoDiff !== 0) {
                return periodoDiff;
            }

            return String(a.concepto || '').localeCompare(String(b.concepto || ''));
        });

        const estudiantesConDeuda = new Set(state.pendientes.map((item) => Number(item.estudiante_id || 0))).size;
        const vencidos = state.pendientes.filter((item) => item.estado === 'VENCIDO').length;
        const partesResumen = ['Listado general de estudiantes con cuentas por cobrar al ' + formatDateISO(fechaCorte) + '.'];
        if (anioFiltroEfectivo > 0) {
            const anio = state.aniosEscolares.get(anioFiltroEfectivo) || null;
            partesResumen.push('A&ntilde;o escolar: ' + escHtml(String(anio ? anio.nombre || anioFiltroEfectivo : anioFiltroEfectivo)) + '.');
        }
        if (periodoFiltro > 0 && periodoSeleccionado) {
            partesResumen.push('Periodo: ' + escHtml(String(periodoSeleccionado.nombre || periodoFiltro)) + '.');
        }
        reglaResumen.innerHTML = partesResumen.join(' ');
        estudianteInfo.textContent = 'Estudiantes con deuda: ' + estudiantesConDeuda + ' | Conceptos vencidos: ' + vencidos + ' | Se incluyen inscripciones y cuotas sin pagar.';

        renderTablaPendientes();
    }

    async function init() {
        fechaCorteInput.value = getTodayIso();

        try {
            const [
                estudiantes,
                inscripciones,
                planificaciones,
                anios,
                periodos,
                parametros,
                pagos,
            ] = await Promise.all([
                apiRequest('estudiantes', 'index', { method: 'GET', query: { limit: 3000, offset: 0 } }),
                apiRequest('inscripciones', 'index', { method: 'GET', query: { limit: 3000, offset: 0 } }),
                apiRequest('planificaciones_academicas', 'index', { method: 'GET', query: { limit: 3000, offset: 0 } }),
                apiRequest('anios_escolares', 'index', { method: 'GET', query: { limit: 200, offset: 0 } }),
                apiRequest('periodos_cobro', 'index', { method: 'GET', query: { limit: 500, offset: 0 } }),
                apiRequest('parametros_financieros', 'index', { method: 'GET', query: { limit: 200, offset: 0 } }),
                apiRequest('pagos', 'index', { method: 'GET', query: { limit: 3000, offset: 0 } }),
            ]);

            state.estudiantes = Array.isArray(estudiantes) ? estudiantes : [];
            state.inscripciones = Array.isArray(inscripciones) ? inscripciones : [];
            state.planificaciones = toMap(Array.isArray(planificaciones) ? planificaciones : [], 'id');
            state.aniosEscolares = toMap(Array.isArray(anios) ? anios : [], 'id');
            state.periodosCobro = Array.isArray(periodos) ? periodos : [];
            state.periodosCobroById = toMap(state.periodosCobro, 'id');
            state.periodosCobroByAnio = new Map();
            state.periodosCobro.forEach((periodo) => {
                const anioId = Number(periodo.anio_escolar_id || 0);
                if (anioId <= 0) {
                    return;
                }

                const current = state.periodosCobroByAnio.get(anioId) || [];
                current.push(periodo);
                state.periodosCobroByAnio.set(anioId, current);
            });
            state.pagos = Array.isArray(pagos) ? pagos : [];

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

            renderAniosEscolares();
            renderPeriodosForAnio(0);
            refreshCuentasPorCobrar();
        } catch (error) {
            reglaResumen.textContent = error instanceof Error ? error.message : 'No se pudieron cargar las cuentas por cobrar.';
            estudianteInfo.textContent = 'Sin datos para mostrar.';
            state.pendientes = [];
            renderTablaPendientes();
        }
    }

    anioEscolarInput.addEventListener('change', () => {
        renderPeriodosForAnio(Number(anioEscolarInput.value || 0));
        refreshCuentasPorCobrar();
    });

    periodoInput.addEventListener('change', refreshCuentasPorCobrar);
    fechaCorteInput.addEventListener('change', refreshCuentasPorCobrar);

    init();
})();
</script>
