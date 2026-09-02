<?php

declare(strict_types=1);
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Nueva ficha de personal</h1>
</div>

<div id="estado-ficha-personal" class="alert alert-info mb-3">Cargando catalogos...</div>

<form id="form-ficha-personal" novalidate>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informacion basica</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <label class="d-block">Foto del empleado</label>
                    <small class="form-text text-muted mb-2">Sube una imagen clara del rostro. Formatos permitidos: JPG, PNG, WEBP.</small>
                    <div id="preview-foto-personal" class="border rounded d-flex align-items-center justify-content-center bg-light text-muted" style="width:140px;height:170px;overflow:hidden;">Sin foto</div>
                    <div class="custom-file mt-2">
                        <input type="file" class="custom-file-input" id="foto-personal" accept="image/jpeg,image/png,image/webp">
                        <label class="custom-file-label" for="foto-personal" id="foto-personal-label">Seleccionar imagen</label>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="cedula-pasaporte">Cedula / Pasaporte</label>
                            <input type="text" class="form-control" id="cedula-pasaporte" maxlength="30" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="primer-nombre">Primer nombre *</label>
                            <input type="text" class="form-control" id="primer-nombre" maxlength="100" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="segundo-nombre">Segundo nombre</label>
                            <input type="text" class="form-control" id="segundo-nombre" maxlength="100">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="primer-apellido">Primer apellido *</label>
                            <input type="text" class="form-control" id="primer-apellido" maxlength="100" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="segundo-apellido">Segundo apellido</label>
                            <input type="text" class="form-control" id="segundo-apellido" maxlength="100">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="fecha-nacimiento">Fecha de nacimiento</label>
                            <input type="date" class="form-control" id="fecha-nacimiento">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="sexo">Sexo</label>
                            <select class="form-control" id="sexo">
                                <option value="">Selecciona</option>
                                <option value="MASCULINO">Masculino</option>
                                <option value="FEMENINO">Femenino</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="estado-civil">Estado civil</label>
                            <select class="form-control" id="estado-civil">
                                <option value="">Selecciona</option>
                                <option value="SOLTERO">Soltero(a)</option>
                                <option value="CASADO">Casado(a)</option>
                                <option value="VIUDO">Viudo(a)</option>
                                <option value="DIVORCIADO">Divorciado(a)</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nacionalidad">Nacionalidad</label>
                            <select class="form-control" id="nacionalidad">
                                <option value="">Selecciona</option>
                                <option value="Alemana">Alemana</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Brasilena">Brasilena</option>
                                <option value="Chilena">Chilena</option>
                                <option value="Colombiana">Colombiana</option>
                                <option value="Costarricense">Costarricense</option>
                                <option value="Cubana">Cubana</option>
                                <option value="Dominicana">Dominicana</option>
                                <option value="Ecuatoriana">Ecuatoriana</option>
                                <option value="Espanola">Espanola</option>
                                <option value="Estadounidense">Estadounidense</option>
                                <option value="Francesa">Francesa</option>
                                <option value="Guatemalteca">Guatemalteca</option>
                                <option value="Haitiana">Haitiana</option>
                                <option value="Hondurena">Hondurena</option>
                                <option value="Italiana">Italiana</option>
                                <option value="Mexicana">Mexicana</option>
                                <option value="Nicaraguense">Nicaraguense</option>
                                <option value="Panamena">Panamena</option>
                                <option value="Peruana">Peruana</option>
                                <option value="Puertorriquena">Puertorriquena</option>
                                <option value="Salvadorena">Salvadorena</option>
                                <option value="Venezolana">Venezolana</option>
                                <option value="Otra">Otra</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Puesto para el ano actual</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="anio-actual">Ano escolar vigente</label>
                    <input type="text" class="form-control" id="anio-actual" readonly>
                    <input type="hidden" id="anio-escolar-id">
                </div>
                <div class="form-group col-md-4">
                    <label for="departamento-id">Departamento</label>
                    <select class="form-control" id="departamento-id" required>
                        <option value="">Selecciona</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="cargo-id">Cargo (puesto asignado)</label>
                    <select class="form-control" id="cargo-id" required>
                        <option value="">Selecciona</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="condicion-laboral-id">Condicion laboral</label>
                    <select class="form-control" id="condicion-laboral-id" required>
                        <option value="">Selecciona</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="estado-centro">Estado en el centro</label>
                    <select class="form-control" id="estado-centro">
                        <option value="1">Activo</option>
                        <option value="0">Desactivo</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informacion de contacto</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="telefono">Telefono</label>
                    <input type="text" class="form-control" id="telefono" maxlength="30">
                </div>
                <div class="form-group col-md-4">
                    <label for="celular">Celular</label>
                    <input type="text" class="form-control" id="celular" maxlength="30">
                </div>
                <div class="form-group col-md-4">
                    <label for="whatsapp">WhatsApp</label>
                    <input type="text" class="form-control" id="whatsapp" maxlength="30">
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Direccion</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="provincia">Provincia</label>
                    <input type="text" class="form-control" id="provincia" maxlength="100">
                </div>
                <div class="form-group col-md-4">
                    <label for="municipio">Municipio</label>
                    <input type="text" class="form-control" id="municipio" maxlength="100">
                </div>
                <div class="form-group col-md-4">
                    <label for="distrito-municipal">Distrito municipal</label>
                    <input type="text" class="form-control" id="distrito-municipal" maxlength="100">
                </div>
                <div class="form-group col-md-4">
                    <label for="seccion-direccion">Seccion</label>
                    <input type="text" class="form-control" id="seccion-direccion" maxlength="100">
                </div>
                <div class="form-group col-md-4">
                    <label for="barrio">Barrio</label>
                    <input type="text" class="form-control" id="barrio" maxlength="100">
                </div>
                <div class="form-group col-md-4">
                    <label for="sub-barrio">Sub barrio</label>
                    <input type="text" class="form-control" id="sub-barrio" maxlength="100">
                </div>
                <div class="form-group col-md-12">
                    <label for="calle-numero">Calle y numero</label>
                    <input type="text" class="form-control" id="calle-numero" maxlength="255">
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Estudios concluidos</h6>
            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-agregar-concluido">Agregar estudio concluido</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="tabla-estudios-concluidos">
                    <thead class="thead-light">
                    <tr>
                        <th>Nivel academico</th>
                        <th>Entidad</th>
                        <th>Titulo</th>
                        <th>Ano inicio</th>
                        <th>Ano fin</th>
                        <th>No. registro</th>
                        <th>No. folio</th>
                        <th>Pais</th>
                        <th>Ciudad</th>
                        <th>Accion</th>
                    </tr>
                    </thead>
                    <tbody id="tbody-estudios-concluidos"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Estudios en proceso</h6>
            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-agregar-proceso">Agregar estudio en proceso</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="tabla-estudios-proceso">
                    <thead class="thead-light">
                    <tr>
                        <th>Area estudio</th>
                        <th>Entidad</th>
                        <th>Titulo</th>
                        <th>Ano inicio</th>
                        <th>Duracion</th>
                        <th>Horas</th>
                        <th>Pais</th>
                        <th>Ciudad</th>
                        <th>Accion</th>
                    </tr>
                    </thead>
                    <tbody id="tbody-estudios-proceso"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Datos laborales</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="fecha-ingreso">Fecha de ingreso</label>
                    <input type="date" class="form-control" id="fecha-ingreso">
                </div>
                <div class="form-group col-md-4">
                    <label for="tanda-laboral">Tanda</label>
                    <select class="form-control" id="tanda-laboral">
                        <option value="">Selecciona</option>
                        <option value="MATUTINA">Matutina</option>
                        <option value="VESPERTINA">Vespertina</option>
                        <option value="MATUTINA_VESPERTINA">Matutina/Vespertina</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="salario">Salario</label>
                    <input type="number" class="form-control" id="salario" min="0" step="0.01" value="0">
                </div>
                <div class="form-group col-md-6">
                    <label for="banco">Banco</label>
                    <input type="text" class="form-control" id="banco" maxlength="150">
                </div>
                <div class="form-group col-md-6">
                    <label for="numero-cuenta-bancaria">Numero de cuenta bancaria</label>
                    <input type="text" class="form-control" id="numero-cuenta-bancaria" maxlength="100">
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Terminos y condiciones</h6>
        </div>
        <div class="card-body">
            <ul class="mb-3">
                <li>El pago del salario sera los dias 25 de cada mes, los pagos son desde agosto hasta junio para un total de 11 meses.</li>
                <li>Al firmar esta ficha de personal acepto los terminos y condiciones establecidos por la institucion y el reglamento interno.</li>
            </ul>
            <div class="custom-control custom-checkbox mb-2">
                <input class="custom-control-input" type="checkbox" id="acepta-terminos" required>
                <label class="custom-control-label" for="acepta-terminos">Acepta terminos y condiciones</label>
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input class="custom-control-input" type="checkbox" id="empleado-activo" checked>
                <label class="custom-control-label" for="empleado-activo">Empleado activo</label>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <button type="submit" class="btn btn-primary" id="btn-guardar-ficha-personal">
            <i class="fas fa-save mr-1"></i> Guardar ficha
        </button>
    </div>
