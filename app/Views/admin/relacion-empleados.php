<?php

declare(strict_types=1);
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Relacion de empleados</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-md-3">
                <label for="filtro-empleado-estado">Estado</label>
                <select id="filtro-empleado-estado" class="form-control">
                    <option value="">Todos</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="filtro-empleado-departamento">Departamento</label>
                <select id="filtro-empleado-departamento" class="form-control">
                    <option value="">Todos</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="filtro-empleado-cargo">Cargo</label>
                <select id="filtro-empleado-cargo" class="form-control">
                    <option value="">Todos</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="filtro-empleado-nombre">Nombre o cedula</label>
                <input type="text" id="filtro-empleado-nombre" class="form-control" placeholder="Buscar...">
            </div>
        </div>
    </div>
</div>

<div id="estado-relacion-empleados" class="alert alert-info mb-3">Cargando empleados...</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="tabla-relacion-empleados" width="100%" cellspacing="0">
                <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Empleado</th>
                    <th>Cedula/Pasaporte</th>
                    <th>Departamento</th>
                    <th>Cargo</th>
                    <th>Condicion laboral</th>
                    <th>Telefono</th>
                    <th>Estado</th>
                    <th>Accion</th>
                </tr>
                </thead>
                <tbody id="tbody-relacion-empleados">
                <tr>
                    <td colspan="9" class="text-center text-muted">Cargando...</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-ver-empleado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle del empleado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detalle-empleado-content"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-editar-empleado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <form id="form-editar-empleado">
                <div class="modal-header">
                    <h5 class="modal-title">Editar empleado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editar-empleado-id">
                    <input type="hidden" id="editar-asignacion-id">
                    <input type="hidden" id="editar-datos-laborales-id">

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="editar-empleado-telefono">Telefono</label>
                            <input type="text" id="editar-empleado-telefono" class="form-control" maxlength="30">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="editar-empleado-celular">Celular</label>
                            <input type="text" id="editar-empleado-celular" class="form-control" maxlength="30">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="editar-empleado-whatsapp">WhatsApp</label>
                            <input type="text" id="editar-empleado-whatsapp" class="form-control" maxlength="30">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="editar-empleado-departamento">Departamento</label>
                            <select id="editar-empleado-departamento" class="form-control" required>
                                <option value="">Selecciona</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="editar-empleado-cargo">Cargo</label>
                            <select id="editar-empleado-cargo" class="form-control" required>
                                <option value="">Selecciona</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="editar-empleado-condicion">Condicion laboral</label>
                            <select id="editar-empleado-condicion" class="form-control" required>
                                <option value="">Selecciona</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="editar-empleado-estado">Estado en el centro</label>
                            <select id="editar-empleado-estado" class="form-control">
                                <option value="1">Activo</option>
                                <option value="0">Desactivo</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="editar-empleado-fecha-ingreso">Fecha de ingreso</label>
                            <input type="date" id="editar-empleado-fecha-ingreso" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="editar-empleado-tanda">Tanda</label>
                            <select id="editar-empleado-tanda" class="form-control">
                                <option value="">Selecciona</option>
                                <option value="MATUTINA">Matutina</option>
                                <option value="VESPERTINA">Vespertina</option>
                                <option value="MATUTINA_VESPERTINA">Matutina/Vespertina</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="editar-empleado-salario">Salario</label>
                            <input type="number" id="editar-empleado-salario" class="form-control" min="0" step="0.01">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="editar-empleado-banco">Banco</label>
                            <input type="text" id="editar-empleado-banco" class="form-control" maxlength="150">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="editar-empleado-cuenta">Numero de cuenta</label>
                            <input type="text" id="editar-empleado-cuenta" class="form-control" maxlength="100">
                        </div>
                        <div class="form-group col-md-6">
                            <div class="custom-control custom-checkbox mt-4">
                                <input type="checkbox" class="custom-control-input" id="editar-empleado-activo">
                                <label class="custom-control-label" for="editar-empleado-activo">Empleado activo</label>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="custom-control custom-checkbox mt-4">
                                <input type="checkbox" class="custom-control-input" id="editar-empleado-acepta-terminos">
                                <label class="custom-control-label" for="editar-empleado-acepta-terminos">Acepta terminos y condiciones</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btn-guardar-edicion-empleado">
                        <i class="fas fa-save mr-1"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    const apiBase = '../';

    const estadoPanel = document.getElementById('estado-relacion-empleados');
    const tbody = document.getElementById('tbody-relacion-empleados');

    const filtroEstado = document.getElementById('filtro-empleado-estado');
    const filtroDepartamento = document.getElementById('filtro-empleado-departamento');
    const filtroCargo = document.getElementById('filtro-empleado-cargo');
    const filtroNombre = document.getElementById('filtro-empleado-nombre');

    const modalVer = document.getElementById('modal-ver-empleado');
    const detalleContent = document.getElementById('detalle-empleado-content');

    const modalEditar = document.getElementById('modal-editar-empleado');
    const formEditar = document.getElementById('form-editar-empleado');
    const btnGuardarEdicion = document.getElementById('btn-guardar-edicion-empleado');

    const editarEmpleadoId = document.getElementById('editar-empleado-id');
    const editarAsignacionId = document.getElementById('editar-asignacion-id');
    const editarDatosLaboralesId = document.getElementById('editar-datos-laborales-id');
    const editarTelefono = document.getElementById('editar-empleado-telefono');
    const editarCelular = document.getElementById('editar-empleado-celular');
    const editarWhatsapp = document.getElementById('editar-empleado-whatsapp');
    const editarDepartamento = document.getElementById('editar-empleado-departamento');
    const editarCargo = document.getElementById('editar-empleado-cargo');
    const editarCondicion = document.getElementById('editar-empleado-condicion');
    const editarEstado = document.getElementById('editar-empleado-estado');
    const editarFechaIngreso = document.getElementById('editar-empleado-fecha-ingreso');
    const editarTanda = document.getElementById('editar-empleado-tanda');
    const editarSalario = document.getElementById('editar-empleado-salario');
    const editarBanco = document.getElementById('editar-empleado-banco');
    const editarCuenta = document.getElementById('editar-empleado-cuenta');
    const editarActivo = document.getElementById('editar-empleado-activo');
    const editarAceptaTerminos = document.getElementById('editar-empleado-acepta-terminos');

    const state = {
        personal: [],
        anios: [],
        departamentos: new Map(),
        cargos: new Map(),
        condiciones: new Map(),
        asignacionesByPersonal: new Map(),
        datosLaboralesByPersonal: new Map(),
        direccionesByPersonal: new Map(),
        anioVigenteId: 0,
    };

    function escHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function normalizeText(value) {
        return String(value || '')
            .toUpperCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .trim();
    }

    function toPositiveInt(value) {
        const num = Number(value || 0);
        return Number.isInteger(num) && num > 0 ? num : 0;
    }

    function toNullableString(value) {
        const text = String(value || '').trim();
        return text === '' ? null : text;
    }

    function setStatus(message, type) {
        const classes = {
            info: 'alert alert-info mb-3',
            success: 'alert alert-success mb-3',
            warning: 'alert alert-warning mb-3',
            danger: 'alert alert-danger mb-3',
        };

        estadoPanel.className = classes[type] || classes.info;
        estadoPanel.textContent = message;
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

    async function apiUpdate(resource, criteria, data) {
        const response = await fetch(apiBase + '?resource=' + resource + '&action=update', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ criteria: criteria, data: data }),
        });

        const json = await response.json();
        if (!json.success) {
            throw new Error(json.message || ('No se pudo actualizar ' + resource));
        }

        return json.data || {};
    }

    async function apiStore(resource, data) {
        const response = await fetch(apiBase + '?resource=' + resource + '&action=store', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ data: data }),
        });

        const json = await response.json();
        if (!json.success) {
            throw new Error(json.message || ('No se pudo crear en ' + resource));
        }

        return json.data || {};
    }

    function nombreCompleto(persona) {
        const parts = [
            persona.primer_nombre,
            persona.segundo_nombre,
            persona.primer_apellido,
            persona.segundo_apellido,
        ].map((part) => String(part || '').trim()).filter(Boolean);

        return parts.length > 0 ? parts.join(' ') : '-';
    }

    function currentYearIso() {
        const now = new Date();
        const y = now.getFullYear();
        const m = String(now.getMonth() + 1).padStart(2, '0');
        const d = String(now.getDate()).padStart(2, '0');
        return y + '-' + m + '-' + d;
    }

    function resolveAnioVigenteId(anios) {
        const today = currentYearIso();
        const vigente = anios.find((a) => {
            const start = String(a.fecha_inicio || '').trim();
            const end = String(a.fecha_fin || '').trim();
            return start !== '' && end !== '' && start <= today && end >= today;
        });

        if (vigente) {
            return Number(vigente.id || 0);
        }

        return anios.length > 0 ? Number(anios[0].id || 0) : 0;
    }

    function fillFilterCatalogs() {
        const depOptions = ['<option value="">Todos</option>'];
        state.departamentos.forEach((item, id) => {
            if (Number(item.estado ?? 1) !== 1) {
                return;
            }

            depOptions.push('<option value="' + id + '">' + escHtml(String(item.nombre || '')) + '</option>');
        });
        filtroDepartamento.innerHTML = depOptions.join('');

        const cargoOptions = ['<option value="">Todos</option>'];
        state.cargos.forEach((item, id) => {
            if (Number(item.estado ?? 1) !== 1) {
                return;
            }

            cargoOptions.push('<option value="' + id + '">' + escHtml(String(item.nombre || '')) + '</option>');
        });
        filtroCargo.innerHTML = cargoOptions.join('');

        const editorDepOptions = ['<option value="">Selecciona</option>'];
        state.departamentos.forEach((item, id) => {
            if (Number(item.estado ?? 1) !== 1) {
                return;
            }
            editorDepOptions.push('<option value="' + id + '">' + escHtml(String(item.nombre || '')) + '</option>');
        });
        editarDepartamento.innerHTML = editorDepOptions.join('');

        const editorCargoOptions = ['<option value="">Selecciona</option>'];
        state.cargos.forEach((item, id) => {
            if (Number(item.estado ?? 1) !== 1) {
                return;
            }
            editorCargoOptions.push('<option value="' + id + '">' + escHtml(String(item.nombre || '')) + '</option>');
        });
        editarCargo.innerHTML = editorCargoOptions.join('');

        const editorCondOptions = ['<option value="">Selecciona</option>'];
        state.condiciones.forEach((item, id) => {
            if (Number(item.estado ?? 1) !== 1) {
                return;
            }
            editorCondOptions.push('<option value="' + id + '">' + escHtml(String(item.nombre || '')) + '</option>');
        });
        editarCondicion.innerHTML = editorCondOptions.join('');
    }

    function buildAsignacionesByPersonal(asignaciones) {
        const map = new Map();

        asignaciones.forEach((item) => {
            const personalId = Number(item.personal_id || 0);
            if (personalId <= 0) {
                return;
            }

            if (!map.has(personalId)) {
                map.set(personalId, []);
            }

            map.get(personalId).push(item);
        });

        return map;
    }

    function selectAsignacionActual(personalId) {
        const list = state.asignacionesByPersonal.get(personalId) || [];
        if (list.length === 0) {
            return null;
        }

        const vigente = list.find((item) => Number(item.anio_escolar_id || 0) === state.anioVigenteId);
        if (vigente) {
            return vigente;
        }

        return list.slice().sort((a, b) => Number(b.id || 0) - Number(a.id || 0))[0] || null;
    }

    function normalizeRows() {
        return state.personal.map((persona) => {
            const personalId = Number(persona.id || 0);
            const asignacion = selectAsignacionActual(personalId);
            const datosLaborales = state.datosLaboralesByPersonal.get(personalId) || null;

            const departamento = asignacion ? state.departamentos.get(Number(asignacion.departamento_id || 0)) : null;
            const cargo = asignacion ? state.cargos.get(Number(asignacion.cargo_id || 0)) : null;
            const condicion = asignacion ? state.condiciones.get(Number(asignacion.condicion_laboral_id || 0)) : null;

            return {
                personalId: personalId,
                nombre: nombreCompleto(persona),
                nombreBusqueda: normalizeText(nombreCompleto(persona) + ' ' + String(persona.cedula_pasaporte || '')),
                cedula: String(persona.cedula_pasaporte || '-').trim() || '-',
                telefono: String(persona.telefono || persona.celular || '-').trim() || '-',
                departamentoId: departamento ? Number(departamento.id || 0) : 0,
                cargoId: cargo ? Number(cargo.id || 0) : 0,
                departamento: departamento ? String(departamento.nombre || '-') : '-',
                cargo: cargo ? String(cargo.nombre || '-') : '-',
                condicion: condicion ? String(condicion.nombre || '-') : '-',
                estado: Number(persona.estado ?? 1) === 1,
                personaRaw: persona,
                asignacionRaw: asignacion,
                datosLaboralesRaw: datosLaborales,
                direccionRaw: state.direccionesByPersonal.get(personalId) || null,
            };
        });
    }

    function getFilteredRows() {
        const estado = String(filtroEstado.value || '').trim();
        const departamentoId = Number(filtroDepartamento.value || 0);
        const cargoId = Number(filtroCargo.value || 0);
        const term = normalizeText(filtroNombre.value || '');

        return normalizeRows().filter((row) => {
            const estadoOk = estado === '' || (estado === '1' ? row.estado : !row.estado);
            const departamentoOk = departamentoId <= 0 || row.departamentoId === departamentoId;
            const cargoOk = cargoId <= 0 || row.cargoId === cargoId;
            const textOk = term === '' || row.nombreBusqueda.includes(term);
            return estadoOk && departamentoOk && cargoOk && textOk;
        });
    }

    function renderRows() {
        const rows = getFilteredRows();
        if (rows.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted">No hay empleados para los filtros aplicados.</td></tr>';
            setStatus('No se encontraron empleados para el filtro actual.', 'warning');
            return;
        }

        tbody.innerHTML = rows.map((row) => {
            const estadoBadge = row.estado
                ? '<span class="badge badge-success">Activo</span>'
                : '<span class="badge badge-secondary">Inactivo</span>';
            const estadoBtn = row.estado
                ? '<button class="btn btn-sm btn-outline-danger btn-accion" data-accion="toggle" data-id="' + row.personalId + '">Desactivar</button>'
                : '<button class="btn btn-sm btn-outline-success btn-accion" data-accion="toggle" data-id="' + row.personalId + '">Activar</button>';

            return ''
                + '<tr>'
                + '<td>' + escHtml(String(row.personalId)) + '</td>'
                + '<td>' + escHtml(row.nombre) + '</td>'
                + '<td>' + escHtml(row.cedula) + '</td>'
                + '<td>' + escHtml(row.departamento) + '</td>'
                + '<td>' + escHtml(row.cargo) + '</td>'
                + '<td>' + escHtml(row.condicion) + '</td>'
                + '<td>' + escHtml(row.telefono) + '</td>'
                + '<td>' + estadoBadge + '</td>'
                + '<td>'
                + '<div class="d-flex flex-wrap" style="gap:6px;">'
                + '<button class="btn btn-sm btn-info btn-accion" data-accion="ver" data-id="' + row.personalId + '">Ver</button>'
                + '<button class="btn btn-sm btn-warning btn-accion" data-accion="editar" data-id="' + row.personalId + '">Editar</button>'
                + estadoBtn
                + '</div>'
                + '</td>'
                + '</tr>';
        }).join('');

        setStatus('Mostrando ' + rows.length + ' empleado(s).', 'success');
    }

    function getRowByPersonalId(personalId) {
        return normalizeRows().find((row) => row.personalId === personalId) || null;
    }

    function fieldRow(label, value) {
        const safe = value === null || value === undefined || String(value).trim() === '' ? '-' : String(value);
        return '<tr><th style="width:35%;">' + escHtml(label) + '</th><td>' + escHtml(safe) + '</td></tr>';
    }

    function openVerModal(row) {
        const persona = row.personaRaw || {};
        const asignacion = row.asignacionRaw || {};
        const datosLab = row.datosLaboralesRaw || {};
        const direccion = row.direccionRaw || {};

        const dep = state.departamentos.get(Number(asignacion.departamento_id || 0)) || null;
        const cargo = state.cargos.get(Number(asignacion.cargo_id || 0)) || null;
        const condicion = state.condiciones.get(Number(asignacion.condicion_laboral_id || 0)) || null;

        const html = ''
            + '<div class="card shadow-sm mb-3"><div class="card-header py-2"><h6 class="m-0 font-weight-bold text-primary">Datos personales</h6></div><div class="card-body p-0">'
            + '<table class="table table-sm table-bordered mb-0">'
            + fieldRow('Nombre', row.nombre)
            + fieldRow('Cedula/Pasaporte', row.cedula)
            + fieldRow('Fecha nacimiento', persona.fecha_nacimiento || '-')
            + fieldRow('Sexo', persona.sexo || '-')
            + fieldRow('Estado civil', persona.estado_civil || '-')
            + fieldRow('Nacionalidad', persona.nacionalidad || '-')
            + fieldRow('Estado en centro', row.estado ? 'Activo' : 'Inactivo')
            + '</table></div></div>'
            + '<div class="card shadow-sm mb-3"><div class="card-header py-2"><h6 class="m-0 font-weight-bold text-primary">Asignacion laboral</h6></div><div class="card-body p-0">'
            + '<table class="table table-sm table-bordered mb-0">'
            + fieldRow('Departamento', dep ? dep.nombre : '-')
            + fieldRow('Cargo', cargo ? cargo.nombre : '-')
            + fieldRow('Condicion laboral', condicion ? condicion.nombre : '-')
            + '</table></div></div>'
            + '<div class="card shadow-sm mb-3"><div class="card-header py-2"><h6 class="m-0 font-weight-bold text-primary">Contacto y laboral</h6></div><div class="card-body p-0">'
            + '<table class="table table-sm table-bordered mb-0">'
            + fieldRow('Telefono', persona.telefono || '-')
            + fieldRow('Celular', persona.celular || '-')
            + fieldRow('WhatsApp', persona.whatsapp || '-')
            + fieldRow('Fecha ingreso', datosLab.fecha_ingreso || '-')
            + fieldRow('Tanda', datosLab.tanda || '-')
            + fieldRow('Salario', datosLab.salario !== undefined && datosLab.salario !== null ? String(datosLab.salario) : '-')
            + fieldRow('Banco', datosLab.banco || '-')
            + fieldRow('Cuenta bancaria', datosLab.numero_cuenta_bancaria || '-')
            + fieldRow('Acepta terminos', Number(datosLab.acepta_terminos || 0) === 1 ? 'Si' : 'No')
            + fieldRow('Empleado activo', Number(datosLab.empleado_activo || 0) === 1 ? 'Si' : 'No')
            + '</table></div></div>'
            + '<div class="card shadow-sm"><div class="card-header py-2"><h6 class="m-0 font-weight-bold text-primary">Direccion</h6></div><div class="card-body p-0">'
            + '<table class="table table-sm table-bordered mb-0">'
            + fieldRow('Provincia', direccion.provincia || '-')
            + fieldRow('Municipio', direccion.municipio || '-')
            + fieldRow('Distrito municipal', direccion.distrito_municipal || '-')
            + fieldRow('Seccion', direccion.seccion || '-')
            + fieldRow('Barrio', direccion.barrio || '-')
            + fieldRow('Sub barrio', direccion.sub_barrio || '-')
            + fieldRow('Calle y numero', direccion.calle_numero || '-')
            + '</table></div></div>';

        detalleContent.innerHTML = html;
        if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.modal === 'function') {
            window.jQuery(modalVer).modal('show');
        }
    }

    function openEditarModal(row) {
        const persona = row.personaRaw || {};
        const asignacion = row.asignacionRaw || null;
        const datosLab = row.datosLaboralesRaw || null;

        editarEmpleadoId.value = String(row.personalId);
        editarAsignacionId.value = asignacion ? String(asignacion.id || '') : '';
        editarDatosLaboralesId.value = datosLab ? String(datosLab.id || '') : '';

        editarTelefono.value = String(persona.telefono || '');
        editarCelular.value = String(persona.celular || '');
        editarWhatsapp.value = String(persona.whatsapp || '');
        editarDepartamento.value = asignacion ? String(asignacion.departamento_id || '') : '';
        editarCargo.value = asignacion ? String(asignacion.cargo_id || '') : '';
        editarCondicion.value = asignacion ? String(asignacion.condicion_laboral_id || '') : '';
        editarEstado.value = row.estado ? '1' : '0';

        editarFechaIngreso.value = String((datosLab && datosLab.fecha_ingreso) || '');
        editarTanda.value = String((datosLab && datosLab.tanda) || '');
        editarSalario.value = String((datosLab && datosLab.salario !== null && datosLab.salario !== undefined) ? datosLab.salario : 0);
        editarBanco.value = String((datosLab && datosLab.banco) || '');
        editarCuenta.value = String((datosLab && datosLab.numero_cuenta_bancaria) || '');
        editarActivo.checked = Number((datosLab && datosLab.empleado_activo) || 0) === 1;
        editarAceptaTerminos.checked = Number((datosLab && datosLab.acepta_terminos) || 0) === 1;

        if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.modal === 'function') {
            window.jQuery(modalEditar).modal('show');
        }
    }

    async function toggleEstado(row) {
        const nextState = row.estado ? 0 : 1;
        const label = nextState === 1 ? 'activar' : 'desactivar';
        const ok = window.confirm('Deseas ' + label + ' a ' + row.nombre + '?');
        if (!ok) {
            return;
        }

        await apiUpdate('personal', { id: row.personalId }, { estado: nextState });

        const datosLab = row.datosLaboralesRaw || null;
        if (datosLab && Number(datosLab.id || 0) > 0) {
            await apiUpdate('datos_laborales', { id: Number(datosLab.id) }, { empleado_activo: nextState });
        }

        const asignacion = row.asignacionRaw || null;
        if (asignacion && Number(asignacion.id || 0) > 0) {
            await apiUpdate('asignaciones_laborales', { id: Number(asignacion.id) }, { estado: nextState });
        }

        await loadData();
        setStatus('Estado actualizado correctamente.', 'success');
    }

    async function saveEdit(event) {
        event.preventDefault();

        const personalId = toPositiveInt(editarEmpleadoId.value);
        if (personalId <= 0) {
            window.alert('Empleado no valido.');
            return;
        }

        const depId = toPositiveInt(editarDepartamento.value);
        const cargoId = toPositiveInt(editarCargo.value);
        const condicionId = toPositiveInt(editarCondicion.value);
        if (depId <= 0 || cargoId <= 0 || condicionId <= 0) {
            window.alert('Selecciona departamento, cargo y condicion laboral.');
            return;
        }

        const salario = Number(editarSalario.value || 0);
        if (!Number.isFinite(salario) || salario < 0) {
            window.alert('Salario no valido.');
            return;
        }

        const original = btnGuardarEdicion.innerHTML;
        btnGuardarEdicion.disabled = true;
        btnGuardarEdicion.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...';

        try {
            const estadoValue = Number(editarEstado.value || '1') === 1 ? 1 : 0;

            await apiUpdate('personal', { id: personalId }, {
                telefono: toNullableString(editarTelefono.value),
                celular: toNullableString(editarCelular.value),
                whatsapp: toNullableString(editarWhatsapp.value),
                estado: estadoValue,
            });

            const asignacionPayload = {
                personal_id: personalId,
                anio_escolar_id: state.anioVigenteId,
                departamento_id: depId,
                cargo_id: cargoId,
                condicion_laboral_id: condicionId,
                estado: estadoValue,
            };

            const asignacionId = toPositiveInt(editarAsignacionId.value);
            if (asignacionId > 0) {
                await apiUpdate('asignaciones_laborales', { id: asignacionId }, asignacionPayload);
            } else {
                await apiStore('asignaciones_laborales', asignacionPayload);
            }

            const datosPayload = {
                personal_id: personalId,
                fecha_ingreso: toNullableString(editarFechaIngreso.value),
                tanda: toNullableString(editarTanda.value),
                salario: Number(salario.toFixed(2)),
                banco: toNullableString(editarBanco.value),
                numero_cuenta_bancaria: toNullableString(editarCuenta.value),
                acepta_terminos: editarAceptaTerminos.checked ? 1 : 0,
                empleado_activo: editarActivo.checked ? 1 : 0,
            };

            const datosId = toPositiveInt(editarDatosLaboralesId.value);
            if (datosId > 0) {
                await apiUpdate('datos_laborales', { id: datosId }, datosPayload);
            } else {
                await apiStore('datos_laborales', datosPayload);
            }

            if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.modal === 'function') {
                window.jQuery(modalEditar).modal('hide');
            }

            await loadData();
            setStatus('Empleado actualizado correctamente.', 'success');
        } catch (error) {
            setStatus(error.message || 'No se pudo actualizar el empleado.', 'danger');
        } finally {
            btnGuardarEdicion.disabled = false;
            btnGuardarEdicion.innerHTML = original;
        }
    }

    async function loadData() {
        const [
            personal,
            asignaciones,
            anios,
            departamentos,
            cargos,
            condiciones,
            datosLaborales,
            direcciones,
        ] = await Promise.all([
            apiGet('personal'),
            apiGet('asignaciones_laborales'),
            apiGet('anios_escolares'),
            apiGet('departamentos'),
            apiGet('cargos'),
            apiGet('condiciones_laborales'),
            apiGet('datos_laborales'),
            apiGet('direcciones_personal'),
        ]);

        state.personal = personal;
        state.anios = anios;
        state.anioVigenteId = resolveAnioVigenteId(anios);
        state.departamentos = new Map(departamentos.map((row) => [Number(row.id || 0), row]));
        state.cargos = new Map(cargos.map((row) => [Number(row.id || 0), row]));
        state.condiciones = new Map(condiciones.map((row) => [Number(row.id || 0), row]));
        state.asignacionesByPersonal = buildAsignacionesByPersonal(asignaciones);
        state.datosLaboralesByPersonal = new Map(datosLaborales.map((row) => [Number(row.personal_id || 0), row]));
        state.direccionesByPersonal = new Map(direcciones.map((row) => [Number(row.personal_id || 0), row]));

        fillFilterCatalogs();
        renderRows();
    }

    async function init() {
        setStatus('Cargando datos de empleados...', 'info');

        try {
            await loadData();
        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="9" class="text-center text-danger">No se pudo cargar la relacion de empleados.</td></tr>';
            setStatus(error.message || 'Error cargando la relacion de empleados.', 'danger');
        }
    }

    tbody.addEventListener('click', async function (event) {
        const target = event.target instanceof HTMLElement ? event.target.closest('.btn-accion') : null;
        if (!target) {
            return;
        }

        const action = String(target.dataset.accion || '').trim();
        const id = toPositiveInt(target.dataset.id || 0);
        const row = getRowByPersonalId(id);
        if (!row) {
            window.alert('No se encontro el empleado seleccionado.');
            return;
        }

        try {
            if (action === 'ver') {
                openVerModal(row);
                return;
            }

            if (action === 'editar') {
                openEditarModal(row);
                return;
            }

            if (action === 'toggle') {
                await toggleEstado(row);
            }
        } catch (error) {
            setStatus(error.message || 'No se pudo ejecutar la accion.', 'danger');
        }
    });

    formEditar.addEventListener('submit', saveEdit);

    [filtroEstado, filtroDepartamento, filtroCargo].forEach((el) => {
        el.addEventListener('change', renderRows);
    });
    filtroNombre.addEventListener('input', renderRows);

    init();
})();
</script>
