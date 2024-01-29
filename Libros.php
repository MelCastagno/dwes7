
    <?php

    class Libros
    {
        private $conn;  // Variable para almacenar la conexión

        /**
         * Constructor que acepta una conexión existente o establece una nueva.
         *
         * @param mysqli|null $conn Objeto de conexión MySQLi existente (opcional).
         */
        public function __construct($conn = null)
        {
            if ($conn !== null) {
                $this->conn = $conn;
            }
        }

        /**
         * Método para establecer la conexión a la base de datos.
         *
         * @param string $servidor   Nombre del servidor de la base de datos.
         * @param string $baseDatos  Nombre de la base de datos.
         * @param string $usuario    Nombre de usuario de la base de datos.
         * @param string $contraseña Contraseña de la base de datos.
         *
         * @return mysqli|null Objeto de conexión MySQLi si la conexión es exitosa, null si hay un error.
         */
        // Configuración base de datos

        public function conexion($servidor, $base_de_datos, $usuario, $contrasena)
        {

            // Se crea la conexión a la base de datos
            try {
                $mysqli = new \mysqli($servidor, $usuario, $contrasena, $base_de_datos);
            } catch (mysqli_sql_exception $e) {
                # error_log($e->__toString());
                return null;
            }
            return $mysqli;
        }

        /**
         * Método para consultar autores.
         *
         * @param mysqli $conexion Objeto de conexión MySQLi.
         * @param int|null $idAutor ID del autor a consultar (opcional).
         *
         * @return array|null Array de autores si la consulta es exitosa, null si hay un error.
         */
        public function consultarAutores($conexion, $idAutor = null)
        {
            $query = "SELECT * FROM Autor";
            if ($idAutor !== null) {
                $query .= " WHERE id = $idAutor";
            }

            $result = $conexion->query($query);

            // Verificar si hubo algún error en la consulta
            if ($result === false) {
                return null;
            }

            // Obtener los resultados como un array de objetos
            $autores = $result->fetch_all(MYSQLI_ASSOC);

            return $autores;
        }

        /**
         * Método para consultar libros de un autor.
         *
         * @param mysqli $conexion Objeto de conexión MySQLi.
         * @param int|null $idAutor ID del autor para consultar libros (opcional).
         *
         * @return array|null Array de libros si la consulta es exitosa, null si hay un error.
         */
        public function consultarLibros($conexion, $idAutor = null)
        {
            $query = "SELECT * FROM Libro";
            if ($idAutor !== null) {
                $query .= " WHERE id_autor = $idAutor";
            }

            $result = $conexion->query($query);

            // Verificar si hubo algún error en la consulta
            if ($result === false) {
                return null;
            }

            // Obtener los resultados como un array de objetos
            $libros = $result->fetch_all(MYSQLI_ASSOC);

            return $libros;
        }


        /**
         * Método para consultar datos de un libro.
         *
         * @param mysqli $conexion Objeto de conexión MySQLi.
         * @param int $idLibro ID del libro a consultar.
         *
         * @return array|null Array de datos del libro si la consulta es exitosa, null si hay un error.
         */
        public function consultarDatosLibro($conexion, $idLibro)
        {
            $query = "SELECT * FROM Libro WHERE id = $idLibro";

            $result = $conexion->query($query);

            // Verificar si hubo algún error en la consulta
            if ($result === false) {
                return null;
            }

            // Obtener los resultados como un array de objetos
            $datosLibro = $result->fetch_assoc();

            return $datosLibro;
        }

        /**
         * Método para borrar un autor por ID.
         *
         * @param mysqli $conexion Objeto de conexión MySQLi.
         * @param int $idAutor ID del autor a borrar.
         *
         * @return bool true si la operación de borrado es exitosa, false si hay un error.
         */
        public function borrarAutor($conexion, $idAutor)
        {
            $query = "DELETE FROM Autor WHERE id = $idAutor";

            // Ejecutar la consulta y verificar si fue exitosa
            return $conexion->query($query);
        }

        /** 
         * Método para borrar un libro por ID.
         *
         * @param mysqli $conexion Objeto de conexión MySQLi.
         * @param int $idLibro ID del libro a borrar.
         *
         * @return bool true si la operación de borrado es exitosa, false si hay un error.
         */
        public function borrarLibro($conexion, $idLibro)
        {
            $query = "DELETE FROM Libro WHERE id = $idLibro";

            // Ejecutar la consulta y verificar si fue exitosa
            return $conexion->query($query);
        }
    }




    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "Libros";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password);

    // Comprobar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    /*
    // Crear base de datos
    $sql = "CREATE DATABASE IF NOT EXISTS $database";
    if ($conn->query($sql) === TRUE) {
        echo "Base de datos creada correctamente<br>";
    } else {
        echo "Error al crear la base de datos: " . $conn->error . "<br>";
    }

    // Seleccionar la base de datos
    $conn->select_db($database);

    // Crear la tabla Autor
    $sql = "CREATE TABLE IF NOT EXISTS Autor (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(15) NOT NULL,
            apellidos VARCHAR(25) NOT NULL,
            nacionalidad VARCHAR(10) NOT NULL
        )";

    if ($conn->query($sql) === TRUE) {
        echo "Tabla Autor creada correctamente<br>";
    } else {
        echo "Error al crear la tabla Autor: " . $conn->error . "<br>";
    }

    // Insertar datos en la tabla Autor
    $sql = "INSERT INTO Autor (nombre, apellidos, nacionalidad) VALUES
            ('J.R.R.', 'Tolkien', 'Inglaterra'),
            ('Isaac', 'Asimov', 'Rusia')";

    if ($conn->query($sql) === TRUE) {
        echo "Datos insertados en la tabla Autor correctamente<br>";
    } else {
        echo "Error al insertar datos en la tabla Autor: " . $conn->error . "<br>";
    }

    // Crear la tabla Libro
    $sql = "CREATE TABLE IF NOT EXISTS Libro (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(50) NOT NULL,
            f_publicacion DATE NOT NULL,
            id_autor INT,
            FOREIGN KEY (id_autor) REFERENCES Autor(id)
        )";

    if ($conn->query($sql) === TRUE) {
        echo "Tabla Libro creada correctamente<br>";
    } else {
        echo "Error al crear la tabla Libro: " . $conn->error . "<br>";
    }

    // Insertar datos en la tabla Libro
    $sql = "INSERT INTO Libro (titulo, f_publicacion, id_autor) VALUES 
            ('El Hobbit', '1937-09-21', 1),
            ('La Comunidad del Anillo', '1954-07-29', 1),
            ('Las dos torres', '1954-11-11', 1),
            ('El retorno del Rey', '1955-10-20', 1),
            ('Un guijarro en el cielo', '1950-01-19', 2),
            ('Fundación', '1951-06-01', 2),
            ('Yo, robot', '1950-12-02', 2)";

    if ($conn->query($sql) === TRUE) {
        echo "Datos insertados en la tabla Libro correctamente<br>";
    } else {
        echo "Error al insertar datos en la tabla Libro: " . $conn->error . "<br>";
    }

    // Cerrar la conexión
    $conn->close();
    */

    ?>