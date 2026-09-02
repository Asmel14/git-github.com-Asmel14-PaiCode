<?php

declare(strict_types=1);
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Usuarios</h1>
    <div>
        <button id="btn-nuevo-usuario" class="btn btn-sm btn-primary shadow-sm" type="button">
            <i class="fas fa-user-plus fa-sm text-white-50"></i> Nuevo usuario
        </button>
        <button id="btn-recargar-usuarios" class="btn btn-sm btn-outline-primary shadow-sm" type="button">
            <i class="fas fa-sync-alt fa-sm"></i> Recargar
        </button>
    </div>
</div>

<div id="usuarios-alert" class="alert d-none" role="alert"></div>

<div id="usuarios-form-card" class="card shadow mb-4 d-none">
    <div class="card-header py-3">
        <h6 id="usuarios-form-title" class="m-0 font-weight-bold text-primary">Nuevo usuario</h6>
    </div>
    <div class="card-body">
        <input type="hidden" id="usuario-id">
        <div class="form-row">
            <div class="form-group col-md-5">
                <label for="usuario-nombre">Nombre completo</label>
                <input id="usuario-nombre" class="form-control" type="text" maxlength="150" placeholder="Nombre completo">
            </div>
            <div class="form-group col-md-4">
                <label for="usuario-correo">Correo</label>
                <input id="usuario-correo" class="form-control" type="email" maxlength="150" placeholder="correo@dominio.com">
            </div>
            <div class="form-group col-md-3">
                <label for="usuario-estado">Estado</label>
                <select id="usuario-estado" class="form-control">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="usuario-contrasena">Contrasena</label>
                <input id="usuario-contrasena" class="form-control" type="password" minlength="8" maxlength="255" placeholder="Minimo 8 caracteres">
                <small class="form-text text-muted">En edicion, deja este campo vacio para mantener la contrasena actual.</small>
            </div>
            <div class="form-group col-md-6">
                <label>Roles del usuario</label>
                <div id="usuario-roles-list" class="border rounded p-2" style="max-height: 170px; overflow-y: auto;">
                    <span class="text-muted small">Cargando roles...</span>
                </div>
            </div>
        </div>
        <button id="btn-guardar-usuario" class="btn btn-primary btn-sm" type="button">Guardar</button>
        <button id="btn-cancelar-usuario" class="btn btn-secondary btn-sm" type="button">Cancelar</button>
    </div>
</div>

