<?php

declare(strict_types=1);
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Configuracion del sistema</h1>
    <button id="btn-recargar-todo" class="btn btn-sm btn-primary shadow-sm" type="button">
        <i class="fas fa-sync-alt fa-sm text-white-50"></i> Recargar todo
    </button>
</div>

<div id="config-alert" class="alert d-none" role="alert"></div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <ul class="nav nav-tabs card-header-tabs" id="config-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" id="tab-roles-link" data-toggle="tab" href="#tab-roles" role="tab" aria-controls="tab-roles" aria-selected="true">Roles</a></li>
            <li class="nav-item"><a class="nav-link" id="tab-tarifas-link" data-toggle="tab" href="#tab-tarifas" role="tab" aria-controls="tab-tarifas" aria-selected="false">Tarifas por grado</a></li>
            <li class="nav-item"><a class="nav-link" id="tab-centro-link" data-toggle="tab" href="#tab-centro" role="tab" aria-controls="tab-centro" aria-selected="false">Datos centro educativo</a></li>
            <li class="nav-item"><a class="nav-link" id="tab-rrhh-link" data-toggle="tab" href="#tab-rrhh" role="tab" aria-controls="tab-rrhh" aria-selected="false">Catalogos RRHH</a></li>
            <li class="nav-item"><a class="nav-link" id="tab-parametros-link" data-toggle="tab" href="#tab-parametros" role="tab" aria-controls="tab-parametros" aria-selected="false">Parametros financieros</a></li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-roles" role="tabpanel" aria-labelledby="tab-roles-link">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0 font-weight-bold text-primary">Gestion de roles</h6>
                    <button class="btn btn-sm btn-success" type="button" onclick="ConfigSistema.startCreate('roles')">Nuevo rol</button>
                </div>
                <div id="form-roles" class="border rounded p-3 mb-3 d-none">
                    <input type="hidden" id="roles-id">
                    <div class="form-row">
                        <div class="form-group col-md-4"><label for="roles-nombre">Nombre</label><input id="roles-nombre" class="form-control" type="text" maxlength="50"></div>
                        <div class="form-group col-md-5"><label for="roles-descripcion">Descripcion</label><input id="roles-descripcion" class="form-control" type="text" maxlength="255"></div>
                        <div class="form-group col-md-3"><label for="roles-estado">Estado</label><select id="roles-estado" class="form-control"><option value="1">Activo</option><option value="0">Inactivo</option></select></div>
                    </div>
                    <button class="btn btn-primary btn-sm" type="button" onclick="ConfigSistema.save('roles')">Guardar</button>
                    <button class="btn btn-secondary btn-sm" type="button" onclick="ConfigSistema.cancel('roles')">Cancelar</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light"><tr><th>ID</th><th>Nombre</th><th>Descripcion</th><th>Estado</th><th>Acciones</th></tr></thead>
                        <tbody id="tbody-roles"><tr><td colspan="5" class="text-center text-muted">Cargando...</td></tr></tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-tarifas" role="tabpanel" aria-labelledby="tab-tarifas-link">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0 font-weight-bold text-primary">Gestion de tarifas por grado</h6>
                    <button class="btn btn-sm btn-success" type="button" onclick="ConfigSistema.startCreate('tarifas-grados')">Nueva tarifa</button>
                </div>
                <div id="form-tarifas-grados" class="border rounded p-3 mb-3 d-none">
                    <input type="hidden" id="tarifas-grados-id">
                    <input type="hidden" id="tarifas-grados-tarifario-id" value="">
                    <input type="hidden" id="tarifas-grados-nivel-id" value="">
                    <input type="hidden" id="tarifas-grados-grado-id" value="">
                    <input type="hidden" id="tarifas-grados-jornada" value="">
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="tarifas-grados-planif-id">Oferta academica *</label>
                            <select id="tarifas-grados-planif-id" class="form-control">
                                <option value="">Selecciona nivel, grado, seccion y tanda</option>
                            </select>
                            <small id="tarifas-grados-ayuda" class="form-text text-muted">Se carga desde Planeacion academica.</small>
                        </div>
                        <div class="form-group col-md-2"><label for="tarifas-grados-inscripcion">Inscripcion</label><input id="tarifas-grados-inscripcion" class="form-control" type="number" step="0.01" min="0"></div>
                        <div class="form-group col-md-2"><label for="tarifas-grados-mensualidad">Mensualidad</label><input id="tarifas-grados-mensualidad" class="form-control" type="number" step="0.01" min="0"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3"><label>Nivel</label><input id="tarifas-grados-nivel-label" class="form-control" type="text" readonly></div>
                        <div class="form-group col-md-3"><label>Grado</label><input id="tarifas-grados-grado-label" class="form-control" type="text" readonly></div>
                        <div class="form-group col-md-3"><label>Seccion</label><input id="tarifas-grados-seccion-label" class="form-control" type="text" readonly></div>
                        <div class="form-group col-md-3"><label>Tanda</label><input id="tarifas-grados-tanda-label" class="form-control" type="text" readonly></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2"><label for="tarifas-grados-activo">Activo</label><select id="tarifas-grados-activo" class="form-control"><option value="1">Si</option><option value="0">No</option></select></div>
                    </div>
                    <button class="btn btn-primary btn-sm" type="button" onclick="ConfigSistema.save('tarifas-grados')">Guardar</button>
                    <button class="btn btn-secondary btn-sm" type="button" onclick="ConfigSistema.cancel('tarifas-grados')">Cancelar</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light"><tr><th>ID</th><th>Nivel</th><th>Grado</th><th>Seccion</th><th>Tanda</th><th>Jornada</th><th>Inscripcion</th><th>Mensualidad</th><th>Activo</th><th>Acciones</th></tr></thead>
                        <tbody id="tbody-tarifas-grados"><tr><td colspan="10" class="text-center text-muted">Cargando...</td></tr></tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-centro" role="tabpanel" aria-labelledby="tab-centro-link">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos del centro educativo</h6>
                   </div>
                <div id="form-datos-centro" class="border rounded p-3 mb-3 d-none">
                    <input type="hidden" id="datos-centro-id">
                    <div class="form-row">
                        <div class="form-group col-md-6"><label for="datos-centro-nombre">Nombre centro</label><input id="datos-centro-nombre" class="form-control" type="text" maxlength="255"></div>
                        <div class="form-group col-md-3"><label for="datos-centro-codigo">Codigo centro</label><input id="datos-centro-codigo" class="form-control" type="text" maxlength="50"></div>
                        <div class="form-group col-md-3"><label for="datos-centro-rnc">RNC</label><input id="datos-centro-rnc" class="form-control" type="text" maxlength="30"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3"><label for="datos-centro-telefono">Telefono</label><input id="datos-centro-telefono" class="form-control" type="text" maxlength="30"></div>
                        <div class="form-group col-md-3"><label for="datos-centro-celular">Celular</label><input id="datos-centro-celular" class="form-control" type="text" maxlength="30"></div>
                        <div class="form-group col-md-6"><label for="datos-centro-correo">Correo electronico</label><input id="datos-centro-correo" class="form-control" type="email" maxlength="150"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4"><label for="datos-centro-lema">Lema</label><input id="datos-centro-lema" class="form-control" type="text" maxlength="255"></div>
                        <div class="form-group col-md-5"><label for="datos-centro-direccion">Direccion</label><input id="datos-centro-direccion" class="form-control" type="text" maxlength="255"></div>
                        <div class="form-group col-md-3">
                            <label for="datos-centro-logo-file">Logo</label>
                            <input id="datos-centro-logo-file" class="form-control-file" type="file" accept="image/png,image/jpeg,image/webp,image/gif">
                            <input id="datos-centro-logo" type="hidden" value="">
                            <small id="datos-centro-logo-ruta" class="form-text text-muted">Sin logo cargado</small>
                            <img id="datos-centro-logo-preview" class="img-thumbnail mt-2 d-none" alt="Vista previa del logo" style="max-height:90px;">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2"><label for="datos-centro-estado">Estado</label><select id="datos-centro-estado" class="form-control"><option value="1">Activo</option><option value="0">Inactivo</option></select></div>
                    </div>
                    <button class="btn btn-primary btn-sm" type="button" onclick="ConfigSistema.save('datos-centro')">Guardar</button>
                    <button class="btn btn-secondary btn-sm" type="button" onclick="ConfigSistema.cancel('datos-centro')">Cancelar</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light"><tr><th>ID</th><th>Centro</th><th>Codigo</th><th>Telefono</th><th>Correo</th><th>Estado</th><th>Acciones</th></tr></thead>
                        <tbody id="tbody-datos-centro"><tr><td colspan="7" class="text-center text-muted">Cargando...</td></tr></tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-rrhh" role="tabpanel" aria-labelledby="tab-rrhh-link">
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="m-0 font-weight-bold text-primary">Departamentos</h6>
                            <button class="btn btn-sm btn-success" type="button" onclick="ConfigSistema.startCreate('departamentos')">Nuevo</button>
                        </div>
                        <div id="form-departamentos" class="border rounded p-3 mb-3 d-none">
                            <input type="hidden" id="departamentos-id">
                            <div class="form-group"><label for="departamentos-nombre">Nombre</label><input id="departamentos-nombre" class="form-control" type="text" maxlength="150"></div>
                            <div class="form-group"><label for="departamentos-estado">Estado</label><select id="departamentos-estado" class="form-control"><option value="1">Activo</option><option value="0">Inactivo</option></select></div>
                            <button class="btn btn-primary btn-sm" type="button" onclick="ConfigSistema.save('departamentos')">Guardar</button>
                            <button class="btn btn-secondary btn-sm" type="button" onclick="ConfigSistema.cancel('departamentos')">Cancelar</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="thead-light"><tr><th>ID</th><th>Nombre</th><th>Estado</th><th>Acciones</th></tr></thead>
                                <tbody id="tbody-departamentos"><tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="m-0 font-weight-bold text-primary">Cargos</h6>
                            <button class="btn btn-sm btn-success" type="button" onclick="ConfigSistema.startCreate('cargos')">Nuevo</button>
                        </div>
                        <div id="form-cargos" class="border rounded p-3 mb-3 d-none">
                            <input type="hidden" id="cargos-id">
                            <div class="form-group"><label for="cargos-nombre">Nombre</label><input id="cargos-nombre" class="form-control" type="text" maxlength="150"></div>
                            <div class="form-group"><label for="cargos-descripcion">Descripcion</label><input id="cargos-descripcion" class="form-control" type="text" maxlength="255"></div>
                            <div class="form-group"><label for="cargos-estado">Estado</label><select id="cargos-estado" class="form-control"><option value="1">Activo</option><option value="0">Inactivo</option></select></div>
                            <button class="btn btn-primary btn-sm" type="button" onclick="ConfigSistema.save('cargos')">Guardar</button>
                            <button class="btn btn-secondary btn-sm" type="button" onclick="ConfigSistema.cancel('cargos')">Cancelar</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="thead-light"><tr><th>ID</th><th>Nombre</th><th>Descripcion</th><th>Estado</th><th>Acciones</th></tr></thead>
                                <tbody id="tbody-cargos"><tr><td colspan="5" class="text-center text-muted">Cargando...</td></tr></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="m-0 font-weight-bold text-primary">Condiciones laborales</h6>
                            <button class="btn btn-sm btn-success" type="button" onclick="ConfigSistema.startCreate('condiciones-laborales')">Nueva</button>
                        </div>
                        <div id="form-condiciones-laborales" class="border rounded p-3 mb-3 d-none">
                            <input type="hidden" id="condiciones-laborales-id">
                            <div class="form-group"><label for="condiciones-laborales-nombre">Nombre</label><input id="condiciones-laborales-nombre" class="form-control" type="text" maxlength="100"></div>
                            <div class="form-group"><label for="condiciones-laborales-estado">Estado</label><select id="condiciones-laborales-estado" class="form-control"><option value="1">Activo</option><option value="0">Inactivo</option></select></div>
                            <button class="btn btn-primary btn-sm" type="button" onclick="ConfigSistema.save('condiciones-laborales')">Guardar</button>
                            <button class="btn btn-secondary btn-sm" type="button" onclick="ConfigSistema.cancel('condiciones-laborales')">Cancelar</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="thead-light"><tr><th>ID</th><th>Nombre</th><th>Estado</th><th>Acciones</th></tr></thead>
                                <tbody id="tbody-condiciones-laborales"><tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-parametros" role="tabpanel" aria-labelledby="tab-parametros-link">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0 font-weight-bold text-primary">Parametros financieros</h6>
                    <button class="btn btn-sm btn-success" type="button" onclick="ConfigSistema.startCreate('parametros-financieros')">Nuevo parametro</button>
                </div>
                <div id="form-parametros-financieros" class="border rounded p-3 mb-3 d-none">
                    <input type="hidden" id="parametros-financieros-id">
                    <div class="form-row">
                        <div class="form-group col-md-2"><label for="parametros-financieros-anio">Anio escolar ID</label><input id="parametros-financieros-anio" class="form-control" type="number" min="1"></div>
                        <div class="form-group col-md-2"><label for="parametros-financieros-dia">Dia vencimiento</label><input id="parametros-financieros-dia" class="form-control" type="number" min="1" max="31"></div>
                        <div class="form-group col-md-2"><label for="parametros-financieros-mora">Mora mensual</label><input id="parametros-financieros-mora" class="form-control" type="number" min="0" step="0.01"></div>
                        <div class="form-group col-md-3"><label for="parametros-financieros-pago-agosto">Pago agosto libera junio</label><select id="parametros-financieros-pago-agosto" class="form-control"><option value="1">Si</option><option value="0">No</option></select></div>
                        <div class="form-group col-md-3"><label for="parametros-financieros-estado">Estado</label><select id="parametros-financieros-estado" class="form-control"><option value="1">Activo</option><option value="0">Inactivo</option></select></div>
                    </div>
                    <div class="form-group"><label for="parametros-financieros-regla">Regla especial</label><textarea id="parametros-financieros-regla" class="form-control" rows="3"></textarea></div>
                    <button class="btn btn-primary btn-sm" type="button" onclick="ConfigSistema.save('parametros-financieros')">Guardar</button>
                    <button class="btn btn-secondary btn-sm" type="button" onclick="ConfigSistema.cancel('parametros-financieros')">Cancelar</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light"><tr><th>ID</th><th>Anio escolar</th><th>Vencimiento</th><th>Mora</th><th>Pago agosto</th><th>Estado</th><th>Acciones</th></tr></thead>
                        <tbody id="tbody-parametros-financieros"><tr><td colspan="7" class="text-center text-muted">Cargando...</td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const apiBase = '../';
    const csrfToken = '<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>';

    const state = {
        roles: [],
        tarifasGrados: [],
        datosCentro: [],
        departamentosCatalogo: [],
        cargosCatalogo: [],
        condicionesLaboralesCatalogo: [],
        parametrosFinancieros: [],
        aniosEscolares: [],
        planificaciones: [],
        nivelesMap: new Map(),
        gradosMap: new Map(),
        seccionesMap: new Map(),
        tandasMap: new Map(),
        defaultTarifarioId: 0,
    };

    function showAlert(type, message) {
        const alert = document.getElementById('config-alert');
        alert.className = 'alert alert-' + type;
        alert.textContent = message;
        alert.classList.remove('d-none');
    }

    function clearAlert() {
        const alert = document.getElementById('config-alert');
        alert.className = 'alert d-none';
        alert.textContent = '';
    }

    function asInt(value) {
        const parsed = parseInt(String(value), 10);
        return Number.isNaN(parsed) ? 0 : parsed;
    }

    function asFloat(value) {
        const parsed = parseFloat(String(value));
        return Number.isNaN(parsed) ? 0 : parsed;
    }

    function buildMap(rows, keyField) {
        const map = new Map();
        rows.forEach((row) => {
            const id = Number(row[keyField] || 0);
            if (id > 0) {
                map.set(id, row);
            }
        });

        return map;
    }

    function getPlanifKey(nivelId, gradoId, jornada) {
        return String(nivelId) + '|' + String(gradoId) + '|' + String((jornada || '').toUpperCase());
    }

    function getTandaNombreByPlanif(planif) {
        const tandaId = Number(planif.tanda_id || 0);
        if (tandaId > 0 && state.tandasMap.has(tandaId)) {
            const tanda = state.tandasMap.get(tandaId);
            return String(tanda.nombre || tanda.codigo || '');
        }

        return '';
    }

    function getJornadaByPlanif(planif) {
        const explicit = String(planif.jornada || '').toUpperCase();
        if (explicit !== '') {
            return explicit;
        }

        const tandaId = Number(planif.tanda_id || 0);
        if (tandaId > 0 && state.tandasMap.has(tandaId)) {
            const tanda = state.tandasMap.get(tandaId);
            const codigo = String(tanda.codigo || '').toUpperCase();
            if (codigo === 'MATUTINO' || codigo === 'VESPERTINO') {
                return codigo;
            }
        }

        return 'MATUTINO';
    }

    function getPlanifByTarifa(row) {
        const key = getPlanifKey(row.nivel_id, row.grado_id, row.jornada);
        return state.planificaciones.find((p) => getPlanifKey(p.nivel_id, p.grado_id, getJornadaByPlanif(p)) === key) || null;
    }

    async function apiGet(resource, extraQuery) {
        const query = extraQuery ? '&' + extraQuery : '';
        const response = await fetch(apiBase + '?resource=' + resource + '&action=index&limit=500' + query, {
            method: 'GET',
            headers: { 'Accept': 'application/json' }
        });
        const json = await response.json();
        if (!json.success) {
            throw new Error(json.message || 'Error al cargar ' + resource);
        }
        return Array.isArray(json.data) ? json.data : [];
    }

    async function apiStore(resource, data) {
        const response = await fetch(apiBase + '?resource=' + resource + '&action=store', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ data })
        });
        const json = await response.json();
        if (!json.success) {
            throw new Error(json.message || 'No se pudo guardar.');
        }
    }

    async function apiUpdate(resource, criteria, data) {
        const response = await fetch(apiBase + '?resource=' + resource + '&action=update', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ criteria, data })
        });
        const json = await response.json();
        if (!json.success) {
            throw new Error(json.message || 'No se pudo actualizar.');
        }
    }

    async function apiDestroy(resource, criteria) {
        const response = await fetch(apiBase + '?resource=' + resource + '&action=destroy', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ criteria })
        });
        const json = await response.json();
        if (!json.success) {
            throw new Error(json.message || 'No se pudo eliminar.');
        }
    }

    function resolveLogoUrl(path) {
        const raw = String(path || '').trim();
        if (raw === '') {
            return '';
        }

        if (raw.startsWith('http://') || raw.startsWith('https://') || raw.startsWith('data:') || raw.startsWith('/')) {
            return raw;
        }

        return '../' + raw.replace(/^\.\//, '');
    }

    function syncLogoPreview(path) {
        const preview = document.getElementById('datos-centro-logo-preview');
        const info = document.getElementById('datos-centro-logo-ruta');
        const normalizedPath = String(path || '').trim();

        if (normalizedPath === '') {
            preview.classList.add('d-none');
            preview.removeAttribute('src');
            info.textContent = 'Sin logo cargado';
            return;
        }

        preview.src = resolveLogoUrl(normalizedPath);
        preview.classList.remove('d-none');
        info.textContent = normalizedPath;
    }

    async function uploadLogoIfNeeded() {
        const fileInput = document.getElementById('datos-centro-logo-file');
        const hiddenInput = document.getElementById('datos-centro-logo');
        const file = fileInput.files && fileInput.files.length > 0 ? fileInput.files[0] : null;

        if (!file) {
            return hiddenInput.value.trim() || null;
        }

        const formData = new FormData();
        formData.append('logo', file);
        formData.append('_csrf', csrfToken);

        const currentLogo = hiddenInput.value.trim();
        if (currentLogo !== '') {
            formData.append('current_logo', currentLogo);
        }

        const response = await fetch('upload-logo.php', {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: formData,
        });

        const json = await response.json();
        if (!json.success) {
            throw new Error(json.message || 'No se pudo cargar el logo.');
        }

        const logoPath = String(json.path || '').trim();
        hiddenInput.value = logoPath;
        fileInput.value = '';
        syncLogoPreview(logoPath);

        return logoPath === '' ? null : logoPath;
    }

    function showForm(key) {
        document.getElementById('form-' + key).classList.remove('d-none');
    }

    function hideForm(key) {
        document.getElementById('form-' + key).classList.add('d-none');
    }

    function renderRoles() {
        const tbody = document.getElementById('tbody-roles');
        if (state.roles.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Sin registros</td></tr>';
            return;
        }

        tbody.innerHTML = state.roles.map((row) => '<tr>' +
            '<td>' + row.id + '</td>' +
            '<td>' + (row.nombre || '') + '</td>' +
            '<td>' + (row.descripcion || '') + '</td>' +
            '<td>' + (Number(row.estado) === 1 ? 'Activo' : 'Inactivo') + '</td>' +
            '<td>' +
            '<button class="btn btn-sm btn-info mr-1" onclick="ConfigSistema.startEdit(\'roles\',' + row.id + ')">Editar</button>' +
            '<button class="btn btn-sm btn-danger" onclick="ConfigSistema.remove(\'roles\',' + row.id + ')">Eliminar</button>' +
            '</td>' +
            '</tr>').join('');
    }

    function renderTarifasGrados() {
        const tbody = document.getElementById('tbody-tarifas-grados');
        if (state.tarifasGrados.length === 0) {
            tbody.innerHTML = '<tr><td colspan="10" class="text-center text-muted">Sin registros</td></tr>';
            return;
        }

        tbody.innerHTML = state.tarifasGrados.map((row) => '<tr>' +
            '<td>' + row.id + '</td>' +
            '<td>' + (((state.nivelesMap.get(Number(row.nivel_id || 0)) || {}).nivel) || ('#' + row.nivel_id)) + '</td>' +
            '<td>' + (((state.gradosMap.get(Number(row.grado_id || 0)) || {}).grado) || ('#' + row.grado_id)) + '</td>' +
            '<td>' + (function(){ const p = getPlanifByTarifa(row); return p ? ((((state.seccionesMap.get(Number(p.seccion_id || 0)) || {}).seccion) || ('#' + p.seccion_id)) ) : '-'; })() + '</td>' +
            '<td>' + (function(){ const p = getPlanifByTarifa(row); const t = p ? getTandaNombreByPlanif(p) : ''; return t !== '' ? t : '-'; })() + '</td>' +
            '<td>' + (row.jornada || '') + '</td>' +
            '<td>' + row.tarifa_inscripcion + '</td>' +
            '<td>' + row.mensualidad + '</td>' +
            '<td>' + (Number(row.activo) === 1 ? 'Si' : 'No') + '</td>' +
            '<td>' +
            '<button class="btn btn-sm btn-info mr-1" onclick="ConfigSistema.startEdit(\'tarifas-grados\',' + row.id + ')">Editar</button>' +
            '<button class="btn btn-sm btn-danger" onclick="ConfigSistema.remove(\'tarifas-grados\',' + row.id + ')">Eliminar</button>' +
            '</td>' +
            '</tr>').join('');
    }

    function clearOfertaTarifaFields() {
        document.getElementById('tarifas-grados-planif-id').value = '';
        document.getElementById('tarifas-grados-nivel-id').value = '';
        document.getElementById('tarifas-grados-grado-id').value = '';
        document.getElementById('tarifas-grados-jornada').value = '';
        document.getElementById('tarifas-grados-nivel-label').value = '';
        document.getElementById('tarifas-grados-grado-label').value = '';
        document.getElementById('tarifas-grados-seccion-label').value = '';
        document.getElementById('tarifas-grados-tanda-label').value = '';
    }

    function applyPlanifToTarifaForm(planif) {
        if (!planif) {
            clearOfertaTarifaFields();
            return;
        }

        const nivelId = Number(planif.nivel_id || 0);
        const gradoId = Number(planif.grado_id || 0);
        const seccionId = Number(planif.seccion_id || 0);
        const jornada = getJornadaByPlanif(planif);
        const tanda = getTandaNombreByPlanif(planif);

        document.getElementById('tarifas-grados-nivel-id').value = String(nivelId);
        document.getElementById('tarifas-grados-grado-id').value = String(gradoId);
        document.getElementById('tarifas-grados-jornada').value = jornada;

        document.getElementById('tarifas-grados-nivel-label').value = ((state.nivelesMap.get(nivelId) || {}).nivel) || '';
        document.getElementById('tarifas-grados-grado-label').value = ((state.gradosMap.get(gradoId) || {}).grado) || '';
        document.getElementById('tarifas-grados-seccion-label').value = ((state.seccionesMap.get(seccionId) || {}).seccion) || '';
        document.getElementById('tarifas-grados-tanda-label').value = tanda;
    }

    function renderOfertaAcademicaTarifas() {
        const select = document.getElementById('tarifas-grados-planif-id');
        const ayuda = document.getElementById('tarifas-grados-ayuda');

        select.innerHTML = '<option value="">Selecciona nivel, grado, seccion y tanda</option>';

        if (state.planificaciones.length === 0) {
            ayuda.className = 'form-text text-danger';
            ayuda.textContent = 'No hay planificaciones activas para enlazar tarifas.';
            return;
        }

        const rows = state.planificaciones.slice().sort((a, b) => Number(b.anio_escolar_id || 0) - Number(a.anio_escolar_id || 0));
        rows.forEach((planif) => {
            const nivelId = Number(planif.nivel_id || 0);
            const gradoId = Number(planif.grado_id || 0);
            const seccionId = Number(planif.seccion_id || 0);
            const nivelTxt = String(((state.nivelesMap.get(nivelId) || {}).nivel) || ('Nivel #' + nivelId)).toUpperCase();
            const gradoTxt = String(((state.gradosMap.get(gradoId) || {}).grado) || ('Grado #' + gradoId)).toUpperCase();
            const seccionTxt = String(((state.seccionesMap.get(seccionId) || {}).seccion) || ('#' + seccionId)).toUpperCase();
            const tandaTxt = String(getTandaNombreByPlanif(planif) || getJornadaByPlanif(planif)).toUpperCase();

            const option = document.createElement('option');
            option.value = String(planif.id || '');
            option.textContent = nivelTxt + '-' + tandaTxt + ' - ' + gradoTxt + ' - Seccion ' + seccionTxt;
            select.appendChild(option);
        });

        ayuda.className = 'form-text text-muted';
        ayuda.textContent = 'Oferta academica enlazada desde Planeacion academica.';
    }

    function handleOfertaAcademicaTarifaChange() {
        const planifId = Number(document.getElementById('tarifas-grados-planif-id').value || 0);
        const planif = state.planificaciones.find((p) => Number(p.id || 0) === planifId) || null;
        applyPlanifToTarifaForm(planif);
    }

    function resolveDefaultTarifarioId(tarifarios) {
        const tarifarioActivo = tarifarios.find((t) => Number(t.estado ?? 1) === 1) || tarifarios[0] || null;
        state.defaultTarifarioId = tarifarioActivo ? Number(tarifarioActivo.id || 0) : 0;
        document.getElementById('tarifas-grados-tarifario-id').value = state.defaultTarifarioId > 0 ? String(state.defaultTarifarioId) : '';
    }

    async function ensureDefaultTarifario() {
        if (state.defaultTarifarioId > 0) {
            return;
        }

        const planifAnioId = state.planificaciones.length > 0
            ? Number(state.planificaciones[0].anio_escolar_id || 0)
            : 0;
        const fallbackAnioId = state.aniosEscolares.length > 0
            ? Number(state.aniosEscolares[0].id || 0)
            : 0;
        const anioEscolarId = planifAnioId > 0 ? planifAnioId : fallbackAnioId;

        if (anioEscolarId <= 0) {
            throw new Error('No existe anio escolar para crear tarifario base automaticamente.');
        }

        const nombre = 'Tarifario base ' + new Date().toISOString().slice(0, 10);
        try {
            await apiStore('tarifarios', {
                anio_escolar_id: anioEscolarId,
                nombre,
                estado: 1,
            });
        } catch (error) {
            const msg = String(error && error.message ? error.message : '');
            if (msg.toLowerCase().indexOf('ya existe') === -1) {
                throw error;
            }
        }

        const tarifarios = await apiGet('tarifarios');
        resolveDefaultTarifarioId(tarifarios);

        if (state.defaultTarifarioId <= 0) {
            throw new Error('No se pudo establecer un tarifario base.');
        }
    }

    function renderDatosCentro() {
        const tbody = document.getElementById('tbody-datos-centro');
        if (state.datosCentro.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Sin registros</td></tr>';
            return;
        }

        tbody.innerHTML = state.datosCentro.map((row) => '<tr>' +
            '<td>' + row.id + '</td>' +
            '<td>' + (row.nombre_centro || '') + '</td>' +
            '<td>' + (row.codigo_centro || '') + '</td>' +
            '<td>' + (row.telefono || '') + '</td>' +
            '<td>' + (row.correo_electronico || '') + '</td>' +
            '<td>' + (Number(row.estado) === 1 ? 'Activo' : 'Inactivo') + '</td>' +
            '<td>' +
            '<button class="btn btn-sm btn-info" onclick="ConfigSistema.startEdit(\'datos-centro\',' + row.id + ')">Editar</button>' +
            '</td>' +
            '</tr>').join('');
    }

    function renderDepartamentosCatalogo() {
        const tbody = document.getElementById('tbody-departamentos');
        if (state.departamentosCatalogo.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Sin registros</td></tr>';
            return;
        }

        tbody.innerHTML = state.departamentosCatalogo.map((row) => '<tr>'
            + '<td>' + row.id + '</td>'
            + '<td>' + (row.nombre || '') + '</td>'
            + '<td>' + (Number(row.estado) === 1 ? 'Activo' : 'Inactivo') + '</td>'
            + '<td>'
            + '<button class="btn btn-sm btn-info mr-1" onclick="ConfigSistema.startEdit(\'departamentos\',' + row.id + ')">Editar</button>'
            + '<button class="btn btn-sm btn-danger" onclick="ConfigSistema.remove(\'departamentos\',' + row.id + ')">Eliminar</button>'
            + '</td>'
            + '</tr>').join('');
    }

    function renderCargosCatalogo() {
        const tbody = document.getElementById('tbody-cargos');
        if (state.cargosCatalogo.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Sin registros</td></tr>';
            return;
        }

        tbody.innerHTML = state.cargosCatalogo.map((row) => '<tr>'
            + '<td>' + row.id + '</td>'
            + '<td>' + (row.nombre || '') + '</td>'
            + '<td>' + (row.descripcion || '') + '</td>'
            + '<td>' + (Number(row.estado) === 1 ? 'Activo' : 'Inactivo') + '</td>'
            + '<td>'
            + '<button class="btn btn-sm btn-info mr-1" onclick="ConfigSistema.startEdit(\'cargos\',' + row.id + ')">Editar</button>'
            + '<button class="btn btn-sm btn-danger" onclick="ConfigSistema.remove(\'cargos\',' + row.id + ')">Eliminar</button>'
            + '</td>'
            + '</tr>').join('');
    }

    function renderCondicionesLaboralesCatalogo() {
        const tbody = document.getElementById('tbody-condiciones-laborales');
        if (state.condicionesLaboralesCatalogo.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Sin registros</td></tr>';
            return;
        }

        tbody.innerHTML = state.condicionesLaboralesCatalogo.map((row) => '<tr>'
            + '<td>' + row.id + '</td>'
            + '<td>' + (row.nombre || '') + '</td>'
            + '<td>' + (Number(row.estado) === 1 ? 'Activo' : 'Inactivo') + '</td>'
            + '<td>'
            + '<button class="btn btn-sm btn-info mr-1" onclick="ConfigSistema.startEdit(\'condiciones-laborales\',' + row.id + ')">Editar</button>'
            + '<button class="btn btn-sm btn-danger" onclick="ConfigSistema.remove(\'condiciones-laborales\',' + row.id + ')">Eliminar</button>'
            + '</td>'
            + '</tr>').join('');
    }

    function renderParametros() {
        const tbody = document.getElementById('tbody-parametros-financieros');
        if (state.parametrosFinancieros.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Sin registros</td></tr>';
            return;
        }

        tbody.innerHTML = state.parametrosFinancieros.map((row) => '<tr>' +
            '<td>' + row.id + '</td>' +
            '<td>' + row.anio_escolar_id + '</td>' +
            '<td>' + row.dia_vencimiento_mensual + '</td>' +
            '<td>' + row.mora_mensual + '</td>' +
            '<td>' + (Number(row.pago_agosto_libera_junio) === 1 ? 'Si' : 'No') + '</td>' +
            '<td>' + (Number(row.estado) === 1 ? 'Activo' : 'Inactivo') + '</td>' +
            '<td>' +
            '<button class="btn btn-sm btn-info mr-1" onclick="ConfigSistema.startEdit(\'parametros-financieros\',' + row.id + ')">Editar</button>' +
            '<button class="btn btn-sm btn-danger" onclick="ConfigSistema.remove(\'parametros-financieros\',' + row.id + ')">Eliminar</button>' +
            '</td>' +
            '</tr>').join('');
    }

    async function loadAll() {
        clearAlert();
        try {
            const [roles, tarifarios, tarifasGrados, datosCentro, departamentos, cargos, condicionesLaborales, parametros, aniosEscolares, planificaciones, niveles, grados, secciones, tandas] = await Promise.all([
                apiGet('roles'),
                apiGet('tarifarios'),
                apiGet('tarifas_grados'),
                apiGet('datos_centro_educativo'),
                apiGet('departamentos'),
                apiGet('cargos'),
                apiGet('condiciones_laborales'),
                apiGet('parametros_financieros'),
                apiGet('anios_escolares'),
                apiGet('planificaciones_academicas'),
                apiGet('niveles'),
                apiGet('grados'),
                apiGet('secciones'),
                apiGet('tandas')
            ]);

            state.roles = roles;
            state.tarifasGrados = tarifasGrados;
            state.datosCentro = datosCentro;
            state.departamentosCatalogo = departamentos;
            state.cargosCatalogo = cargos;
            state.condicionesLaboralesCatalogo = condicionesLaborales;
            state.parametrosFinancieros = parametros;
            state.aniosEscolares = aniosEscolares;
            state.planificaciones = planificaciones.filter((row) => Number(row.estado ?? 1) === 1);
            state.nivelesMap = buildMap(niveles, 'id');
            state.gradosMap = buildMap(grados, 'id');
            state.seccionesMap = buildMap(secciones, 'id');
            state.tandasMap = buildMap(tandas, 'id');

            resolveDefaultTarifarioId(tarifarios);

            renderRoles();
            renderOfertaAcademicaTarifas();
            renderTarifasGrados();
            renderDatosCentro();
            renderDepartamentosCatalogo();
            renderCargosCatalogo();
            renderCondicionesLaboralesCatalogo();
            renderParametros();

            if (state.defaultTarifarioId <= 0) {
                showAlert('warning', 'No hay tarifario base aun. Se creara automaticamente al guardar la primera tarifa.');
            }
        } catch (error) {
            showAlert('danger', error.message);
        }
    }

    const config = {
        'roles': {
            resource: 'roles',
            idField: 'id',
            stateKey: 'roles',
            getData: () => ({
                nombre: document.getElementById('roles-nombre').value.trim(),
                descripcion: document.getElementById('roles-descripcion').value.trim() || null,
                estado: asInt(document.getElementById('roles-estado').value),
            }),
            fill: (row) => {
                document.getElementById('roles-id').value = row.id || '';
                document.getElementById('roles-nombre').value = row.nombre || '';
                document.getElementById('roles-descripcion').value = row.descripcion || '';
                document.getElementById('roles-estado').value = String(row.estado ?? 1);
            },
            clear: () => {
                document.getElementById('roles-id').value = '';
                document.getElementById('roles-nombre').value = '';
                document.getElementById('roles-descripcion').value = '';
                document.getElementById('roles-estado').value = '1';
            },
        },
        'tarifas-grados': {
            resource: 'tarifas_grados',
            idField: 'id',
            stateKey: 'tarifasGrados',
            getData: () => ({
                tarifario_id: asInt(document.getElementById('tarifas-grados-tarifario-id').value || state.defaultTarifarioId),
                nivel_id: asInt(document.getElementById('tarifas-grados-nivel-id').value),
                grado_id: asInt(document.getElementById('tarifas-grados-grado-id').value),
                jornada: document.getElementById('tarifas-grados-jornada').value,
                tarifa_inscripcion: asFloat(document.getElementById('tarifas-grados-inscripcion').value),
                mensualidad: asFloat(document.getElementById('tarifas-grados-mensualidad').value),
                activo: asInt(document.getElementById('tarifas-grados-activo').value),
            }),
            fill: (row) => {
                document.getElementById('tarifas-grados-id').value = row.id || '';
                document.getElementById('tarifas-grados-tarifario-id').value = row.tarifario_id || state.defaultTarifarioId || '';
                document.getElementById('tarifas-grados-nivel-id').value = row.nivel_id || '';
                document.getElementById('tarifas-grados-grado-id').value = row.grado_id || '';
                document.getElementById('tarifas-grados-jornada').value = row.jornada || 'MATUTINO';
                document.getElementById('tarifas-grados-inscripcion').value = row.tarifa_inscripcion || 0;
                document.getElementById('tarifas-grados-mensualidad').value = row.mensualidad || 0;
                document.getElementById('tarifas-grados-activo').value = String(row.activo ?? 1);

                const planif = getPlanifByTarifa(row);
                document.getElementById('tarifas-grados-planif-id').value = planif ? String(planif.id || '') : '';
                applyPlanifToTarifaForm(planif);
            },
            clear: () => {
                document.getElementById('tarifas-grados-id').value = '';
                document.getElementById('tarifas-grados-tarifario-id').value = state.defaultTarifarioId > 0 ? String(state.defaultTarifarioId) : '';
                clearOfertaTarifaFields();
                document.getElementById('tarifas-grados-inscripcion').value = '0';
                document.getElementById('tarifas-grados-mensualidad').value = '0';
                document.getElementById('tarifas-grados-activo').value = '1';
            },
        },
        'datos-centro': {
            resource: 'datos_centro_educativo',
            idField: 'id',
            stateKey: 'datosCentro',
            getData: () => ({
                nombre_centro: document.getElementById('datos-centro-nombre').value.trim(),
                codigo_centro: document.getElementById('datos-centro-codigo').value.trim() || null,
                rnc: document.getElementById('datos-centro-rnc').value.trim() || null,
                telefono: document.getElementById('datos-centro-telefono').value.trim() || null,
                celular: document.getElementById('datos-centro-celular').value.trim() || null,
                correo_electronico: document.getElementById('datos-centro-correo').value.trim() || null,
                lema: document.getElementById('datos-centro-lema').value.trim() || null,
                direccion: document.getElementById('datos-centro-direccion').value.trim() || null,
                logo: document.getElementById('datos-centro-logo').value.trim() || null,
                estado: asInt(document.getElementById('datos-centro-estado').value),
            }),
            fill: (row) => {
                document.getElementById('datos-centro-id').value = row.id || '';
                document.getElementById('datos-centro-nombre').value = row.nombre_centro || '';
                document.getElementById('datos-centro-codigo').value = row.codigo_centro || '';
                document.getElementById('datos-centro-rnc').value = row.rnc || '';
                document.getElementById('datos-centro-telefono').value = row.telefono || '';
                document.getElementById('datos-centro-celular').value = row.celular || '';
                document.getElementById('datos-centro-correo').value = row.correo_electronico || '';
                document.getElementById('datos-centro-lema').value = row.lema || '';
                document.getElementById('datos-centro-direccion').value = row.direccion || '';
                document.getElementById('datos-centro-logo').value = row.logo || '';
                document.getElementById('datos-centro-logo-file').value = '';
                syncLogoPreview(row.logo || '');
                document.getElementById('datos-centro-estado').value = String(row.estado ?? 1);
            },
            clear: () => {
                document.getElementById('datos-centro-id').value = '';
                document.getElementById('datos-centro-nombre').value = '';
                document.getElementById('datos-centro-codigo').value = '';
                document.getElementById('datos-centro-rnc').value = '';
                document.getElementById('datos-centro-telefono').value = '';
                document.getElementById('datos-centro-celular').value = '';
                document.getElementById('datos-centro-correo').value = '';
                document.getElementById('datos-centro-lema').value = '';
                document.getElementById('datos-centro-direccion').value = '';
                document.getElementById('datos-centro-logo').value = '';
                document.getElementById('datos-centro-logo-file').value = '';
                syncLogoPreview('');
                document.getElementById('datos-centro-estado').value = '1';
            },
        },
        'departamentos': {
            resource: 'departamentos',
            idField: 'id',
            stateKey: 'departamentosCatalogo',
            getData: () => ({
                nombre: document.getElementById('departamentos-nombre').value.trim(),
                estado: asInt(document.getElementById('departamentos-estado').value),
            }),
            fill: (row) => {
                document.getElementById('departamentos-id').value = row.id || '';
                document.getElementById('departamentos-nombre').value = row.nombre || '';
                document.getElementById('departamentos-estado').value = String(row.estado ?? 1);
            },
            clear: () => {
                document.getElementById('departamentos-id').value = '';
                document.getElementById('departamentos-nombre').value = '';
                document.getElementById('departamentos-estado').value = '1';
            },
        },
        'cargos': {
            resource: 'cargos',
            idField: 'id',
            stateKey: 'cargosCatalogo',
            getData: () => ({
                nombre: document.getElementById('cargos-nombre').value.trim(),
                descripcion: document.getElementById('cargos-descripcion').value.trim() || null,
                estado: asInt(document.getElementById('cargos-estado').value),
            }),
            fill: (row) => {
                document.getElementById('cargos-id').value = row.id || '';
                document.getElementById('cargos-nombre').value = row.nombre || '';
                document.getElementById('cargos-descripcion').value = row.descripcion || '';
                document.getElementById('cargos-estado').value = String(row.estado ?? 1);
            },
            clear: () => {
                document.getElementById('cargos-id').value = '';
                document.getElementById('cargos-nombre').value = '';
                document.getElementById('cargos-descripcion').value = '';
                document.getElementById('cargos-estado').value = '1';
            },
        },
        'condiciones-laborales': {
            resource: 'condiciones_laborales',
            idField: 'id',
            stateKey: 'condicionesLaboralesCatalogo',
            getData: () => ({
                nombre: document.getElementById('condiciones-laborales-nombre').value.trim(),
                estado: asInt(document.getElementById('condiciones-laborales-estado').value),
            }),
            fill: (row) => {
                document.getElementById('condiciones-laborales-id').value = row.id || '';
                document.getElementById('condiciones-laborales-nombre').value = row.nombre || '';
                document.getElementById('condiciones-laborales-estado').value = String(row.estado ?? 1);
            },
            clear: () => {
                document.getElementById('condiciones-laborales-id').value = '';
                document.getElementById('condiciones-laborales-nombre').value = '';
                document.getElementById('condiciones-laborales-estado').value = '1';
            },
        },
        'parametros-financieros': {
            resource: 'parametros_financieros',
            idField: 'id',
            stateKey: 'parametrosFinancieros',
            getData: () => ({
                anio_escolar_id: asInt(document.getElementById('parametros-financieros-anio').value),
                dia_vencimiento_mensual: asInt(document.getElementById('parametros-financieros-dia').value),
                mora_mensual: asFloat(document.getElementById('parametros-financieros-mora').value),
                regla_especial: document.getElementById('parametros-financieros-regla').value.trim() || null,
                pago_agosto_libera_junio: asInt(document.getElementById('parametros-financieros-pago-agosto').value),
                estado: asInt(document.getElementById('parametros-financieros-estado').value),
            }),
            fill: (row) => {
                document.getElementById('parametros-financieros-id').value = row.id || '';
                document.getElementById('parametros-financieros-anio').value = row.anio_escolar_id || '';
                document.getElementById('parametros-financieros-dia').value = row.dia_vencimiento_mensual || '';
                document.getElementById('parametros-financieros-mora').value = row.mora_mensual || 0;
                document.getElementById('parametros-financieros-regla').value = row.regla_especial || '';
                document.getElementById('parametros-financieros-pago-agosto').value = String(row.pago_agosto_libera_junio ?? 1);
                document.getElementById('parametros-financieros-estado').value = String(row.estado ?? 1);
            },
            clear: () => {
                document.getElementById('parametros-financieros-id').value = '';
                document.getElementById('parametros-financieros-anio').value = '';
                document.getElementById('parametros-financieros-dia').value = '';
                document.getElementById('parametros-financieros-mora').value = '0';
                document.getElementById('parametros-financieros-regla').value = '';
                document.getElementById('parametros-financieros-pago-agosto').value = '1';
                document.getElementById('parametros-financieros-estado').value = '1';
            },
        },
    };

    function findById(section, id) {
        const entry = config[section];
        const rowId = Number(id);
        return state[entry.stateKey].find((row) => Number(row[entry.idField]) === rowId) || null;
    }

    async function save(section) {
        clearAlert();
        const entry = config[section];
        const idInput = document.getElementById(section + '-id');
        const id = idInput ? idInput.value.trim() : '';

        try {
            if (section === 'datos-centro') {
                await uploadLogoIfNeeded();
            }

            if (section === 'tarifas-grados') {
                await ensureDefaultTarifario();

                const planifId = Number(document.getElementById('tarifas-grados-planif-id').value || 0);
                if (planifId <= 0) {
                    throw new Error('Debes seleccionar una oferta academica de planeacion.');
                }
            }

            const data = entry.getData();
            if (id === '') {
                await apiStore(entry.resource, data);
                showAlert('success', 'Registro creado correctamente.');
            } else {
                await apiUpdate(entry.resource, { id: Number(id) }, data);
                showAlert('success', 'Registro actualizado correctamente.');
            }

            entry.clear();
            hideForm(section);
            await loadAll();
        } catch (error) {
            showAlert('danger', error.message);
        }
    }

    async function remove(section, id) {
        clearAlert();
        if (!window.confirm('Confirma que deseas eliminar el registro #' + id + '?')) {
            return;
        }

        const entry = config[section];
        try {
            await apiDestroy(entry.resource, { id: Number(id) });
            showAlert('warning', 'Registro eliminado correctamente.');
            await loadAll();
        } catch (error) {
            showAlert('danger', error.message);
        }
    }

    function startCreate(section) {
        clearAlert();
        const entry = config[section];
        entry.clear();
        showForm(section);
    }

    function startEdit(section, id) {
        clearAlert();
        const entry = config[section];
        const row = findById(section, id);
        if (!row) {
            showAlert('danger', 'No se encontro el registro seleccionado.');
            return;
        }

        entry.fill(row);
        showForm(section);
    }

    function cancel(section) {
        const entry = config[section];
        entry.clear();
        hideForm(section);
        clearAlert();
    }

    document.getElementById('btn-recargar-todo').addEventListener('click', loadAll);
    document.getElementById('tarifas-grados-planif-id').addEventListener('change', handleOfertaAcademicaTarifaChange);
    document.getElementById('datos-centro-logo-file').addEventListener('change', () => {
        const fileInput = document.getElementById('datos-centro-logo-file');
        const file = fileInput.files && fileInput.files.length > 0 ? fileInput.files[0] : null;
        const info = document.getElementById('datos-centro-logo-ruta');

        if (!file) {
            syncLogoPreview(document.getElementById('datos-centro-logo').value);
            return;
        }

        info.textContent = 'Archivo seleccionado: ' + file.name;
    });

    window.ConfigSistema = {
        startCreate,
        startEdit,
        save,
        remove,
        cancel,
    };

    loadAll();
})();
</script>
