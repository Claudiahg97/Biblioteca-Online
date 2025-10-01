<?php
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['nombre'])) {
    header("Location: inicio.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $conn = require( "../db/conection.php");

    $target_dir = "../uploads/";

// Si no subió nada, usamos una imagen por defecto
    if (empty($_FILES["fileToUpload"]["name"])) {
        $target_file = $target_dir . "default.jpg"; // tu imagen por defecto en uploads/
        $uploadOk = 1;
        $imageFileType = "jpg";
    } else {
        // Si subió algo, seguimos con el flujo normal
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verificación de si realmente es una imagen
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
    }
    
    // Obtener datos del formulario
    $isbn = $_POST['isbn'];
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $fecha = $_POST['fecha'];
    $link = $_POST['link'];
    $descripcion = $_POST['descripcion'];
    $generos = $_POST['generos']; // Array de IDs de géneros
    $id_usuario = $_SESSION['id_usuario'];
    $img = password_hash($target_file, PASSWORD_ARGON2ID);
    
    // Preparar consulta para insertar libro
    $stmt = $conn->prepare("INSERT INTO libros (isbn, titulo, autor, fecha, link, descripcion, id_usuario, img) VALUES (?, ?, ?, ?, ?, ?, ?,?)");
    $stmt->bind_param("ssssssi", $isbn, $titulo, $autor, $fecha, $link, $descripcion, $id_usuario, $img);
    
    if ($stmt->execute()) {
        $id_libro = $stmt->insert_id; // Obtener ID del libro insertado
        
        // Insertar los géneros en la tabla libro_genero
        $stmt_genero = $conn->prepare("INSERT INTO libro_genero (id_libro, id_genero) VALUES (?, ?)");
        
        foreach ($generos as $id_genero) {
            $stmt_genero->bind_param("ii", $id_libro, $id_genero);
            $stmt_genero->execute();
        }
        
        $stmt_genero->close();
        
        echo "<script>alert('Libro registrado exitosamente'); window.location.href='insertarLibro.php';</script>";
    } else {
        echo "<script>alert('Error al registrar el libro: " . $conn->error . "');</script>";
    }
    
    $stmt->close();
    $conn->close();
}
?>