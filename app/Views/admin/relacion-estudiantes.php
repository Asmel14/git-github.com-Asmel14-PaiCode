<?php

declare(strict_types=1);
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Relacion de estudiantes inscritos</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-flex flex-column" style="gap: 12px;">
            <h6 class="m-0 font-weight-bold text-primary">Filtros de inscripcion</h6>
            <div class="d-flex flex-column flex-lg-row" style="gap: 12px;">
                <div style="min-width: 220px;">
                    <label for="filtro-anio" class="small font-weight-bold text-muted mb-1 d-block">Ano escolar</label>
                    <select id="filtro-anio" class="form-control">
                        <option value="">Cargando anos...</option>
                    </select>
                </div>
                <div style="min-width: 220px;">
                    <label for="filtro-grado" class="small font-weight-bold text-muted mb-1 d-block">Listado de grados</label>
                    <select id="filtro-grado" class="form-control">
                        <option value="">Cargando grados...</option>
                    </select>
                </div>
                <div style="min-width: 240px;">
                    <label for="filtro-nombre" class="small font-weight-bold text-muted mb-1 d-block">Filtrar por nombre</label>
                    <input id="filtro-nombre" type="text" class="form-control" placeholder="Escribe nombre del estudiante">
                </div>
                <div style="min-width: 200px;">
                    <label for="filtro-sigerd" class="small font-weight-bold text-muted mb-1 d-block">Filtrar por ID SIGERD</label>
                    <input id="filtro-sigerd" type="text" class="form-control" placeholder="Ej: SIG-001">
                </div>
                <div class="align-self-end">
                    <button type="button" class="btn btn-danger" id="btn-reporte-pdf">
                        <i class="fas fa-file-pdf mr-1"></i> Reporte PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="estado-carga" class="alert alert-info mb-3">Cargando relacion de estudiantes...</div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="tabla-relacion-estudiantes" width="100%" cellspacing="0">
                <thead class="thead-light">
                <tr>
                    <th>ID Estudiante</th>
                    <th>Estudiante</th>
                    <th>ID SIGERD</th>
                    <th>Ano escolar</th>
                    <th>Edad</th>
                    <th>Grado</th>
                    <th>Seccion</th>
                    <th>Tanda</th>
                    <th>Fecha inscripcion</th>
                    <th>Estado</th>
                    <th style="min-width: 420px;">Accion</th>
                </tr>
                </thead>
                <tbody id="tbody-relacion-estudiantes">
                <tr>
                    <td colspan="11" class="text-center text-muted">Sin datos</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-ver-estudiante" tabindex="-1" role="dialog" aria-labelledby="modal-ver-estudiante-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-ver-estudiante-title">Detalle del estudiante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detalle-estudiante-content"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-reinscribir-desde-detalle">
                    <i class="fas fa-redo-alt mr-1"></i> Reinscribir
                </button>
                <button type="button" class="btn btn-danger" id="btn-imprimir-detalle-pdf">
                    <i class="fas fa-file-pdf mr-1"></i> Imprimir PDF
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-editar-inscripcion" tabindex="-1" role="dialog" aria-labelledby="modal-editar-inscripcion-title" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document" style="max-width:96vw; margin:0.5rem auto;">
        <div class="modal-content">
            <form id="form-editar-inscripcion">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-editar-inscripcion-title">Editar expediente del estudiante</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height:72vh; overflow-y:auto;">
                    <div class="alert alert-info py-2 mb-3" role="alert">
                        Desliza hacia abajo para ver todas las secciones del expediente (registro civil, direccion, familiares, vacunas, oferta academica e inscripcion).
                    </div>
                    <input type="hidden" id="editar-inscripcion-id">
                    <input type="hidden" id="editar-estudiante-id">

                    <h6 class="font-weight-bold text-primary mb-2">Foto del estudiante</h6>
                    <div class="form-row mb-2">
                        <div class="col-md-4">
                            <div id="editar-preview-foto" class="border rounded d-flex align-items-center justify-content-center bg-light" style="height:190px; overflow:hidden;">
                                <span class="text-muted">Sin foto</span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label for="editar-foto-estudiante">Actualizar foto</label>
                            <input id="editar-foto-estudiante" type="file" class="form-control-file" accept="image/jpeg,image/png,image/webp">
                            <small id="editar-foto-nombre" class="form-text text-muted">No se selecciono nueva foto.</small>
                        </div>
                    </div>

                    <h6 class="font-weight-bold text-primary mb-2">Informacion basica</h6>
                    <div class="form-row">
                        <div class="form-group col-md-3"><label for="editar-id-sigerd">ID SIGERD</label><input id="editar-id-sigerd" type="text" class="form-control" maxlength="50"></div>
                        <div class="form-group col-md-3"><label for="editar-primer-nombre">Primer nombre *</label><input id="editar-primer-nombre" type="text" class="form-control" maxlength="100" required></div>
                        <div class="form-group col-md-3"><label for="editar-segundo-nombre">Segundo nombre</label><input id="editar-segundo-nombre" type="text" class="form-control" maxlength="100"></div>
                        <div class="form-group col-md-3"><label for="editar-primer-apellido">Primer apellido *</label><input id="editar-primer-apellido" type="text" class="form-control" maxlength="100" required></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3"><label for="editar-segundo-apellido">Segundo apellido</label><input id="editar-segundo-apellido" type="text" class="form-control" maxlength="100"></div>
                        <div class="form-group col-md-3"><label for="editar-fecha-nacimiento">Fecha nacimiento</label><input id="editar-fecha-nacimiento" type="date" class="form-control"></div>
                        <div class="form-group col-md-3">
                            <label for="editar-sexo">Sexo</label>
                            <select id="editar-sexo" class="form-control">
                                <option value="">Selecciona</option>
                                <option value="MASCULINO">MASCULINO</option>
                                <option value="FEMENINO">FEMENINO</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="editar-estado-civil">Estado civil</label>
                            <select id="editar-estado-civil" class="form-control">
                                <option value="">Selecciona</option>
                                <option value="SOLTERO">SOLTERO</option>
                                <option value="CASADO">CASADO</option>
                                <option value="VIUDO">VIUDO</option>
                                <option value="DIVORCIADO">DIVORCIADO</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3"><label for="editar-nacionalidad">Nacionalidad</label><input id="editar-nacionalidad" type="text" class="form-control" maxlength="100"></div>
                        <div class="form-group col-md-3"><label for="editar-telefono">Telefono</label><input id="editar-telefono" type="text" class="form-control" maxlength="30"></div>
                        <div class="form-group col-md-3"><label for="editar-celular">Celular</label><input id="editar-celular" type="text" class="form-control" maxlength="30"></div>
                        <div class="form-group col-md-3"><label for="editar-whatsapp">Whatsapp</label><input id="editar-whatsapp" type="text" class="form-control" maxlength="30"></div>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold text-primary mb-2">Registro civil</h6>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="editar-estado-acta">Estado acta</label>
                            <select id="editar-estado-acta" class="form-control">
                                <option value="">Selecciona</option>
                                <option value="DECLARADO">DECLARADO</option>
                                <option value="NO_DECLARADO">NO_DECLARADO</option>
                                <option value="NO_DISPONIBLE">NO_DISPONIBLE</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3"><label for="editar-numero-acta">Numero acta</label><input id="editar-numero-acta" type="text" class="form-control" maxlength="50"></div>
                        <div class="form-group col-md-3"><label for="editar-provincia-jce">Provincia JCE</label><input id="editar-provincia-jce" type="text" class="form-control" maxlength="100"></div>
                        <div class="form-group col-md-3"><label for="editar-municipio-jce">Municipio JCE</label><input id="editar-municipio-jce" type="text" class="form-control" maxlength="100"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3"><label for="editar-oficialia-jce">Oficialia JCE</label><input id="editar-oficialia-jce" type="text" class="form-control" maxlength="150"></div>
                        <div class="form-group col-md-2"><label for="editar-libro-jce">Libro</label><input id="editar-libro-jce" type="text" class="form-control" maxlength="50"></div>
                        <div class="form-group col-md-2"><label for="editar-folio-jce">Folio</label><input id="editar-folio-jce" type="text" class="form-control" maxlength="50"></div>
                        <div class="form-group col-md-2"><label for="editar-anio-jce">Ano</label><input id="editar-anio-jce" type="number" class="form-control" min="1900" max="2155"></div>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold text-primary mb-2">Direccion</h6>
                    <div class="form-row">
                        <div class="form-group col-md-3"><label for="editar-provincia">Provincia</label><input id="editar-provincia" type="text" class="form-control" maxlength="100"></div>
                        <div class="form-group col-md-3"><label for="editar-municipio">Municipio</label><input id="editar-municipio" type="text" class="form-control" maxlength="100"></div>
                        <div class="form-group col-md-3"><label for="editar-distrito">Distrito municipal</label><input id="editar-distrito" type="text" class="form-control" maxlength="100"></div>
                        <div class="form-group col-md-3"><label for="editar-seccion-direccion">Seccion</label><input id="editar-seccion-direccion" type="text" class="form-control" maxlength="100"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3"><label for="editar-barrio">Barrio</label><input id="editar-barrio" type="text" class="form-control" maxlength="100"></div>
                        <div class="form-group col-md-3"><label for="editar-sub-barrio">Sub barrio</label><input id="editar-sub-barrio" type="text" class="form-control" maxlength="100"></div>
                        <div class="form-group col-md-6"><label for="editar-calle-numero">Calle y numero</label><input id="editar-calle-numero" type="text" class="form-control" maxlength="255"></div>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold text-primary mb-2">Familiares</h6>
                    <div class="form-row">
                        <div class="form-group col-md-4"><label for="editar-madre-nombre">Madre primer nombre</label><input id="editar-madre-nombre" type="text" class="form-control" maxlength="100"></div>
                        <div class="form-group col-md-4"><label for="editar-madre-apellido">Madre primer apellido</label><input id="editar-madre-apellido" type="text" class="form-control" maxlength="100"></div>
                        <div class="form-group col-md-4"><label for="editar-madre-cedula">Madre cedula</label><input id="editar-madre-cedula" type="text" class="form-control" maxlength="20"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4"><label for="editar-padre-nombre">Padre primer nombre</label><input id="editar-padre-nombre" type="text" class="form-control" maxlength="100"></div>
                        <div class="form-group col-md-4"><label for="editar-padre-apellido">Padre primer apellido</label><input id="editar-padre-apellido" type="text" class="form-control" maxlength="100"></div>
                        <div class="form-group col-md-4"><label for="editar-padre-cedula">Padre cedula</label><input id="editar-padre-cedula" type="text" class="form-control" maxlength="20"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4"><label for="editar-tutor-nombre">Tutor primer nombre</label><input id="editar-tutor-nombre" type="text" class="form-control" maxlength="100"></div>
                        <div class="form-group col-md-4"><label for="editar-tutor-apellido">Tutor primer apellido</label><input id="editar-tutor-apellido" type="text" class="form-control" maxlength="100"></div>
                        <div class="form-group col-md-4"><label for="editar-tutor-cedula">Tutor cedula</label><input id="editar-tutor-cedula" type="text" class="form-control" maxlength="20"></div>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold text-primary mb-2">Vacunas</h6>
                    <div id="editar-vacunas-list" class="mb-2"></div>

                    <hr>
                    <h6 class="font-weight-bold text-primary mb-2">Discapacidades</h6>
                    <div id="editar-discapacidades-list" class="mb-2"></div>

                    <hr>
                    <h6 class="font-weight-bold text-primary mb-2">Requisitos de inscripcion</h6>
                    <div id="editar-requisitos-list" class="mb-2"></div>

                    <hr>
                    <h6 class="font-weight-bold text-primary mb-2">Oferta academica</h6>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="editar-anio-academico">Ano escolar *</label>
                            <select id="editar-anio-academico" class="form-control" required>
                                <option value="">Selecciona</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="editar-grado-academico">Grado *</label>
                            <select id="editar-grado-academico" class="form-control" required>
                                <option value="">Selecciona</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="editar-seccion-academica">Seccion *</label>
                            <select id="editar-seccion-academica" class="form-control" required>
                                <option value="">Selecciona</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="editar-tanda-academica">Tanda *</label>
                            <select id="editar-tanda-academica" class="form-control" required>
                                <option value="">Selecciona</option>
                            </select>
                        </div>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold text-primary mb-2">Inscripcion</h6>
                    <div class="form-group">
                        <label for="editar-centro-procedencia">Centro de procedencia</label>
                        <input id="editar-centro-procedencia" type="text" class="form-control" maxlength="255">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="editar-fecha-inscripcion">Fecha de inscripcion *</label>
                            <input id="editar-fecha-inscripcion" type="date" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6 d-flex align-items-end">
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input" type="checkbox" id="editar-acepta-terminos">
                                <label class="custom-control-label" for="editar-acepta-terminos">Acepta terminos y condiciones</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="editar-tarifa-inscripcion">Tarifa inscripcion</label>
                            <input id="editar-tarifa-inscripcion" type="number" class="form-control" min="0" step="0.01" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="editar-mensualidad">Mensualidad</label>
                            <input id="editar-mensualidad" type="number" class="form-control" min="0" step="0.01" required>
                            <small id="editar-tarifa-auto-ayuda" class="form-text text-muted">La tarifa se ajusta segun la oferta academica seleccionada.</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editar-observaciones">Observaciones</label>
                        <textarea id="editar-observaciones" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="editar-inscripcion-activa">
                        <label class="custom-control-label" for="editar-inscripcion-activa">Inscripcion activa</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btn-guardar-edicion">
                        <i class="fas fa-save mr-1"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-historial-inscripciones" tabindex="-1" role="dialog" aria-labelledby="modal-historial-inscripciones-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-historial-inscripciones-title">Historial de inscripciones</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="historial-inscripciones-content"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btn-imprimir-historial-pdf">
                    <i class="fas fa-file-pdf mr-1"></i> Imprimir historial PDF
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
    const filtroAnio = document.getElementById('filtro-anio');
    const filtroGrado = document.getElementById('filtro-grado');
    const filtroNombre = document.getElementById('filtro-nombre');
    const filtroSigerd = document.getElementById('filtro-sigerd');
    const btnReportePdf = document.getElementById('btn-reporte-pdf');
    const estadoCarga = document.getElementById('estado-carga');
    const tbody = document.getElementById('tbody-relacion-estudiantes');

    const modalVer = document.getElementById('modal-ver-estudiante');
    const modalEditar = document.getElementById('modal-editar-inscripcion');
    const modalHistorial = document.getElementById('modal-historial-inscripciones');

    const detalleContent = document.getElementById('detalle-estudiante-content');
    const historialContent = document.getElementById('historial-inscripciones-content');
    const btnReinscribirDesdeDetalle = document.getElementById('btn-reinscribir-desde-detalle');
    const btnImprimirDetallePdf = document.getElementById('btn-imprimir-detalle-pdf');
    const btnImprimirHistorialPdf = document.getElementById('btn-imprimir-historial-pdf');

    const formEditar = document.getElementById('form-editar-inscripcion');
    const editarInscripcionIdInput = document.getElementById('editar-inscripcion-id');
    const editarEstudianteIdInput = document.getElementById('editar-estudiante-id');
    const editarFotoInput = document.getElementById('editar-foto-estudiante');
    const editarFotoNombre = document.getElementById('editar-foto-nombre');
    const editarPreviewFoto = document.getElementById('editar-preview-foto');
    const editarVacunasList = document.getElementById('editar-vacunas-list');
    const editarDiscapacidadesList = document.getElementById('editar-discapacidades-list');
    const editarRequisitosList = document.getElementById('editar-requisitos-list');
    const editarIdSigerdInput = document.getElementById('editar-id-sigerd');
    const editarPrimerNombreInput = document.getElementById('editar-primer-nombre');
    const editarSegundoNombreInput = document.getElementById('editar-segundo-nombre');
    const editarPrimerApellidoInput = document.getElementById('editar-primer-apellido');
    const editarSegundoApellidoInput = document.getElementById('editar-segundo-apellido');
    const editarFechaNacimientoInput = document.getElementById('editar-fecha-nacimiento');
    const editarSexoInput = document.getElementById('editar-sexo');
    const editarEstadoCivilInput = document.getElementById('editar-estado-civil');
    const editarNacionalidadInput = document.getElementById('editar-nacionalidad');
    const editarTelefonoInput = document.getElementById('editar-telefono');
    const editarCelularInput = document.getElementById('editar-celular');
    const editarWhatsappInput = document.getElementById('editar-whatsapp');

    const editarEstadoActaInput = document.getElementById('editar-estado-acta');
    const editarNumeroActaInput = document.getElementById('editar-numero-acta');
    const editarProvinciaJceInput = document.getElementById('editar-provincia-jce');
    const editarMunicipioJceInput = document.getElementById('editar-municipio-jce');
    const editarOficialiaJceInput = document.getElementById('editar-oficialia-jce');
    const editarLibroJceInput = document.getElementById('editar-libro-jce');
    const editarFolioJceInput = document.getElementById('editar-folio-jce');
    const editarAnioJceInput = document.getElementById('editar-anio-jce');

    const editarProvinciaInput = document.getElementById('editar-provincia');
    const editarMunicipioInput = document.getElementById('editar-municipio');
    const editarDistritoInput = document.getElementById('editar-distrito');
    const editarSeccionDireccionInput = document.getElementById('editar-seccion-direccion');
    const editarBarrioInput = document.getElementById('editar-barrio');
    const editarSubBarrioInput = document.getElementById('editar-sub-barrio');
    const editarCalleNumeroInput = document.getElementById('editar-calle-numero');

    const editarMadreNombreInput = document.getElementById('editar-madre-nombre');
    const editarMadreApellidoInput = document.getElementById('editar-madre-apellido');
    const editarMadreCedulaInput = document.getElementById('editar-madre-cedula');
    const editarPadreNombreInput = document.getElementById('editar-padre-nombre');
    const editarPadreApellidoInput = document.getElementById('editar-padre-apellido');
    const editarPadreCedulaInput = document.getElementById('editar-padre-cedula');
    const editarTutorNombreInput = document.getElementById('editar-tutor-nombre');
    const editarTutorApellidoInput = document.getElementById('editar-tutor-apellido');
    const editarTutorCedulaInput = document.getElementById('editar-tutor-cedula');

    const editarAnioAcademicoInput = document.getElementById('editar-anio-academico');
    const editarGradoAcademicoInput = document.getElementById('editar-grado-academico');
    const editarSeccionAcademicaInput = document.getElementById('editar-seccion-academica');
    const editarTandaAcademicaInput = document.getElementById('editar-tanda-academica');

    const editarCentroInput = document.getElementById('editar-centro-procedencia');
    const editarFechaInscripcionInput = document.getElementById('editar-fecha-inscripcion');
    const editarAceptaTerminosInput = document.getElementById('editar-acepta-terminos');
    const editarTarifaInput = document.getElementById('editar-tarifa-inscripcion');
    const editarMensualidadInput = document.getElementById('editar-mensualidad');
    const editarTarifaAutoAyuda = document.getElementById('editar-tarifa-auto-ayuda');
    const editarObservacionesInput = document.getElementById('editar-observaciones');
    const editarActivaInput = document.getElementById('editar-inscripcion-activa');
    const btnGuardarEdicion = document.getElementById('btn-guardar-edicion');

    const state = {
        aniosMap: new Map(),
        gradosMap: new Map(),
        nivelesMap: new Map(),
        seccionesMap: new Map(),
        tandasMap: new Map(),
        planificacionesMap: new Map(),
        estudiantesMap: new Map(),
        inscripciones: [],
        datosCentro: null,
        registrosCivilesMap: new Map(),
        direccionesMap: new Map(),
        vacunasMap: new Map(),
        discapacidadesMap: new Map(),
        familiaresMap: new Map(),
        requisitosMap: new Map(),
        tarifarios: [],
        tarifasGrados: [],
        defaultTarifarioId: 0,
        estudianteVacunasByEstudianteId: new Map(),
        estudianteDiscapacidadesByEstudianteId: new Map(),
        estudianteFamiliaresByEstudianteId: new Map(),
        requisitosByInscripcionId: new Map(),
        historialAcademicoByKey: new Map(),
    };

    let detalleActual = null;
    let historialActual = null;
    let fallbackBackdrop = null;
    let filtrosDesdeQueryAplicados = false;

    function getQueryParam(name) {
        const params = new URLSearchParams(window.location.search || '');
        return String(params.get(name) || '').trim();
    }

    function buildRelacionReturnUrl() {
        const params = new URLSearchParams();
        params.set('view', 'relacion-estudiantes');
        params.set('f_anio', String(filtroAnio.value || '0'));
        params.set('f_grado', String(filtroGrado.value || '0'));
        params.set('f_nombre', String(filtroNombre.value || ''));
        params.set('f_sigerd', String(filtroSigerd.value || ''));
        return 'index.php?' + params.toString();
    }

    function applyFiltersFromQuery() {
        if (filtrosDesdeQueryAplicados) {
            return;
        }

        const anio = getQueryParam('f_anio');
        const grado = getQueryParam('f_grado');
        const nombre = getQueryParam('f_nombre');
        const sigerd = getQueryParam('f_sigerd');

        if (nombre !== '') {
            filtroNombre.value = nombre;
        }
        if (sigerd !== '') {
            filtroSigerd.value = sigerd;
        }

        if (anio !== '' && filtroAnio.querySelector('option[value="' + anio + '"]')) {
            filtroAnio.value = anio;
            renderFiltroGrados();
        }

        if (grado !== '' && filtroGrado.querySelector('option[value="' + grado + '"]')) {
            filtroGrado.value = grado;
        }

        filtrosDesdeQueryAplicados = true;
    }

    function escHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formatDateIso(value) {
        const text = String(value || '').trim();
        if (!/^\d{4}-\d{2}-\d{2}$/.test(text)) {
            return '-';
        }

        const parts = text.split('-');
        return parts[2] + '/' + parts[1] + '/' + parts[0];
    }

    function pushToMapArray(map, key, value) {
        if (!map.has(key)) {
            map.set(key, []);
        }

        map.get(key).push(value);
    }

    function buildMapArray(rows, keyField) {
        const map = new Map();
        rows.forEach((row) => {
            const key = Number(row[keyField] || 0);
            if (key > 0) {
                pushToMapArray(map, key, row);
            }
        });

        return map;
    }

    function fieldRow(label, value) {
        const safe = value === null || value === undefined || String(value).trim() === '' ? '-' : String(value);
        return '<tr><th style="width:35%;">' + escHtml(label) + '</th><td>' + escHtml(safe) + '</td></tr>';
    }

    function buildSectionHtml(title, rows) {
        return '<div class="card shadow-sm mb-3">'
            + '<div class="card-header py-2"><h6 class="m-0 font-weight-bold text-primary">' + escHtml(title) + '</h6></div>'
            + '<div class="card-body p-0"><table class="table table-sm table-bordered mb-0">'
            + rows.join('')
            + '</table></div>'
            + '</div>';
    }

    function normalizeText(value) {
        return String(value || '')
            .toUpperCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .trim();
    }

    function calculateEdad(fechaNacimiento) {
        const value = String(fechaNacimiento || '').trim();
        if (!/^\d{4}-\d{2}-\d{2}$/.test(value)) {
            return '-';
        }

        const birth = new Date(value + 'T00:00:00');
        if (Number.isNaN(birth.getTime())) {
            return '-';
        }

        const today = new Date();
        let edad = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            edad -= 1;
        }

        return edad >= 0 ? String(edad) : '-';
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

    function setStatus(message, type) {
        const cssByType = {
            info: 'alert alert-info mb-3',
            success: 'alert alert-success mb-3',
            warning: 'alert alert-warning mb-3',
            danger: 'alert alert-danger mb-3',
        };

        estadoCarga.className = cssByType[type] || cssByType.info;
        estadoCarga.textContent = message;
    }

    function toNullableString(value) {
        const cleaned = String(value || '').trim();
        return cleaned === '' ? null : cleaned;
    }

    function toDecimal(value) {
        const numeric = Number(value);
        if (!Number.isFinite(numeric) || numeric < 0) {
            return 0;
        }

        return Number(numeric.toFixed(2));
    }

    function toNullableYear(value) {
        const cleaned = String(value || '').trim();
        if (cleaned === '') {
            return null;
        }

        const year = Number(cleaned);
        return Number.isInteger(year) ? year : null;
    }

    function hasAnyValue(values) {
        return values.some((value) => String(value || '').trim() !== '');
    }

    function setInputValue(input, value) {
        if (!input) {
            return;
        }

        input.value = value === null || value === undefined ? '' : String(value);
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

    async function uploadFotoEstudianteIfNeeded() {
        const file = editarFotoInput.files && editarFotoInput.files.length > 0 ? editarFotoInput.files[0] : null;
        if (!file) {
            return '';
        }

        const maxBytes = 8 * 1024 * 1024;
        if (Number(file.size || 0) > maxBytes) {
            throw new Error('La foto excede 8 MB. Reduce el tamano e intenta nuevamente.');
        }

        const formData = new FormData();
        formData.append('foto', file);
        formData.append('foto_estudiante', file);
        formData.append('_csrf', csrfToken);

        const response = await fetch(getPublicBaseUrl() + 'admin/upload-estudiante-foto.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-Token': csrfToken,
            },
            body: formData,
        });

        const raw = await response.text();
        let json = null;
        try {
            json = JSON.parse(raw);
        } catch (parseError) {
            throw new Error('Respuesta invalida del servidor al subir la foto.');
        }

        if (!json.success) {
            throw new Error(json.message || 'No se pudo subir la foto del estudiante.');
        }

        return String(json.path || '').trim();
    }

    function showModal(element) {
        if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.modal === 'function') {
            window.jQuery(element).modal('show');
            const body = element instanceof HTMLElement ? element.querySelector('.modal-body') : null;
            if (body instanceof HTMLElement) {
                body.scrollTop = 0;
            }
            return;
        }

        if (!(element instanceof HTMLElement)) {
            return;
        }

        element.style.display = 'block';
        element.classList.add('show');
        element.setAttribute('aria-modal', 'true');
        element.removeAttribute('aria-hidden');
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';

        if (!fallbackBackdrop) {
            fallbackBackdrop = document.createElement('div');
            fallbackBackdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(fallbackBackdrop);
        }

        const body = element.querySelector('.modal-body');
        if (body instanceof HTMLElement) {
            body.scrollTop = 0;
        }
    }

    function hideModal(element) {
        if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.modal === 'function') {
            window.jQuery(element).modal('hide');
            return;
        }

        if (!(element instanceof HTMLElement)) {
            return;
        }

        element.classList.remove('show');
        element.setAttribute('aria-hidden', 'true');
        element.removeAttribute('aria-modal');
        element.style.display = 'none';

        if (fallbackBackdrop && fallbackBackdrop.parentNode) {
            fallbackBackdrop.parentNode.removeChild(fallbackBackdrop);
            fallbackBackdrop = null;
        }

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

    async function apiUpdate(resource, criteria, data) {
        const response = await fetch(apiBase + '?resource=' + resource + '&action=update', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ criteria, data }),
        });

        const json = await response.json();
        if (!json.success) {
            throw new Error(json.message || ('No se pudo actualizar en ' + resource));
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

    function buildHistorialAcademicoKey(estudianteId, anioId, nivelId, gradoId, seccionId) {
        return [
            Number(estudianteId || 0),
            Number(anioId || 0),
            Number(nivelId || 0),
            Number(gradoId || 0),
            Number(seccionId || 0),
        ].join('|');
    }

    function mapEstadoFinalFromDb(estadoRaw) {
        const estado = String(estadoRaw || '').trim().toUpperCase();
        if (estado === 'PROMOVIDO') {
            return 'Promovido';
        }
        if (estado === 'REPROBADO') {
            return 'Reprobado';
        }
        if (estado === 'RETIRADO') {
            return 'Abandono';
        }
        if (estado === 'TRASLADADO') {
            return 'Transferido';
        }
        if (estado === 'GRADUADO') {
            return 'Graduado';
        }
        return 'No definido';
    }

    function getCondicionFinalByInscripcion(inscripcion) {
        const planif = state.planificacionesMap.get(Number(inscripcion.planificacion_academica_id || 0)) || null;
        if (!planif) {
            return 'No definido';
        }

        const key = buildHistorialAcademicoKey(
            Number(inscripcion.estudiante_id || 0),
            Number(planif.anio_escolar_id || 0),
            Number(planif.nivel_id || 0),
            Number(planif.grado_id || 0),
            Number(planif.seccion_id || 0)
        );
        const historial = state.historialAcademicoByKey.get(key) || null;
        return mapEstadoFinalFromDb(historial ? historial.estado : '');
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

    function normalizeRows() {
        return state.inscripciones.map((inscripcion) => {
            const estudianteId = Number(inscripcion.estudiante_id || 0);
            const planifId = Number(inscripcion.planificacion_academica_id || 0);
            const estudiante = state.estudiantesMap.get(estudianteId) || null;
            const planif = state.planificacionesMap.get(planifId) || null;

            const anioId = planif ? Number(planif.anio_escolar_id || 0) : 0;
            const nivel = planif ? state.nivelesMap.get(Number(planif.nivel_id || 0)) : null;
            const grado = planif ? state.gradosMap.get(Number(planif.grado_id || 0)) : null;
            const seccion = planif ? state.seccionesMap.get(Number(planif.seccion_id || 0)) : null;
            const anio = state.aniosMap.get(anioId) || null;

            return {
                estudianteId,
                inscripcionId: Number(inscripcion.id || 0),
                anioId,
                gradoId: planif ? Number(planif.grado_id || 0) : 0,
                estudiante: nombreEstudiante(estudiante),
                nombreBusqueda: normalizeText(nombreEstudiante(estudiante)),
                estudianteRaw: estudiante,
                inscripcionRaw: inscripcion,
                planifRaw: planif,
                idSigerd: estudiante ? String(estudiante.id_sigerd || '-').trim() || '-' : '-',
                anio: anio ? String(anio.nombre || '').trim() || '-' : '-',
                edad: calculateEdad(estudiante ? estudiante.fecha_nacimiento : ''),
                nivel: nivel ? String(nivel.nivel || '').trim() || '-' : '-',
                grado: grado ? String(grado.grado || '').trim() || '-' : '-',
                seccion: seccion ? String(seccion.seccion || '').trim() || '-' : '-',
                tanda: planif ? buildTurnoLabel(planif) : '-',
                fechaInscripcion: formatDateIso(inscripcion.fecha_inscripcion || ''),
                inscripcionActiva: Number(inscripcion.inscripcion_activa || 0) === 1,
                telefono: estudiante ? String(estudiante.telefono || estudiante.celular || '-').trim() || '-' : '-',
            };
        });
    }

    function getCurrentFilteredRows() {
        const selectedAnio = Number(filtroAnio.value || 0);
        const selectedGrado = Number(filtroGrado.value || 0);
        const nombreTerm = normalizeText(filtroNombre.value || '');
        const sigerdTerm = normalizeText(filtroSigerd.value || '');

        return normalizeRows().filter((row) => {
            const anioOk = selectedAnio <= 0 || row.anioId === selectedAnio;
            const gradoOk = selectedGrado <= 0 || row.gradoId === selectedGrado;
            const nombreOk = nombreTerm === '' || row.nombreBusqueda.includes(nombreTerm);
            const sigerdOk = sigerdTerm === '' || normalizeText(row.idSigerd).includes(sigerdTerm);
            return anioOk && gradoOk && nombreOk && sigerdOk;
        });
    }

    function renderRows() {
        const rows = getCurrentFilteredRows();

        if (rows.length === 0) {
            tbody.innerHTML = '<tr><td colspan="11" class="text-center text-muted">No hay estudiantes inscritos para los filtros seleccionados.</td></tr>';
            setStatus('No se encontraron inscritos para el filtro actual.', 'warning');
            return;
        }

        tbody.innerHTML = rows.map((row, index) => {
            const badge = row.inscripcionActiva
                ? '<span class="badge badge-success">Activa</span>'
                : '<span class="badge badge-secondary">Inactiva</span>';

            const actionButtons = ''
                + '<div class="d-flex flex-wrap" style="gap:6px;">'
                + '<button type="button" class="btn btn-info btn-sm btn-action" data-action="ver" data-inscripcion-id="' + row.inscripcionId + '"><i class="fas fa-eye"></i> </button>'
                + '<button type="button" class="btn btn-warning btn-sm btn-action" data-action="editar" data-inscripcion-id="' + row.inscripcionId + '"><i class="fas fa-edit"></i> </button>'
                + '<button type="button" class="btn btn-secondary btn-sm btn-action" data-action="historial" data-inscripcion-id="' + row.inscripcionId + '"><i class="fas fa-history"></i> </button>'
                + '<button type="button" class="btn btn-success btn-sm btn-action" data-action="carnet" data-inscripcion-id="' + row.inscripcionId + '"><i class="fas fa-id-card"></i> </button>'
                + '<button type="button" class="btn btn-danger btn-sm btn-action" data-action="eliminar" data-inscripcion-id="' + row.inscripcionId + '"><i class="fas fa-trash"></i> </button>'
                + '</div>';

            return ''
                + '<tr>'
                + '<td>' + escHtml(String(row.estudianteId)) + '</td>'
                + '<td>' + escHtml(row.estudiante) + '</td>'
                + '<td>' + escHtml(row.idSigerd) + '</td>'
                + '<td>' + escHtml(row.anio) + '</td>'
                + '<td>' + escHtml(row.edad) + '</td>'
                + '<td>' + escHtml(row.grado) + '</td>'
                + '<td>' + escHtml(row.seccion) + '</td>'
                + '<td>' + escHtml(row.tanda) + '</td>'
                + '<td>' + escHtml(row.fechaInscripcion) + '</td>'
                + '<td>' + badge + '</td>'
                + '<td>' + actionButtons + '</td>'
                + '</tr>';
        }).join('');

        setStatus('Mostrando ' + rows.length + ' estudiante(s) inscrito(s).', 'success');
    }

    function buildReportePdf(rows) {
        const centro = state.datosCentro || {};
        const filterAnioText = filtroAnio.options[filtroAnio.selectedIndex] ? filtroAnio.options[filtroAnio.selectedIndex].textContent : 'Todos';
        const filterGradoText = filtroGrado.options[filtroGrado.selectedIndex] ? filtroGrado.options[filtroGrado.selectedIndex].textContent : 'Todos';
        const filterNombreText = String(filtroNombre.value || '').trim() || 'Todos';
        const filterSigerdText = String(filtroSigerd.value || '').trim() || 'Todos';

        const bodyRows = rows.map((row, index) => {
            const estado = row.inscripcionActiva ? 'Activa' : 'Inactiva';
            return ''
                + '<tr>'
                + '<td>' + (index + 1) + '</td>'
                + '<td>' + escHtml(String(row.estudianteId)) + '</td>'
                + '<td>' + escHtml(row.estudiante) + '</td>'
                + '<td>' + escHtml(row.idSigerd) + '</td>'
                + '<td>' + escHtml(row.anio) + '</td>'
                + '<td>' + escHtml(row.edad) + '</td>'
                + '<td>' + escHtml(row.grado) + '</td>'
                + '<td>' + escHtml(row.seccion) + '</td>'
                + '<td>' + escHtml(row.tanda) + '</td>'
                + '<td>' + escHtml(row.fechaInscripcion) + '</td>'
                + '<td>' + escHtml(estado) + '</td>'
                + '</tr>';
        }).join('');

        const html = ''
            + '<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Reporte relacion de estudiantes</title>'
            + '<style>'
            + 'body{font-family:Arial,Helvetica,sans-serif;margin:18px;color:#1f2933;}'
            + '.head{border:2px solid #1f2933;padding:10px 12px;margin-bottom:12px;}'
            + '.head h1{margin:0;font-size:18px;text-transform:uppercase;}'
            + '.meta{font-size:12px;margin-top:4px;}'
            + '.filters{margin-top:6px;padding-top:6px;border-top:1px solid #cbd5df;}'
            + 'table{width:100%;border-collapse:collapse;margin-top:10px;}'
            + 'th,td{border:1px solid #cfd7df;padding:6px 7px;font-size:11px;}'
            + 'th{background:#f1f5f9;text-transform:uppercase;}'
            + '.actions{margin-bottom:8px;}'
            + '.actions button{padding:6px 10px;font-size:12px;}'
            + '@media print {.actions{display:none;} body{margin:8mm;}}'
            + '</style></head><body>'
            + '<div class="actions"><button onclick="window.print()">Imprimir</button> <button onclick="window.close()">Cerrar</button></div>'
            + '<div class="head">'
            + '<h1>Relacion de estudiantes inscritos</h1>'
            + '<div class="meta"><strong>Centro:</strong> ' + escHtml(String(centro.nombre_centro || 'Centro educativo')) + '</div>'
            + '<div class="meta"><strong>Total registros:</strong> ' + rows.length + '</div>'
            + '<div class="meta filters"><strong>Filtros</strong> | Ano escolar: ' + escHtml(filterAnioText || 'Todos') + ' | Grado: ' + escHtml(filterGradoText || 'Todos') + ' | Nombre: ' + escHtml(filterNombreText) + ' | ID SIGERD: ' + escHtml(filterSigerdText) + '</div>'
            + '</div>'
            + '<table>'
            + '<thead><tr><th>#</th><th>ID Estudiante</th><th>Estudiante</th><th>ID SIGERD</th><th>Ano escolar</th><th>Edad</th><th>Grado</th><th>Seccion</th><th>Tanda</th><th>Fecha inscripcion</th><th>Estado</th></tr></thead>'
            + '<tbody>' + bodyRows + '</tbody>'
            + '</table>'
            + '<script>window.onload=function(){setTimeout(function(){window.print();},250);};<' + '/script>'
            + '</body></html>';

        const win = window.open('', '_blank');
        if (!win) {
            window.alert('No se pudo abrir la ventana de impresion para el reporte.');
            return;
        }

        win.document.open();
        win.document.write(html);
        win.document.close();
    }

    function renderFiltroAnios() {
        const uniqueIds = new Set();
        state.planificacionesMap.forEach((planif) => {
            const anioId = Number(planif.anio_escolar_id || 0);
            if (anioId > 0) {
                uniqueIds.add(anioId);
            }
        });

        const ids = Array.from(uniqueIds).sort((a, b) => b - a);
        const options = ['<option value="0">Todos los anos escolares</option>'];

        ids.forEach((id) => {
            const anio = state.aniosMap.get(id);
            const nombre = String((anio && anio.nombre) || ('ANO #' + id)).trim();
            options.push('<option value="' + id + '">' + escHtml(nombre) + '</option>');
        });

        filtroAnio.innerHTML = options.join('');
        if (ids.length > 0) {
            filtroAnio.value = String(ids[0]);
        }
    }

    function renderFiltroGrados() {
        const selectedAnio = Number(filtroAnio.value || 0);
        const gradoIds = new Set();

        state.planificacionesMap.forEach((planif) => {
            const anioId = Number(planif.anio_escolar_id || 0);
            const gradoId = Number(planif.grado_id || 0);
            if (gradoId <= 0) {
                return;
            }

            if (selectedAnio > 0 && anioId !== selectedAnio) {
                return;
            }

            gradoIds.add(gradoId);
        });

        const sortedIds = Array.from(gradoIds).sort((a, b) => a - b);
        const options = ['<option value="0">Todos los grados</option>'];
        sortedIds.forEach((gradoId) => {
            const grado = state.gradosMap.get(gradoId);
            const nombre = String((grado && grado.grado) || ('GRADO #' + gradoId)).trim();
            options.push('<option value="' + gradoId + '">' + escHtml(nombre) + '</option>');
        });

        filtroGrado.innerHTML = options.join('');
    }

    function getRowByInscripcionId(inscripcionId) {
        const numericId = Number(inscripcionId || 0);
        return normalizeRows().find((row) => row.inscripcionId === numericId) || null;
    }

    function getVacunasByEstudianteId(estudianteId) {
        const rows = state.estudianteVacunasByEstudianteId.get(estudianteId) || [];
        return rows.map((item) => {
            const vacuna = state.vacunasMap.get(Number(item.vacuna_id || 0));
            return String((vacuna && vacuna.nombre) || '').trim();
        }).filter(Boolean);
    }

    function getDiscapacidadesByEstudianteId(estudianteId) {
        const rows = state.estudianteDiscapacidadesByEstudianteId.get(estudianteId) || [];
        return rows.map((item) => {
            const discapacidad = state.discapacidadesMap.get(Number(item.discapacidad_id || 0));
            return String((discapacidad && discapacidad.nombre) || '').trim();
        }).filter(Boolean);
    }

    function getFamiliaresByEstudianteId(estudianteId) {
        const rows = state.estudianteFamiliaresByEstudianteId.get(estudianteId) || [];
        return rows.map((item) => {
            const familiar = state.familiaresMap.get(Number(item.familiar_id || 0));
            return familiar || null;
        }).filter(Boolean);
    }

    function getFamiliarByRole(estudianteId, role) {
        const roleKey = String(role || '').toUpperCase();
        const familiares = getFamiliaresByEstudianteId(estudianteId);
        return familiares.find((familiar) => String(familiar.tipo_familiar || '').toUpperCase() === roleKey) || null;
    }

    async function syncFamiliarByRole(estudianteId, role, nombre, apellido, cedula) {
        const existing = getFamiliarByRole(estudianteId, role);
        const hasData = hasAnyValue([nombre, apellido, cedula]);

        if (!hasData) {
            if (existing) {
                await apiDestroy('estudiante_familiares', {
                    estudiante_id: estudianteId,
                    familiar_id: Number(existing.id || 0),
                });
            }
            return;
        }

        if (String(nombre || '').trim() === '' || String(apellido || '').trim() === '') {
            throw new Error('Para ' + role.toLowerCase() + ' debes completar primer nombre y primer apellido.');
        }

        const payload = {
            tipo_familiar: role,
            primer_nombre: String(nombre || '').trim(),
            primer_apellido: String(apellido || '').trim(),
            cedula: toNullableString(cedula),
        };

        if (existing) {
            await apiUpdate('familiares', { id: Number(existing.id || 0) }, payload);
            return;
        }

        const created = await apiStore('familiares', payload);
        const familiarId = Number(created.id || 0);
        if (familiarId <= 0) {
            throw new Error('No se pudo obtener el ID del familiar creado.');
        }

        await apiStore('estudiante_familiares', {
            estudiante_id: estudianteId,
            familiar_id: familiarId,
        });
    }

    function getRequisitosByInscripcionId(inscripcionId) {
        const rows = state.requisitosByInscripcionId.get(inscripcionId) || [];
        return rows
            .filter((item) => Number(item.presentado || 0) === 1)
            .map((item) => {
                const requisito = state.requisitosMap.get(Number(item.requisito_id || 0));
                return String((requisito && requisito.nombre) || '').trim();
            })
            .filter(Boolean);
    }

    function getSelectedIdsFromContainer(container) {
        if (!container) {
            return [];
        }

        return Array.from(container.querySelectorAll('input[type="checkbox"][data-item-id]:checked'))
            .map((el) => Number(el.getAttribute('data-item-id') || 0))
            .filter((id) => id > 0);
    }

    function renderCheckboxCatalog(container, prefix, rows, selectedIds) {
        if (!container) {
            return;
        }

        if (!rows || rows.length === 0) {
            container.innerHTML = '<div class="text-muted">No hay datos disponibles.</div>';
            return;
        }

        const selectedSet = new Set(selectedIds || []);
        const html = rows.map((row) => {
            const id = Number(row.id || 0);
            const name = String(row.nombre || '').trim();
            const checkboxId = prefix + '-' + id;
            return ''
                + '<div class="custom-control custom-checkbox mb-1">'
                + '<input class="custom-control-input" type="checkbox" id="' + escHtml(checkboxId) + '" data-item-id="' + id + '"' + (selectedSet.has(id) ? ' checked' : '') + '>'
                + '<label class="custom-control-label" for="' + escHtml(checkboxId) + '">' + escHtml(name) + '</label>'
                + '</div>';
        }).join('');

        container.innerHTML = html;
    }

    function renderEditCatalogs(row) {
        const estudianteId = Number(row.estudianteId || 0);
        const inscripcionId = Number(row.inscripcionId || 0);

        const vacunasRows = Array.from(state.vacunasMap.values()).filter((item) => Number(item.estado ?? 1) === 1);
        const discapRows = Array.from(state.discapacidadesMap.values()).filter((item) => Number(item.estado ?? 1) === 1);
        const requisitosRows = Array.from(state.requisitosMap.values()).filter((item) => Number(item.estado ?? 1) === 1);

        const selectedVacunas = (state.estudianteVacunasByEstudianteId.get(estudianteId) || []).map((rowItem) => Number(rowItem.vacuna_id || 0));
        const selectedDiscap = (state.estudianteDiscapacidadesByEstudianteId.get(estudianteId) || []).map((rowItem) => Number(rowItem.discapacidad_id || 0));
        const selectedReq = (state.requisitosByInscripcionId.get(inscripcionId) || [])
            .filter((rowItem) => Number(rowItem.presentado || 0) === 1)
            .map((rowItem) => Number(rowItem.requisito_id || 0));

        renderCheckboxCatalog(editarVacunasList, 'edit-vacuna', vacunasRows, selectedVacunas);
        renderCheckboxCatalog(editarDiscapacidadesList, 'edit-discap', discapRows, selectedDiscap);
        renderCheckboxCatalog(editarRequisitosList, 'edit-req', requisitosRows, selectedReq);
    }

    function getActivePlanificaciones() {
        return Array.from(state.planificacionesMap.values()).filter((row) => Number(row.estado ?? 1) === 1);
    }

    function resolveDefaultTarifarioId() {
        const activo = state.tarifarios.find((row) => Number(row.estado ?? 1) === 1) || state.tarifarios[0] || null;
        state.defaultTarifarioId = activo ? Number(activo.id || 0) : 0;
    }

    function setTarifaAyuda(message, type) {
        if (!editarTarifaAutoAyuda) {
            return;
        }

        const css = {
            info: 'form-text text-muted',
            success: 'form-text text-success',
            warning: 'form-text text-warning',
        };

        editarTarifaAutoAyuda.className = css[type] || css.info;
        editarTarifaAutoAyuda.textContent = message;
    }

    function applyTarifaByPlanifId(planifId) {
        const numericPlanifId = Number(planifId || 0);
        const planif = state.planificacionesMap.get(numericPlanifId) || null;
        if (!planif) {
            setTarifaAyuda('No se encontro una oferta academica valida para calcular tarifa.', 'warning');
            return;
        }

        const gradoId = Number(planif.grado_id || 0);
        const tarifaMatches = state.tarifasGrados
            .filter((row) => Number(row.grado_id || 0) === gradoId)
            .filter((row) => Number(row.estado ?? 1) === 1)
            .filter((row) => {
                if (state.defaultTarifarioId <= 0) {
                    return true;
                }
                return Number(row.tarifario_id || 0) === state.defaultTarifarioId;
            })
            .sort((a, b) => Number(b.id || 0) - Number(a.id || 0));

        if (tarifaMatches.length === 0) {
            setTarifaAyuda('No hay tarifa configurada para este grado en el tarifario activo.', 'warning');
            return;
        }

        const tarifa = tarifaMatches[0];
        editarTarifaInput.value = String(tarifa.tarifa_inscripcion ?? 0);
        editarMensualidadInput.value = String(tarifa.mensualidad ?? 0);
        setTarifaAyuda('Tarifa cargada automaticamente desde configuracion del sistema.', 'success');
    }

    function fillSelectWithOptions(selectEl, options, selectedValue) {
        if (!selectEl) {
            return;
        }

        const html = ['<option value="">Selecciona</option>'];
        options.forEach((item) => {
            html.push('<option value="' + escHtml(String(item.value)) + '">' + escHtml(String(item.label)) + '</option>');
        });
        selectEl.innerHTML = html.join('');

        const selected = String(selectedValue || '').trim();
        if (selected !== '' && selectEl.querySelector('option[value="' + selected + '"]')) {
            selectEl.value = selected;
        }
    }

    function renderEditarOfertaAnios(selectedAnioId) {
        const planifs = getActivePlanificaciones();
        const ids = Array.from(new Set(planifs.map((row) => Number(row.anio_escolar_id || 0)).filter((id) => id > 0))).sort((a, b) => b - a);
        const options = ids.map((id) => {
            const anio = state.aniosMap.get(id);
            const label = String((anio && anio.nombre) || ('ANO #' + id)).trim();
            return { value: id, label };
        });

        fillSelectWithOptions(editarAnioAcademicoInput, options, selectedAnioId);
    }

    function renderEditarOfertaGrados(selectedGradoId) {
        const selectedAnioId = Number(editarAnioAcademicoInput.value || 0);
        const planifs = getActivePlanificaciones().filter((row) => {
            return selectedAnioId > 0 ? Number(row.anio_escolar_id || 0) === selectedAnioId : true;
        });

        const ids = Array.from(new Set(planifs.map((row) => Number(row.grado_id || 0)).filter((id) => id > 0))).sort((a, b) => a - b);
        const options = ids.map((id) => {
            const grado = state.gradosMap.get(id);
            const label = String((grado && grado.grado) || ('GRADO #' + id)).trim();
            return { value: id, label };
        });

        fillSelectWithOptions(editarGradoAcademicoInput, options, selectedGradoId);
    }

    function renderEditarOfertaSecciones(selectedSeccionId) {
        const selectedAnioId = Number(editarAnioAcademicoInput.value || 0);
        const selectedGradoId = Number(editarGradoAcademicoInput.value || 0);
        const planifs = getActivePlanificaciones().filter((row) => {
            const anioOk = selectedAnioId > 0 ? Number(row.anio_escolar_id || 0) === selectedAnioId : true;
            const gradoOk = selectedGradoId > 0 ? Number(row.grado_id || 0) === selectedGradoId : true;
            return anioOk && gradoOk;
        });

        const ids = Array.from(new Set(planifs.map((row) => Number(row.seccion_id || 0)).filter((id) => id > 0))).sort((a, b) => a - b);
        const options = ids.map((id) => {
            const seccion = state.seccionesMap.get(id);
            const label = String((seccion && seccion.seccion) || ('SECCION #' + id)).trim();
            return { value: id, label };
        });

        fillSelectWithOptions(editarSeccionAcademicaInput, options, selectedSeccionId);
    }

    function renderEditarOfertaTandas(selectedTandaId) {
        const selectedAnioId = Number(editarAnioAcademicoInput.value || 0);
        const selectedGradoId = Number(editarGradoAcademicoInput.value || 0);
        const selectedSeccionId = Number(editarSeccionAcademicaInput.value || 0);
        const planifs = getActivePlanificaciones().filter((row) => {
            const anioOk = selectedAnioId > 0 ? Number(row.anio_escolar_id || 0) === selectedAnioId : true;
            const gradoOk = selectedGradoId > 0 ? Number(row.grado_id || 0) === selectedGradoId : true;
            const seccionOk = selectedSeccionId > 0 ? Number(row.seccion_id || 0) === selectedSeccionId : true;
            return anioOk && gradoOk && seccionOk;
        });

        const ids = Array.from(new Set(planifs.map((row) => Number(row.tanda_id || 0)).filter((id) => id > 0))).sort((a, b) => a - b);
        const options = ids.map((id) => {
            const tanda = state.tandasMap.get(id);
            const label = String((tanda && (tanda.nombre || tanda.codigo)) || ('TANDA #' + id)).trim();
            return { value: id, label };
        });

        fillSelectWithOptions(editarTandaAcademicaInput, options, selectedTandaId);
    }

    function hydrateEditarOfertaFromRow(row) {
        const planif = row.planifRaw || null;
        const anioId = planif ? Number(planif.anio_escolar_id || 0) : 0;
        const gradoId = planif ? Number(planif.grado_id || 0) : 0;
        const seccionId = planif ? Number(planif.seccion_id || 0) : 0;
        const tandaId = planif ? Number(planif.tanda_id || 0) : 0;

        renderEditarOfertaAnios(anioId > 0 ? String(anioId) : '');
        renderEditarOfertaGrados(gradoId > 0 ? String(gradoId) : '');
        renderEditarOfertaSecciones(seccionId > 0 ? String(seccionId) : '');
        renderEditarOfertaTandas(tandaId > 0 ? String(tandaId) : '');
    }

    function resolveActivePlanificacionIdFromEditSelection() {
        const anioId = Number(editarAnioAcademicoInput.value || 0);
        const gradoId = Number(editarGradoAcademicoInput.value || 0);
        const seccionId = Number(editarSeccionAcademicaInput.value || 0);
        const tandaId = Number(editarTandaAcademicaInput.value || 0);

        if (anioId <= 0 || gradoId <= 0 || seccionId <= 0 || tandaId <= 0) {
            return 0;
        }

        const candidates = getActivePlanificaciones()
            .filter((row) => Number(row.anio_escolar_id || 0) === anioId)
            .filter((row) => Number(row.grado_id || 0) === gradoId)
            .filter((row) => Number(row.seccion_id || 0) === seccionId)
            .filter((row) => Number(row.tanda_id || 0) === tandaId)
            .sort((a, b) => Number(b.id || 0) - Number(a.id || 0));

        return candidates.length > 0 ? Number(candidates[0].id || 0) : 0;
    }

    function getTodayLocalISO() {
        const now = new Date();
        const offsetMs = now.getTimezoneOffset() * 60000;
        return new Date(now.getTime() - offsetMs).toISOString().slice(0, 10);
    }

    function buildDetalleSections(row) {
        const estudiante = row.estudianteRaw || {};
        const inscripcion = row.inscripcionRaw || {};
        const planif = row.planifRaw || {};
        const registroCivil = state.registrosCivilesMap.get(row.estudianteId) || {};
        const direccion = state.direccionesMap.get(row.estudianteId) || {};
        const familiares = getFamiliaresByEstudianteId(row.estudianteId);
        const vacunas = getVacunasByEstudianteId(row.estudianteId);
        const discapacidades = getDiscapacidadesByEstudianteId(row.estudianteId);
        const requisitos = getRequisitosByInscripcionId(row.inscripcionId);

        const madre = familiares.find((f) => String(f.tipo_familiar || '').toUpperCase() === 'MADRE') || null;
        const padre = familiares.find((f) => String(f.tipo_familiar || '').toUpperCase() === 'PADRE') || null;
        const tutor = familiares.find((f) => String(f.tipo_familiar || '').toUpperCase() === 'TUTOR') || null;

        return [
            {
                titulo: 'Informacion basica',
                rows: [
                    fieldRow('ID del Sigerd', row.idSigerd),
                    fieldRow('Primer nombre', estudiante.primer_nombre),
                    fieldRow('Segundo nombre', estudiante.segundo_nombre),
                    fieldRow('Primer apellido', estudiante.primer_apellido),
                    fieldRow('Segundo apellido', estudiante.segundo_apellido),
                    fieldRow('Fecha nacimiento', formatDateIso(estudiante.fecha_nacimiento)),
                    fieldRow('Edad', row.edad),
                    fieldRow('Sexo', estudiante.sexo),
                    fieldRow('Estado civil', estudiante.estado_civil),
                    fieldRow('Nacionalidad', estudiante.nacionalidad),
                    fieldRow('Telefono', estudiante.telefono),
                    fieldRow('Celular', estudiante.celular),
                    fieldRow('Whatsapp', estudiante.whatsapp),
                ],
            },
            {
                titulo: 'Registro civil',
                rows: [
                    fieldRow('Estado del acta', registroCivil.estado_acta),
                    fieldRow('Numero de acta', registroCivil.numero_acta),
                    fieldRow('Provincia JCE', registroCivil.provincia_jce),
                    fieldRow('Municipio JCE', registroCivil.municipio_jce),
                    fieldRow('Oficialia JCE', registroCivil.oficialia_jce),
                    fieldRow('Libro', registroCivil.libro),
                    fieldRow('Folio', registroCivil.folio),
                    fieldRow('Ano', registroCivil.anio),
                ],
            },
            {
                titulo: 'Direccion',
                rows: [
                    fieldRow('Provincia', direccion.provincia),
                    fieldRow('Municipio', direccion.municipio),
                    fieldRow('Distrito municipal', direccion.distrito_municipal),
                    fieldRow('Seccion', direccion.seccion),
                    fieldRow('Barrio', direccion.barrio),
                    fieldRow('Sub barrio', direccion.sub_barrio),
                    fieldRow('Calle y numero', direccion.calle_numero),
                ],
            },
            {
                titulo: 'Vacunas y discapacidades',
                rows: [
                    fieldRow('Vacunas', vacunas.length > 0 ? vacunas.join(', ') : 'Ninguna seleccionada'),
                    fieldRow('Discapacidades', discapacidades.length > 0 ? discapacidades.join(', ') : 'Ninguna seleccionada'),
                ],
            },
            {
                titulo: 'Datos familiares',
                rows: [
                    fieldRow('Madre primer nombre', madre ? madre.primer_nombre : null),
                    fieldRow('Madre primer apellido', madre ? madre.primer_apellido : null),
                    fieldRow('Madre cedula', madre ? madre.cedula : null),
                    fieldRow('Padre primer nombre', padre ? padre.primer_nombre : null),
                    fieldRow('Padre primer apellido', padre ? padre.primer_apellido : null),
                    fieldRow('Padre cedula', padre ? padre.cedula : null),
                    fieldRow('Tutor primer nombre', tutor ? tutor.primer_nombre : null),
                    fieldRow('Tutor primer apellido', tutor ? tutor.primer_apellido : null),
                    fieldRow('Tutor cedula', tutor ? tutor.cedula : null),
                ],
            },
            {
                titulo: 'Inscripcion academica y tarifas',
                rows: [
                    fieldRow('Ano escolar', row.anio),
                    fieldRow('Nivel', row.nivel),
                    fieldRow('Grado', row.grado),
                    fieldRow('Seccion', row.seccion),
                    fieldRow('Tanda', row.tanda),
                    fieldRow('Planificacion ID', planif.id),
                    fieldRow('Centro de procedencia', inscripcion.centro_procedencia),
                    fieldRow('Tarifa inscripcion', inscripcion.tarifa_inscripcion),
                    fieldRow('Mensualidad', inscripcion.mensualidad),
                    fieldRow('Fecha de inscripcion', formatDateIso(inscripcion.fecha_inscripcion)),
                ],
            },
            {
                titulo: 'Requisitos y terminos',
                rows: [
                    fieldRow('Requisitos entregados', requisitos.length > 0 ? requisitos.join(', ') : 'Ninguno seleccionado'),
                    fieldRow('Acepta terminos y condiciones', Number(inscripcion.acepta_terminos || 0) === 1 ? 'Si' : 'No'),
                    fieldRow('Inscripcion activa', Number(inscripcion.inscripcion_activa || 0) === 1 ? 'Si' : 'No'),
                    fieldRow('Observaciones', inscripcion.observaciones || estudiante.observaciones),
                ],
            },
        ];
    }

    function buildPdfHtmlFromDetalle(detalle) {
        const resumen = detalle.resumen;
        const seccionBasica = resumen.secciones.find((section) => section.titulo === 'Informacion basica') || null;
        const seccionesRestantesHtml = resumen.secciones
            .filter((section) => section.titulo !== 'Informacion basica')
            .map((section) => '<section class="section"><h3>' + escHtml(section.titulo) + '</h3><table>' + section.rows.join('') + '</table></section>')
            .join('');

        const basicRows = seccionBasica ? seccionBasica.rows : [];
        const basicInfoHtml = '<section class="section">'
            + '<h3>Informacion basica</h3>'
            + '<div class="basic-grid">'
            + '<div class="basic-main"><table>' + basicRows.join('') + '</table></div>'
            + '<aside class="basic-photo-card">'
            + '<div class="basic-photo-title">Foto del estudiante</div>'
            + '<div class="foto foto-estudiante">' + (resumen.fotoPerfil
                ? '<img src="' + escHtml(resumen.fotoPerfil) + '" alt="Foto del estudiante">'
                : '<div class="foto-empty">Sin foto</div>') + '</div>'
            + '</aside>'
            + '</div>'
            + '</section>';

        const logoCentroHtml = resumen.centro.logo_url
            ? '<img src="' + escHtml(resumen.centro.logo_url) + '" alt="Logo del centro">'
            : '<div class="foto-empty">Sin logo</div>';

        return '<!doctype html><html lang="es"><head><meta charset="utf-8">'
            + '<title>Inscripcion estudiante</title>'
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
            + '.foto-estudiante{width:160px;min-width:160px;height:200px;}'
            + '.foto-empty{font-size:11px;color:#66717d;text-align:center;padding:8px;}'
            + '.section{margin-bottom:14px;page-break-inside:avoid;}'
            + '.section h3{margin:0 0 8px 0;font-size:14px;background:#eef2f6;padding:7px 9px;border:1px solid #c8d1db;text-transform:uppercase;letter-spacing:.3px;}'
            + '.basic-grid{display:flex;gap:14px;align-items:stretch;}'
            + '.basic-main{flex:1;min-width:0;}'
            + '.basic-photo-card{width:190px;border:1px solid #c8d1db;background:#fbfcfd;padding:8px;display:flex;flex-direction:column;align-items:center;}'
            + '.basic-photo-title{font-size:12px;font-weight:700;margin-bottom:6px;letter-spacing:.2px;text-transform:uppercase;color:#334155;}'
            + 'table{width:100%;border-collapse:collapse;}'
            + 'th,td{border:1px solid #d3dae2;padding:6px 8px;font-size:12px;vertical-align:top;}'
            + 'th{width:36%;text-align:left;background:#f7f9fb;font-weight:700;}'
            + '.actions{margin-bottom:12px;}'
            + '.actions button{padding:6px 12px;font-size:12px;}'
            + '.actions .btn-volver{margin-left:6px;background:#f1f3f5;border:1px solid #b5bcc3;}'
            + '.firmas{margin-top:30px;display:flex;justify-content:space-between;gap:24px;page-break-inside:avoid;}'
            + '.firma{flex:1;text-align:center;}'
            + '.firma-linea{margin:0 auto;width:86%;border-top:1px solid #1f2933;height:18px;}'
            + '.firma-label{font-size:12px;color:#333;}'
            + '@media print {.actions{display:none;} body{margin:8mm;} .section{break-inside:avoid;}}'
            + '</style></head><body>'
            + '<div class="actions"><button onclick="window.print()">Imprimir</button><button class="btn-volver" onclick="window.close()">Volver</button></div>'
            + '<div class="head">'
            + '<div class="head-top">'
            + '<div class="head-identidad">'
            + '<h1>' + escHtml(resumen.centro.nombre_centro) + '</h1>'
            + '<p>Codigo de centro: ' + escHtml(resumen.centro.codigo_centro) + ' | RNC: ' + escHtml(resumen.centro.rnc) + '</p>'
            + '<p>Direccion: ' + escHtml(resumen.centro.direccion) + '</p>'
            + '<p>Telefono: ' + escHtml(resumen.centro.telefono) + ' | Celular: ' + escHtml(resumen.centro.celular) + '</p>'
            + '<p>Correo: ' + escHtml(resumen.centro.correo_electronico) + '</p>'
            + (resumen.centro.lema !== '' ? '<p><strong>Lema:</strong> ' + escHtml(resumen.centro.lema) + '</p>' : '')
            + '<div class="head-documento">'
            + '<h2>Matricula estudiatil</h2>'
            + '<div class="meta">Fecha de generacion: ' + escHtml(resumen.fechaGeneracion) + '</div>'
            + '</div>'
            + '</div>'
            + '<div class="foto">' + logoCentroHtml + '</div>'
            + '</div>'
            + '</div>'
            + basicInfoHtml
            + seccionesRestantesHtml
            + '<section class="firmas">'
            + '<div class="firma"><div class="firma-linea"></div><div class="firma-label">Firma del padre, madre o tutor</div></div>'
            + '<div class="firma"><div class="firma-linea"></div><div class="firma-label">Firma del director del centro</div></div>'
            + '</section>'
            + '<script>window.onload=function(){setTimeout(function(){window.print();},300);};<' + '/script>'
            + '</body></html>';
    }

    function openPrintableDetallePdf(detalle) {
        const html = buildPdfHtmlFromDetalle(detalle);
        const win = window.open('', '_blank');
        if (!win) {
            throw new Error('No se pudo abrir la ventana de impresion. Habilita pop-ups para este sitio.');
        }

        win.document.open();
        win.document.write(html);
        win.document.close();
    }

    function openVerModal(row) {
        const estudiante = row.estudianteRaw || {};
        const centro = state.datosCentro || {};
        const sections = buildDetalleSections(row);
        const fotoPerfil = resolveStoredAssetUrl(String(estudiante.foto || ''));

        detalleActual = {
            estudianteId: Number(row.estudianteId || 0),
            resumen: {
                fechaGeneracion: formatDateIso(new Date().toISOString().slice(0, 10)),
                fotoPerfil,
                centro: {
                    nombre_centro: String(centro.nombre_centro || 'Centro educativo').trim(),
                    codigo_centro: String(centro.codigo_centro || '-').trim(),
                    rnc: String(centro.rnc || '-').trim(),
                    telefono: String(centro.telefono || '-').trim(),
                    celular: String(centro.celular || '-').trim(),
                    correo_electronico: String(centro.correo_electronico || '-').trim(),
                    lema: String(centro.lema || '').trim(),
                    direccion: String(centro.direccion || '-').trim(),
                    logo_url: resolveStoredAssetUrl(centro.logo || ''),
                },
                secciones: sections,
            },
        };

        const sectionsHtml = sections.map((section) => buildSectionHtml(section.titulo, section.rows)).join('');
        const fotoHtml = fotoPerfil !== ''
            ? '<img src="' + escHtml(fotoPerfil) + '" alt="Foto del estudiante" class="img-fluid rounded border" style="max-height:220px;">'
            : '<div class="border rounded p-4 text-muted bg-light text-center">Sin foto</div>';

        detalleContent.innerHTML = ''
            + '<div class="card shadow-sm mb-3">'
            + '<div class="card-header py-2"><h6 class="m-0 font-weight-bold text-primary">Foto del estudiante</h6></div>'
            + '<div class="card-body text-center">' + fotoHtml + '</div>'
            + '</div>'
            + sectionsHtml;

        showModal(modalVer);
    }

    function openEditarModal(row) {
        const estudiante = row.estudianteRaw || {};
        const registroCivil = state.registrosCivilesMap.get(row.estudianteId) || {};
        const direccion = state.direccionesMap.get(row.estudianteId) || {};
        const madre = getFamiliarByRole(row.estudianteId, 'MADRE');
        const padre = getFamiliarByRole(row.estudianteId, 'PADRE');
        const tutor = getFamiliarByRole(row.estudianteId, 'TUTOR');

        editarInscripcionIdInput.value = String(row.inscripcionId);
        editarEstudianteIdInput.value = String(row.estudianteId);

        setInputValue(editarIdSigerdInput, estudiante.id_sigerd);
        setInputValue(editarPrimerNombreInput, estudiante.primer_nombre);
        setInputValue(editarSegundoNombreInput, estudiante.segundo_nombre);
        setInputValue(editarPrimerApellidoInput, estudiante.primer_apellido);
        setInputValue(editarSegundoApellidoInput, estudiante.segundo_apellido);
        setInputValue(editarFechaNacimientoInput, estudiante.fecha_nacimiento);
        setInputValue(editarSexoInput, estudiante.sexo);
        setInputValue(editarEstadoCivilInput, estudiante.estado_civil);
        setInputValue(editarNacionalidadInput, estudiante.nacionalidad);
        setInputValue(editarTelefonoInput, estudiante.telefono);
        setInputValue(editarCelularInput, estudiante.celular);
        setInputValue(editarWhatsappInput, estudiante.whatsapp);

        setInputValue(editarEstadoActaInput, registroCivil.estado_acta);
        setInputValue(editarNumeroActaInput, registroCivil.numero_acta);
        setInputValue(editarProvinciaJceInput, registroCivil.provincia_jce);
        setInputValue(editarMunicipioJceInput, registroCivil.municipio_jce);
        setInputValue(editarOficialiaJceInput, registroCivil.oficialia_jce);
        setInputValue(editarLibroJceInput, registroCivil.libro);
        setInputValue(editarFolioJceInput, registroCivil.folio);
        setInputValue(editarAnioJceInput, registroCivil.anio);

        setInputValue(editarProvinciaInput, direccion.provincia);
        setInputValue(editarMunicipioInput, direccion.municipio);
        setInputValue(editarDistritoInput, direccion.distrito_municipal);
        setInputValue(editarSeccionDireccionInput, direccion.seccion);
        setInputValue(editarBarrioInput, direccion.barrio);
        setInputValue(editarSubBarrioInput, direccion.sub_barrio);
        setInputValue(editarCalleNumeroInput, direccion.calle_numero);

        setInputValue(editarMadreNombreInput, madre ? madre.primer_nombre : '');
        setInputValue(editarMadreApellidoInput, madre ? madre.primer_apellido : '');
        setInputValue(editarMadreCedulaInput, madre ? madre.cedula : '');
        setInputValue(editarPadreNombreInput, padre ? padre.primer_nombre : '');
        setInputValue(editarPadreApellidoInput, padre ? padre.primer_apellido : '');
        setInputValue(editarPadreCedulaInput, padre ? padre.cedula : '');
        setInputValue(editarTutorNombreInput, tutor ? tutor.primer_nombre : '');
        setInputValue(editarTutorApellidoInput, tutor ? tutor.primer_apellido : '');
        setInputValue(editarTutorCedulaInput, tutor ? tutor.cedula : '');

        const fotoActual = resolveStoredAssetUrl(String(estudiante.foto || ''));
        if (fotoActual !== '') {
            editarPreviewFoto.innerHTML = '<img src="' + escHtml(fotoActual) + '" alt="Foto del estudiante" class="img-fluid" style="width:100%;height:100%;object-fit:cover;">';
        } else {
            editarPreviewFoto.innerHTML = '<span class="text-muted">Sin foto</span>';
        }
        editarFotoInput.value = '';
        editarFotoNombre.textContent = 'No se selecciono nueva foto.';

        renderEditCatalogs(row);
        hydrateEditarOfertaFromRow(row);

        editarCentroInput.value = String(row.inscripcionRaw.centro_procedencia || '');
        editarFechaInscripcionInput.value = String(row.inscripcionRaw.fecha_inscripcion || '').trim() || getTodayLocalISO();
        editarAceptaTerminosInput.checked = Number(row.inscripcionRaw.acepta_terminos || 0) === 1;
        editarTarifaInput.value = String(row.inscripcionRaw.tarifa_inscripcion ?? 0);
        editarMensualidadInput.value = String(row.inscripcionRaw.mensualidad ?? 0);
        editarObservacionesInput.value = String(row.inscripcionRaw.observaciones || '');
        editarActivaInput.checked = Number(row.inscripcionRaw.inscripcion_activa || 0) === 1;
        setTarifaAyuda('La tarifa se ajusta segun la oferta academica seleccionada.', 'info');
        showModal(modalEditar);
    }

    function openHistorialModal(row) {
        const historial = state.inscripciones
            .filter((item) => Number(item.estudiante_id || 0) === row.estudianteId)
            .sort((a, b) => String(b.fecha_inscripcion || '').localeCompare(String(a.fecha_inscripcion || '')));

        const estudiante = row.estudianteRaw || {};
        const registroCivil = state.registrosCivilesMap.get(row.estudianteId) || {};
        const oficialia = String(registroCivil.oficialia_jce || '-').trim();
        const municipioActa = String(registroCivil.municipio_jce || '-').trim();
        const libroActa = String(registroCivil.libro || '-').trim();
        const folioActa = String(registroCivil.folio || '-').trim();
        const numeroActa = String(registroCivil.numero_acta || '-').trim();
        const anioActa = String(registroCivil.anio || '-').trim();
        const estadoActaRaw = String(registroCivil.estado_acta || '').trim().toUpperCase();
        const estadoActa = estadoActaRaw === 'DECLARADO'
            ? 'Declarado'
            : (estadoActaRaw === 'NO_DECLARADO' ? 'No declarado' : (estadoActaRaw === 'NO_DISPONIBLE' ? 'No disponible' : '-'));

        historialActual = {
            row,
            historial,
            encabezado: {
                nombre: String(row.estudiante || '-').trim() || '-',
                sigerd: String(row.idSigerd || '-').trim() || '-',
                fechaNacimiento: formatDateIso(String(estudiante.fecha_nacimiento || '')),
                actaTexto: 'Acta de Nacimiento ' + estadoActa
                    + ' Oficialia ' + oficialia
                    + ' Municipio ' + municipioActa
                    + ' Libro ' + libroActa
                    + ' Folio ' + folioActa
                    + ' Acta ' + numeroActa
                    + ' Ano ' + anioActa,
            },
        };

        if (historial.length === 0) {
            historialContent.innerHTML = ''
                + '<div class="mb-3">'
                + '<div><strong>Nombre del estudiante:</strong> ' + escHtml(historialActual.encabezado.nombre) + '</div>'
                + '<div><strong>[' + escHtml(historialActual.encabezado.sigerd) + ']</strong></div>'
                + '<div><strong>Fecha de nacimiento:</strong> ' + escHtml(historialActual.encabezado.fechaNacimiento) + '</div>'
                + '<div><strong>' + escHtml(historialActual.encabezado.actaTexto) + '</strong></div>'
                + '</div>'
                + '<div class="alert alert-warning mb-0">No hay historial disponible para este estudiante.</div>';
            showModal(modalHistorial);
            return;
        }

        const rowsHtml = historial.map((item, idx) => {
            const planif = state.planificacionesMap.get(Number(item.planificacion_academica_id || 0)) || null;
            const anio = planif ? state.aniosMap.get(Number(planif.anio_escolar_id || 0)) : null;
            const grado = planif ? state.gradosMap.get(Number(planif.grado_id || 0)) : null;
            const seccion = planif ? state.seccionesMap.get(Number(planif.seccion_id || 0)) : null;
            const estado = Number(item.inscripcion_activa || 0) === 1 ? 'Activa' : 'Inactiva';
            const condicionFinal = getCondicionFinalByInscripcion(item);

            return ''
                + '<tr>'
                + '<td>' + (idx + 1) + '</td>'
                + '<td>' + escHtml(String((anio && anio.nombre) || '-')) + '</td>'
                + '<td>' + escHtml(String((grado && grado.grado) || '-')) + '</td>'
                + '<td>' + escHtml(String((seccion && seccion.seccion) || '-')) + '</td>'
                + '<td>' + escHtml(formatDateIso(item.fecha_inscripcion || '')) + '</td>'
                + '<td>' + escHtml(estado) + '</td>'
                + '<td>' + escHtml(condicionFinal) + '</td>'
                + '</tr>';
        }).join('');

        historialContent.innerHTML = ''
            + '<div class="mb-3">'
            + '<div><strong>Nombre del estudiante:</strong> ' + escHtml(historialActual.encabezado.nombre) + '</div>'
            + '<div><strong>[' + escHtml(historialActual.encabezado.sigerd) + ']</strong></div>'
            + '<div><strong>Fecha de nacimiento:</strong> ' + escHtml(historialActual.encabezado.fechaNacimiento) + '</div>'
            + '<div><strong>' + escHtml(historialActual.encabezado.actaTexto) + '</strong></div>'
            + '</div>'
            + '<div class="table-responsive">'
            + '<table class="table table-bordered table-sm">'
            + '<thead class="thead-light"><tr><th>#</th><th>Ano escolar</th><th>Grado</th><th>Seccion</th><th>Fecha</th><th>Estado</th><th>Condicion final</th></tr></thead>'
            + '<tbody>' + rowsHtml + '</tbody>'
            + '</table>'
            + '</div>';

        showModal(modalHistorial);
    }

    function buildHistorialPdfHtml(payload) {
        const encabezado = payload.encabezado || {};
        const row = payload.row || {};
        const historial = Array.isArray(payload.historial) ? payload.historial : [];
        const centro = state.datosCentro || {};

        const rowsHtml = historial.map((item, idx) => {
            const planif = state.planificacionesMap.get(Number(item.planificacion_academica_id || 0)) || null;
            const anio = planif ? state.aniosMap.get(Number(planif.anio_escolar_id || 0)) : null;
            const grado = planif ? state.gradosMap.get(Number(planif.grado_id || 0)) : null;
            const seccion = planif ? state.seccionesMap.get(Number(planif.seccion_id || 0)) : null;
            const estado = Number(item.inscripcion_activa || 0) === 1 ? 'Activa' : 'Inactiva';
            const condicionFinal = getCondicionFinalByInscripcion(item);

            return ''
                + '<tr>'
                + '<td>' + (idx + 1) + '</td>'
                + '<td>' + escHtml(String((anio && anio.nombre) || '-')) + '</td>'
                + '<td>' + escHtml(String((grado && grado.grado) || '-')) + '</td>'
                + '<td>' + escHtml(String((seccion && seccion.seccion) || '-')) + '</td>'
                + '<td>' + escHtml(formatDateIso(item.fecha_inscripcion || '')) + '</td>'
                + '<td>' + escHtml(estado) + '</td>'
                + '<td>' + escHtml(condicionFinal) + '</td>'
                + '</tr>';
        }).join('');

        return ''
            + '<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Historial del estudiante</title>'
            + '<style>'
            + 'body{font-family:Arial,Helvetica,sans-serif;color:#111827;background:#fff;margin:10mm;}'
            + '.actions{margin-bottom:10px;}'
            + '.actions button{padding:6px 12px;font-size:12px;margin-right:6px;}'
            + '.head{border-bottom:2px solid #111827;padding-bottom:8px;margin-bottom:12px;}'
            + '.head h1{margin:0 0 4px;font-size:20px;}'
            + '.head p{margin:0;font-size:12px;color:#4b5563;}'
            + '.student{margin-bottom:10px;line-height:1.5;}'
            + '.student strong{font-weight:700;}'
            + 'table{width:100%;border-collapse:collapse;font-size:13px;}'
            + 'th,td{border:1px solid #d1d5db;padding:8px;text-align:left;}'
            + 'th{background:#f3f4f6;text-transform:uppercase;font-size:11px;}'
            + '.empty{border:1px dashed #d1d5db;padding:10px;color:#6b7280;}'
            + '@media print {.actions{display:none;} body{margin:8mm;}}'
            + '</style></head><body>'
            + '<div class="actions"><button onclick="window.print()">Imprimir</button><button onclick="window.close()">Cerrar</button></div>'
            + '<div class="head">'
            + '<h1>Historial de inscripciones del estudiante</h1>'
            + '<p>Centro: ' + escHtml(String(centro.nombre_centro || 'Centro educativo')) + '</p>'
            + '</div>'
            + '<div class="student">'
            + '<div><strong>Nombre del estudiante:</strong> ' + escHtml(String(encabezado.nombre || row.estudiante || '-')) + '</div>'
            + '<div><strong>[' + escHtml(String(encabezado.sigerd || row.idSigerd || '-')) + ']</strong></div>'
            + '<div><strong>Fecha de nacimiento:</strong> ' + escHtml(String(encabezado.fechaNacimiento || '-')) + '</div>'
            + '<div><strong>' + escHtml(String(encabezado.actaTexto || '-')) + '</strong></div>'
            + '</div>'
            + (historial.length > 0
                ? ('<table><thead><tr><th>#</th><th>Ano escolar</th><th>Grado</th><th>Seccion</th><th>Fecha</th><th>Estado</th><th>Condicion final</th></tr></thead><tbody>' + rowsHtml + '</tbody></table>')
                : '<div class="empty">No hay historial de inscripciones para este estudiante.</div>')
            + '<script>window.onload=function(){setTimeout(function(){window.print();},250);};<' + '/script>'
            + '</body></html>';
    }

    function openPrintableHistorialPdf(payload) {
        const html = buildHistorialPdfHtml(payload);
        const win = window.open('', '_blank');
        if (!win) {
            throw new Error('No se pudo abrir la ventana de impresion para el historial.');
        }

        win.document.open();
        win.document.write(html);
        win.document.close();
    }

    function openCarnet(row) {
        const foto = row.estudianteRaw ? resolveStoredAssetUrl(String(row.estudianteRaw.foto || '')) : '';
        const fotoHtml = foto !== ''
            ? '<img src="' + escHtml(foto) + '" alt="Foto" style="width:100%;height:100%;object-fit:cover;">'
            : '<div style="font-size:12px;color:#6b7280;display:flex;align-items:center;justify-content:center;height:100%;">Sin foto</div>';

        const estudianteId = Number(row.estudianteId || 0);
        const inscripcionId = Number(row.inscripcionId || 0);
        const sigerd = String(row.idSigerd || '').trim();
        const verifyUrl = getPublicBaseUrl()
            + 'verificar-carnet.php?inscripcion=' + encodeURIComponent(String(inscripcionId))
            + '&estudiante=' + encodeURIComponent(String(estudianteId))
            + '&sigerd=' + encodeURIComponent(sigerd);
        const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=170x170&data=' + encodeURIComponent(verifyUrl);

        const centro = state.datosCentro || {};
        const html = ''
            + '<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Carnet estudiante</title>'
            + '<style>'
            + '@page{size:91.6mm 60mm;margin:0;}'
            + '*{box-sizing:border-box;}'
            + 'body{font-family:Arial,Helvetica,sans-serif;background:#eef2f7;margin:0;padding:0;display:flex;align-items:center;justify-content:center;min-height:100vh;}'
            + '.sheet{width:91.6mm;height:60mm;padding:3mm;background:linear-gradient(145deg,#f8fafc,#e2e8f0);display:flex;align-items:center;justify-content:center;}'
            + '.card{width:85.6mm;height:54mm;background:#fff;border:0.35mm solid #1f2937;border-radius:2.4mm;overflow:hidden;position:relative;}'
            + '.head{height:8mm;background:#1f2937;color:#fff;padding:1.6mm 2.5mm;font-weight:700;letter-spacing:.2px;font-size:2.6mm;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}'
            + '.safe{padding:3mm;display:grid;grid-template-columns:14.5mm 1fr;gap:2.2mm;}'
            + '.foto{width:14.5mm;height:18.5mm;border:0.25mm solid #cfd7df;background:#fff;overflow:hidden;border-radius:1mm;}'
            + '.info{min-width:0;display:grid;grid-template-columns:1fr 16.5mm;gap:1.2mm;align-items:start;}'
            + '.datos{min-width:0;}'
            + '.nombre{font-size:2.8mm;font-weight:700;line-height:1.1;max-height:7.2mm;overflow:hidden;margin:0 0 .2mm 0;}'
            + '.meta{font-size:2.35mm;line-height:1.2;color:#0f172a;margin:0 0 .35mm 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}'
            + '.meta b{font-weight:700;}'
            + '.verify{display:flex;flex-direction:column;align-items:center;gap:1.1mm;}'
            + '.verify-qr{width:16.5mm;height:16.5mm;border:0.25mm solid #cfd7df;border-radius:1mm;overflow:hidden;background:#fff;display:flex;align-items:center;justify-content:center;}'
            + '.verify-qr img{width:100%;height:100%;object-fit:contain;}'
            + '.verify-text{font-size:1.85mm;color:#334155;text-align:center;line-height:1.15;}'
            + '.foot{position:absolute;left:0;right:0;bottom:0;height:5mm;border-top:0.25mm solid #d7dce3;padding:1mm 2.5mm;display:flex;justify-content:flex-start;align-items:center;font-size:2mm;color:#475569;background:#fff;}'
            + '.actions{position:fixed;top:8px;left:8px;display:flex;gap:8px;z-index:9999;}'
            + '.actions button{border:1px solid #cbd5e1;background:#fff;padding:6px 10px;border-radius:6px;font-size:12px;cursor:pointer;}'
            + '@media print {.actions{display:none;} body{background:#fff;} .sheet{padding:3mm;}}'
            + '</style></head><body>'
            + '<div class="actions"><button onclick="window.print()">Imprimir</button><button onclick="window.close()">Cerrar</button></div>'
            + '<div class="sheet">'
            + '<div class="card">'
            + '<div class="head">' + escHtml(String(centro.nombre_centro || 'Centro educativo')) + ' - CARNET ESTUDIANTIL</div>'
            + '<div class="safe">'
            + '<div class="foto">' + fotoHtml + '</div>'
            + '<div class="info">'
            + '<div class="datos">'
            + '<div class="nombre">' + escHtml(row.estudiante) + '</div>'
            + '<div class="meta"><b>ID SIGERD:</b> ' + escHtml(row.idSigerd) + '</div>'
            + '<div class="meta"><b>Ano:</b> ' + escHtml(row.anio) + '</div>'
            + '<div class="meta"><b>Nivel/Grado:</b> ' + escHtml(row.nivel) + ' / ' + escHtml(row.grado) + '</div>'
            + '<div class="meta"><b>Seccion/Tanda:</b> ' + escHtml(row.seccion) + ' / ' + escHtml(row.tanda) + '</div>'
            + '</div>'
            + '<div class="verify">'
            + '<div class="verify-qr"><img src="' + escHtml(qrUrl) + '" alt="QR de verificacion"></div>'
            + '<div class="verify-text"><strong>Verificar</strong><br>Autenticidad</div>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '<div class="foot"><span>Emision: ' + escHtml(formatDateIso(new Date().toISOString().slice(0, 10))) + '</span></div>'
            + '</div>'
            + '</div>'
            + '<script>window.onload=function(){setTimeout(function(){window.print();},250);};<' + '/script>'
            + '</body></html>';

        const win = window.open('', '_blank');
        if (!win) {
            window.alert('No se pudo abrir la ventana de impresion.');
            return;
        }

        win.document.open();
        win.document.write(html);
        win.document.close();
    }

    async function deleteInscripcion(row) {
        const confirmed = window.confirm('Deseas eliminar la inscripcion ID ' + row.inscripcionId + ' de ' + row.estudiante + '?');
        if (!confirmed) {
            return;
        }

        await apiDestroy('inscripciones', { id: row.inscripcionId });
        await reloadData();
        setStatus('Inscripcion eliminada correctamente.', 'success');
    }

    async function reloadData() {
        const [
            inscripciones,
            estudiantes,
            planificaciones,
            anios,
            niveles,
            grados,
            secciones,
            tandas,
            datosCentro,
            registrosCiviles,
            direcciones,
            estudianteVacunas,
            vacunas,
            estudianteDiscapacidades,
            discapacidades,
            estudianteFamiliares,
            familiares,
            inscripcionRequisitos,
            requisitosInscripcion,
            tarifarios,
            tarifasGrados,
            historialAcademico,
        ] = await Promise.all([
            apiGet('inscripciones'),
            apiGet('estudiantes'),
            apiGet('planificaciones_academicas'),
            apiGet('anios_escolares'),
            apiGet('niveles'),
            apiGet('grados'),
            apiGet('secciones'),
            apiGet('tandas'),
            apiGet('datos_centro_educativo'),
            apiGet('registros_civiles'),
            apiGet('direcciones_estudiantes'),
            apiGet('estudiante_vacunas'),
            apiGet('vacunas'),
            apiGet('estudiante_discapacidades'),
            apiGet('discapacidades'),
            apiGet('estudiante_familiares'),
            apiGet('familiares'),
            apiGet('inscripcion_requisitos'),
            apiGet('requisitos_inscripcion'),
            apiGet('tarifarios'),
            apiGet('tarifas_grados'),
            apiGet('historial_academico'),
        ]);

        state.inscripciones = inscripciones;
        state.estudiantesMap = toMap(estudiantes, 'id');
        state.planificacionesMap = toMap(planificaciones, 'id');
        state.aniosMap = toMap(anios, 'id');
        state.nivelesMap = toMap(niveles, 'id');
        state.gradosMap = toMap(grados, 'id');
        state.seccionesMap = toMap(secciones, 'id');
        state.tandasMap = toMap(tandas, 'id');
        state.datosCentro = (datosCentro.find((row) => Number(row.estado ?? 1) === 1) || datosCentro[0] || null);
        state.registrosCivilesMap = new Map(registrosCiviles.map((row) => [Number(row.estudiante_id || 0), row]));
        state.direccionesMap = new Map(direcciones.map((row) => [Number(row.estudiante_id || 0), row]));
        state.estudianteVacunasByEstudianteId = buildMapArray(estudianteVacunas, 'estudiante_id');
        state.estudianteDiscapacidadesByEstudianteId = buildMapArray(estudianteDiscapacidades, 'estudiante_id');
        state.estudianteFamiliaresByEstudianteId = buildMapArray(estudianteFamiliares, 'estudiante_id');
        state.requisitosByInscripcionId = buildMapArray(inscripcionRequisitos, 'inscripcion_id');
        state.vacunasMap = toMap(vacunas, 'id');
        state.discapacidadesMap = toMap(discapacidades, 'id');
        state.familiaresMap = toMap(familiares, 'id');
        state.requisitosMap = toMap(requisitosInscripcion, 'id');
        state.tarifarios = tarifarios;
        state.tarifasGrados = tarifasGrados;
        state.historialAcademicoByKey = new Map();
        historialAcademico.forEach((item) => {
            const key = buildHistorialAcademicoKey(
                Number(item.estudiante_id || 0),
                Number(item.anio_escolar_id || 0),
                Number(item.nivel_id || 0),
                Number(item.grado_id || 0),
                Number(item.seccion_id || 0)
            );
            if (key === '0|0|0|0|0') {
                return;
            }

            const current = state.historialAcademicoByKey.get(key) || null;
            if (!current || Number(item.id || 0) > Number(current.id || 0)) {
                state.historialAcademicoByKey.set(key, item);
            }
        });
        resolveDefaultTarifarioId();

        const selectedAnioBefore = Number(filtroAnio.value || 0);
        const selectedGradoBefore = Number(filtroGrado.value || 0);

        renderFiltroAnios();
        if (selectedAnioBefore > 0) {
            const stillExistsAnio = filtroAnio.querySelector('option[value="' + selectedAnioBefore + '"]');
            if (stillExistsAnio) {
                filtroAnio.value = String(selectedAnioBefore);
            }
        }

        renderFiltroGrados();
        if (selectedGradoBefore > 0) {
            const stillExistsGrado = filtroGrado.querySelector('option[value="' + selectedGradoBefore + '"]');
            if (stillExistsGrado) {
                filtroGrado.value = String(selectedGradoBefore);
            }
        }

        applyFiltersFromQuery();

        renderRows();
    }

    async function init() {
        setStatus('Cargando datos de inscripciones, anos y grados...', 'info');

        try {
            await reloadData();
        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="11" class="text-center text-danger">No se pudo cargar la relacion de estudiantes.</td></tr>';
            setStatus(error.message || 'Error cargando la relacion de estudiantes.', 'danger');
        }
    }

    tbody.addEventListener('click', async function (event) {
        const target = event.target instanceof HTMLElement ? event.target.closest('.btn-action') : null;
        if (!(target instanceof HTMLElement)) {
            return;
        }

        const action = String(target.dataset.action || '').trim();
        const inscripcionId = Number(target.dataset.inscripcionId || 0);
        const row = getRowByInscripcionId(inscripcionId);
        if (!row) {
            window.alert('No se encontro la inscripcion seleccionada.');
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

            if (action === 'historial') {
                openHistorialModal(row);
                return;
            }

            if (action === 'carnet') {
                openCarnet(row);
                return;
            }

            if (action === 'eliminar') {
                await deleteInscripcion(row);
            }
        } catch (error) {
            window.alert(error.message || 'No se pudo ejecutar la accion solicitada.');
        }
    });

    formEditar.addEventListener('submit', async function (event) {
        event.preventDefault();

        if (!formEditar.reportValidity()) {
            return;
        }

        const inscripcionId = Number(editarInscripcionIdInput.value || 0);
        const estudianteId = Number(editarEstudianteIdInput.value || 0);
        if (inscripcionId <= 0) {
            window.alert('Inscripcion no valida para editar.');
            return;
        }
        if (estudianteId <= 0) {
            window.alert('Estudiante no valido para editar.');
            return;
        }

        const original = btnGuardarEdicion.innerHTML;
        btnGuardarEdicion.disabled = true;
        btnGuardarEdicion.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...';

        try {
            const planificacionIdNueva = resolveActivePlanificacionIdFromEditSelection();
            if (planificacionIdNueva <= 0) {
                window.alert('La combinacion de ano, grado, seccion y tanda no tiene una oferta academica activa.');
                return;
            }

            await apiUpdate('estudiantes', { id: estudianteId }, {
                id_sigerd: toNullableString(editarIdSigerdInput.value),
                primer_nombre: String(editarPrimerNombreInput.value || '').trim(),
                segundo_nombre: toNullableString(editarSegundoNombreInput.value),
                primer_apellido: String(editarPrimerApellidoInput.value || '').trim(),
                segundo_apellido: toNullableString(editarSegundoApellidoInput.value),
                fecha_nacimiento: toNullableString(editarFechaNacimientoInput.value),
                sexo: toNullableString(editarSexoInput.value),
                estado_civil: toNullableString(editarEstadoCivilInput.value),
                nacionalidad: toNullableString(editarNacionalidadInput.value),
                telefono: toNullableString(editarTelefonoInput.value),
                celular: toNullableString(editarCelularInput.value),
                whatsapp: toNullableString(editarWhatsappInput.value),
            });

            const registroCivilPayload = {
                estado_acta: toNullableString(editarEstadoActaInput.value),
                numero_acta: toNullableString(editarNumeroActaInput.value),
                provincia_jce: toNullableString(editarProvinciaJceInput.value),
                municipio_jce: toNullableString(editarMunicipioJceInput.value),
                oficialia_jce: toNullableString(editarOficialiaJceInput.value),
                libro: toNullableString(editarLibroJceInput.value),
                folio: toNullableString(editarFolioJceInput.value),
                anio: toNullableYear(editarAnioJceInput.value),
            };

            const hasRegistroCivilData = hasAnyValue([
                registroCivilPayload.estado_acta,
                registroCivilPayload.numero_acta,
                registroCivilPayload.provincia_jce,
                registroCivilPayload.municipio_jce,
                registroCivilPayload.oficialia_jce,
                registroCivilPayload.libro,
                registroCivilPayload.folio,
                registroCivilPayload.anio,
            ]);

            const existingRegistroCivil = state.registrosCivilesMap.get(estudianteId) || null;
            if (existingRegistroCivil) {
                await apiUpdate('registros_civiles', { id: Number(existingRegistroCivil.id || 0) }, registroCivilPayload);
            } else if (hasRegistroCivilData) {
                await apiStore('registros_civiles', Object.assign({ estudiante_id: estudianteId }, registroCivilPayload));
            }

            const direccionPayload = {
                provincia: toNullableString(editarProvinciaInput.value),
                municipio: toNullableString(editarMunicipioInput.value),
                distrito_municipal: toNullableString(editarDistritoInput.value),
                seccion: toNullableString(editarSeccionDireccionInput.value),
                barrio: toNullableString(editarBarrioInput.value),
                sub_barrio: toNullableString(editarSubBarrioInput.value),
                calle_numero: toNullableString(editarCalleNumeroInput.value),
            };

            const hasDireccionData = hasAnyValue([
                direccionPayload.provincia,
                direccionPayload.municipio,
                direccionPayload.distrito_municipal,
                direccionPayload.seccion,
                direccionPayload.barrio,
                direccionPayload.sub_barrio,
                direccionPayload.calle_numero,
            ]);

            const existingDireccion = state.direccionesMap.get(estudianteId) || null;
            if (existingDireccion) {
                await apiUpdate('direcciones_estudiantes', { id: Number(existingDireccion.id || 0) }, direccionPayload);
            } else if (hasDireccionData) {
                await apiStore('direcciones_estudiantes', Object.assign({ estudiante_id: estudianteId }, direccionPayload));
            }

            await syncFamiliarByRole(estudianteId, 'MADRE', editarMadreNombreInput.value, editarMadreApellidoInput.value, editarMadreCedulaInput.value);
            await syncFamiliarByRole(estudianteId, 'PADRE', editarPadreNombreInput.value, editarPadreApellidoInput.value, editarPadreCedulaInput.value);
            await syncFamiliarByRole(estudianteId, 'TUTOR', editarTutorNombreInput.value, editarTutorApellidoInput.value, editarTutorCedulaInput.value);

            const nuevaFotoPath = await uploadFotoEstudianteIfNeeded();
            if (nuevaFotoPath !== '') {
                await apiUpdate('estudiantes', { id: estudianteId }, { foto: nuevaFotoPath });
            }

            const selectedVacunaIds = getSelectedIdsFromContainer(editarVacunasList);
            const selectedVacunaSet = new Set(selectedVacunaIds);
            const currentVacunaRows = state.estudianteVacunasByEstudianteId.get(estudianteId) || [];
            const currentVacunaIds = currentVacunaRows.map((rowItem) => Number(rowItem.vacuna_id || 0));

            for (const vacunaId of currentVacunaIds) {
                if (!selectedVacunaSet.has(vacunaId)) {
                    await apiDestroy('estudiante_vacunas', {
                        estudiante_id: estudianteId,
                        vacuna_id: vacunaId,
                    });
                }
            }

            for (const vacunaId of selectedVacunaSet) {
                if (!currentVacunaIds.includes(vacunaId)) {
                    await apiStore('estudiante_vacunas', {
                        estudiante_id: estudianteId,
                        vacuna_id: vacunaId,
                    });
                }
            }

            const selectedDiscapIds = getSelectedIdsFromContainer(editarDiscapacidadesList);
            const selectedDiscapSet = new Set(selectedDiscapIds);
            const currentDiscapRows = state.estudianteDiscapacidadesByEstudianteId.get(estudianteId) || [];
            const currentDiscapIds = currentDiscapRows.map((rowItem) => Number(rowItem.discapacidad_id || 0));

            for (const discapacidadId of currentDiscapIds) {
                if (!selectedDiscapSet.has(discapacidadId)) {
                    await apiDestroy('estudiante_discapacidades', {
                        estudiante_id: estudianteId,
                        discapacidad_id: discapacidadId,
                    });
                }
            }

            for (const discapacidadId of selectedDiscapSet) {
                if (!currentDiscapIds.includes(discapacidadId)) {
                    await apiStore('estudiante_discapacidades', {
                        estudiante_id: estudianteId,
                        discapacidad_id: discapacidadId,
                    });
                }
            }

            const selectedReqIds = getSelectedIdsFromContainer(editarRequisitosList);
            const selectedReqSet = new Set(selectedReqIds);
            const currentReqRows = state.requisitosByInscripcionId.get(inscripcionId) || [];
            const currentReqMap = new Map(currentReqRows.map((rowItem) => [Number(rowItem.requisito_id || 0), rowItem]));
            const allReqIds = Array.from(state.requisitosMap.keys()).map((id) => Number(id || 0)).filter((id) => id > 0);

            for (const requisitoId of allReqIds) {
                const selected = selectedReqSet.has(requisitoId);
                const existing = currentReqMap.get(requisitoId) || null;

                if (!existing && selected) {
                    await apiStore('inscripcion_requisitos', {
                        inscripcion_id: inscripcionId,
                        requisito_id: requisitoId,
                        presentado: 1,
                    });
                    continue;
                }

                if (existing) {
                    const expected = selected ? 1 : 0;
                    const current = Number(existing.presentado || 0);
                    if (current !== expected) {
                        await apiUpdate('inscripcion_requisitos', {
                            inscripcion_id: inscripcionId,
                            requisito_id: requisitoId,
                        }, {
                            presentado: expected,
                        });
                    }
                }
            }

            await apiUpdate('inscripciones', { id: inscripcionId }, {
                planificacion_academica_id: planificacionIdNueva,
                centro_procedencia: toNullableString(editarCentroInput.value),
                fecha_inscripcion: toNullableString(editarFechaInscripcionInput.value),
                acepta_terminos: editarAceptaTerminosInput.checked ? 1 : 0,
                tarifa_inscripcion: toDecimal(editarTarifaInput.value),
                mensualidad: toDecimal(editarMensualidadInput.value),
                inscripcion_activa: editarActivaInput.checked ? 1 : 0,
                observaciones: toNullableString(editarObservacionesInput.value),
            });

            hideModal(modalEditar);
            await reloadData();
            setStatus('Inscripcion actualizada correctamente.', 'success');
        } catch (error) {
            window.alert(error.message || 'No se pudo actualizar la inscripcion.');
        } finally {
            btnGuardarEdicion.disabled = false;
            btnGuardarEdicion.innerHTML = original;
        }
    });

    filtroAnio.addEventListener('change', function () {
        renderFiltroGrados();
        renderRows();
    });

    filtroNombre.addEventListener('input', renderRows);
    filtroSigerd.addEventListener('input', renderRows);
    filtroGrado.addEventListener('change', renderRows);

    editarFotoInput.addEventListener('change', function () {
        const file = editarFotoInput.files && editarFotoInput.files.length > 0 ? editarFotoInput.files[0] : null;
        if (!file) {
            editarFotoNombre.textContent = 'No se selecciono nueva foto.';
            return;
        }

        editarFotoNombre.textContent = file.name;
        const reader = new FileReader();
        reader.onload = function (event) {
            const src = String(event.target && event.target.result ? event.target.result : '');
            if (src !== '') {
                editarPreviewFoto.innerHTML = '<img src="' + src + '" alt="Foto del estudiante" class="img-fluid" style="width:100%;height:100%;object-fit:cover;">';
            }
        };
        reader.readAsDataURL(file);
    });

    editarAnioAcademicoInput.addEventListener('change', function () {
        renderEditarOfertaGrados('');
        renderEditarOfertaSecciones('');
        renderEditarOfertaTandas('');
        const planifId = resolveActivePlanificacionIdFromEditSelection();
        if (planifId > 0) {
            applyTarifaByPlanifId(planifId);
        } else {
            setTarifaAyuda('Completa la combinacion de ano, grado, seccion y tanda para calcular tarifa.', 'warning');
        }
    });

    editarGradoAcademicoInput.addEventListener('change', function () {
        renderEditarOfertaSecciones('');
        renderEditarOfertaTandas('');
        const planifId = resolveActivePlanificacionIdFromEditSelection();
        if (planifId > 0) {
            applyTarifaByPlanifId(planifId);
        } else {
            setTarifaAyuda('Completa la combinacion de ano, grado, seccion y tanda para calcular tarifa.', 'warning');
        }
    });

    editarSeccionAcademicaInput.addEventListener('change', function () {
        renderEditarOfertaTandas('');
        const planifId = resolveActivePlanificacionIdFromEditSelection();
        if (planifId > 0) {
            applyTarifaByPlanifId(planifId);
        } else {
            setTarifaAyuda('Completa la combinacion de ano, grado, seccion y tanda para calcular tarifa.', 'warning');
        }
    });

    editarTandaAcademicaInput.addEventListener('change', function () {
        const planifId = resolveActivePlanificacionIdFromEditSelection();
        if (planifId > 0) {
            applyTarifaByPlanifId(planifId);
        } else {
            setTarifaAyuda('Completa la combinacion de ano, grado, seccion y tanda para calcular tarifa.', 'warning');
        }
    });

    btnReportePdf.addEventListener('click', function () {
        const rows = getCurrentFilteredRows();
        if (rows.length === 0) {
            window.alert('No hay registros para generar el reporte con los filtros actuales.');
            return;
        }

        buildReportePdf(rows);
    });

    btnImprimirDetallePdf.addEventListener('click', function () {
        if (!detalleActual) {
            window.alert('Primero abre el detalle de un estudiante.');
            return;
        }

        try {
            openPrintableDetallePdf(detalleActual);
        } catch (error) {
            window.alert(error.message || 'No se pudo generar el PDF del detalle.');
        }
    });

    btnReinscribirDesdeDetalle.addEventListener('click', function () {
        if (!detalleActual || Number(detalleActual.estudianteId || 0) <= 0) {
            window.alert('No se pudo identificar el estudiante para reinscribir.');
            return;
        }

        const estudianteId = Number(detalleActual.estudianteId || 0);
        const returnTo = buildRelacionReturnUrl();
        window.location.href = 'index.php?view=reinscripcion-estudiantes&estudiante_id=' + encodeURIComponent(String(estudianteId)) + '&return_to=' + encodeURIComponent(returnTo);
    });

    btnImprimirHistorialPdf.addEventListener('click', function () {
        if (!historialActual) {
            window.alert('Primero abre el historial de un estudiante.');
            return;
        }

        try {
            openPrintableHistorialPdf(historialActual);
        } catch (error) {
            window.alert(error.message || 'No se pudo generar el PDF del historial.');
        }
    });

    document.addEventListener('click', function (event) {
        const target = event.target instanceof HTMLElement ? event.target : null;
        if (!target) {
            return;
        }

        const dismiss = target.closest('[data-dismiss="modal"]');
        if (!dismiss) {
            return;
        }

        const modal = dismiss.closest('.modal');
        if (modal instanceof HTMLElement) {
            hideModal(modal);
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') {
            return;
        }

        [modalVer, modalEditar, modalHistorial].forEach((modal) => {
            if (!(modal instanceof HTMLElement)) {
                return;
            }

            if (modal.classList.contains('show') || modal.style.display === 'block') {
                hideModal(modal);
            }
        });
    });

    init();
})();
</script>