</form>

<div class="modal fade" id="modal-ficha-personal-preview" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Revision de ficha de personal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="ficha-personal-preview-content"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btn-imprimir-ficha-preview">
                    <i class="fas fa-file-pdf mr-1"></i> Imprimir ficha PDF
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const apiBase = '../';
    const csrfToken = <?= json_encode($csrfToken ?? '', JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

    const estadoFicha = document.getElementById('estado-ficha-personal');
    const form = document.getElementById('form-ficha-personal');
    const btnGuardar = document.getElementById('btn-guardar-ficha-personal');
    const modalPreview = document.getElementById('modal-ficha-personal-preview');
    const previewContent = document.getElementById('ficha-personal-preview-content');
    const btnImprimirPreview = document.getElementById('btn-imprimir-ficha-preview');

    const fotoInput = document.getElementById('foto-personal');
    const fotoLabel = document.getElementById('foto-personal-label');
    const fotoPreview = document.getElementById('preview-foto-personal');

    const anioActualInput = document.getElementById('anio-actual');
    const anioEscolarIdInput = document.getElementById('anio-escolar-id');
    const departamentoSelect = document.getElementById('departamento-id');
    const cargoSelect = document.getElementById('cargo-id');
    const condicionLaboralSelect = document.getElementById('condicion-laboral-id');

    const tbodyConcluidos = document.getElementById('tbody-estudios-concluidos');
    const tbodyProceso = document.getElementById('tbody-estudios-proceso');
    const btnAgregarConcluido = document.getElementById('btn-agregar-concluido');
    const btnAgregarProceso = document.getElementById('btn-agregar-proceso');

    const state = {
        anios: [],
        departamentos: [],
        cargos: [],
        condicionesLaborales: [],
    };
    let fichaPrintHtmlActual = '';

    function setStatus(message, type) {
        const cssByType = {
            info: 'alert alert-info mb-3',
            success: 'alert alert-success mb-3',
            warning: 'alert alert-warning mb-3',
            danger: 'alert alert-danger mb-3',
        };

        estadoFicha.className = cssByType[type] || cssByType.info;
        estadoFicha.textContent = message;
    }

    function escHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function toNullableString(value) {
        const clean = String(value || '').trim();
        return clean === '' ? null : clean;
    }

    function toNullableYear(value) {
        const clean = String(value || '').trim();
        if (clean === '') {
            return null;
        }

        const year = Number(clean);
        if (!Number.isInteger(year) || year < 1900 || year > 2100) {
            throw new Error('Los anos deben estar entre 1900 y 2100.');
        }

        return year;
    }

    function toNullableInt(value) {
        const clean = String(value || '').trim();
        if (clean === '') {
            return null;
        }

        const number = Number(clean);
        if (!Number.isInteger(number) || number < 0) {
            throw new Error('El valor numerico no es valido.');
        }

        return number;
    }

    function toPositiveInt(value) {
        const number = Number(value || 0);
        return Number.isInteger(number) && number > 0 ? number : 0;
    }

    function formatDateIso(value) {
        const text = String(value || '').trim();
        if (!/^\d{4}-\d{2}-\d{2}$/.test(text)) {
            return '-';
        }

        const parts = text.split('-');
        return parts[2] + '/' + parts[1] + '/' + parts[0];
    }

    function selectedText(selectId) {
        const select = document.getElementById(selectId);
        if (!(select instanceof HTMLSelectElement)) {
            return '-';
        }

        const option = select.options[select.selectedIndex];
        const text = option ? String(option.textContent || '').trim() : '';
        return text === '' ? '-' : text;
    }

    function getTodayISO() {
        const d = new Date();
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return y + '-' + m + '-' + day;
    }

    function resolveCurrentAnio(anios) {
        if (!Array.isArray(anios) || anios.length === 0) {
            return null;
        }

        const today = getTodayISO();
        const vigente = anios.find((item) => {
            const start = String(item.fecha_inicio || '').trim();
            const end = String(item.fecha_fin || '').trim();
            if (start === '' || end === '') {
                return false;
            }

            return start <= today && end >= today;
        });

        if (vigente) {
            return vigente;
        }

        return anios[0] || null;
    }

    function clearSelectOptions(select, placeholder) {
        select.innerHTML = '<option value="">' + escHtml(placeholder || 'Selecciona') + '</option>';
    }

    function fillSelectWithRows(select, rows, idField, textField) {
        clearSelectOptions(select, 'Selecciona');
        rows.forEach((row) => {
            if (Number(row.estado ?? 1) !== 1) {
                return;
            }

            const id = Number(row[idField] || 0);
            if (id <= 0) {
                return;
            }

            const option = document.createElement('option');
            option.value = String(id);
            option.textContent = String(row[textField] || '').trim() || ('ID ' + id);
            select.appendChild(option);
        });
    }

    function buildConcluidoRowHtml() {
        return ''
            + '<tr>'
            + '<td><input type="text" class="form-control form-control-sm" data-field="nivel_academico" maxlength="150"></td>'
            + '<td><input type="text" class="form-control form-control-sm" data-field="entidad" maxlength="255"></td>'
            + '<td><input type="text" class="form-control form-control-sm" data-field="titulo" maxlength="255"></td>'
            + '<td><input type="number" class="form-control form-control-sm" data-field="anio_inicio" min="1900" max="2100"></td>'
            + '<td><input type="number" class="form-control form-control-sm" data-field="anio_fin" min="1900" max="2100"></td>'
            + '<td><input type="text" class="form-control form-control-sm" data-field="numero_registro" maxlength="100"></td>'
            + '<td><input type="text" class="form-control form-control-sm" data-field="numero_folio" maxlength="100"></td>'
            + '<td><input type="text" class="form-control form-control-sm" data-field="pais" maxlength="100"></td>'
            + '<td><input type="text" class="form-control form-control-sm" data-field="ciudad" maxlength="100"></td>'
            + '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" data-remove-row="1">Quitar</button></td>'
            + '</tr>';
    }

    function buildProcesoRowHtml() {
        return ''
            + '<tr>'
            + '<td><input type="text" class="form-control form-control-sm" data-field="area_estudio" maxlength="150"></td>'
            + '<td><input type="text" class="form-control form-control-sm" data-field="entidad" maxlength="255"></td>'
            + '<td><input type="text" class="form-control form-control-sm" data-field="titulo" maxlength="255"></td>'
            + '<td><input type="number" class="form-control form-control-sm" data-field="anio_inicio" min="1900" max="2100"></td>'
            + '<td><input type="text" class="form-control form-control-sm" data-field="duracion" maxlength="100"></td>'
            + '<td><input type="number" class="form-control form-control-sm" data-field="horas" min="0"></td>'
            + '<td><input type="text" class="form-control form-control-sm" data-field="pais" maxlength="100"></td>'
            + '<td><input type="text" class="form-control form-control-sm" data-field="ciudad" maxlength="100"></td>'
            + '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" data-remove-row="1">Quitar</button></td>'
            + '</tr>';
    }

    function appendConcluidoRow() {
        tbodyConcluidos.insertAdjacentHTML('beforeend', buildConcluidoRowHtml());
    }

    function appendProcesoRow() {
        tbodyProceso.insertAdjacentHTML('beforeend', buildProcesoRowHtml());
    }

    function ensureAtLeastOneRow(tbody, appendFn) {
        if (!tbody.querySelector('tr')) {
            appendFn();
        }
    }

    function removeRowIfPossible(button, tbody, appendFn) {
        const tr = button.closest('tr');
        if (!tr) {
            return;
        }

        tr.remove();
        ensureAtLeastOneRow(tbody, appendFn);
    }

    function readRowData(tr) {
        const data = {};
        tr.querySelectorAll('[data-field]').forEach((input) => {
            const key = String(input.getAttribute('data-field') || '').trim();
            if (key !== '') {
                data[key] = String(input.value || '').trim();
            }
        });
        return data;
    }

    function hasAnyValue(obj) {
        return Object.keys(obj).some((key) => String(obj[key] || '').trim() !== '');
    }

    function readConcluidosRows() {
        const rows = [];
        tbodyConcluidos.querySelectorAll('tr').forEach((tr) => {
            const raw = readRowData(tr);
            if (!hasAnyValue(raw)) {
                return;
            }

            rows.push({
                nivel_academico: toNullableString(raw.nivel_academico),
                entidad: toNullableString(raw.entidad),
                titulo: toNullableString(raw.titulo),
                anio_inicio: toNullableYear(raw.anio_inicio),
                anio_fin: toNullableYear(raw.anio_fin),
                numero_registro: toNullableString(raw.numero_registro),
                numero_folio: toNullableString(raw.numero_folio),
                pais: toNullableString(raw.pais),
                ciudad: toNullableString(raw.ciudad),
            });
        });

        return rows;
    }

    function readProcesoRows() {
        const rows = [];
        tbodyProceso.querySelectorAll('tr').forEach((tr) => {
            const raw = readRowData(tr);
            if (!hasAnyValue(raw)) {
                return;
            }

            rows.push({
                area_estudio: toNullableString(raw.area_estudio),
                entidad: toNullableString(raw.entidad),
                titulo: toNullableString(raw.titulo),
                anio_inicio: toNullableYear(raw.anio_inicio),
                duracion: toNullableString(raw.duracion),
                horas: toNullableInt(raw.horas),
                pais: toNullableString(raw.pais),
                ciudad: toNullableString(raw.ciudad),
            });
        });

        return rows;
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

    async function apiDestroy(resource, criteria) {
        const response = await fetch(apiBase + '?resource=' + resource + '&action=destroy', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ criteria }),
        });

        const json = await response.json();
        if (!json.success) {
            throw new Error(json.message || ('No se pudo eliminar en ' + resource));
        }

        return json.data || {};
    }

    async function uploadFotoPersonalIfNeeded() {
        const file = fotoInput.files && fotoInput.files.length > 0 ? fotoInput.files[0] : null;
        if (!file) {
            return '';
        }

        const maxBytes = 8 * 1024 * 1024;
        if (Number(file.size || 0) > maxBytes) {
            throw new Error('La foto excede 8 MB.');
        }

        const formData = new FormData();
        formData.append('foto', file);
        formData.append('foto_personal', file);
        formData.append('_csrf', csrfToken);

        const response = await fetch('upload-personal-foto.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-Token': csrfToken,
            },
            body: formData,
        });

        const text = await response.text();
        let json = null;
        try {
            json = JSON.parse(text);
        } catch (error) {
            throw new Error('Respuesta invalida al subir foto de personal.');
        }

        if (!json.success) {
            throw new Error(json.message || 'No se pudo subir la foto del empleado.');
        }

        return String(json.path || '').trim();
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

    function syncFotoPreview() {
        const file = fotoInput.files && fotoInput.files[0] ? fotoInput.files[0] : null;
        if (!file) {
            fotoLabel.textContent = 'Seleccionar imagen';
            fotoPreview.innerHTML = 'Sin foto';
            return;
        }

        fotoLabel.textContent = file.name;
        const reader = new FileReader();
        reader.onload = function (event) {
            const src = String(event && event.target ? event.target.result || '' : '');
            if (src === '') {
                fotoPreview.innerHTML = 'Sin vista previa';
                return;
            }

            fotoPreview.innerHTML = '<img src="' + escHtml(src) + '" alt="Foto del empleado" class="img-fluid" style="width:100%;height:100%;object-fit:cover;">';
        };
        reader.readAsDataURL(file);
    }

    function showPreviewModal() {
        if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.modal === 'function') {
            window.jQuery(modalPreview).modal('show');
        }
    }

    function buildFichaPreviewHtml() {
        const concluidos = readConcluidosRows();
        const proceso = readProcesoRows();
        const fotoImg = fotoPreview.querySelector('img');
        const fotoSrc = fotoImg ? String(fotoImg.getAttribute('src') || '').trim() : '';
        const fotoHtml = fotoSrc !== ''
            ? '<img src="' + escHtml(fotoSrc) + '" alt="Foto" class="img-fluid" style="width:100%;height:100%;object-fit:cover;">'
            : '<span class="text-muted">Sin foto</span>';

        const fullName = [
            document.getElementById('primer-nombre').value,
            document.getElementById('segundo-nombre').value,
            document.getElementById('primer-apellido').value,
            document.getElementById('segundo-apellido').value,
        ].map((v) => String(v || '').trim()).filter(Boolean).join(' ');

        return ''
            + '<div class="row">'
            + '<div class="col-md-3 mb-3">'
            + '<div class="border rounded d-flex align-items-center justify-content-center bg-light" style="height:220px;overflow:hidden;">' + fotoHtml + '</div>'
            + '</div>'
            + '<div class="col-md-9">'
            + '<div class="card shadow-sm mb-3"><div class="card-header py-2"><strong>Informacion basica</strong></div>'
            + '<div class="card-body p-2">'
            + '<div><strong>Nombre:</strong> ' + escHtml(fullName || '-') + '</div>'
            + '<div><strong>Cedula/Pasaporte:</strong> ' + escHtml(document.getElementById('cedula-pasaporte').value || '-') + '</div>'
            + '<div><strong>Fecha nacimiento:</strong> ' + escHtml(formatDateIso(document.getElementById('fecha-nacimiento').value || '')) + '</div>'
            + '<div><strong>Sexo:</strong> ' + escHtml(selectedText('sexo')) + '</div>'
            + '<div><strong>Estado civil:</strong> ' + escHtml(selectedText('estado-civil')) + '</div>'
            + '<div><strong>Nacionalidad:</strong> ' + escHtml(selectedText('nacionalidad')) + '</div>'
            + '</div></div>'
            + '<div class="card shadow-sm mb-3"><div class="card-header py-2"><strong>Puesto para el ano actual</strong></div>'
            + '<div class="card-body p-2">'
            + '<div><strong>Ano escolar:</strong> ' + escHtml(document.getElementById('anio-actual').value || '-') + '</div>'
            + '<div><strong>Departamento:</strong> ' + escHtml(selectedText('departamento-id')) + '</div>'
            + '<div><strong>Cargo:</strong> ' + escHtml(selectedText('cargo-id')) + '</div>'
            + '<div><strong>Condicion laboral:</strong> ' + escHtml(selectedText('condicion-laboral-id')) + '</div>'
            + '<div><strong>Estado en el centro:</strong> ' + escHtml(selectedText('estado-centro')) + '</div>'
            + '</div></div>'
            + '</div>'
            + '</div>'
            + '<div class="card shadow-sm mb-3"><div class="card-header py-2"><strong>Contacto y direccion</strong></div>'
            + '<div class="card-body p-2">'
            + '<div><strong>Telefono:</strong> ' + escHtml(document.getElementById('telefono').value || '-') + '</div>'
            + '<div><strong>Celular:</strong> ' + escHtml(document.getElementById('celular').value || '-') + '</div>'
            + '<div><strong>WhatsApp:</strong> ' + escHtml(document.getElementById('whatsapp').value || '-') + '</div>'
            + '<div><strong>Direccion:</strong> ' + escHtml(document.getElementById('calle-numero').value || '-') + ', ' + escHtml(document.getElementById('barrio').value || '-') + ', ' + escHtml(document.getElementById('municipio').value || '-') + '</div>'
            + '</div></div>'
            + '<div class="card shadow-sm mb-3"><div class="card-header py-2"><strong>Estudios concluidos</strong></div><div class="card-body p-2">'
            + '<div>' + escHtml(String(concluidos.length)) + ' registro(s)</div>'
            + '</div></div>'
            + '<div class="card shadow-sm"><div class="card-header py-2"><strong>Estudios en proceso</strong></div><div class="card-body p-2">'
            + '<div>' + escHtml(String(proceso.length)) + ' registro(s)</div>'
            + '</div></div>';
    }

    function buildFichaPrintHtml() {
        const concluidos = readConcluidosRows();
        const proceso = readProcesoRows();

        const fotoImg = fotoPreview.querySelector('img');
        const fotoSrc = fotoImg ? String(fotoImg.getAttribute('src') || '').trim() : '';
        const fotoHtml = fotoSrc !== ''
            ? '<img src="' + escHtml(fotoSrc) + '" alt="Foto" style="width:100%;height:100%;object-fit:cover;">'
            : '<div style="font-size:12px;color:#6b7280;display:flex;align-items:center;justify-content:center;height:100%;">Sin foto</div>';

        const estudiosConcluidosRows = concluidos.length > 0
            ? concluidos.map((row, idx) => {
                return ''
                    + '<tr>'
                    + '<td>' + (idx + 1) + '</td>'
                    + '<td>' + escHtml(String(row.nivel_academico || '-')) + '</td>'
                    + '<td>' + escHtml(String(row.entidad || '-')) + '</td>'
                    + '<td>' + escHtml(String(row.titulo || '-')) + '</td>'
                    + '<td>' + escHtml(String(row.anio_inicio ?? '-')) + '</td>'
                    + '<td>' + escHtml(String(row.anio_fin ?? '-')) + '</td>'
                    + '<td>' + escHtml(String(row.numero_registro || '-')) + '</td>'
                    + '<td>' + escHtml(String(row.numero_folio || '-')) + '</td>'
                    + '<td>' + escHtml(String(row.pais || '-')) + '</td>'
                    + '<td>' + escHtml(String(row.ciudad || '-')) + '</td>'
                    + '</tr>';
            }).join('')
            : '<tr><td colspan="10" class="muted">Sin registros</td></tr>';

        const estudiosProcesoRows = proceso.length > 0
            ? proceso.map((row, idx) => {
                return ''
                    + '<tr>'
                    + '<td>' + (idx + 1) + '</td>'
                    + '<td>' + escHtml(String(row.area_estudio || '-')) + '</td>'
                    + '<td>' + escHtml(String(row.entidad || '-')) + '</td>'
                    + '<td>' + escHtml(String(row.titulo || '-')) + '</td>'
                    + '<td>' + escHtml(String(row.anio_inicio ?? '-')) + '</td>'
                    + '<td>' + escHtml(String(row.duracion || '-')) + '</td>'
                    + '<td>' + escHtml(String(row.horas ?? '-')) + '</td>'
                    + '<td>' + escHtml(String(row.pais || '-')) + '</td>'
                    + '<td>' + escHtml(String(row.ciudad || '-')) + '</td>'
                    + '</tr>';
            }).join('')
            : '<tr><td colspan="9" class="muted">Sin registros</td></tr>';

        const salario = Number(document.getElementById('salario').value || 0);

        return ''
            + '<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Ficha de personal</title>'
            + '<style>'
            + 'body{font-family:Arial,Helvetica,sans-serif;margin:10mm;color:#111827;background:#fff;}'
            + '.actions{margin-bottom:10px;}'
            + '.actions button{padding:6px 12px;font-size:12px;margin-right:6px;}'
            + '.head{border-bottom:2px solid #111827;padding-bottom:8px;margin-bottom:12px;}'
            + '.head h1{margin:0 0 4px;font-size:20px;}'
            + '.grid{display:grid;grid-template-columns:40mm 1fr;gap:12px;align-items:start;margin-bottom:12px;}'
            + '.photo{width:40mm;height:50mm;border:1px solid #d1d5db;overflow:hidden;border-radius:4px;background:#f9fafb;}'
            + '.box{border:1px solid #d1d5db;border-radius:6px;margin-bottom:10px;}'
            + '.box h3{margin:0;padding:8px 10px;background:#f3f4f6;font-size:13px;text-transform:uppercase;}'
            + '.box table{width:100%;border-collapse:collapse;font-size:12px;}'
            + '.box th,.box td{border:1px solid #e5e7eb;padding:6px;text-align:left;vertical-align:top;}'
            + '.box th{width:32%;background:#f9fafb;}'
            + '.tbl th,.tbl td{font-size:11px;}'
            + '.tbl th{background:#f3f4f6;text-transform:uppercase;}'
            + '.muted{color:#6b7280;text-align:center;}'
            + 'ul{margin:8px 0 0 18px;padding:0;}'
            + '@media print {.actions{display:none;} body{margin:8mm;}}'
            + '</style></head><body>'
            + '<div class="actions"><button onclick="window.print()">Imprimir</button><button onclick="window.close()">Cerrar</button></div>'
            + '<div class="head"><h1>Ficha de personal</h1><div>Generada el ' + escHtml(formatDateIso(getTodayISO())) + '</div></div>'
            + '<div class="grid">'
            + '<div class="photo">' + fotoHtml + '</div>'
            + '<div class="box"><h3>Informacion basica</h3><table>'
            + '<tr><th>Cedula/Pasaporte</th><td>' + escHtml(String(document.getElementById('cedula-pasaporte').value || '-')) + '</td></tr>'
            + '<tr><th>Nombre completo</th><td>' + escHtml(String(document.getElementById('primer-nombre').value || '') + ' ' + String(document.getElementById('segundo-nombre').value || '') + ' ' + String(document.getElementById('primer-apellido').value || '') + ' ' + String(document.getElementById('segundo-apellido').value || '')) + '</td></tr>'
            + '<tr><th>Fecha nacimiento</th><td>' + escHtml(formatDateIso(document.getElementById('fecha-nacimiento').value || '')) + '</td></tr>'
            + '<tr><th>Sexo</th><td>' + escHtml(selectedText('sexo')) + '</td></tr>'
            + '<tr><th>Estado civil</th><td>' + escHtml(selectedText('estado-civil')) + '</td></tr>'
            + '<tr><th>Nacionalidad</th><td>' + escHtml(selectedText('nacionalidad')) + '</td></tr>'
            + '</table></div>'
            + '</div>'
            + '<div class="box"><h3>Puesto para el ano actual</h3><table>'
            + '<tr><th>Ano escolar</th><td>' + escHtml(String(document.getElementById('anio-actual').value || '-')) + '</td></tr>'
            + '<tr><th>Departamento</th><td>' + escHtml(selectedText('departamento-id')) + '</td></tr>'
            + '<tr><th>Cargo</th><td>' + escHtml(selectedText('cargo-id')) + '</td></tr>'
            + '<tr><th>Condicion laboral</th><td>' + escHtml(selectedText('condicion-laboral-id')) + '</td></tr>'
            + '<tr><th>Estado en el centro</th><td>' + escHtml(selectedText('estado-centro')) + '</td></tr>'
            + '</table></div>'
            + '<div class="box"><h3>Informacion de contacto</h3><table>'
            + '<tr><th>Telefono</th><td>' + escHtml(String(document.getElementById('telefono').value || '-')) + '</td></tr>'
            + '<tr><th>Celular</th><td>' + escHtml(String(document.getElementById('celular').value || '-')) + '</td></tr>'
            + '<tr><th>WhatsApp</th><td>' + escHtml(String(document.getElementById('whatsapp').value || '-')) + '</td></tr>'
            + '</table></div>'
            + '<div class="box"><h3>Direccion</h3><table>'
            + '<tr><th>Provincia</th><td>' + escHtml(String(document.getElementById('provincia').value || '-')) + '</td></tr>'
            + '<tr><th>Municipio</th><td>' + escHtml(String(document.getElementById('municipio').value || '-')) + '</td></tr>'
            + '<tr><th>Distrito municipal</th><td>' + escHtml(String(document.getElementById('distrito-municipal').value || '-')) + '</td></tr>'
            + '<tr><th>Seccion</th><td>' + escHtml(String(document.getElementById('seccion-direccion').value || '-')) + '</td></tr>'
            + '<tr><th>Barrio</th><td>' + escHtml(String(document.getElementById('barrio').value || '-')) + '</td></tr>'
            + '<tr><th>Sub barrio</th><td>' + escHtml(String(document.getElementById('sub-barrio').value || '-')) + '</td></tr>'
            + '<tr><th>Calle y numero</th><td>' + escHtml(String(document.getElementById('calle-numero').value || '-')) + '</td></tr>'
            + '</table></div>'
            + '<div class="box"><h3>Estudios concluidos</h3><table class="tbl"><thead><tr><th>#</th><th>Nivel</th><th>Entidad</th><th>Titulo</th><th>Ano inicio</th><th>Ano fin</th><th>Registro</th><th>Folio</th><th>Pais</th><th>Ciudad</th></tr></thead><tbody>' + estudiosConcluidosRows + '</tbody></table></div>'
            + '<div class="box"><h3>Estudios en proceso</h3><table class="tbl"><thead><tr><th>#</th><th>Area</th><th>Entidad</th><th>Titulo</th><th>Ano inicio</th><th>Duracion</th><th>Horas</th><th>Pais</th><th>Ciudad</th></tr></thead><tbody>' + estudiosProcesoRows + '</tbody></table></div>'
            + '<div class="box"><h3>Datos laborales</h3><table>'
            + '<tr><th>Fecha ingreso</th><td>' + escHtml(formatDateIso(document.getElementById('fecha-ingreso').value || '')) + '</td></tr>'
            + '<tr><th>Tanda</th><td>' + escHtml(selectedText('tanda-laboral')) + '</td></tr>'
            + '<tr><th>Salario</th><td>RD$ ' + escHtml(Number.isFinite(salario) ? salario.toFixed(2) : '0.00') + '</td></tr>'
            + '<tr><th>Banco</th><td>' + escHtml(String(document.getElementById('banco').value || '-')) + '</td></tr>'
            + '<tr><th>Numero de cuenta</th><td>' + escHtml(String(document.getElementById('numero-cuenta-bancaria').value || '-')) + '</td></tr>'
            + '</table></div>'
            + '<div class="box"><h3>Terminos y condiciones</h3><div style="padding:8px 10px;font-size:12px;">'
            + '<ul>'
            + '<li>El pago del salario sera los dias 25 de cada mes, los pagos son desde agosto hasta junio para un total de 11 meses.</li>'
            + '<li>Al firmar esta ficha de personal acepto los terminos y condiciones establecidos por la institucion y el reglamento interno.</li>'
            + '</ul>'
            + '<p><strong>Acepta terminos:</strong> ' + (document.getElementById('acepta-terminos').checked ? 'Si' : 'No') + '</p>'
            + '<p><strong>Empleado activo:</strong> ' + (document.getElementById('empleado-activo').checked ? 'Si' : 'No') + '</p>'
            + '</div></div>'
            + '</body></html>';
    }

    function openPrintableFicha(html) {
        const win = window.open('', '_blank');
        if (!win) {
            throw new Error('No se pudo abrir la ventana de impresion.');
        }

        win.document.open();
        win.document.write(html);
        win.document.close();
    }

    async function rollbackCreatedEntities(created) {
        const actions = [];

        created.estudiosProcesoIds.slice().reverse().forEach((id) => {
            actions.push(() => apiDestroy('estudios_proceso', { id: id }));
        });

        created.estudiosConcluidosIds.slice().reverse().forEach((id) => {
            actions.push(() => apiDestroy('estudios_concluidos', { id: id }));
        });

        if (created.datosLaboralesId > 0) {
            actions.push(() => apiDestroy('datos_laborales', { id: created.datosLaboralesId }));
        }

        if (created.direccionId > 0) {
            actions.push(() => apiDestroy('direcciones_personal', { id: created.direccionId }));
        }

        if (created.asignacionId > 0) {
            actions.push(() => apiDestroy('asignaciones_laborales', { id: created.asignacionId }));
        }

        if (created.personalId > 0) {
            actions.push(() => apiDestroy('personal', { id: created.personalId }));
        }

        for (const action of actions) {
            try {
                await action();
            } catch (error) {
                // Ignorar errores de rollback para no ocultar el principal.
            }
        }
    }

    function readCorePayload() {
        const anioEscolarId = toPositiveInt(anioEscolarIdInput.value);
        if (anioEscolarId <= 0) {
            throw new Error('No hay ano escolar vigente para asignar el puesto.');
        }

        const departamentoId = toPositiveInt(departamentoSelect.value);
        const cargoId = toPositiveInt(cargoSelect.value);
        const condicionLaboralId = toPositiveInt(condicionLaboralSelect.value);

        if (departamentoId <= 0 || cargoId <= 0 || condicionLaboralId <= 0) {
            throw new Error('Debes seleccionar departamento, cargo y condicion laboral.');
        }

        if (!document.getElementById('acepta-terminos').checked) {
            throw new Error('Debes aceptar los terminos y condiciones.');
        }

        const personalPayload = {
            cedula_pasaporte: String(document.getElementById('cedula-pasaporte').value || '').trim(),
            primer_nombre: String(document.getElementById('primer-nombre').value || '').trim(),
            segundo_nombre: toNullableString(document.getElementById('segundo-nombre').value),
            primer_apellido: String(document.getElementById('primer-apellido').value || '').trim(),
            segundo_apellido: toNullableString(document.getElementById('segundo-apellido').value),
            fecha_nacimiento: toNullableString(document.getElementById('fecha-nacimiento').value),
            sexo: toNullableString(document.getElementById('sexo').value),
            estado_civil: toNullableString(document.getElementById('estado-civil').value),
            nacionalidad: toNullableString(document.getElementById('nacionalidad').value),
            telefono: toNullableString(document.getElementById('telefono').value),
            celular: toNullableString(document.getElementById('celular').value),
            whatsapp: toNullableString(document.getElementById('whatsapp').value),
            estado: Number(document.getElementById('estado-centro').value || '1') === 1 ? 1 : 0,
        };

        if (personalPayload.cedula_pasaporte === '' || personalPayload.primer_nombre === '' || personalPayload.primer_apellido === '') {
            throw new Error('Cedula/Pasaporte, primer nombre y primer apellido son obligatorios.');
        }

        const asignacionPayload = {
            anio_escolar_id: anioEscolarId,
            departamento_id: departamentoId,
            cargo_id: cargoId,
            condicion_laboral_id: condicionLaboralId,
            estado: Number(document.getElementById('estado-centro').value || '1') === 1 ? 1 : 0,
        };

        const direccionPayload = {
            provincia: toNullableString(document.getElementById('provincia').value),
            municipio: toNullableString(document.getElementById('municipio').value),
            distrito_municipal: toNullableString(document.getElementById('distrito-municipal').value),
            seccion: toNullableString(document.getElementById('seccion-direccion').value),
            barrio: toNullableString(document.getElementById('barrio').value),
            sub_barrio: toNullableString(document.getElementById('sub-barrio').value),
            calle_numero: toNullableString(document.getElementById('calle-numero').value),
        };

        const datosLaboralesPayload = {
            fecha_ingreso: toNullableString(document.getElementById('fecha-ingreso').value),
            tanda: toNullableString(document.getElementById('tanda-laboral').value),
            salario: Number(document.getElementById('salario').value || 0),
            banco: toNullableString(document.getElementById('banco').value),
            numero_cuenta_bancaria: toNullableString(document.getElementById('numero-cuenta-bancaria').value),
            acepta_terminos: document.getElementById('acepta-terminos').checked ? 1 : 0,
            empleado_activo: document.getElementById('empleado-activo').checked ? 1 : 0,
        };

        if (!Number.isFinite(datosLaboralesPayload.salario) || datosLaboralesPayload.salario < 0) {
            throw new Error('El salario no es valido.');
        }

        return {
            personalPayload,
            asignacionPayload,
            direccionPayload,
            datosLaboralesPayload,
            estudiosConcluidos: readConcluidosRows(),
            estudiosProceso: readProcesoRows(),
        };
    }

    function resetFormState() {
        form.reset();
        fotoPreview.innerHTML = 'Sin foto';
        fotoLabel.textContent = 'Seleccionar imagen';

        tbodyConcluidos.innerHTML = '';
        tbodyProceso.innerHTML = '';
        appendConcluidoRow();
        appendProcesoRow();

        const anioActual = resolveCurrentAnio(state.anios);
        if (anioActual) {
            anioActualInput.value = String(anioActual.nombre || '');
            anioEscolarIdInput.value = String(anioActual.id || '');
        }

        document.getElementById('salario').value = '0';
        document.getElementById('empleado-activo').checked = true;
    }

    async function onSubmit(event) {
        event.preventDefault();

        const original = btnGuardar.innerHTML;
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...';

        const created = {
            personalId: 0,
            asignacionId: 0,
            direccionId: 0,
            datosLaboralesId: 0,
            estudiosConcluidosIds: [],
            estudiosProcesoIds: [],
        };

        try {
            const payload = readCorePayload();
            setStatus('Guardando ficha de personal...', 'info');

            const fotoPath = await uploadFotoPersonalIfNeeded();
            if (fotoPath !== '') {
                payload.personalPayload.foto = fotoPath;
                const fotoGuardadaUrl = resolveStoredAssetUrl(fotoPath);
                fotoPreview.innerHTML = '<img src="' + escHtml(fotoGuardadaUrl) + '" alt="Foto del empleado" class="img-fluid" style="width:100%;height:100%;object-fit:cover;">';
            }

            const personalResp = await apiStore('personal', payload.personalPayload);
            created.personalId = toPositiveInt(personalResp.id);
            if (created.personalId <= 0) {
                throw new Error('No se pudo obtener el ID del personal creado.');
            }

            const asignacionResp = await apiStore('asignaciones_laborales', Object.assign({}, payload.asignacionPayload, {
                personal_id: created.personalId,
            }));
            created.asignacionId = toPositiveInt(asignacionResp.id);

            const hasDireccion = Object.values(payload.direccionPayload).some((value) => value !== null);
            if (hasDireccion) {
                const direccionResp = await apiStore('direcciones_personal', Object.assign({}, payload.direccionPayload, {
                    personal_id: created.personalId,
                }));
                created.direccionId = toPositiveInt(direccionResp.id);
            }

            const datosResp = await apiStore('datos_laborales', Object.assign({}, payload.datosLaboralesPayload, {
                personal_id: created.personalId,
            }));
            created.datosLaboralesId = toPositiveInt(datosResp.id);

            for (const estudio of payload.estudiosConcluidos) {
                const resp = await apiStore('estudios_concluidos', Object.assign({}, estudio, {
                    personal_id: created.personalId,
                }));
                const id = toPositiveInt(resp.id);
                if (id > 0) {
                    created.estudiosConcluidosIds.push(id);
                }
            }

            for (const estudio of payload.estudiosProceso) {
                const resp = await apiStore('estudios_proceso', Object.assign({}, estudio, {
                    personal_id: created.personalId,
                }));
                const id = toPositiveInt(resp.id);
                if (id > 0) {
                    created.estudiosProcesoIds.push(id);
                }
            }

            fichaPrintHtmlActual = buildFichaPrintHtml();
            previewContent.innerHTML = buildFichaPreviewHtml();
            showPreviewModal();
            setStatus('Ficha de personal guardada correctamente. Revisa el modal y luego imprime el PDF.', 'success');
            resetFormState();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } catch (error) {
            if (created.personalId > 0) {
                await rollbackCreatedEntities(created);
            }

            setStatus(error.message || 'No se pudo guardar la ficha de personal.', 'danger');
        } finally {
            btnGuardar.disabled = false;
            btnGuardar.innerHTML = original;
        }
    }

    async function init() {
        appendConcluidoRow();
        appendProcesoRow();

        try {
            const [anios, departamentos, cargos, condiciones] = await Promise.all([
                apiGet('anios_escolares'),
                apiGet('departamentos'),
                apiGet('cargos'),
                apiGet('condiciones_laborales'),
            ]);

            state.anios = anios;
            state.departamentos = departamentos;
            state.cargos = cargos;
            state.condicionesLaborales = condiciones;

            fillSelectWithRows(departamentoSelect, state.departamentos, 'id', 'nombre');
            fillSelectWithRows(cargoSelect, state.cargos, 'id', 'nombre');
            fillSelectWithRows(condicionLaboralSelect, state.condicionesLaborales, 'id', 'nombre');

            if (departamentoSelect.options.length <= 1 || cargoSelect.options.length <= 1 || condicionLaboralSelect.options.length <= 1) {
                setStatus('Debes crear Departamentos, Cargos y Condiciones laborales en Configuracion del sistema, pestana Catalogos RRHH.', 'warning');
                return;
            }

            const anioActual = resolveCurrentAnio(state.anios);
            if (anioActual) {
                anioActualInput.value = String(anioActual.nombre || '');
                anioEscolarIdInput.value = String(anioActual.id || '');
            } else {
                anioActualInput.value = 'No disponible';
                anioEscolarIdInput.value = '';
                setStatus('No hay anos escolares cargados. Debes crear uno para registrar personal.', 'warning');
                return;
            }

            setStatus('Completa la ficha y presiona Guardar ficha.', 'info');
        } catch (error) {
            setStatus(error.message || 'No se pudieron cargar los catalogos.', 'danger');
        }
    }

    fotoInput.addEventListener('change', syncFotoPreview);
    btnImprimirPreview.addEventListener('click', function () {
        try {
            if (fichaPrintHtmlActual === '') {
                throw new Error('No hay una ficha lista para imprimir.');
            }

            openPrintableFicha(fichaPrintHtmlActual);
        } catch (error) {
            setStatus(error.message || 'No se pudo generar el PDF de la ficha.', 'danger');
        }
    });
    btnAgregarConcluido.addEventListener('click', appendConcluidoRow);
    btnAgregarProceso.addEventListener('click', appendProcesoRow);

    tbodyConcluidos.addEventListener('click', function (event) {
        const target = event.target instanceof HTMLElement ? event.target.closest('[data-remove-row="1"]') : null;
        if (!target) {
            return;
        }

        removeRowIfPossible(target, tbodyConcluidos, appendConcluidoRow);
    });

    tbodyProceso.addEventListener('click', function (event) {
        const target = event.target instanceof HTMLElement ? event.target.closest('[data-remove-row="1"]') : null;
        if (!target) {
            return;
        }

        removeRowIfPossible(target, tbodyProceso, appendProcesoRow);
    });

    form.addEventListener('submit', onSubmit);

    init();
})();
</script>
