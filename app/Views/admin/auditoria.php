<?php

declare(strict_types=1);
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Auditoria</h1>
    <div class="d-flex">
        <button class="btn btn-sm btn-outline-secondary shadow-sm mr-2" type="button" id="btn-auditoria-limpiar">
            <i class="fas fa-eraser fa-sm"></i> Limpiar filtros
        </button>
        <button class="btn btn-sm btn-outline-primary shadow-sm" type="button" id="btn-auditoria-recargar">
            <i class="fas fa-sync fa-sm"></i> Recargar
        </button>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filtros de bitacora</h6>
    </div>
    <div class="card-body">
        <div class="form-row align-items-end">
            <div class="form-group col-lg-2">
                <label for="auditoria-fecha-desde">Desde</label>
                <input id="auditoria-fecha-desde" type="date" class="form-control">
            </div>
            <div class="form-group col-lg-2">
                <label for="auditoria-fecha-hasta">Hasta</label>
                <input id="auditoria-fecha-hasta" type="date" class="form-control">
            </div>
            <div class="form-group col-lg-3">
                <label for="auditoria-usuario">Usuario</label>
                <select id="auditoria-usuario" class="form-control">
                    <option value="">Todos los usuarios</option>
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="auditoria-modulo">Modulo</label>
                <select id="auditoria-modulo" class="form-control">
                    <option value="">Todos los modulos</option>
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="auditoria-accion">Accion</label>
                <select id="auditoria-accion" class="form-control">
                    <option value="">Todas</option>
                </select>
            </div>
            <div class="form-group col-lg-1">
                <button type="button" class="btn btn-primary btn-block" id="btn-auditoria-aplicar">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <div class="form-group mb-0">
            <label for="auditoria-buscar">Buscar texto</label>
            <input id="auditoria-buscar" type="text" class="form-control" placeholder="Buscar por detalle, modulo, accion o usuario">
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Eventos</div>
                <div class="h4 mb-0 font-weight-bold text-gray-800" id="auditoria-total-eventos">0</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Eventos hoy</div>
                <div class="h4 mb-0 font-weight-bold text-gray-800" id="auditoria-eventos-hoy">0</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Modulos</div>
                <div class="h4 mb-0 font-weight-bold text-gray-800" id="auditoria-total-modulos">0</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Usuarios</div>
                <div class="h4 mb-0 font-weight-bold text-gray-800" id="auditoria-total-usuarios">0</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Bitacora del sistema</h6>
        <span id="auditoria-registros-badge" class="badge badge-light">0 registros</span>
    </div>
    <div class="card-body">
        <div id="auditoria-alerta" class="alert alert-info py-2 mb-3">Cargando eventos de auditoria...</div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Modulo</th>
                        <th>Accion</th>
                        <th>Tabla</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody id="auditoria-tbody">
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay eventos para mostrar.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Alertas de seguridad</div>
                <div class="h6 mb-0 font-weight-bold text-gray-800">Sin alertas activas</div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Eventos filtrados</div>
                <div class="h4 mb-0 font-weight-bold text-gray-800" id="auditoria-eventos-filtrados">0</div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const fechaDesdeInput = document.getElementById('auditoria-fecha-desde');
    const fechaHastaInput = document.getElementById('auditoria-fecha-hasta');
    const usuarioInput = document.getElementById('auditoria-usuario');
    const moduloInput = document.getElementById('auditoria-modulo');
    const accionInput = document.getElementById('auditoria-accion');
    const buscarInput = document.getElementById('auditoria-buscar');
    const btnAplicar = document.getElementById('btn-auditoria-aplicar');
    const btnLimpiar = document.getElementById('btn-auditoria-limpiar');
    const btnRecargar = document.getElementById('btn-auditoria-recargar');

    const alerta = document.getElementById('auditoria-alerta');
    const tbody = document.getElementById('auditoria-tbody');
    const badgeRegistros = document.getElementById('auditoria-registros-badge');
    const totalEventos = document.getElementById('auditoria-total-eventos');
    const eventosHoy = document.getElementById('auditoria-eventos-hoy');
    const totalModulos = document.getElementById('auditoria-total-modulos');
    const totalUsuarios = document.getElementById('auditoria-total-usuarios');
    const eventosFiltrados = document.getElementById('auditoria-eventos-filtrados');

    const state = {
        auditoria: [],
        usuarios: [],
        usuariosById: new Map(),
        filtros: {
            fechaDesde: '',
            fechaHasta: '',
            usuarioId: '',
            modulo: '',
            accion: '',
            buscar: '',
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

    function todayIso() {
        const now = new Date();
        return [now.getFullYear(), pad2(now.getMonth() + 1), pad2(now.getDate())].join('-');
    }

    function formatMoney(value) {
        const amount = Number(value || 0);
        return 'RD$ ' + amount.toLocaleString('es-DO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function formatDateTime(value) {
        const text = String(value || '').trim();
        if (text === '') {
            return '-';
        }

        const match = text.match(/^(\d{4}-\d{2}-\d{2})(?:[ T](\d{2}:\d{2})(?::\d{2})?)?/);
        if (!match) {
            return text;
        }

        const fecha = match[1].slice(8, 10) + '/' + match[1].slice(5, 7) + '/' + match[1].slice(0, 4);
        return match[2] ? (fecha + ' ' + match[2]) : fecha;
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

    function isoDateFromRow(row) {
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

    function userLabelById(userId) {
        const id = Number(userId || 0);
        if (id <= 0) {
            return 'Sistema';
        }

        const user = state.usuariosById.get(id) || null;
        if (!user) {
            return 'Usuario #' + id;
        }

        return String(user.nombre_completo || user.correo || ('Usuario #' + id));
    }

    function auditDateLabel(row) {
        const dt = isoDateFromRow(row);
        if (!(dt instanceof Date)) {
            return '-';
        }

        const fecha = [dt.getFullYear(), pad2(dt.getMonth() + 1), pad2(dt.getDate())].join('-');
        const hora = (() => {
            const text = String(row.fecha || row.created_at || row.fecha_creacion || row.fecha_registro || row.timestamp || row.date_created || '');
            const match = text.match(/(\d{2}:\d{2})(?::\d{2})?/);
            return match ? match[1] : '';
        })();

        return hora !== '' ? (formatDateTime(fecha + ' ' + hora)) : formatDateTime(fecha);
    }

    function normalizeText(value) {
        return String(value || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }

    function getFilteredRows() {
        const filtros = state.filtros;
        const texto = normalizeText(filtros.buscar);
        const fechaDesde = filtros.fechaDesde !== '' ? parseDateValue(filtros.fechaDesde) : null;
        const fechaHasta = filtros.fechaHasta !== '' ? parseDateValue(filtros.fechaHasta) : null;

        return state.auditoria.filter((row) => {
            const rowDate = isoDateFromRow(row);
            if (fechaDesde instanceof Date && rowDate instanceof Date && rowDate.getTime() < fechaDesde.getTime()) {
                return false;
            }
            if (fechaHasta instanceof Date && rowDate instanceof Date && rowDate.getTime() > fechaHasta.getTime()) {
                return false;
            }

            if (filtros.usuarioId !== '' && String(Number(row.usuario_id || 0)) !== filtros.usuarioId) {
                return false;
            }

            if (filtros.modulo !== '' && String(row.modulo || '') !== filtros.modulo) {
                return false;
            }

            if (filtros.accion !== '' && String(row.accion || '') !== filtros.accion) {
                return false;
            }

            if (texto !== '') {
                const hayCoincidencia = [
                    userLabelById(row.usuario_id),
                    row.modulo,
                    row.accion,
                    row.tabla,
                    row.descripcion,
                    row.ip,
                    row.registro_id,
                ].some((value) => normalizeText(value).includes(texto));

                if (!hayCoincidencia) {
                    return false;
                }
            }

            return true;
        });
    }

    function uniqueSorted(values) {
        return Array.from(new Set(values.filter((value) => String(value || '').trim() !== '')))
            .sort((a, b) => String(a).localeCompare(String(b)));
    }

    function renderUserSelect() {
        const rows = state.usuarios.slice().sort((a, b) => String(a.nombre_completo || '').localeCompare(String(b.nombre_completo || '')));
        let html = '<option value="">Todos los usuarios</option>';
        rows.forEach((user) => {
            const id = Number(user.id || 0);
            if (id <= 0) {
                return;
            }
            html += '<option value="' + id + '">' + escHtml(String(user.nombre_completo || user.correo || ('Usuario #' + id))) + '</option>';
        });
        usuarioInput.innerHTML = html;
    }

    function renderFilterSelects() {
        const modulos = uniqueSorted(state.auditoria.map((row) => String(row.modulo || '').trim()));
        const acciones = uniqueSorted(state.auditoria.map((row) => String(row.accion || '').trim()));

        let moduloHtml = '<option value="">Todos los modulos</option>';
        modulos.forEach((modulo) => {
            moduloHtml += '<option value="' + escHtml(modulo) + '">' + escHtml(modulo) + '</option>';
        });
        moduloInput.innerHTML = moduloHtml;

        let accionHtml = '<option value="">Todas</option>';
        acciones.forEach((accion) => {
            accionHtml += '<option value="' + escHtml(accion) + '">' + escHtml(accion) + '</option>';
        });
        accionInput.innerHTML = accionHtml;
    }

    function renderSummary(filteredRows) {
        totalEventos.textContent = String(state.auditoria.length);
        eventosFiltrados.textContent = String(filteredRows.length);

        const hoy = todayIso();
        const hoyCount = state.auditoria.filter((row) => {
            const dt = isoDateFromRow(row);
            if (!(dt instanceof Date)) {
                return false;
            }
            const iso = [dt.getFullYear(), pad2(dt.getMonth() + 1), pad2(dt.getDate())].join('-');
            return iso === hoy;
        }).length;

        eventosHoy.textContent = String(hoyCount);
        totalModulos.textContent = String(uniqueSorted(state.auditoria.map((row) => String(row.modulo || '').trim())).length);
        totalUsuarios.textContent = String(new Set(state.auditoria.map((row) => Number(row.usuario_id || 0)).filter((id) => id > 0)).size);
        badgeRegistros.textContent = filteredRows.length + ' registros';
    }

    function renderTable() {
        const filteredRows = getFilteredRows();
        renderSummary(filteredRows);

        if (filteredRows.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No hay eventos para mostrar.</td></tr>';
            return;
        }

        let html = '';
        filteredRows.forEach((row) => {
            const detalle = String(row.descripcion || '').trim();
            const visibleDetalle = detalle !== '' ? detalle : '-';
            const fechaLabel = auditDateLabel(row);
            const usuarioLabel = userLabelById(row.usuario_id);

            html += '<tr>'
                + '<td>' + escHtml(fechaLabel) + '</td>'
                + '<td>' + escHtml(usuarioLabel) + '</td>'
                + '<td>' + escHtml(String(row.modulo || '-')) + '</td>'
                + '<td>' + escHtml(String(row.accion || '-')) + '</td>'
                + '<td>' + escHtml(String(row.tabla || '-')) + '</td>'
                + '<td>' + escHtml(visibleDetalle) + '</td>'
                + '</tr>';
        });

        tbody.innerHTML = html;
    }

    function syncFiltersFromUi() {
        state.filtros.fechaDesde = String(fechaDesdeInput.value || '').trim();
        state.filtros.fechaHasta = String(fechaHastaInput.value || '').trim();
        state.filtros.usuarioId = String(usuarioInput.value || '').trim();
        state.filtros.modulo = String(moduloInput.value || '').trim();
        state.filtros.accion = String(accionInput.value || '').trim();
        state.filtros.buscar = String(buscarInput.value || '').trim();
    }

    function clearFilters() {
        fechaDesdeInput.value = '';
        fechaHastaInput.value = '';
        usuarioInput.value = '';
        moduloInput.value = '';
        accionInput.value = '';
        buscarInput.value = '';
        syncFiltersFromUi();
        renderTable();
    }

    function setStatus(message, type) {
        const map = {
            info: 'alert alert-info py-2',
            success: 'alert alert-success py-2',
            warning: 'alert alert-warning py-2',
            danger: 'alert alert-danger py-2',
        };

        alerta.className = map[type] || map.info;
        alerta.textContent = message;
    }

    async function loadData() {
        setStatus('Cargando eventos de auditoria...', 'info');
        try {
            const [auditoria, usuarios] = await Promise.all([
                apiRequest('auditoria', 'index', { method: 'GET', query: { limit: 5000, offset: 0 } }),
                apiRequest('usuarios', 'index', { method: 'GET', query: { limit: 3000, offset: 0 } }),
            ]);

            state.auditoria = Array.isArray(auditoria) ? auditoria : [];
            state.usuarios = Array.isArray(usuarios) ? usuarios : [];
            state.usuariosById = new Map(state.usuarios.map((user) => [Number(user.id || 0), user]).filter((entry) => entry[0] > 0));

            renderUserSelect();
            renderFilterSelects();
            syncFiltersFromUi();
            renderTable();
            setStatus('Auditoria cargada correctamente.', 'success');
        } catch (error) {
            state.auditoria = [];
            renderTable();
            setStatus(error instanceof Error ? error.message : 'No se pudo cargar la auditoria.', 'danger');
        }
    }

    btnAplicar.addEventListener('click', () => {
        syncFiltersFromUi();
        renderTable();
    });

    btnLimpiar.addEventListener('click', clearFilters);
    btnRecargar.addEventListener('click', loadData);

    [fechaDesdeInput, fechaHastaInput, usuarioInput, moduloInput, accionInput].forEach((control) => {
        control.addEventListener('change', () => {
            syncFiltersFromUi();
            renderTable();
        });
    });

    buscarInput.addEventListener('input', () => {
        syncFiltersFromUi();
        renderTable();
    });

    loadData();
})();
</script>
