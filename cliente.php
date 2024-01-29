<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API DWES</title>
    <style>
        body {
            box-sizing: border-box;
            font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            margin: 4em;
        }
        .titulo {
            border-bottom: 1px solid #e76f51;
        }
        a {
            text-decoration: none;
            color: black;
            
        }
        a:hover {
                color: #e76f51;
            }
        #obras {
            color: #e76f51;
        }   
    </style>
</head>

<body>

    <?php
    /**
     * Función para obtener de forma segura los parámetros de la URL.
     *
     * @param string $parametro El nombre del parámetro.
     * @return string|null Retorna el valor del parámetro o null si no está presente.
     */
    function obtenerParametroSeguro($parametro)
    {
        return isset($_GET[$parametro]) ? htmlspecialchars($_GET[$parametro]) : null;
    }

    if (isset($_GET["action"]) && isset($_GET["id"])) {
        if ($_GET["action"] == "get_datos_autor") {
            // Realizar la petición a la API para obtener datos del autor
            $app_info = file_get_contents('http://localhost/dwes/api.php?action=get_datos_autor&id=' . $_GET["id"]);
            $app_info = json_decode($app_info);

            if ($app_info) {
                echo "<h2 class='titulo'>AUTOR</h3>";
                echo "<p><strong>Nombre: </strong>" . $app_info->nombre . "</p>";
                echo "<p><strong>Apellidos: </strong>" . $app_info->apellidos . "</p>";
                echo "<p><strong>Fecha de nacimiento: </strong>" . $app_info->nacionalidad . "</p>";

                if ($app_info->libros) {
                    echo "<h3 id='obras'>OBRAS</h4>";
                    echo "<ul>";
                    foreach ($app_info->libros as $libro) {
                        echo "<li><a href='http://localhost/dwes/cliente.php?action=get_datos_libro&id={$libro->id}'>{$libro->titulo}</a></li>";
                    }
                    echo "</ul>";
                }

                echo "<br />";
                echo "<a href='http://localhost/dwes/cliente.php?action=get_listado_autores' alt='Lista de autores'>Volver a la lista de autores</a>";
                echo "<br>";
            } else {
                echo "Autor no encontrado.";
            }
        } elseif ($_GET["action"] == "get_datos_libro") {
            // Realizar la petición a la API para obtener datos del libro
            $app_info = file_get_contents('http://localhost/dwes/api.php?action=get_datos_libro&id=' . $_GET["id"]);
            $app_info = json_decode($app_info);


            if ($app_info) {
                echo "<p><strong>Titulo: </strong>" . $app_info->titulo . "</p>";
                echo "<p><strong>Fecha de publicación:</strong> " . $app_info->f_publicacion . "</p>";
                echo "<p><strong>Autor:</strong> <a href='http://localhost/dwes/cliente.php?action=get_datos_autor&id={$app_info->id_autor}'>{$app_info->nombre_apellidos}</a></p>";

                echo "<br />";
                echo "<a href='http://localhost/dwes/cliente.php?action=get_listado_autores' alt='Lista de autores'>Volver a la lista de autores</a>";
                echo "<br />";
            } else {
                echo "Libro no encontrado.";
            }
        }
    } else {
        // Petición para obtener lista de autores
        $lista_autores = file_get_contents('http://localhost/dwes/api.php?action=get_listado_autores');
        $lista_autores = json_decode($lista_autores);

        echo "<h2 class='titulo'>LISTA DE AUTORES</h2>";
        echo "<ul>";
        foreach ($lista_autores as $autor) {
            echo "<li><a href='http://localhost/dwes/cliente.php?action=get_datos_autor&id={$autor->id}'>{$autor->nombre} {$autor->apellidos}</a></li>";
        }
        echo "</ul>";
    }


     // Función para obtener los detalles de un libro
    /**
     * Obtiene el enlace formateado de un libro para mostrar en la lista.
     *
     * @param object $libro El objeto que representa un libro.
     * @return string El enlace HTML del libro.
     */
    function obtenerDetallesLibro($libro)
    {
        return "<a href='http://localhost/dwes/cliente.php?action=get_datos_libro&id={$libro->id}'>{$libro->titulo}</a>";
    }

    // Comprobar si la acción es obtener una lista de libros
    if (isset($_GET["action"]) && $_GET["action"] == "get_listado_libros") {
        // Solicitar una lista de libros a la API
        $lista_libros = file_get_contents('http://localhost/dwes/api.php?action=get_listado_libros');

        // Decodificar la respuesta JSON
        $lista_libros = json_decode($lista_libros);

        // Comprobar si la decodificación fue exitosa
        if ($lista_libros !== null) {
            echo "<ul>";
            // Mostrar cada libro en la lista
            foreach ($lista_libros as $libro) {
                echo "<li>" . obtenerDetallesLibro($libro) . "</li>";
            }
            echo "</ul>";

            echo "<br />";
            echo "<a href='http://localhost/dwes/cliente.php?action=get_listado_autores' alt='Lista de autores'>Volver a la lista de autores</a>";
            echo "<br />";
        } else {
            echo "Error al decodificar los datos JSON.";
        }
    } else {
        // Si no se especifica ninguna acción, mostrar un enlace a la lista de libros
        echo "<a href='http://localhost/dwes/cliente.php?action=get_listado_libros'>Ver lista de libros</a>";
    }

    ?>

    <?php

    ?>
</body>

</html>