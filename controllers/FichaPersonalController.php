<?php
require_once __DIR__ . '/../models/Persona.php';

class FichaPersonalController {
    // Listar todas las fichas de personal
    public function index() {
        // Aquí iría la lógica para obtener todas las personas
        // Ejemplo: $personas = Persona::all();
        // require '../views/ficha_listado.php';
    }

    // Mostrar formulario de registro
    public function create() {
        // require '../views/ficha_form.php';
    }

    // Guardar nueva ficha
    public function store($data) {
        // Lógica para guardar en la base de datos
    }

    // Mostrar detalle de una ficha
    public function show($id) {
        // require '../views/ficha.php';
    }

    // Mostrar formulario de edición
    public function edit($id) {
        // require '../views/ficha_form.php';
    }

    // Actualizar ficha
    public function update($id, $data) {
        // Lógica para actualizar en la base de datos
    }

    // Eliminar ficha
    public function delete($id) {
        // Lógica para eliminar
    }
}
