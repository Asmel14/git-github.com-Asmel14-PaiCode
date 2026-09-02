<?php

declare(strict_types=1);
?>
<style>
    :root {
        --dash-bg: linear-gradient(135deg, #10243f 0%, #17436b 45%, #0c5a6b 100%);
        --dash-card-shadow: 0 14px 38px rgba(15, 23, 42, 0.12);
        --dash-border: rgba(15, 23, 42, 0.08);
        --dash-soft: #f8fafc;
    }

    .dashboard-hero {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
        background: var(--dash-bg);
        color: #fff;
        padding: 1.35rem 1.5rem;
        box-shadow: var(--dash-card-shadow);
        margin-bottom: 1.25rem;
    }

    .dashboard-hero::before,
    .dashboard-hero::after {
        content: '';
        position: absolute;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.08);
        pointer-events: none;
    }

    .dashboard-hero::before {
        width: 240px;
        height: 240px;
        right: -80px;
        top: -90px;
    }

    .dashboard-hero::after {
        width: 180px;
        height: 180px;
        right: 120px;
        bottom: -90px;
    }

    .dashboard-hero h1 {
        color: #fff;
        margin-bottom: 0.35rem;
    }

    .dashboard-hero .hero-subtitle {
        color: rgba(255, 255, 255, 0.82);
        max-width: 820px;
        margin-bottom: 0;
    }

    .dashboard-hero .hero-meta {
        margin-top: 1rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.75rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        font-size: 0.875rem;
    }

    .metric-card {
        border: 1px solid var(--dash-border);
        border-radius: 1rem;
        box-shadow: var(--dash-card-shadow);
        overflow: hidden;
        background: #fff;
    }

    .metric-card .card-body {
        padding: 1rem 1rem 0.95rem;
    }

    .metric-card .metric-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        margin-bottom: 0.3rem;
    }

    .metric-card .metric-value {
        font-size: 1.7rem;
        font-weight: 800;
        line-height: 1.1;
        color: #0f172a;
    }

    .metric-card .metric-subvalue {
        font-size: 0.86rem;
        color: #64748b;
        margin-top: 0.35rem;
    }

    .metric-card .metric-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.12);
    }

    .metric-card.primary .metric-icon { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
    .metric-card.success .metric-icon { background: linear-gradient(135deg, #10b981, #059669); }
    .metric-card.warning .metric-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .metric-card.danger .metric-icon { background: linear-gradient(135deg, #ef4444, #dc2626); }
    .metric-card.info .metric-icon { background: linear-gradient(135deg, #14b8a6, #0f766e); }
    .metric-card.dark .metric-icon { background: linear-gradient(135deg, #334155, #0f172a); }

    .panel-card {
        border: 1px solid var(--dash-border);
        border-radius: 1rem;
        box-shadow: var(--dash-card-shadow);
        background: #fff;
        overflow: hidden;
    }

    .panel-card .card-header {
        background: linear-gradient(180deg, #ffffff, #fbfdff);
        border-bottom: 1px solid rgba(148, 163, 184, 0.22);
    }

    .panel-subtitle {
        color: #64748b;
        font-size: 0.92rem;
    }

    .activity-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 88px;
        padding: 0.2rem 0.55rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .activity-badge.create { background: #dcfce7; color: #166534; }
    .activity-badge.update { background: #dbeafe; color: #1d4ed8; }
    .activity-badge.delete { background: #fee2e2; color: #b91c1c; }
    .activity-badge.login { background: #e0f2fe; color: #0369a1; }
    .activity-badge.logout { background: #f3e8ff; color: #7c3aed; }
    .activity-badge.default { background: #e2e8f0; color: #334155; }

    .dashboard-grid-gap {
        gap: 1rem;
    }

    .chart-box {
        position: relative;
        min-height: 320px;
    }

    .empty-state {
        min-height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        background: linear-gradient(135deg, #f8fafc, #eef2ff);
        border: 1px dashed rgba(100, 116, 139, 0.25);
        border-radius: 0.75rem;
    }
</style>

<div class="dashboard-hero">
    <div class="position-relative" style="z-index: 1;">
        <div class="d-flex flex-wrap justify-content-between align-items-start">
            <div class="pr-3">
                <h1 class="h3 mb-2">Dashboard administrativo</h1>
                <p class="hero-subtitle">Vista consolidada del sistema con indicadores de estudiantes, personal, cobros, cuentas por cobrar, secciones y actividad reciente.</p>
            </div>
            <div class="text-right mt-3 mt-md-0">
                <div class="hero-pill mb-2"><i class="fas fa-heartbeat"></i> API operativa</div>
                <div class="hero-pill"><i class="fas fa-calendar-day"></i> Actualizado hoy</div>
            </div>
        </div>
        <div class="hero-meta">
            <div class="hero-pill"><i class="fas fa-user-graduate"></i> Estudiantes y matrículas</div>
            <div class="hero-pill"><i class="fas fa-hand-holding-usd"></i> Pagos y cartera</div>
            <div class="hero-pill"><i class="fas fa-shield-alt"></i> Auditoria del sistema</div>
        </div>
    </div>
</div>

<div class="row dashboard-grid-gap">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card primary h-100">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="metric-label">Estudiantes</div>
                    <div class="metric-value" id="dash-estudiantes">0</div>
                    <div class="metric-subvalue" id="dash-estudiantes-sub">Total registrados</div>
                </div>
                <div class="metric-icon"><i class="fas fa-user-graduate"></i></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card success h-100">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="metric-label">Personal</div>
                    <div class="metric-value" id="dash-personal">0</div>
                    <div class="metric-subvalue" id="dash-personal-sub">Activos / total</div>
                </div>
                <div class="metric-icon"><i class="fas fa-users-cog"></i></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card info h-100">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="metric-label">Pagos</div>
                    <div class="metric-value" id="dash-pagos">0</div>
                    <div class="metric-subvalue" id="dash-recaudado">RD$ 0.00 recaudados</div>
                </div>
                <div class="metric-icon"><i class="fas fa-cash-register"></i></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card danger h-100">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="metric-label">Cuentas por cobrar</div>
                    <div class="metric-value" id="dash-cxc">0</div>
                    <div class="metric-subvalue" id="dash-cxc-monto">RD$ 0.00 pendiente</div>
                </div>
                <div class="metric-icon"><i class="fas fa-file-invoice-dollar"></i></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card warning h-100">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="metric-label">Secciones</div>
                    <div class="metric-value" id="dash-secciones">0</div>
                    <div class="metric-subvalue" id="dash-secciones-sub">Catalogo academico</div>
                </div>
                <div class="metric-icon"><i class="fas fa-th-large"></i></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card dark h-100">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="metric-label">Inscripciones activas</div>
                    <div class="metric-value" id="dash-inscripciones">0</div>
                    <div class="metric-subvalue" id="dash-inscripciones-sub">Estudiantes inscritos</div>
                </div>
                <div class="metric-icon"><i class="fas fa-id-card"></i></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card primary h-100">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="metric-label">Usuarios activos</div>
                    <div class="metric-value" id="dash-usuarios">0</div>
                    <div class="metric-subvalue" id="dash-usuarios-sub">Acceso al sistema</div>
                </div>
                <div class="metric-icon"><i class="fas fa-user-shield"></i></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card success h-100">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="metric-label">Mora acumulada</div>
                    <div class="metric-value" id="dash-mora">RD$ 0.00</div>
                    <div class="metric-subvalue" id="dash-vencidos">0 vencidos</div>
                </div>
                <div class="metric-icon"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card info h-100">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="metric-label">Anios escolares</div>
                    <div class="metric-value" id="dash-anios">0</div>
                    <div class="metric-subvalue" id="dash-periodos">0 periodos de cobro</div>
                </div>
                <div class="metric-icon"><i class="fas fa-school"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 mb-4">
        <div class="card panel-card h-100">
            <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h6 class="m-0 font-weight-bold text-primary">Recaudacion y cartera reciente</h6>
                    <div class="panel-subtitle">Monto cobrado vs. deuda proyectada en los ultimos 6 meses.</div>
                </div>
                <span class="badge badge-light" id="dash-rango-fechas">Ultimos 6 meses</span>
            </div>
            <div class="card-body">
                <div class="chart-box">
                    <canvas id="chart-finanzas"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 mb-4">
        <div class="card panel-card h-100">
            <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h6 class="m-0 font-weight-bold text-primary">Cuentas por cobrar</h6>
                    <div class="panel-subtitle">Distribucion por concepto.</div>
                </div>
                <span class="badge badge-light" id="dash-deuda-items">0 items</span>
            </div>
            <div class="card-body">
                <div class="chart-box" style="min-height: 300px;">
                    <canvas id="chart-deuda"></canvas>
                </div>
                <div id="dash-deuda-empty" class="empty-state d-none">No hay deuda pendiente para mostrar.</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-5 mb-4">
        <div class="card panel-card h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Inventario del sistema</h6>
                <div class="panel-subtitle">Resumen rapido de catalogos y operaciones.</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody id="dash-inventory-body">
                            <tr><td class="text-muted">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-7 mb-4">
        <div class="card panel-card h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="m-0 font-weight-bold text-primary">Actividad reciente</h6>
                    <div class="panel-subtitle">Ultimas acciones registradas en auditoria.</div>
                </div>
                <span class="badge badge-light" id="dash-auditoria-count">0 eventos</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Modulo</th>
                                <th>Accion</th>
                                <th>Detalle</th>
                            </tr>
                        </thead>
                        <tbody id="dash-auditoria-body">
                            <tr><td colspan="5" class="text-center text-muted">Cargando actividad...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6 mb-4">
        <div class="card panel-card h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pagos recientes</h6>
                <div class="panel-subtitle">Ultimos cobros registrados en el sistema.</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Recibo</th>
                                <th>Monto</th>
                                <th>Metodo</th>
                            </tr>
                        </thead>
                        <tbody id="dash-pagos-body">
                            <tr><td colspan="4" class="text-center text-muted">Cargando pagos...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 mb-4">
        <div class="card panel-card h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Cartera pendiente</h6>
                <div class="panel-subtitle">Inscripcion + cuotas pendientes por estudiante.</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Estudiante</th>
                                <th>Concepto</th>
                                <th>Periodo</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody id="dash-cxc-body">
                            <tr><td colspan="4" class="text-center text-muted">Cargando cuentas por cobrar...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(function () {
    const MARCADOR_DETALLE = 'DETALLE_PAGO_JSON:';
    const hoy = new Date();

    const state = {
        estudiantes: [],
        personal: [],
        usuarios: [],
        usuariosById: new Map(),
        pagos: [],
        metodosPago: new Map(),
        inscripciones: [],
        planificaciones: new Map(),
        anios: new Map(),
        secciones: [],
        periodos: [],
        periodosById: new Map(),
        periodosByAnio: new Map(),
        parametrosByAnio: new Map(),
        auditoria: [],
        pagosDetalleById: new Map(),
        cuentas: [],
        charts: {
            finanzas: null,
            deuda: null,
        },
    };

    function escHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function pad2(value) {
        return String(value).padStart(2, '0');
    }

    function formatMoney(value) {
        const amount = Number(value || 0);
        return 'RD$ ' + amount.toLocaleString('es-DO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function fullName(persona) {
        const parts = [
            persona.primer_nombre,
            persona.segundo_nombre,
            persona.primer_apellido,
            persona.segundo_apellido,
        ].map((item) => String(item || '').trim()).filter(Boolean);

        return parts.join(' ');
    }

    function normalizeText(value) {
        return String(value || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }

    function todayIso() {
        return [hoy.getFullYear(), pad2(hoy.getMonth() + 1), pad2(hoy.getDate())].join('-');
    }

    function monthKey(dateObj) {
        return [dateObj.getFullYear(), pad2(dateObj.getMonth() + 1)].join('-');
    }

    function monthLabel(dateObj) {
        const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        return months[dateObj.getMonth()] + ' ' + dateObj.getFullYear();
    }

    function parseDateValue(value) {
        const text = String(value || '').trim();
        const match = text.match(/^(\d{4}-\d{2}-\d{2})/);
        if (!match) {
            return null;
        }

        const dt = new Date(match[1] + 'T00:00:00');
        return Number.isNaN(dt.getTime()) ? null : dt;
    }

    function formatDateTime(value) {
        const text = String(value || '').trim();
        const match = text.match(/^(\d{4}-\d{2}-\d{2})(?:[ T](\d{2}:\d{2})(?::\d{2})?)?/);
        if (!match) {
            return text || '-';
        }

        const fecha = match[1].slice(8, 10) + '/' + match[1].slice(5, 7) + '/' + match[1].slice(0, 4);
        return match[2] ? (fecha + ' ' + match[2]) : fecha;
    }

    function apiRequest(resource, action, options) {
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

        return fetch('../?' + params.toString(), {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: payload ? JSON.stringify(payload) : undefined,
            credentials: 'same-origin',
        })
        .then((response) => response.text().then((raw) => ({ response: response, raw: raw })))
        .then(({ response, raw }) => {
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
        });
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

    function isActiveRow(row) {
        if (!row || typeof row !== 'object') {
            return false;
        }

        if (Object.prototype.hasOwnProperty.call(row, 'estado')) {
            const estado = String(row.estado || '').toUpperCase();
            return estado === '1' || estado === 'ACTIVO' || estado === 'ACTIVA';
        }

        if (Object.prototype.hasOwnProperty.call(row, 'activo')) {
            return Number(row.activo || 0) === 1;
        }

        return true;
    }

    function countActive(rows) {
        return rows.filter((row) => isActiveRow(row)).length;
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

    function getStudentById(id) {
        return state.estudiantes.find((row) => Number(row.id || 0) === Number(id || 0)) || null;
    }

    function getPeriodoCobroByAnioYMes(anioEscolarId, numeroMes) {
        const periodos = state.periodosByAnio.get(Number(anioEscolarId || 0)) || [];
        return periodos.find((periodo) => Number(periodo.numero_mes || 0) === Number(numeroMes || 0)) || null;
    }

    function buildPendingDebt(inscripcion, fechaCorteIso) {
        const pendientes = [];
        const inscripcionId = Number(inscripcion.id || 0);
        const estudianteId = Number(inscripcion.estudiante_id || 0);
        const estudiante = getStudentById(estudianteId);
        const planificacion = state.planificaciones.get(Number(inscripcion.planificacion_academica_id || 0)) || null;
        const anio = planificacion ? (state.anios.get(Number(planificacion.anio_escolar_id || 0)) || null) : null;
        const anioEscolarId = anio ? Number(anio.id || 0) : 0;
        const parametros = anio ? (state.parametrosByAnio.get(Number(anio.id || 0)) || null) : null;

        const fechaInscripcion = parseDateValue(inscripcion.fecha_inscripcion);
        const fechaCorte = parseDateValue(fechaCorteIso);
        const paidKeys = buildPaidKeysFromHistorial(inscripcionId);

        const inscripcionMonto = Number(inscripcion.tarifa_inscripcion || 0);
        const keyInscripcion = 'INSCRIPCION:' + String(inscripcionId);
        if (!paidKeys.has(keyInscripcion) && inscripcionMonto > 0) {
            pendientes.push({
                estudiante_id: estudianteId,
                estudiante_nombre: estudiante ? fullName(estudiante) : ('ESTUDIANTE #' + estudianteId),
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

            const periodoCobro = getPeriodoCobroByAnioYMes(anioEscolarId, mes);
            const diaLimite = Math.min(diaVencimiento, 28);
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
            pendientes.push({
                estudiante_id: estudianteId,
                estudiante_nombre: estudiante ? fullName(estudiante) : ('ESTUDIANTE #' + estudianteId),
                concepto: 'CUOTA',
                periodo: periodoCobro ? String(periodoCobro.nombre || (monthLabel(new Date(anioCuota + '-' + pad2(mes) + '-01T00:00:00')))) : (monthLabel(new Date(anioCuota + '-' + pad2(mes) + '-01T00:00:00'))),
                monto_base: base,
                mora: mora,
                monto_total: Number((base + mora).toFixed(2)),
                estado: estado,
            });
        }

        return pendientes;
    }

    function buildPendingDebtList(fechaCorteIso) {
        const list = [];

        state.inscripciones.forEach((inscripcion) => {
            if (Number(inscripcion.inscripcion_activa || 0) !== 1) {
                return;
            }

            buildPendingDebt(inscripcion, fechaCorteIso).forEach((item) => {
                list.push(item);
            });
        });

        return list.sort((a, b) => {
            const nombre = String(a.estudiante_nombre || '').localeCompare(String(b.estudiante_nombre || ''));
            if (nombre !== 0) {
                return nombre;
            }

            return String(a.periodo || '').localeCompare(String(b.periodo || ''));
        });
    }

    function extractAuditDate(row) {
        const fields = ['fecha', 'created_at', 'fecha_creacion', 'fecha_registro', 'timestamp', 'date_created'];
        for (const field of fields) {
            if (row && row[field]) {
                const parsed = parseDateValue(row[field]);
                if (parsed instanceof Date) {
                    return parsed;
                }
            }
        }

        return null;
    }

    function auditLabel(row) {
        const dateObj = extractAuditDate(row);
        if (!(dateObj instanceof Date)) {
            return '-';
        }

        const time = String(row.fecha || row.created_at || row.fecha_creacion || row.fecha_registro || row.timestamp || row.date_created || '');
        const timeMatch = time.match(/(\d{2}:\d{2})(?::\d{2})?/);
        const timeLabel = timeMatch ? timeMatch[1] : '';
        const dateLabel = [dateObj.getDate(), pad2(dateObj.getMonth() + 1), dateObj.getFullYear()].join('/');

        return timeLabel !== '' ? (dateLabel + ' ' + timeLabel) : dateLabel;
    }

    function actionBadgeClass(action) {
        const value = normalizeText(action);
        if (value.includes('crear') || value.includes('store') || value.includes('login')) {
            return 'create';
        }
        if (value.includes('actualiz') || value.includes('update')) {
            return 'update';
        }
        if (value.includes('elimin') || value.includes('destroy') || value.includes('logout')) {
            return 'delete';
        }
        if (value.includes('consult') || value.includes('index') || value.includes('show')) {
            return 'default';
        }

        return 'default';
    }

    function methodLabel(method) {
        const value = String(method || '').trim().toUpperCase();
        if (value === '') {
            return 'Sistema';
        }

        return value;
    }

    function updateSummaryCards() {
        const estudiantesTotal = state.estudiantes.length;
        const personalTotal = state.personal.length;
        const personalActivos = countActive(state.personal);
        const pagosTotal = state.pagos.length;
        const recaudado = state.pagos.reduce((acc, pago) => acc + Number(pago.monto_total || 0), 0);
        const activosInscripcion = state.inscripciones.filter((row) => Number(row.inscripcion_activa || 0) === 1).length;
        const usuariosActivos = countActive(state.usuarios);
        const seccionesTotal = state.secciones.length;
        const aniosTotal = state.anios.size;
        const periodosTotal = state.periodos.length;

        const deuda = state.cuentas;
        const deudaCount = deuda.length;
        const deudaMonto = deuda.reduce((acc, item) => acc + Number(item.monto_total || 0), 0);
        const moraMonto = deuda.reduce((acc, item) => acc + Number(item.mora || 0), 0);
        const vencidos = deuda.filter((item) => item.estado === 'VENCIDO').length;

        document.getElementById('dash-estudiantes').textContent = String(estudiantesTotal);
        document.getElementById('dash-estudiantes-sub').textContent = activosInscripcion + ' con inscripcion activa';
        document.getElementById('dash-personal').textContent = String(personalTotal);
        document.getElementById('dash-personal-sub').textContent = personalActivos + ' activos';
        document.getElementById('dash-pagos').textContent = String(pagosTotal);
        document.getElementById('dash-recaudado').textContent = formatMoney(recaudado) + ' recaudados';
        document.getElementById('dash-cxc').textContent = String(deudaCount);
        document.getElementById('dash-cxc-monto').textContent = formatMoney(deudaMonto) + ' pendiente';
        document.getElementById('dash-secciones').textContent = String(seccionesTotal);
        document.getElementById('dash-inscripciones').textContent = String(activosInscripcion);
        document.getElementById('dash-usuarios').textContent = String(usuariosActivos);
        document.getElementById('dash-mora').textContent = formatMoney(moraMonto);
        document.getElementById('dash-vencidos').textContent = String(vencidos) + ' vencidos';
        document.getElementById('dash-anios').textContent = String(aniosTotal);
        document.getElementById('dash-periodos').textContent = String(periodosTotal) + ' periodos de cobro';

        document.getElementById('dash-deuda-items').textContent = String(deudaCount) + ' items';
        document.getElementById('dash-rango-fechas').textContent = 'Corte ' + formatDateTime(todayIso());
    }

    function renderInventory() {
        const rows = [
            ['Estudiantes registrados', state.estudiantes.length],
            ['Personal activo', countActive(state.personal)],
            ['Usuarios activos', countActive(state.usuarios)],
            ['Secciones academicas', state.secciones.length],
            ['Anios escolares', state.anios.size],
            ['Periodos de cobro', state.periodos.length],
            ['Inscripciones activas', state.inscripciones.filter((row) => Number(row.inscripcion_activa || 0) === 1).length],
            ['Pagos registrados', state.pagos.length],
        ];

        document.getElementById('dash-inventory-body').innerHTML = rows.map((item) => {
            return '<tr>'
                + '<td class="text-muted">' + escHtml(item[0]) + '</td>'
                + '<td class="text-right font-weight-bold">' + escHtml(String(item[1])) + '</td>'
                + '</tr>';
        }).join('');
    }

    function renderPaymentsTable() {
        const rows = state.pagos.slice().sort((a, b) => String(b.fecha_pago || '').localeCompare(String(a.fecha_pago || ''))).slice(0, 8);
        if (rows.length === 0) {
            document.getElementById('dash-pagos-body').innerHTML = '<tr><td colspan="4" class="text-center text-muted">No hay pagos registrados.</td></tr>';
            return;
        }

        document.getElementById('dash-pagos-body').innerHTML = rows.map((row) => {
            const metodo = state.metodosPago.get(Number(row.metodo_pago_id || 0)) || null;
            return '<tr>'
                + '<td>' + escHtml(formatDateTime(row.fecha_pago)) + '</td>'
                + '<td>' + escHtml(String(row.numero_recibo || '-')) + '</td>'
                + '<td class="text-right">' + escHtml(formatMoney(Number(row.monto_total || 0))) + '</td>'
                + '<td>' + escHtml(metodo ? String(metodo.nombre || '-') : '-') + '</td>'
                + '</tr>';
        }).join('');
    }

    function renderDebtTable() {
        const rows = state.cuentas.slice(0, 8);
        if (rows.length === 0) {
            document.getElementById('dash-cxc-body').innerHTML = '<tr><td colspan="4" class="text-center text-muted">No hay cuentas por cobrar.</td></tr>';
            document.getElementById('dash-deuda-empty').classList.remove('d-none');
            return;
        }

        document.getElementById('dash-deuda-empty').classList.add('d-none');
        document.getElementById('dash-cxc-body').innerHTML = rows.map((row) => {
            return '<tr>'
                + '<td>' + escHtml(String(row.estudiante_nombre || '-')) + '</td>'
                + '<td>' + escHtml(String(row.concepto || '-')) + '</td>'
                + '<td>' + escHtml(String(row.periodo || '-')) + '</td>'
                + '<td class="text-right">' + escHtml(formatMoney(Number(row.monto_total || 0))) + '</td>'
                + '</tr>';
        }).join('');
    }

    function renderAuditTable() {
        const rows = state.auditoria.slice().sort((a, b) => String(b.fecha || b.created_at || b.fecha_creacion || b.fecha_registro || b.timestamp || b.date_created || '').localeCompare(String(a.fecha || a.created_at || a.fecha_creacion || a.fecha_registro || a.timestamp || a.date_created || ''))).slice(0, 8);
        document.getElementById('dash-auditoria-count').textContent = String(state.auditoria.length) + ' eventos';

        if (rows.length === 0) {
            document.getElementById('dash-auditoria-body').innerHTML = '<tr><td colspan="5" class="text-center text-muted">No hay eventos de auditoria.</td></tr>';
            return;
        }

        document.getElementById('dash-auditoria-body').innerHTML = rows.map((row) => {
            const action = String(row.accion || '-');
            const badgeClass = actionBadgeClass(action);
            const userName = row.usuario_id ? (state.usuariosById.get(Number(row.usuario_id || 0)) || {}).nombre_completo || (state.usuariosById.get(Number(row.usuario_id || 0)) || {}).correo || ('Usuario #' + Number(row.usuario_id || 0)) : 'Sistema';
            const detail = String(row.descripcion || '').trim() || '-';
            return '<tr>'
                + '<td>' + escHtml(auditLabel(row)) + '</td>'
                + '<td>' + escHtml(String(userName)) + '</td>'
                + '<td>' + escHtml(String(row.modulo || '-')) + '</td>'
                + '<td><span class="activity-badge ' + escHtml(badgeClass) + '">' + escHtml(action) + '</span></td>'
                + '<td>' + escHtml(detail) + '</td>'
                + '</tr>';
        }).join('');
    }

    function buildFinanceChart() {
        const labels = [];
        const paymentCounts = [];
        const paymentAmounts = [];
        const debtAmounts = [];

        const months = [];
        const base = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
        for (let i = 5; i >= 0; i -= 1) {
            months.push(new Date(base.getFullYear(), base.getMonth() - i, 1));
        }

        months.forEach((monthDate) => {
            const key = monthKey(monthDate);
            labels.push(monthLabel(monthDate));

            const payments = state.pagos.filter((pago) => {
                const dt = parseDateValue(pago.fecha_pago);
                return dt instanceof Date && monthKey(dt) === key;
            });

            paymentCounts.push(payments.length);
            paymentAmounts.push(payments.reduce((acc, item) => acc + Number(item.monto_total || 0), 0));

            const debtByMonth = state.cuentas.filter((item) => item.concepto === 'CUOTA' && String(item.periodo || '').toLowerCase().includes(monthDate.getFullYear().toString()));
            debtAmounts.push(debtByMonth.reduce((acc, item) => acc + Number(item.monto_total || 0), 0));
        });

        const canvas = document.getElementById('chart-finanzas');
        if (state.charts.finanzas) {
            state.charts.finanzas.destroy();
        }

        state.charts.finanzas = new Chart(canvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Monto recaudado',
                        data: paymentAmounts,
                        borderColor: '#0ea5e9',
                        backgroundColor: 'rgba(14, 165, 233, 0.12)',
                        tension: 0.35,
                        fill: true,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Cuentas por cobrar estimadas',
                        data: debtAmounts,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.12)',
                        tension: 0.35,
                        fill: true,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Cantidad de pagos',
                        data: paymentCounts,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.16)',
                        borderDash: [5, 5],
                        yAxisID: 'y1',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                if (context.dataset.yAxisID === 'y1') {
                                    return context.dataset.label + ': ' + context.formattedValue;
                                }
                                return context.dataset.label + ': ' + formatMoney(Number(context.raw || 0));
                            },
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return formatMoney(Number(value || 0));
                            },
                        },
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { precision: 0 },
                    },
                },
            },
        });
    }

    function buildDebtChart() {
        const byConcept = state.cuentas.reduce((acc, item) => {
            const key = String(item.concepto || 'OTRO').toUpperCase();
            if (!acc[key]) {
                acc[key] = { count: 0, amount: 0 };
            }
            acc[key].count += 1;
            acc[key].amount += Number(item.monto_total || 0);
            return acc;
        }, {});

        const labels = Object.keys(byConcept);
        const amounts = labels.map((label) => byConcept[label].amount);

        const canvas = document.getElementById('chart-deuda');
        if (state.charts.deuda) {
            state.charts.deuda.destroy();
        }

        if (labels.length === 0) {
            document.getElementById('dash-deuda-empty').classList.remove('d-none');
            return;
        }

        document.getElementById('dash-deuda-empty').classList.add('d-none');
        state.charts.deuda = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: amounts,
                    backgroundColor: ['#2563eb', '#f59e0b', '#ef4444', '#10b981', '#8b5cf6', '#06b6d4'],
                    borderWidth: 0,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.label + ': ' + formatMoney(Number(context.raw || 0));
                            },
                        },
                    },
                },
            },
        });
    }

    function applyMetrics() {
        updateSummaryCards();
        renderInventory();
        renderPaymentsTable();
        renderDebtTable();
        renderAuditTable();
        buildFinanceChart();
        buildDebtChart();
    }

    async function init() {
        try {
            const [
                estudiantes,
                personal,
                usuarios,
                pagos,
                metodosPago,
                inscripciones,
                planificaciones,
                anios,
                secciones,
                periodos,
                parametros,
                auditoria,
            ] = await Promise.all([
                apiRequest('estudiantes', 'index', { method: 'GET', query: { limit: 5000, offset: 0 } }),
                apiRequest('personal', 'index', { method: 'GET', query: { limit: 5000, offset: 0 } }),
                apiRequest('usuarios', 'index', { method: 'GET', query: { limit: 5000, offset: 0 } }),
                apiRequest('pagos', 'index', { method: 'GET', query: { limit: 5000, offset: 0 } }),
                apiRequest('metodos_pago', 'index', { method: 'GET', query: { limit: 5000, offset: 0 } }),
                apiRequest('inscripciones', 'index', { method: 'GET', query: { limit: 5000, offset: 0 } }),
                apiRequest('planificaciones_academicas', 'index', { method: 'GET', query: { limit: 5000, offset: 0 } }),
                apiRequest('anios_escolares', 'index', { method: 'GET', query: { limit: 200, offset: 0 } }),
                apiRequest('secciones', 'index', { method: 'GET', query: { limit: 5000, offset: 0 } }),
                apiRequest('periodos_cobro', 'index', { method: 'GET', query: { limit: 5000, offset: 0 } }),
                apiRequest('parametros_financieros', 'index', { method: 'GET', query: { limit: 5000, offset: 0 } }),
                apiRequest('auditoria', 'index', { method: 'GET', query: { limit: 25, offset: 0 } }),
            ]);

            state.estudiantes = Array.isArray(estudiantes) ? estudiantes : [];
            state.personal = Array.isArray(personal) ? personal : [];
            state.usuarios = Array.isArray(usuarios) ? usuarios : [];
            state.usuariosById = new Map(state.usuarios.map((user) => [Number(user.id || 0), user]).filter((entry) => entry[0] > 0));
            state.pagos = Array.isArray(pagos) ? pagos : [];
            state.metodosPago = toMap(Array.isArray(metodosPago) ? metodosPago : [], 'id');
            state.inscripciones = Array.isArray(inscripciones) ? inscripciones : [];
            state.planificaciones = toMap(Array.isArray(planificaciones) ? planificaciones : [], 'id');
            state.anios = toMap(Array.isArray(anios) ? anios : [], 'id');
            state.secciones = Array.isArray(secciones) ? secciones : [];
            state.periodos = Array.isArray(periodos) ? periodos : [];
            state.auditoria = Array.isArray(auditoria) ? auditoria : [];

            state.periodosById = toMap(state.periodos, 'id');
            state.periodosByAnio = new Map();
            state.periodos.forEach((periodo) => {
                const anioId = Number(periodo.anio_escolar_id || 0);
                if (anioId <= 0) {
                    return;
                }

                const bucket = state.periodosByAnio.get(anioId) || [];
                bucket.push(periodo);
                state.periodosByAnio.set(anioId, bucket);
            });

            state.parametrosByAnio = new Map();
            (Array.isArray(parametros) ? parametros : []).forEach((item) => {
                const anioId = Number(item.anio_escolar_id || 0);
                if (anioId <= 0) {
                    return;
                }

                const current = state.parametrosByAnio.get(anioId) || null;
                if (!current || Number(item.id || 0) > Number(current.id || 0)) {
                    state.parametrosByAnio.set(anioId, item);
                }
            });

            state.pagosDetalleById.clear();
            state.pagos.forEach((pago) => {
                state.pagosDetalleById.set(Number(pago.id || 0), parseDetallePagoFromObservaciones(pago.observaciones));
            });

            state.cuentas = buildPendingDebtList(todayIso());
            document.getElementById('dash-deuda-items').textContent = String(state.cuentas.length) + ' items';
            applyMetrics();
        } catch (error) {
            const msg = error instanceof Error ? error.message : 'No se pudo cargar el dashboard.';
            document.querySelector('.dashboard-hero .hero-subtitle').textContent = msg;
            document.getElementById('dash-inventory-body').innerHTML = '<tr><td class="text-danger">' + escHtml(msg) + '</td></tr>';
        }
    }

    init();
})();
</script>
