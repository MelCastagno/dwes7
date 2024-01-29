<?php

require_once 'Libros.php';

$server = "localhost";
$database = "Libros";
$user = "root";
$passwd = "";

/**
 * Obtiene el listado de autores desde la base de datos.
 *
 * @return array|string Retorna un array con la lista de autores o un mensaje de error.
 */
function get_listado_autores()
{
    // Crear una instancia de la clase Libros para acceder a la base de datos
    $libros = new Libros();

    // Establecer la conexión a la base de datos
    global $server, $database, $user, $passwd;
    $conn = $libros->conexion($server, $database, $user, $passwd);

    // Verificar si la conexión es exitosa
    if ($conn === null) {
        // Retornar un mensaje de error
        return "Error en la conexión a la base de datos";
    }

    // Recuperar la lista de autores desde la base de datos
    $lista_autores = $libros->consultarAutores($conn);

    // Cerrar la conexión a la base de datos
    $conn->close();

    // Devolver la lista de autores
    return $lista_autores;
}

/**
 * Obtiene la información de un autor basada en su ID.
 *
 * @param int $id ID del autor.
 * @return array|string Retorna un array con la información del autor y la lista de libros escritos por él, o un mensaje de error.
 */
function get_datos_autor($id)
{
    // Crear una instancia de la clase Libros para acceder a la base de datos
    $libros = new Libros();

    // Establecer la conexión a la base de datos
    global $server, $database, $user, $passwd;
    $conn = $libros->conexion($server, $database, $user, $passwd);

    // Verificar si la conexión es exitosa
    if ($conn === null) {
        // Retornar un mensaje de error
        return "Error en la conexión a la base de datos";
    }

    // Recuperar la información del autor desde la base de datos
    $info_autor = $libros->consultarAutores($conn, $id);

    // Verificar si se encontró el autor
    if (!$info_autor) {
        // Retornar un mensaje de error
        return "Autor no encontrado";
    }

    // Recuperar la lista de libros escritos por el autor desde la base de datos
    $libros_autor = $libros->consultarLibros($conn, $id);

    $info_autor[0]['libros'] = $libros_autor;

    // Cerrar la conexión a la base de datos
    $conn->close();

    // Devolver la información del autor con la lista de libros
    return $info_autor[0];
}

/**
 * Obtiene el listado de libros (ID y título) desde la base de datos.
 *
 * @return array|string Retorna un array con el listado de libros (ID y título) o un mensaje de error.
 */
function get_listado_libros()
{
    // Crear una instancia de la clase Libros para acceder a la base de datos
    $libros = new Libros();

    // Establecer la conexión a la base de datos
    global $server, $database, $user, $passwd;
    $conn = $libros->conexion($server, $database, $user, $passwd);

    // Verificar si la conexión es exitosa
    if ($conn === null) {
        // Retornar un mensaje de error
        return "Error en la conexión a la base de datos";
    }

    // Recuperar el listado de libros (ID y título) desde la base de datos
    $listado_libros = $libros->consultarLibros($conn);

    // Cerrar la conexión a la base de datos
    $conn->close();

    // Devolver el listado de libros (ID y título)
    return $listado_libros;
}

/**
 * Obtiene la información detallada de un libro basada en su ID.
 *
 * @param int $id ID del libro.
 * @return array|string Retorna un array con la información detallada del libro o un mensaje de error.
 */
function get_datos_libro($id)
{
    // Crear una instancia de la clase Libros para acceder a la base de datos
    $libros = new Libros();

    // Establecer la conexión a la base de datos
    global $server, $database, $user, $passwd;
    $conn = $libros->conexion($server, $database, $user, $passwd);

    // Verificar si la conexión es exitosa
    if ($conn === null) {
        // Retornar un mensaje de error
        return "Error en la conexión a la base de datos";
    }

    // Recuperar la información del libro desde la base de datos
    $info_libro = $libros->consultarDatosLibro($conn, $id);

    // Verificar si se encontró el libro
    if (!$info_libro) {
        // Retornar un mensaje de error
        return "Libro no encontrado";
    }
    // Recuperar el nombre y apellidos del autor desde la base de datos
    $info_autor = $libros->consultarAutores($conn, $info_libro['id_autor']);

    // Formatear el resultado (titulo, f_publicacion, nombre_apellidos)
    $resultado = array(
        'titulo' => $info_libro['titulo'],
        'f_publicacion' => $info_libro['f_publicacion'],
        'nombre_apellidos' => $info_autor[0]['nombre'] . ' ' . $info_autor[0]['apellidos'],
        'id_autor' => $info_libro['id_autor']
    );

    // Cerrar la conexión a la base de datos
    $conn->close();

    // Devolver el resultado
    return $resultado;
}

$posibles_URL = array("get_listado_autores", "get_datos_autor", "get_listado_libros", "get_datos_libro");

$valor = "Ha ocurrido un error";

if (isset($_GET["action"]) && in_array($_GET["action"], $posibles_URL)) {
    switch ($_GET["action"]) {
        case "get_listado_autores":
            $valor = get_listado_autores();
            break;
        case "get_datos_autor":
            if (isset($_GET["id"]))
                $valor = get_datos_autor($_GET["id"]);
            else
                $valor = "Argumento no encontrado";
            break;
        case "get_listado_libros":
            $valor = get_listado_libros();
            break;
        case "get_datos_libro":
            if (isset($_GET["id"]))
                $valor = get_datos_libro($_GET["id"]);
            else
                $valor = "Argumento no encontrado";
            break;
    }
}

// Devolvemos los datos serializados en JSON
header('Content-Type: application/json');
exit(json_encode($valor, JSON_FORCE_OBJECT));
