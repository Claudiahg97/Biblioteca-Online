<?php
    session_start();
    $conn = require( "../db/conection.php");

    // Obtener géneros de la base de datos
    $sql = "SELECT * FROM libros";
    $stmt = $conn->query($sql);
    $libros = $stmt->fetch(PDO::FETCH_ASSOC); 
?>
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
</head>
<body>
    Hola
</body>
</html>