<?php

declare(strict_types=1);
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Nueva inscripcion</h1>
</div>

<form id="form-inscripcion-estudiante" class="inscripcion-form">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informacion basica</h6>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-lg-4">
                    <div class="border rounded p-3 h-100 bg-light">
                        <div id="preview-foto" class="d-flex align-items-center justify-content-center border rounded mb-2" style="height: 190px; background:#fff;">
                            <span class="text-muted">Sin foto</span>
                        </div>
                        <label for="foto-estudiante" class="font-weight-bold">Foto del estudiante</label>
                        <p class="small text-muted mb-2">Sube una imagen clara del rostro. Formatos permitidos: JPG, PNG, WEBP.</p>
                        <input id="foto-estudiante" name="foto_estudiante" type="file" class="form-control-file" accept="image/jpeg,image/png,image/webp">
                        <small id="foto-estudiante-nombre" class="form-text text-muted">Ningun archivo seleccionado</small>
                    </div>
                </div>
                <div class="form-group col-lg-8">
                    <div class="form-row">
                        <div class="form-group col-md-4"><label for="sigerd_id">ID del Sigerd</label><input id="sigerd_id" name="sigerd_id" type="text" class="form-control" maxlength="40"></div>
                        <div class="form-group col-md-4"><label for="primer_nombre">Primer nombre *</label><input id="primer_nombre" name="primer_nombre" type="text" class="form-control" required maxlength="80"></div>
                        <div class="form-group col-md-4"><label for="segundo_nombre">Segundo nombre</label><input id="segundo_nombre" name="segundo_nombre" type="text" class="form-control" maxlength="80"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4"><label for="primer_apellido">Primer apellido *</label><input id="primer_apellido" name="primer_apellido" type="text" class="form-control" required maxlength="80"></div>
                        <div class="form-group col-md-4"><label for="segundo_apellido">Segundo apellido</label><input id="segundo_apellido" name="segundo_apellido" type="text" class="form-control" maxlength="80"></div>
                        <div class="form-group col-md-4"><label for="fecha_nacimiento">Fecha nacimiento</label><input id="fecha_nacimiento" name="fecha_nacimiento" type="date" class="form-control"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="sexo">Sexo</label>
                            <select id="sexo" name="sexo" class="form-control">
                                <option value="">Selecciona</option>
                                <option value="MASCULINO">Masculino</option>
                                <option value="FEMENINO">Femenino</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="estado_civil">Estado civil</label>
                            <select id="estado_civil" name="estado_civil" class="form-control">
                                <option value="">Selecciona</option>
                                <option>Soltero(a)</option>
                                <option>Casado(a)</option>
                                <option>Viudo(a)</option>
                                <option>Divorciado(a)</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="nacionalidad">Nacionalidad</label>
                            <select id="nacionalidad" name="nacionalidad" class="form-control">
                                <option value="">Selecciona</option>
                                <option>Alemana</option>
                                <option>Argentina</option>
                                <option>Brasilena</option>
                                <option>Chilena</option>
                                <option>Colombiana</option>
                                <option>Costarricense</option>
                                <option>Cubana</option>
                                <option>Dominicana</option>
                                <option>Ecuatoriana</option>
                                <option>Espanola</option>
                                <option>Estadounidense</option>
                                <option>Francesa</option>
                                <option>Guatemalteca</option>
                                <option>Haitiana</option>
                                <option>Hondurena</option>
                                <option>Italiana</option>
                                <option>Mexicana</option>
                                <option>Nicaraguense</option>
                                <option>Otra</option>
                                <option>Panamena</option>
                                <option>Peruana</option>
                                <option>Puertorriquena</option>
                                <option>Salvadorena</option>
                                <option>Venezolana</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3"><label for="telefono">Telefono</label><input id="telefono" name="telefono" type="text" class="form-control" maxlength="30"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3"><label for="celular">Celular</label><input id="celular" name="celular" type="text" class="form-control" maxlength="30"></div>
                        <div class="form-group col-md-3"><label for="whatsapp">Whatsapp</label><input id="whatsapp" name="whatsapp" type="text" class="form-control" maxlength="30"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Registro civil</h6>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="estado_acta">Estado del acta</label>
                    <select id="estado_acta" name="estado_acta" class="form-control">
                        <option value="">Selecciona</option>
                        <option>Declarado</option>
                        <option>No declarado</option>
                        <option>No disponible</option>
                    </select>
                </div>
                <div class="form-group col-md-3"><label for="numero_acta">Numero de acta</label><input id="numero_acta" name="numero_acta" type="text" class="form-control" maxlength="60"></div>
                <div class="form-group col-md-3"><label for="provincia_jce">Provincia JCE</label><input id="provincia_jce" name="provincia_jce" type="text" class="form-control" maxlength="120"></div>
                <div class="form-group col-md-3"><label for="municipio_jce">Municipio JCE</label><input id="municipio_jce" name="municipio_jce" type="text" class="form-control" maxlength="120"></div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3"><label for="oficialia_jce">Oficialia JCE</label><input id="oficialia_jce" name="oficialia_jce" type="text" class="form-control" maxlength="120"></div>
                <div class="form-group col-md-3"><label for="libro_jce">Libro</label><input id="libro_jce" name="libro_jce" type="text" class="form-control" maxlength="30"></div>
                <div class="form-group col-md-3"><label for="folio_jce">Folio</label><input id="folio_jce" name="folio_jce" type="text" class="form-control" maxlength="30"></div>
                <div class="form-group col-md-3"><label for="anio_jce">Ano</label><input id="anio_jce" name="anio_jce" type="number" class="form-control" min="1900" max="2099"></div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Direccion</h6>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-3"><label for="provincia">Provincia</label><input id="provincia" name="provincia" type="text" class="form-control" maxlength="120"></div>
                <div class="form-group col-md-3"><label for="municipio">Municipio</label><input id="municipio" name="municipio" type="text" class="form-control" maxlength="120"></div>
                <div class="form-group col-md-3"><label for="distrito_municipal">Distrito municipal</label><input id="distrito_municipal" name="distrito_municipal" type="text" class="form-control" maxlength="120"></div>
                <div class="form-group col-md-3"><label for="seccion_direccion">Seccion</label><input id="seccion_direccion" name="seccion_direccion" type="text" class="form-control" maxlength="120"></div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3"><label for="barrio">Barrio</label><input id="barrio" name="barrio" type="text" class="form-control" maxlength="120"></div>
                <div class="form-group col-md-3"><label for="sub_barrio">Sub barrio</label><input id="sub_barrio" name="sub_barrio" type="text" class="form-control" maxlength="120"></div>
                <div class="form-group col-md-6"><label for="calle_numero">Calle y numero</label><input id="calle_numero" name="calle_numero" type="text" class="form-control" maxlength="180"></div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Vacunas y discapacidades</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8">
                    <label class="font-weight-bold d-block">Vacunas</label>
                    <div class="form-row">
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_tuberculosis"><label class="custom-control-label" for="vacuna_tuberculosis">Tuberculosis</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_tosferina"><label class="custom-control-label" for="vacuna_tosferina">Tosferina</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_dt1"><label class="custom-control-label" for="vacuna_dt1">Difteria / Tetano 1</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_dt2"><label class="custom-control-label" for="vacuna_dt2">Difteria / Tetano 2</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_antipolio1"><label class="custom-control-label" for="vacuna_antipolio1">Antipolio 1</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_antipolio2"><label class="custom-control-label" for="vacuna_antipolio2">Antipolio 2</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_antipolio_refuerzo"><label class="custom-control-label" for="vacuna_antipolio_refuerzo">Antipolio Refuerzo</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_antisarampion1"><label class="custom-control-label" for="vacuna_antisarampion1">Antisarampion 1</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_antisarampion_refuerzo"><label class="custom-control-label" for="vacuna_antisarampion_refuerzo">Antisarampion Refuerzo</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_meningitis"><label class="custom-control-label" for="vacuna_meningitis">Meningitis</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_hepatitis1"><label class="custom-control-label" for="vacuna_hepatitis1">Hepatitis 1</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_hepatitis2"><label class="custom-control-label" for="vacuna_hepatitis2">Hepatitis 2</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_hepatitis3"><label class="custom-control-label" for="vacuna_hepatitis3">Hepatitis 3</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_dt_dt1"><label class="custom-control-label" for="vacuna_dt_dt1">Difteria / Tetano DT 1</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_dt_dt2"><label class="custom-control-label" for="vacuna_dt_dt2">Difteria / Tetano DT 2</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_dt_dt3"><label class="custom-control-label" for="vacuna_dt_dt3">Difteria / Tetano DT 3</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_dt_dt3_refuerzo"><label class="custom-control-label" for="vacuna_dt_dt3_refuerzo">Difteria / Tetano DT 3 Refuerzo</label></div></div>
                        <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="vacuna_gripe_ah1n1"><label class="custom-control-label" for="vacuna_gripe_ah1n1">Gripe AH1N1</label></div></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <label class="font-weight-bold d-block">Discapacidades</label>
                    <div class="form-group"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="disc_visual"><label class="custom-control-label" for="disc_visual">Visual</label></div></div>
                    <div class="form-group"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="disc_auditiva"><label class="custom-control-label" for="disc_auditiva">Auditiva</label></div></div>
                    <div class="form-group"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="disc_fisica"><label class="custom-control-label" for="disc_fisica">Fisica</label></div></div>
                    <div class="form-group"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="disc_intelectual"><label class="custom-control-label" for="disc_intelectual">Intelectual</label></div></div>
                    <div class="form-group"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="disc_tgd"><label class="custom-control-label" for="disc_tgd">Trastorno generalizado del desarrollo</label></div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Datos familiares (madre / padre / tutor)</h6>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4"><label for="madre_primer_nombre">Madre primer nombre</label><input id="madre_primer_nombre" name="madre_primer_nombre" type="text" class="form-control" maxlength="80"></div>
                <div class="form-group col-md-4"><label for="madre_primer_apellido">Madre primer apellido</label><input id="madre_primer_apellido" name="madre_primer_apellido" type="text" class="form-control" maxlength="80"></div>
                <div class="form-group col-md-4"><label for="madre_cedula">Madre cedula</label><input id="madre_cedula" name="madre_cedula" type="text" class="form-control" maxlength="30"></div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4"><label for="padre_primer_nombre">Padre primer nombre</label><input id="padre_primer_nombre" name="padre_primer_nombre" type="text" class="form-control" maxlength="80"></div>
                <div class="form-group col-md-4"><label for="padre_primer_apellido">Padre primer apellido</label><input id="padre_primer_apellido" name="padre_primer_apellido" type="text" class="form-control" maxlength="80"></div>
                <div class="form-group col-md-4"><label for="padre_cedula">Padre cedula</label><input id="padre_cedula" name="padre_cedula" type="text" class="form-control" maxlength="30"></div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4"><label for="tutor_primer_nombre">Tutor primer nombre</label><input id="tutor_primer_nombre" name="tutor_primer_nombre" type="text" class="form-control" maxlength="80"></div>
                <div class="form-group col-md-4"><label for="tutor_primer_apellido">Tutor primer apellido</label><input id="tutor_primer_apellido" name="tutor_primer_apellido" type="text" class="form-control" maxlength="80"></div>
                <div class="form-group col-md-4"><label for="tutor_cedula">Tutor cedula</label><input id="tutor_cedula" name="tutor_cedula" type="text" class="form-control" maxlength="30"></div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Inscripcion academica y tarifas</h6>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4"><label for="centro_procedencia">Centro de procedencia</label><input id="centro_procedencia" name="centro_procedencia" type="text" class="form-control" maxlength="180"></div>
                <div class="form-group col-md-4">
                    <label for="filtro_anio_escolar">Ano escolar *</label>
                    <select id="filtro_anio_escolar" class="form-control" required>
                        <option value="">Selecciona ano escolar</option>
                    </select>
                    <small class="form-text text-muted">Filtra la oferta academica antes de elegir grado.</small>
                </div>
                <div class="form-group col-md-4">
                    <label for="grado">Grado *</label>
                    <select id="grado" name="grado" class="form-control" required>
                        <option value="">Selecciona grado (incluye seccion)</option>
                    </select>
                    <small id="oferta-academica-ayuda" class="form-text text-muted">Se carga desde Planeacion academica con su seccion relacionada.</small>
                </div>
            </div>
            <input id="planificacion_id" name="planificacion_id" type="hidden" value="">
            <input id="anio_escolar_id_rel" name="anio_escolar_id_rel" type="hidden" value="">
            <input id="nivel_id_rel" name="nivel_id_rel" type="hidden" value="">
            <input id="grado_id_rel" name="grado_id_rel" type="hidden" value="">
            <input id="seccion_id_rel" name="seccion_id_rel" type="hidden" value="">
            <input id="tanda_id_rel" name="tanda_id_rel" type="hidden" value="">
            <div class="form-row">
                <div class="form-group col-md-3"><label for="tarifa_inscripcion">Tarifa inscripcion</label><input id="tarifa_inscripcion" name="tarifa_inscripcion" type="number" min="0" step="0.01" class="form-control"></div>
                <div class="form-group col-md-3"><label for="mensualidad">Mensualidad</label><input id="mensualidad" name="mensualidad" type="number" min="0" step="0.01" class="form-control"></div>
                <div class="form-group col-md-3"><label for="fecha_inscripcion">Fecha de inscripcion</label><input id="fecha_inscripcion" name="fecha_inscripcion" type="date" class="form-control"></div>
            </div>
            <small id="tarifa-auto-ayuda" class="form-text text-muted">La tarifa se completa automaticamente al seleccionar el grado.</small>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Requisitos y terminos</h6>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="req_acta"><label class="custom-control-label" for="req_acta">Acta de nacimiento</label></div></div>
                <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="req_fotos"><label class="custom-control-label" for="req_fotos">2 fotos 2x2</label></div></div>
                <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="req_cedula"><label class="custom-control-label" for="req_cedula">Copia de cedula padre/madre/tutor</label></div></div>
                <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="req_conducta"><label class="custom-control-label" for="req_conducta">Certificacion de conducta</label></div></div>
                <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="req_medico"><label class="custom-control-label" for="req_medico">Certificado medico</label></div></div>
                <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="req_vacunas"><label class="custom-control-label" for="req_vacunas">Record de vacunas</label></div></div>
                <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="req_notas"><label class="custom-control-label" for="req_notas">Record de notas / boletin</label></div></div>
                <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="req_conclusion"><label class="custom-control-label" for="req_conclusion">Certificado de conclusion de estudio</label></div></div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="acepta_terminos"><label class="custom-control-label" for="acepta_terminos">Acepta terminos y condiciones</label></div></div>
                <div class="form-group col-md-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="inscripcion_activa" checked><label class="custom-control-label" for="inscripcion_activa">Inscripcion activa</label></div></div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="form-group mb-3">
                <label for="observaciones" class="font-weight-bold">Observaciones</label>
                <textarea id="observaciones" name="observaciones" rows="4" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar inscripcion
            </button>
        </div>
    </div>
