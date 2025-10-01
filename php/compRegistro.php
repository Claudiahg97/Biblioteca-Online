<?php
    session_start();

    try {
        $conn = require( "../db/conection.php");

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $_SESSION['email']);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC); 
        /*Guardo la información sacada de la base de datos en la variable $usuario
        * con la función fetch(PDO::FETCH_ASSOC) el contenido es un array asosiativo
        */
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    if (empty($usuario)) $_SESSION['comprobarUser'] = true;
    else $_SESSION['comprobarUser'] = false;