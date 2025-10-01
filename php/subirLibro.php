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
    
    // Obtener datos del formulario
    $isbn = $_POST['isbn'];
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $fecha = $_POST['fecha'];
    $link = $_POST['link'];
    $descripcion = $_POST['descripcion'];
    $generos = $_POST['generos']; // Array de IDs de géneros
    $id_usuario = $_SESSION['id_usuario'];
    
    // Preparar consulta para insertar libro
    $stmt = $conn->prepare("INSERT INTO libros (isbn, titulo, autor, fecha, link, descripcion, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $isbn, $titulo, $autor, $fecha, $link, $descripcion, $id_usuario);
    
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