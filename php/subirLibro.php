<?php
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: http://localhost/Biblioteca-Online");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = require("conection.php");

    $target_dir = "../uploads/";
    $target_file = "";

    // Manejo de la imagen
    if (empty($_FILES["fileToUpload"]["name"])) {
        $target_file = "uploads/default.jpg"; // Ruta relativa para guardar en BD
    } else {
        $nameOriginal = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_FILENAME);
        $extension = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
        // Hash único: sha256(nombreArchivo_nombreUsuario)
        $hashNombre = hash('sha256', $nombreOriginal . '_' . $_SESSION['nombre']);
        $newFileName = $hashNombre . '.' . $extension; // Nombre único
        $target_file = $target_dir . $newFileName;
        $target_file_db = "uploads/" . $newFileName; // Ruta para BD

        // Verificar que es una imagen
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check === false) {
            echo "<script>alert('El archivo no es una imagen válida'); window.history.back();</script>";
        }

        // Verificar tamaño (5MB máximo)
        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            echo "<script>alert('El archivo es demasiado grande'); window.history.back();</script>";
        }

        // Permitir ciertos formatos
        if(!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "<script>alert('Solo se permiten archivos JPG, JPEG, PNG y GIF'); window.history.back();</script>";
        }

        // Intentar subir el archivo
        if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<script>alert('Error al subir la imagen'); window.history.back();</script>";
        }
    }
    
    // Obtener datos del formulario
    $isbn = $_POST['isbn'] ?? '';
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $fecha = $_POST['fecha'] ?? null;
    $link = $_POST['link'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $generos = $_POST['generos']; // Array de IDs de géneros
    $id_usuario = $_SESSION['id_usuario'];
    $img = $target_file_db ?? 'uploads/default.jpg'; //Elijo la ruta de la imagen subida o la imagen por defecto.
    
    try {
        // Preparar consulta para insertar libro (con PDO)
        $stmt = $conn->prepare("INSERT INTO libros (isbn, titulo, autor, fecha, link, descripcion, id_usuario, img) 
                               VALUES (:isbn, :titulo, :autor, :fecha, :link, :descripcion, :id_usuario, :img)");
        
        $stmt->bindParam(':isbn', $isbn);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':autor', $autor);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':link', $link);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':img', $img);
        
        if ($stmt->execute()) {
            $id_libro = $conn->lastInsertId(); // Obtener ID del libro insertado
            
            // Insertar los géneros en la tabla libro_genero
            $stmt_genero = $conn->prepare("INSERT INTO libro_genero (id_libro, id_genero) VALUES (:id_libro, :id_genero)");
            
            foreach ($generos as $id_genero) {
                $stmt_genero->bindParam(':id_libro', $id_libro);
                $stmt_genero->bindParam(':id_genero', $id_genero);
                $stmt_genero->execute();
            }
            
            echo "<script>alert('Libro registrado exitosamente'); window.location.href='biblioteca.php';</script>";
        }
    } catch(PDOException $e) {
        echo "<script>alert('Error al registrar el libro: " . $e->getMessage() . "'); window.history.back();</script>";
    }
    
    $conn = null;
}