<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Usuarios activos</div>
                <div id="stat-usuarios-activos" class="h4 mb-0 font-weight-bold text-gray-800">0</div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Roles configurados</div>
                <div id="stat-roles-configurados" class="h4 mb-0 font-weight-bold text-gray-800">0</div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ultimo acceso</div>
                <div id="stat-ultimo-acceso" class="h6 mb-0 font-weight-bold text-gray-800">Sin registros</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Listado de usuarios</h6>
        <input id="usuarios-filtro" class="form-control form-control-sm" type="text" placeholder="Filtrar por nombre o correo" style="max-width: 280px;">
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre completo</th>
                        <th>Correo</th>
                        <th>Roles</th>
                        <th>Ultimo acceso</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="usuarios-tbody"><tr><td colspan="7" class="text-center text-muted">Cargando...</td></tr></tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function () {
    const apiBase = '../';

    const state = {
        usuarios: [],
        roles: [],
        usuarioRoles: [],
        filteredUsuarios: [],
    };

    function showAlert(type, message) {
        const alert = document.getElementById('usuarios-alert');
        alert.className = 'alert alert-' + type;
        alert.textContent = message;
        alert.classList.remove('d-none');
    }

    function clearAlert() {
        const alert = document.getElementById('usuarios-alert');
        alert.className = 'alert d-none';
        alert.textContent = '';
    }

    async function apiRequest(resource, action, method, body) {
        const response = await fetch(apiBase + '?resource=' + resource + '&action=' + action + '&limit=5000', {
            method,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: body ? JSON.stringify(body) : undefined,
        });

        const json = await response.json();
        if (!json.success) {
            throw new Error(json.message || 'Error en ' + resource + '/' + action);
        }

        return json;
    }

    function normalizeUsersRows(rows) {
        return rows.map((row) => ({
            id: Number(row.id),
            nombre_completo: row.nombre_completo || '',
            correo: row.correo || '',
            estado: Number(row.estado || 0),
            ultimo_acceso: row.ultimo_acceso || '',
        }));
    }

    function roleNameMap() {
        const map = {};
        state.roles.forEach((r) => {
            map[Number(r.id)] = r.nombre || ('Rol ' + r.id);
        });
        return map;
    }

    function rolesByUserId() {
        const grouped = {};
        state.usuarioRoles.forEach((ur) => {
            const userId = Number(ur.usuario_id);
            const rolId = Number(ur.rol_id);
            if (!grouped[userId]) {
                grouped[userId] = [];
            }
            grouped[userId].push(rolId);
        });
        return grouped;
    }

    function renderStats() {
        const activeCount = state.usuarios.filter((u) => u.estado === 1).length;
        document.getElementById('stat-usuarios-activos').textContent = String(activeCount);
        document.getElementById('stat-roles-configurados').textContent = String(state.roles.length);

        const latest = state.usuarios
            .map((u) => u.ultimo_acceso)
            .filter((v) => typeof v === 'string' && v.trim() !== '')
            .sort()
            .reverse()[0] || '';

        document.getElementById('stat-ultimo-acceso').textContent = latest === '' ? 'Sin registros' : latest;
    }

    function applyFilter() {
        const term = document.getElementById('usuarios-filtro').value.trim().toLowerCase();
        if (term === '') {
            state.filteredUsuarios = state.usuarios.slice();
        } else {
            state.filteredUsuarios = state.usuarios.filter((u) =>
                u.nombre_completo.toLowerCase().includes(term) || u.correo.toLowerCase().includes(term)
            );
        }
        renderUsersTable();
    }

    function renderUsersTable() {
        const tbody = document.getElementById('usuarios-tbody');
        const rows = state.filteredUsuarios;
        if (rows.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Sin registros</td></tr>';
            return;
        }

        const roleMap = roleNameMap();
        const userRolesMap = rolesByUserId();

        tbody.innerHTML = rows.map((u) => {
            const roleIds = userRolesMap[u.id] || [];
            const roleNames = roleIds.map((rid) => roleMap[rid] || ('Rol ' + rid));
            const rolesText = roleNames.length > 0 ? roleNames.join(', ') : 'Sin roles';
            const ultimo = u.ultimo_acceso && u.ultimo_acceso.trim() !== '' ? u.ultimo_acceso : '-';
            const estado = u.estado === 1 ? 'Activo' : 'Inactivo';

            return '<tr>' +
                '<td>' + u.id + '</td>' +
                '<td>' + escapeHtml(u.nombre_completo) + '</td>' +
                '<td>' + escapeHtml(u.correo) + '</td>' +
                '<td>' + escapeHtml(rolesText) + '</td>' +
                '<td>' + escapeHtml(ultimo) + '</td>' +
                '<td>' + estado + '</td>' +
                '<td>' +
                '<button type="button" class="btn btn-sm btn-info mr-1" onclick="UsuariosView.edit(' + u.id + ')">Editar</button>' +
                '<button type="button" class="btn btn-sm btn-danger" onclick="UsuariosView.remove(' + u.id + ')">Eliminar</button>' +
                '</td>' +
                '</tr>';
        }).join('');
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderRolesChecklist(selectedRoleIds) {
        const container = document.getElementById('usuario-roles-list');
        if (state.roles.length === 0) {
            container.innerHTML = '<span class="text-muted small">No hay roles configurados.</span>';
            return;
        }

        const selectedSet = new Set((selectedRoleIds || []).map((v) => Number(v)));
        container.innerHTML = state.roles.map((role) => {
            const roleId = Number(role.id);
            const checked = selectedSet.has(roleId) ? 'checked' : '';
            return '<div class="custom-control custom-checkbox mb-1">' +
                '<input type="checkbox" class="custom-control-input usuario-rol-item" id="usuario-rol-' + roleId + '" value="' + roleId + '" ' + checked + '>' +
                '<label class="custom-control-label" for="usuario-rol-' + roleId + '">' + escapeHtml(role.nombre || ('Rol ' + roleId)) + '</label>' +
                '</div>';
        }).join('');
    }

    function getSelectedRoleIds() {
        return Array.from(document.querySelectorAll('.usuario-rol-item:checked')).map((el) => Number(el.value));
    }

    function showForm(mode, user) {
        const title = document.getElementById('usuarios-form-title');
        const card = document.getElementById('usuarios-form-card');
        const idInput = document.getElementById('usuario-id');

        if (mode === 'create') {
            title.textContent = 'Nuevo usuario';
            idInput.value = '';
            document.getElementById('usuario-nombre').value = '';
            document.getElementById('usuario-correo').value = '';
            document.getElementById('usuario-contrasena').value = '';
            document.getElementById('usuario-estado').value = '1';
            renderRolesChecklist([]);
        } else {
            title.textContent = 'Editar usuario';
            idInput.value = String(user.id);
            document.getElementById('usuario-nombre').value = user.nombre_completo || '';
            document.getElementById('usuario-correo').value = user.correo || '';
            document.getElementById('usuario-contrasena').value = '';
            document.getElementById('usuario-estado').value = String(user.estado);
            const selectedRoles = rolesByUserId()[user.id] || [];
            renderRolesChecklist(selectedRoles);
        }

        card.classList.remove('d-none');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function hideForm() {
        document.getElementById('usuarios-form-card').classList.add('d-none');
    }

    function validateUserInput(isEdit) {
        const nombre = document.getElementById('usuario-nombre').value.trim();
        const correo = document.getElementById('usuario-correo').value.trim();
        const contrasena = document.getElementById('usuario-contrasena').value;

        if (nombre === '') {
            throw new Error('Nombre completo es obligatorio.');
        }
        if (correo === '') {
            throw new Error('Correo es obligatorio.');
        }
        if (!/^\S+@\S+\.\S+$/.test(correo)) {
            throw new Error('Correo no tiene formato valido.');
        }
        if (!isEdit && contrasena.trim() === '') {
            throw new Error('Contrasena es obligatoria para crear.');
        }
        if (contrasena.trim() !== '' && contrasena.length < 8) {
            throw new Error('Contrasena debe tener al menos 8 caracteres.');
        }
    }

    async function syncUserRoles(usuarioId, selectedRoleIds) {
        const currentRows = state.usuarioRoles.filter((row) => Number(row.usuario_id) === Number(usuarioId));
        const current = new Set(currentRows.map((row) => Number(row.rol_id)));
        const target = new Set(selectedRoleIds.map((v) => Number(v)));

        const toAdd = [];
        const toRemove = [];

        target.forEach((id) => {
            if (!current.has(id)) {
                toAdd.push(id);
            }
        });
        current.forEach((id) => {
            if (!target.has(id)) {
                toRemove.push(id);
            }
        });

        for (let i = 0; i < toAdd.length; i += 1) {
            await apiRequest('usuario_roles', 'store', 'POST', {
                data: {
                    usuario_id: Number(usuarioId),
                    rol_id: Number(toAdd[i]),
                }
            });
        }

        for (let i = 0; i < toRemove.length; i += 1) {
            await apiRequest('usuario_roles', 'destroy', 'DELETE', {
                criteria: {
                    usuario_id: Number(usuarioId),
                    rol_id: Number(toRemove[i]),
                }
            });
        }
    }

    async function saveUser() {
        clearAlert();
        const idValue = document.getElementById('usuario-id').value.trim();
        const isEdit = idValue !== '';

        try {
            validateUserInput(isEdit);
            const payload = {
                nombre_completo: document.getElementById('usuario-nombre').value.trim(),
                correo: document.getElementById('usuario-correo').value.trim(),
                estado: Number(document.getElementById('usuario-estado').value),
            };

            const rawPwd = document.getElementById('usuario-contrasena').value;
            if (!isEdit || rawPwd.trim() !== '') {
                payload.contrasena = rawPwd;
            }

            let usuarioId = 0;
            if (isEdit) {
                usuarioId = Number(idValue);
                await apiRequest('usuarios', 'update', 'PUT', {
                    criteria: { id: usuarioId },
                    data: payload,
                });
            } else {
                const created = await apiRequest('usuarios', 'store', 'POST', { data: payload });
                usuarioId = Number(created.data && created.data.id ? created.data.id : 0);
                if (usuarioId <= 0) {
                    throw new Error('No se pudo identificar el usuario creado.');
                }
            }

            await syncUserRoles(usuarioId, getSelectedRoleIds());

            hideForm();
            await loadAll();
            showAlert('success', isEdit ? 'Usuario actualizado correctamente.' : 'Usuario creado correctamente.');
        } catch (error) {
            showAlert('danger', error.message);
        }
    }

    async function removeUser(id) {
        clearAlert();
        if (!window.confirm('Confirma que deseas eliminar el usuario #' + id + '?')) {
            return;
        }

        try {
            const rels = state.usuarioRoles.filter((row) => Number(row.usuario_id) === Number(id));
            for (let i = 0; i < rels.length; i += 1) {
                await apiRequest('usuario_roles', 'destroy', 'DELETE', {
                    criteria: {
                        usuario_id: Number(id),
                        rol_id: Number(rels[i].rol_id),
                    }
                });
            }

            await apiRequest('usuarios', 'destroy', 'DELETE', { criteria: { id: Number(id) } });
            await loadAll();
            showAlert('warning', 'Usuario eliminado correctamente.');
        } catch (error) {
            showAlert('danger', error.message);
        }
    }

    async function loadAll() {
        clearAlert();
        try {
            const [usuariosRes, rolesRes, usuarioRolesRes] = await Promise.all([
                apiRequest('usuarios', 'index', 'GET'),
                apiRequest('roles', 'index', 'GET'),
                apiRequest('usuario_roles', 'index', 'GET'),
            ]);

            state.usuarios = normalizeUsersRows(Array.isArray(usuariosRes.data) ? usuariosRes.data : []);
            state.roles = Array.isArray(rolesRes.data) ? rolesRes.data : [];
            state.usuarioRoles = Array.isArray(usuarioRolesRes.data) ? usuarioRolesRes.data : [];

            renderStats();
            renderRolesChecklist([]);
            applyFilter();
        } catch (error) {
            showAlert('danger', error.message);
        }
    }

    function editUser(id) {
        clearAlert();
        const user = state.usuarios.find((u) => u.id === Number(id));
        if (!user) {
            showAlert('danger', 'No se encontro el usuario seleccionado.');
            return;
        }

        showForm('edit', user);
    }

    document.getElementById('btn-nuevo-usuario').addEventListener('click', function () {
        clearAlert();
        showForm('create');
    });

    document.getElementById('btn-recargar-usuarios').addEventListener('click', loadAll);
    document.getElementById('btn-guardar-usuario').addEventListener('click', saveUser);
    document.getElementById('btn-cancelar-usuario').addEventListener('click', function () {
        hideForm();
        clearAlert();
    });
    document.getElementById('usuarios-filtro').addEventListener('input', applyFilter);

    window.UsuariosView = {
        edit: editUser,
        remove: removeUser,
    };

    loadAll();
})();
</script>