</form>

<div class="modal fade" id="modal-revision-inscripcion" tabindex="-1" role="dialog" aria-labelledby="modal-revision-inscripcion-title" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-revision-inscripcion-title">Verifica los datos antes de imprimir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Revisa toda la informacion. Si encuentras algun error, presiona Corregir para volver al formulario.</p>
                <div id="revision-inscripcion-contenido"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btn-corregir-inscripcion" data-dismiss="modal">Corregir</button>
                <button type="button" class="btn btn-primary" id="btn-continuar-inscripcion">
                    <i class="fas fa-file-pdf"></i> Continuar e imprimir PDF
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const apiBase = '../';
    const csrfToken = <?= json_encode($csrfToken ?? '', JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    const fotoInput = document.getElementById('foto-estudiante');
    const fotoNombre = document.getElementById('foto-estudiante-nombre');
    const preview = document.getElementById('preview-foto');
    const fechaInscripcionInput = document.getElementById('fecha_inscripcion');
    const anioOfertaSelect = document.getElementById('filtro_anio_escolar');
    const gradoSelect = document.getElementById('grado');
    const ayudaOferta = document.getElementById('oferta-academica-ayuda');
    const tarifaInscripcionInput = document.getElementById('tarifa_inscripcion');
    const mensualidadInput = document.getElementById('mensualidad');
    const tarifaAutoAyuda = document.getElementById('tarifa-auto-ayuda');
    const formInscripcion = document.getElementById('form-inscripcion-estudiante');
    const submitButton = formInscripcion.querySelector('button[type="submit"]');
    const modalRevision = document.getElementById('modal-revision-inscripcion');
    const contenidoRevision = document.getElementById('revision-inscripcion-contenido');
    const btnCorregir = document.getElementById('btn-corregir-inscripcion');
    const btnContinuar = document.getElementById('btn-continuar-inscripcion');

    const VACUNA_CHECKBOX_IDS = [
        'vacuna_tuberculosis', 'vacuna_tosferina', 'vacuna_dt1', 'vacuna_dt2',
        'vacuna_antipolio1', 'vacuna_antipolio2', 'vacuna_antipolio_refuerzo',
        'vacuna_antisarampion1', 'vacuna_antisarampion_refuerzo', 'vacuna_meningitis',
        'vacuna_hepatitis1', 'vacuna_hepatitis2', 'vacuna_hepatitis3',
        'vacuna_dt_dt1', 'vacuna_dt_dt2', 'vacuna_dt_dt3',
        'vacuna_dt_dt3_refuerzo', 'vacuna_gripe_ah1n1',
    ];

    const DISCAPACIDAD_CHECKBOX_IDS = [
        'disc_visual', 'disc_auditiva', 'disc_fisica', 'disc_intelectual', 'disc_tgd',
    ];

    const REQUISITO_CHECKBOX_IDS = [
        'req_acta', 'req_fotos', 'req_cedula', 'req_conducta',
        'req_medico', 'req_vacunas', 'req_notas', 'req_conclusion',
    ];

    const stateOferta = {
        planificaciones: [],
        tarifarios: [],
        tarifasGrados: [],
        datosCentro: null,
        anios: new Map(),
        niveles: new Map(),
        grados: new Map(),
        secciones: new Map(),
        tandas: new Map(),
        defaultTarifarioId: 0,
    };

    const stateCatalogos = {
        vacunas: null,
        discapacidades: null,
        requisitos: null,
    };

    let pendingResumen = null;
    let pendingPayload = null;

    function getPublicBaseUrl() {
        const pathname = String(window.location.pathname || '');
        const basePath = pathname.replace(/\/admin\/index\.php$/, '/');
        return window.location.origin + basePath;
    }

    function resolveStoredAssetUrl(path) {
        const raw = String(path || '').trim();
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

    function setOfertaAyuda(message, isError) {
        ayudaOferta.textContent = message;
        ayudaOferta.className = isError
            ? 'form-text text-danger'
            : 'form-text text-muted';
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

    function buildTurnoLabel(planif) {
        const tandaId = Number(planif.tanda_id || 0);
        if (tandaId > 0 && stateOferta.tandas.has(tandaId)) {
            const tanda = stateOferta.tandas.get(tandaId);
            return String(tanda.nombre || tanda.codigo || 'TANDA').toUpperCase();
        }

        const jornada = String(planif.jornada || '').trim();
        return jornada !== '' ? jornada.toUpperCase() : 'SIN TURNO';
    }

    function getPlanifJornada(planif) {
        const explicit = String(planif.jornada || '').trim().toUpperCase();
        if (explicit === 'MATUTINO' || explicit === 'VESPERTINO') {
            return explicit;
        }

        const tandaId = Number(planif.tanda_id || 0);
        if (tandaId > 0 && stateOferta.tandas.has(tandaId)) {
            const tanda = stateOferta.tandas.get(tandaId);
            const codigo = String(tanda.codigo || '').trim().toUpperCase();
            if (codigo === 'MATUTINO' || codigo === 'VESPERTINO') {
                return codigo;
            }
        }

        return '';
    }

    function resolveDefaultTarifarioId() {
        const activo = stateOferta.tarifarios.find((row) => Number(row.estado ?? 1) === 1) || stateOferta.tarifarios[0] || null;
        stateOferta.defaultTarifarioId = activo ? Number(activo.id || 0) : 0;
    }

    function clearTarifaAuto() {
        tarifaInscripcionInput.value = '0';
        mensualidadInput.value = '0';
        tarifaAutoAyuda.className = 'form-text text-muted';
        tarifaAutoAyuda.textContent = 'La tarifa se completa automaticamente al seleccionar el grado.';
    }

    function applyTarifaByPlanif(planif) {
        if (!planif) {
            clearTarifaAuto();
            return;
        }

        const nivelId = Number(planif.nivel_id || 0);
        const gradoId = Number(planif.grado_id || 0);
        const jornada = getPlanifJornada(planif);

        let candidates = stateOferta.tarifasGrados.filter((row) =>
            Number(row.nivel_id || 0) === nivelId
            && Number(row.grado_id || 0) === gradoId
            && String(row.jornada || '').toUpperCase() === jornada
            && Number(row.activo ?? 1) === 1
        );

        if (stateOferta.defaultTarifarioId > 0) {
            const filtered = candidates.filter((row) => Number(row.tarifario_id || 0) === stateOferta.defaultTarifarioId);
            if (filtered.length > 0) {
                candidates = filtered;
            }
        }

        if (candidates.length === 0) {
            tarifaInscripcionInput.value = '0';
            mensualidadInput.value = '0';
            tarifaAutoAyuda.className = 'form-text text-warning';
            tarifaAutoAyuda.textContent = 'No se encontro tarifa configurada para esta oferta academica.';
            return;
        }

        candidates.sort((a, b) => Number(b.id || 0) - Number(a.id || 0));
        const tarifa = candidates[0];
        tarifaInscripcionInput.value = String(tarifa.tarifa_inscripcion ?? 0);
        mensualidadInput.value = String(tarifa.mensualidad ?? 0);
        tarifaAutoAyuda.className = 'form-text text-success';
        tarifaAutoAyuda.textContent = 'Tarifa cargada automaticamente desde Configuracion del sistema.';
    }

    function renderGradosFromPlanificacion() {
        gradoSelect.innerHTML = '<option value="">Selecciona grado (incluye seccion)</option>';
        clearRelacionIds();

        const anioSeleccionado = Number(anioOfertaSelect.value || 0);
        const ofertasFiltradas = anioSeleccionado > 0
            ? stateOferta.planificaciones.filter((row) => Number(row.anio_escolar_id || 0) === anioSeleccionado)
            : stateOferta.planificaciones.slice();

        if (ofertasFiltradas.length === 0) {
            setOfertaAyuda('No hay planificaciones academicas activas para poblar el campo grado.', true);
            return;
        }

        const ofertas = ofertasFiltradas.slice().sort((a, b) => {
            const anioDiff = Number(b.anio_escolar_id || 0) - Number(a.anio_escolar_id || 0);
            if (anioDiff !== 0) {
                return anioDiff;
            }

            const nivelA = Number(a.nivel_id || 0);
            const nivelB = Number(b.nivel_id || 0);
            if (nivelA !== nivelB) {
                return nivelA - nivelB;
            }

            const gradoA = Number(a.grado_id || 0);
            const gradoB = Number(b.grado_id || 0);
            if (gradoA !== gradoB) {
                return gradoA - gradoB;
            }

            return Number(a.seccion_id || 0) - Number(b.seccion_id || 0);
        });

        ofertas.forEach((planif) => {
            const nivel = stateOferta.niveles.get(Number(planif.nivel_id || 0));
            const grado = stateOferta.grados.get(Number(planif.grado_id || 0));
            const seccion = stateOferta.secciones.get(Number(planif.seccion_id || 0));

            const nivelText = String((nivel && nivel.nivel) || ('NIVEL #' + planif.nivel_id)).toUpperCase();
            const gradoText = String((grado && grado.grado) || ('GRADO #' + planif.grado_id)).toUpperCase();
            const seccionText = String((seccion && seccion.seccion) || ('SECCION #' + planif.seccion_id)).toUpperCase();
            const turnoText = buildTurnoLabel(planif);

            const option = document.createElement('option');
            option.value = String(planif.id || '');
            option.textContent = nivelText + '-' + turnoText + ' - ' + gradoText + ' - Seccion ' + seccionText;
            option.dataset.planificacionId = String(planif.id || '');
            option.dataset.anioId = String(planif.anio_escolar_id || '');
            option.dataset.nivelId = String(planif.nivel_id || '');
            option.dataset.gradoId = String(planif.grado_id || '');
            option.dataset.seccionId = String(planif.seccion_id || '');
            option.dataset.tandaId = String(planif.tanda_id || '');
            gradoSelect.appendChild(option);
        });

        setOfertaAyuda('Oferta academica filtrada por ano escolar (grado + seccion en un solo campo).', false);
    }

    function renderAniosFromPlanificacion() {
        const uniqueIds = new Set();
        stateOferta.planificaciones.forEach((row) => {
            const anioId = Number(row.anio_escolar_id || 0);
            if (anioId > 0) {
                uniqueIds.add(anioId);
            }
        });

        const ids = Array.from(uniqueIds).sort((a, b) => b - a);
        const options = ['<option value="">Selecciona ano escolar</option>'];

        ids.forEach((anioId) => {
            const anio = stateOferta.anios.get(anioId);
            const nombre = String((anio && anio.nombre) || ('ANO #' + anioId)).trim();
            options.push('<option value="' + anioId + '">' + escHtml(nombre) + '</option>');
        });

        anioOfertaSelect.innerHTML = options.join('');
        if (ids.length > 0) {
            anioOfertaSelect.value = String(ids[0]);
        }
    }

    function clearRelacionIds() {
        document.getElementById('planificacion_id').value = '';
        document.getElementById('anio_escolar_id_rel').value = '';
        document.getElementById('nivel_id_rel').value = '';
        document.getElementById('grado_id_rel').value = '';
        document.getElementById('seccion_id_rel').value = '';
        document.getElementById('tanda_id_rel').value = '';
    }

    function getAdminBaseUrl() {
        const pathname = String(window.location.pathname || '');
        const basePath = pathname.replace(/\/admin\/?$/, '/admin/').replace(/\/admin\/index\.php$/, '/admin/');
        return window.location.origin + basePath;
    }

    function handleGradoChange() {
        const selected = gradoSelect.options[gradoSelect.selectedIndex] || null;
        if (!selected || selected.value === '') {
            clearRelacionIds();
            clearTarifaAuto();
            return;
        }

        document.getElementById('planificacion_id').value = selected.dataset.planificacionId || '';
        document.getElementById('anio_escolar_id_rel').value = selected.dataset.anioId || '';
        document.getElementById('nivel_id_rel').value = selected.dataset.nivelId || '';
        document.getElementById('grado_id_rel').value = selected.dataset.gradoId || '';
        document.getElementById('seccion_id_rel').value = selected.dataset.seccionId || '';
        document.getElementById('tanda_id_rel').value = selected.dataset.tandaId || '';

        const planifId = Number(selected.dataset.planificacionId || 0);
        const planif = stateOferta.planificaciones.find((row) => Number(row.id || 0) === planifId) || null;
        applyTarifaByPlanif(planif);
    }

    async function apiGet(resource) {
        const response = await fetch(apiBase + '?resource=' + resource + '&action=index&limit=1000', {
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
            throw new Error(json.message || ('No se pudo eliminar en rollback para ' + resource));
        }

        return json.data || {};
    }

    async function uploadFotoEstudianteIfNeeded() {
        const file = fotoInput.files && fotoInput.files.length > 0 ? fotoInput.files[0] : null;
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

        const response = await fetch(getAdminBaseUrl() + 'upload-estudiante-foto.php', {
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

    async function loadOfertaAcademica() {
        setOfertaAyuda('Cargando oferta academica...', false);
        try {
            const [planificaciones, anios, niveles, grados, secciones, tandas, tarifarios, tarifasGrados, datosCentro] = await Promise.all([
                apiGet('planificaciones_academicas'),
                apiGet('anios_escolares'),
                apiGet('niveles'),
                apiGet('grados'),
                apiGet('secciones'),
                apiGet('tandas'),
                apiGet('tarifarios'),
                apiGet('tarifas_grados'),
                apiGet('datos_centro_educativo'),
            ]);

            stateOferta.anios = toMap(anios, 'id');
            stateOferta.niveles = toMap(niveles, 'id');
            stateOferta.grados = toMap(grados, 'id');
            stateOferta.secciones = toMap(secciones, 'id');
            stateOferta.tandas = toMap(tandas, 'id');
            stateOferta.tarifarios = tarifarios;
            stateOferta.tarifasGrados = tarifasGrados;
            stateOferta.datosCentro = (datosCentro.find((row) => Number(row.estado ?? 1) === 1) || datosCentro[0] || null);
            resolveDefaultTarifarioId();

            stateOferta.planificaciones = planificaciones.filter((row) => Number(row.estado ?? 1) === 1);
            renderAniosFromPlanificacion();
            renderGradosFromPlanificacion();
            clearTarifaAuto();
        } catch (error) {
            anioOfertaSelect.innerHTML = '<option value="">Selecciona ano escolar</option>';
            gradoSelect.innerHTML = '<option value="">Selecciona grado (incluye seccion)</option>';
            clearRelacionIds();
            clearTarifaAuto();
            setOfertaAyuda(error.message, true);
        }
    }

    function getTodayLocalISO() {
        const now = new Date();
        const offsetMs = now.getTimezoneOffset() * 60000;
        return new Date(now.getTime() - offsetMs).toISOString().slice(0, 10);
    }

    function initFechaInscripcionPorDefecto() {
        if (String(fechaInscripcionInput.value || '').trim() === '') {
            fechaInscripcionInput.value = getTodayLocalISO();
        }
    }

    function getValue(id) {
        const el = document.getElementById(id);
        return el ? String(el.value || '').trim() : '';
    }

    function getSelectedText(id) {
        const el = document.getElementById(id);
        if (!el || !el.options || el.selectedIndex < 0) {
            return '';
        }

        const option = el.options[el.selectedIndex];
        return option ? String(option.textContent || '').trim() : '';
    }

    function getCheckedLabels(ids) {
        return ids
            .map((id) => document.getElementById(id))
            .filter((el) => el && el.checked)
            .map((el) => {
                const label = document.querySelector('label[for="' + el.id + '"]');
                return label ? String(label.textContent || '').trim() : el.id;
            });
    }

    function getCheckedIds(ids) {
        return ids.filter((id) => {
            const el = document.getElementById(id);
            return !!(el && el.checked);
        });
    }

    function normalizeText(value) {
        return String(value || '')
            .toUpperCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/\s+/g, ' ')
            .trim();
    }

    function toNullableString(value) {
        const cleaned = String(value || '').trim();
        return cleaned === '' ? null : cleaned;
    }

    function toNullableYear(value) {
        const cleaned = String(value || '').trim();
        if (cleaned === '') {
            return null;
        }

        const year = Number(cleaned);
        return Number.isInteger(year) ? year : null;
    }

    function toDecimal(value) {
        const numeric = Number(value);
        if (!Number.isFinite(numeric) || numeric < 0) {
            return 0;
        }

        return Number(numeric.toFixed(2));
    }

    function mapEstadoCivil(rawValue) {
        const key = normalizeText(rawValue);
        if (key.startsWith('SOLTERO')) {
            return 'SOLTERO';
        }
        if (key.startsWith('CASADO')) {
            return 'CASADO';
        }
        if (key.startsWith('VIUDO')) {
            return 'VIUDO';
        }
        if (key.startsWith('DIVORCIADO')) {
            return 'DIVORCIADO';
        }

        return null;
    }

    function mapEstadoActa(rawValue) {
        const key = normalizeText(rawValue);
        if (key === 'DECLARADO') {
            return 'DECLARADO';
        }
        if (key === 'NO DECLARADO') {
            return 'NO_DECLARADO';
        }
        if (key === 'NO DISPONIBLE') {
            return 'NO_DISPONIBLE';
        }

        return null;
    }

    function hasValue(value) {
        return String(value || '').trim() !== '';
    }

    function hasAnyValue(values) {
        return values.some((value) => hasValue(value));
    }

    async function ensureCatalogosInscripcion() {
        if (stateCatalogos.vacunas && stateCatalogos.discapacidades && stateCatalogos.requisitos) {
            return;
        }

        const [vacunas, discapacidades, requisitos] = await Promise.all([
            apiGet('vacunas'),
            apiGet('discapacidades'),
            apiGet('requisitos_inscripcion'),
        ]);

        stateCatalogos.vacunas = vacunas.filter((row) => Number(row.estado ?? 1) === 1);
        stateCatalogos.discapacidades = discapacidades.filter((row) => Number(row.estado ?? 1) === 1);
        stateCatalogos.requisitos = requisitos.filter((row) => Number(row.estado ?? 1) === 1);
    }

    function resolveCatalogIdsByCheckboxes(checkboxIds, catalogRows) {
        const catalogMap = new Map();
        (catalogRows || []).forEach((row) => {
            const id = Number(row.id || 0);
            const name = normalizeText(row.nombre || '');
            if (id > 0 && name !== '') {
                catalogMap.set(name, id);
            }
        });

        const ids = [];
        checkboxIds.forEach((checkboxId) => {
            const checkbox = document.getElementById(checkboxId);
            if (!checkbox || !checkbox.checked) {
                return;
            }

            const label = document.querySelector('label[for="' + checkboxId + '"]');
            const labelText = normalizeText(label ? label.textContent : '');
            const id = catalogMap.get(labelText);
            if (id) {
                ids.push(id);
            }
        });

        return Array.from(new Set(ids));
    }

    function buildFamiliaresPayload() {
        const familiares = [];

        const madreNombre = getValue('madre_primer_nombre');
        const madreApellido = getValue('madre_primer_apellido');
        const madreCedula = getValue('madre_cedula');
        if (hasAnyValue([madreNombre, madreApellido, madreCedula])) {
            if (!hasAnyValue([madreNombre, madreApellido])) {
                throw new Error('Para guardar la madre, debes completar primer nombre y primer apellido.');
            }

            familiares.push({
                tipo_familiar: 'MADRE',
                primer_nombre: madreNombre,
                primer_apellido: madreApellido,
                cedula: toNullableString(madreCedula),
            });
        }

        const padreNombre = getValue('padre_primer_nombre');
        const padreApellido = getValue('padre_primer_apellido');
        const padreCedula = getValue('padre_cedula');
        if (hasAnyValue([padreNombre, padreApellido, padreCedula])) {
            if (!hasAnyValue([padreNombre, padreApellido])) {
                throw new Error('Para guardar el padre, debes completar primer nombre y primer apellido.');
            }

            familiares.push({
                tipo_familiar: 'PADRE',
                primer_nombre: padreNombre,
                primer_apellido: padreApellido,
                cedula: toNullableString(padreCedula),
            });
        }

        const tutorNombre = getValue('tutor_primer_nombre');
        const tutorApellido = getValue('tutor_primer_apellido');
        const tutorCedula = getValue('tutor_cedula');
        if (hasAnyValue([tutorNombre, tutorApellido, tutorCedula])) {
            if (!hasAnyValue([tutorNombre, tutorApellido])) {
                throw new Error('Para guardar el tutor, debes completar primer nombre y primer apellido.');
            }

            familiares.push({
                tipo_familiar: 'TUTOR',
                primer_nombre: tutorNombre,
                primer_apellido: tutorApellido,
                cedula: toNullableString(tutorCedula),
            });
        }

        return familiares;
    }

    function buildPersistPayload() {
        const planificacionId = Number(getValue('planificacion_id') || 0);
        if (planificacionId <= 0) {
            throw new Error('Debes seleccionar un grado valido de la oferta academica.');
        }

        const anioEscolarId = Number(getValue('anio_escolar_id_rel') || 0);
        if (anioEscolarId <= 0) {
            throw new Error('Debes seleccionar un ano escolar valido para la inscripcion.');
        }

        const fechaInscripcion = getValue('fecha_inscripcion') || getTodayLocalISO();
        if (!/^\d{4}-\d{2}-\d{2}$/.test(fechaInscripcion)) {
            throw new Error('La fecha de inscripcion no es valida.');
        }

        return {
            estudiante: {
                id_sigerd: toNullableString(getValue('sigerd_id')),
                primer_nombre: getValue('primer_nombre'),
                segundo_nombre: toNullableString(getValue('segundo_nombre')),
                primer_apellido: getValue('primer_apellido'),
                segundo_apellido: toNullableString(getValue('segundo_apellido')),
                fecha_nacimiento: toNullableString(getValue('fecha_nacimiento')),
                sexo: toNullableString(getValue('sexo')),
                estado_civil: mapEstadoCivil(getValue('estado_civil')),
                nacionalidad: toNullableString(getValue('nacionalidad')),
                telefono: toNullableString(getValue('telefono')),
                celular: toNullableString(getValue('celular')),
                whatsapp: toNullableString(getValue('whatsapp')),
                observaciones: toNullableString(getValue('observaciones')),
            },
            registroCivil: {
                estado_acta: mapEstadoActa(getValue('estado_acta')),
                numero_acta: toNullableString(getValue('numero_acta')),
                provincia_jce: toNullableString(getValue('provincia_jce')),
                municipio_jce: toNullableString(getValue('municipio_jce')),
                oficialia_jce: toNullableString(getValue('oficialia_jce')),
                libro: toNullableString(getValue('libro_jce')),
                folio: toNullableString(getValue('folio_jce')),
                anio: toNullableYear(getValue('anio_jce')),
            },
            direccion: {
                provincia: toNullableString(getValue('provincia')),
                municipio: toNullableString(getValue('municipio')),
                distrito_municipal: toNullableString(getValue('distrito_municipal')),
                seccion: toNullableString(getValue('seccion_direccion')),
                barrio: toNullableString(getValue('barrio')),
                sub_barrio: toNullableString(getValue('sub_barrio')),
                calle_numero: toNullableString(getValue('calle_numero')),
            },
            familiares: buildFamiliaresPayload(),
            inscripcion: {
                planificacion_academica_id: planificacionId,
                centro_procedencia: toNullableString(getValue('centro_procedencia')),
                tarifa_inscripcion: toDecimal(getValue('tarifa_inscripcion')),
                mensualidad: toDecimal(getValue('mensualidad')),
                fecha_inscripcion: fechaInscripcion,
                acepta_terminos: document.getElementById('acepta_terminos').checked ? 1 : 0,
                inscripcion_activa: document.getElementById('inscripcion_activa').checked ? 1 : 0,
                observaciones: toNullableString(getValue('observaciones')),
            },
            vacunasSeleccionadas: getCheckedIds(VACUNA_CHECKBOX_IDS),
            discapacidadesSeleccionadas: getCheckedIds(DISCAPACIDAD_CHECKBOX_IDS),
            requisitosSeleccionados: getCheckedIds(REQUISITO_CHECKBOX_IDS),
        };
    }

    function shouldCreateRegistroCivil(payload) {
        return payload.estado_acta !== null
            || hasAnyValue([
                payload.numero_acta,
                payload.provincia_jce,
                payload.municipio_jce,
                payload.oficialia_jce,
                payload.libro,
                payload.folio,
                payload.anio,
            ]);
    }

    function shouldCreateDireccion(payload) {
        return hasAnyValue([
            payload.provincia,
            payload.municipio,
            payload.distrito_municipal,
            payload.seccion,
            payload.barrio,
            payload.sub_barrio,
            payload.calle_numero,
        ]);
    }

    function isDuplicateCedulaError(errorMessage) {
        return normalizeText(errorMessage).includes('CEDULA');
    }

    async function findFamiliarIdByCedula(cedula) {
        const value = String(cedula || '').trim();
        if (value === '') {
            return 0;
        }

        const familiares = await apiGet('familiares');
        const row = familiares.find((item) => String(item.cedula || '').trim() === value) || null;
        return row ? Number(row.id || 0) : 0;
    }

    async function persistInscripcion(prebuiltPayload) {
        const payload = prebuiltPayload || buildPersistPayload();
        await ensureCatalogosInscripcion();

        const rollbackStack = [];
        try {
            const fotoPath = await uploadFotoEstudianteIfNeeded();
            if (fotoPath !== '') {
                payload.estudiante.foto = fotoPath;
            }

            const estudianteResponse = await apiStore('estudiantes', payload.estudiante);
            const estudianteId = Number(estudianteResponse.id || 0);
            if (estudianteId <= 0) {
                throw new Error('No se pudo obtener el ID del estudiante creado.');
            }

            rollbackStack.push({ resource: 'estudiantes', criteria: { id: estudianteId } });

            if (shouldCreateRegistroCivil(payload.registroCivil)) {
                await apiStore('registros_civiles', Object.assign({ estudiante_id: estudianteId }, payload.registroCivil));
                rollbackStack.push({ resource: 'registros_civiles', criteria: { estudiante_id: estudianteId } });
            }

            if (shouldCreateDireccion(payload.direccion)) {
                await apiStore('direcciones_estudiantes', Object.assign({ estudiante_id: estudianteId }, payload.direccion));
                rollbackStack.push({ resource: 'direcciones_estudiantes', criteria: { estudiante_id: estudianteId } });
            }

            for (const familiar of payload.familiares) {
                let familiarId = 0;
                let familiarFueCreado = false;

                try {
                    const familiarResponse = await apiStore('familiares', familiar);
                    familiarId = Number(familiarResponse.id || 0);
                    familiarFueCreado = true;
                } catch (error) {
                    const message = error && error.message ? String(error.message) : 'No se pudo guardar familiar.';
                    if (!isDuplicateCedulaError(message) || !familiar.cedula) {
                        throw error;
                    }

                    familiarId = await findFamiliarIdByCedula(familiar.cedula);
                }

                if (familiarId <= 0) {
                    throw new Error('No se pudo resolver el familiar para vincularlo al estudiante.');
                }

                if (familiarFueCreado) {
                    rollbackStack.push({ resource: 'familiares', criteria: { id: familiarId } });
                }

                await apiStore('estudiante_familiares', {
                    estudiante_id: estudianteId,
                    familiar_id: familiarId,
                });

                rollbackStack.push({
                    resource: 'estudiante_familiares',
                    criteria: { estudiante_id: estudianteId, familiar_id: familiarId },
                });
            }

            const inscripcionResponse = await apiStore('inscripciones', Object.assign({ estudiante_id: estudianteId }, payload.inscripcion));
            const inscripcionId = Number(inscripcionResponse.id || 0);
            if (inscripcionId <= 0) {
                throw new Error('No se pudo obtener el ID de la inscripcion creada.');
            }

            rollbackStack.push({ resource: 'inscripciones', criteria: { id: inscripcionId } });

            const vacunaIds = resolveCatalogIdsByCheckboxes(payload.vacunasSeleccionadas, stateCatalogos.vacunas);
            for (const vacunaId of vacunaIds) {
                await apiStore('estudiante_vacunas', {
                    estudiante_id: estudianteId,
                    vacuna_id: vacunaId,
                });

                rollbackStack.push({
                    resource: 'estudiante_vacunas',
                    criteria: { estudiante_id: estudianteId, vacuna_id: vacunaId },
                });
            }

            const discapacidadIds = resolveCatalogIdsByCheckboxes(payload.discapacidadesSeleccionadas, stateCatalogos.discapacidades);
            for (const discapacidadId of discapacidadIds) {
                await apiStore('estudiante_discapacidades', {
                    estudiante_id: estudianteId,
                    discapacidad_id: discapacidadId,
                });

                rollbackStack.push({
                    resource: 'estudiante_discapacidades',
                    criteria: { estudiante_id: estudianteId, discapacidad_id: discapacidadId },
                });
            }

            const requisitoIds = resolveCatalogIdsByCheckboxes(payload.requisitosSeleccionados, stateCatalogos.requisitos);
            for (const requisitoId of requisitoIds) {
                await apiStore('inscripcion_requisitos', {
                    inscripcion_id: inscripcionId,
                    requisito_id: requisitoId,
                    presentado: 1,
                });

                rollbackStack.push({
                    resource: 'inscripcion_requisitos',
                    criteria: { inscripcion_id: inscripcionId, requisito_id: requisitoId },
                });
            }

            return {
                estudianteId,
                inscripcionId,
            };
        } catch (error) {
            for (let i = rollbackStack.length - 1; i >= 0; i -= 1) {
                const step = rollbackStack[i];
                try {
                    await apiDestroy(step.resource, step.criteria);
                } catch (rollbackError) {
                    // Intencional: evitar romper el flujo principal por errores de rollback.
                }
            }

            throw error;
        }
    }

    function resetInscripcionForm() {
        formInscripcion.reset();
        fotoNombre.textContent = 'Ningun archivo seleccionado';
        preview.innerHTML = '<span class="text-muted">Sin foto</span>';
        clearRelacionIds();
        renderAniosFromPlanificacion();
        renderGradosFromPlanificacion();
        gradoSelect.value = '';
        clearTarifaAuto();
        initFechaInscripcionPorDefecto();
    }

    function formatDateIso(value) {
        if (!value || !/^\d{4}-\d{2}-\d{2}$/.test(value)) {
            return value || '-';
        }

        const parts = value.split('-');
        return parts[2] + '/' + parts[1] + '/' + parts[0];
    }

    function escHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function fieldRow(label, value) {
        return '<tr><th>' + escHtml(label) + '</th><td>' + escHtml(value && value !== '' ? value : '-') + '</td></tr>';
    }

    function buildSectionHtml(title, rows) {
        return '<section class="section">'
            + '<h3>' + escHtml(title) + '</h3>'
            + '<table>' + rows.join('') + '</table>'
            + '</section>';
    }

    function buildSectionPreviewCard(title, rows) {
        return '<div class="card shadow-sm mb-3">'
            + '<div class="card-header py-2"><h6 class="m-0 font-weight-bold text-primary">' + escHtml(title) + '</h6></div>'
            + '<div class="card-body p-0"><table class="table table-sm table-bordered mb-0">'
            + rows.join('')
            + '</table></div>'
            + '</div>';
    }

    function showRevisionModal() {
        if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.modal === 'function') {
            window.jQuery(modalRevision).modal('show');
            return;
        }

        modalRevision.style.display = 'block';
        modalRevision.classList.add('show');
        document.body.classList.add('modal-open');
    }

    function hideRevisionModal() {
        if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.modal === 'function') {
            window.jQuery(modalRevision).modal('hide');
            return;
        }

        modalRevision.style.display = 'none';
        modalRevision.classList.remove('show');
        document.body.classList.remove('modal-open');
    }

    function renderRevisionModal(resumen) {
        const cards = resumen.secciones.map((section) => buildSectionPreviewCard(section.titulo, section.rows)).join('');
        const fotoHtml = resumen.fotoPerfil
            ? '<img src="' + escHtml(resumen.fotoPerfil) + '" alt="Foto del estudiante" class="img-fluid rounded border" style="max-height:220px;">'
            : '<div class="border rounded p-4 text-muted bg-light text-center">Sin foto</div>';

        contenidoRevision.innerHTML = ''
            + '<div class="card border-left-primary mb-3">'
            + '<div class="card-body py-2">'
            + '<div><strong>Centro:</strong> ' + escHtml(resumen.centro.nombre_centro) + '</div>'
            + '<div><strong>Fecha de generacion:</strong> ' + escHtml(resumen.fechaGeneracion) + '</div>'
            + '</div>'
            + '</div>'
            + '<div class="row mb-3">'
            + '<div class="col-md-9">'
            + cards
            + '</div>'
            + '<div class="col-md-3">'
            + '<div class="card shadow-sm">'
            + '<div class="card-header py-2"><h6 class="m-0 font-weight-bold text-primary">Foto del estudiante</h6></div>'
            + '<div class="card-body text-center">' + fotoHtml + '</div>'
            + '</div>'
            + '</div>'
            + '</div>';
    }

    function getFotoPerfilDataUrl() {
        const img = preview.querySelector('img');
        if (!img) {
            return '';
        }

        const src = String(img.getAttribute('src') || '').trim();
        return src;
    }

    function buildResumenInscripcion() {
        const vacunas = getCheckedLabels([
            'vacuna_tuberculosis', 'vacuna_tosferina', 'vacuna_dt1', 'vacuna_dt2',
            'vacuna_antipolio1', 'vacuna_antipolio2', 'vacuna_antipolio_refuerzo',
            'vacuna_antisarampion1', 'vacuna_antisarampion_refuerzo', 'vacuna_meningitis',
            'vacuna_hepatitis1', 'vacuna_hepatitis2', 'vacuna_hepatitis3',
            'vacuna_dt_dt1', 'vacuna_dt_dt2', 'vacuna_dt_dt3',
            'vacuna_dt_dt3_refuerzo', 'vacuna_gripe_ah1n1',
        ]);

        const discapacidades = getCheckedLabels([
            'disc_visual', 'disc_auditiva', 'disc_fisica', 'disc_intelectual', 'disc_tgd',
        ]);

        const requisitos = getCheckedLabels([
            'req_acta', 'req_fotos', 'req_cedula', 'req_conducta',
            'req_medico', 'req_vacunas', 'req_notas', 'req_conclusion',
        ]);

        const anioEscolarText = getSelectedText('filtro_anio_escolar');
        const gradoText = getSelectedText('grado');
        const planificacionId = getValue('planificacion_id');
        const centro = stateOferta.datosCentro || {};

        return {
            fechaGeneracion: formatDateIso(getTodayLocalISO()),
            fotoPerfil: getFotoPerfilDataUrl(),
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
            secciones: [
                {
                    titulo: 'Informacion basica',
                    rows: [
                        fieldRow('ID del Sigerd', getValue('sigerd_id')),
                        fieldRow('Primer nombre', getValue('primer_nombre')),
                        fieldRow('Segundo nombre', getValue('segundo_nombre')),
                        fieldRow('Primer apellido', getValue('primer_apellido')),
                        fieldRow('Segundo apellido', getValue('segundo_apellido')),
                        fieldRow('Fecha nacimiento', formatDateIso(getValue('fecha_nacimiento'))),
                        fieldRow('Sexo', getSelectedText('sexo')),
                        fieldRow('Estado civil', getSelectedText('estado_civil')),
                        fieldRow('Nacionalidad', getSelectedText('nacionalidad')),
                        fieldRow('Telefono', getValue('telefono')),
                        fieldRow('Celular', getValue('celular')),
                        fieldRow('Whatsapp', getValue('whatsapp')),
                    ],
                },
                {
                    titulo: 'Registro civil',
                    rows: [
                        fieldRow('Estado del acta', getSelectedText('estado_acta')),
                        fieldRow('Numero de acta', getValue('numero_acta')),
                        fieldRow('Provincia JCE', getValue('provincia_jce')),
                        fieldRow('Municipio JCE', getValue('municipio_jce')),
                        fieldRow('Oficialia JCE', getValue('oficialia_jce')),
                        fieldRow('Libro', getValue('libro_jce')),
                        fieldRow('Folio', getValue('folio_jce')),
                        fieldRow('Ano', getValue('anio_jce')),
                    ],
                },
                {
                    titulo: 'Direccion',
                    rows: [
                        fieldRow('Provincia', getValue('provincia')),
                        fieldRow('Municipio', getValue('municipio')),
                        fieldRow('Distrito municipal', getValue('distrito_municipal')),
                        fieldRow('Seccion', getValue('seccion_direccion')),
                        fieldRow('Barrio', getValue('barrio')),
                        fieldRow('Sub barrio', getValue('sub_barrio')),
                        fieldRow('Calle y numero', getValue('calle_numero')),
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
                        fieldRow('Madre primer nombre', getValue('madre_primer_nombre')),
                        fieldRow('Madre primer apellido', getValue('madre_primer_apellido')),
                        fieldRow('Madre cedula', getValue('madre_cedula')),
                        fieldRow('Padre primer nombre', getValue('padre_primer_nombre')),
                        fieldRow('Padre primer apellido', getValue('padre_primer_apellido')),
                        fieldRow('Padre cedula', getValue('padre_cedula')),
                        fieldRow('Tutor primer nombre', getValue('tutor_primer_nombre')),
                        fieldRow('Tutor primer apellido', getValue('tutor_primer_apellido')),
                        fieldRow('Tutor cedula', getValue('tutor_cedula')),
                    ],
                },
                {
                    titulo: 'Inscripcion academica y tarifas',
                    rows: [
                        fieldRow('Centro de procedencia', getValue('centro_procedencia')),
                        fieldRow('Ano escolar', anioEscolarText),
                        fieldRow('Grado', gradoText),
                        fieldRow('Planificacion ID', planificacionId),
                        fieldRow('Tarifa inscripcion', getValue('tarifa_inscripcion')),
                        fieldRow('Mensualidad', getValue('mensualidad')),
                        fieldRow('Fecha de inscripcion', formatDateIso(getValue('fecha_inscripcion'))),
                    ],
                },
                {
                    titulo: 'Requisitos y terminos',
                    rows: [
                        fieldRow('Requisitos entregados', requisitos.length > 0 ? requisitos.join(', ') : 'Ninguno seleccionado'),
                        fieldRow('Acepta terminos y condiciones', document.getElementById('acepta_terminos').checked ? 'Si' : 'No'),
                        fieldRow('Inscripcion activa', document.getElementById('inscripcion_activa').checked ? 'Si' : 'No'),
                        fieldRow('Observaciones', getValue('observaciones')),
                    ],
                },
            ],
        };
    }

    function openPrintableResumenPdf(resumen) {
        const seccionBasica = resumen.secciones.find((section) => section.titulo === 'Informacion basica') || null;
        const seccionesRestantesHtml = resumen.secciones
            .filter((section) => section.titulo !== 'Informacion basica')
            .map((section) => buildSectionHtml(section.titulo, section.rows))
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

        const html = '<!doctype html><html lang="es"><head><meta charset="utf-8">'
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

        const win = window.open('', '_blank');
        if (!win) {
            throw new Error('No se pudo abrir la ventana de impresion. Habilita pop-ups para este sitio.');
        }

        win.document.open();
        win.document.write(html);
        win.document.close();
    }

    fotoInput.addEventListener('change', function () {
        const file = this.files && this.files.length > 0 ? this.files[0] : null;
        if (!file) {
            fotoNombre.textContent = 'Ningun archivo seleccionado';
            preview.innerHTML = '<span class="text-muted">Sin foto</span>';
            return;
        }

        fotoNombre.textContent = file.name;

        const reader = new FileReader();
        reader.onload = function (e) {
            const src = String(e.target && e.target.result ? e.target.result : '');
            preview.innerHTML = '<img src="' + src + '" alt="Foto del estudiante" class="img-fluid rounded" style="max-height:180px;">';
        };
        reader.readAsDataURL(file);
    });

    gradoSelect.addEventListener('change', handleGradoChange);
    anioOfertaSelect.addEventListener('change', function () {
        renderGradosFromPlanificacion();
        clearTarifaAuto();
    });

    formInscripcion.addEventListener('submit', function (event) {
        event.preventDefault();

        const form = event.currentTarget;
        if (!form.reportValidity()) {
            return;
        }

        try {
            pendingPayload = buildPersistPayload();
            pendingResumen = buildResumenInscripcion();
            renderRevisionModal(pendingResumen);
            showRevisionModal();
        } catch (error) {
            window.alert(error.message || 'No se pudo guardar la inscripcion.');
        }
    });

    btnCorregir.addEventListener('click', function () {
        hideRevisionModal();
    });

    btnContinuar.addEventListener('click', async function () {
        if (!pendingResumen || !pendingPayload) {
            hideRevisionModal();
            return;
        }

        const originalBtnHtml = submitButton.innerHTML;
        const originalContinueHtml = btnContinuar.innerHTML;

        submitButton.disabled = true;
        btnContinuar.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        btnContinuar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

        try {
            const saveResult = await persistInscripcion(pendingPayload);
            hideRevisionModal();
            openPrintableResumenPdf(pendingResumen);
            window.alert('Inscripcion guardada correctamente. Estudiante ID: ' + saveResult.estudianteId + ' | Inscripcion ID: ' + saveResult.inscripcionId);
            resetInscripcionForm();
            pendingResumen = null;
            pendingPayload = null;
        } catch (error) {
            window.alert(error.message || 'No se pudo guardar la inscripcion.');
        } finally {
            submitButton.disabled = false;
            btnContinuar.disabled = false;
            submitButton.innerHTML = originalBtnHtml;
            btnContinuar.innerHTML = originalContinueHtml;
        }
    });

    initFechaInscripcionPorDefecto();
    loadOfertaAcademica();
})();
</script>
