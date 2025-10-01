<?php
    session_start();

    $comprobarPass = false;
    $comprobarEmail = false;

    $_SESSION['nombre'] = $_POST['name'];

    if ($_POST['password'] === $_POST['compPassword'] && $_POST['password'] != "") $comprobarPass = true;
    else{
        $_SESSION['error'] = "La contraseña no es valida";
        header('Location:http://localhost/Practicas/Biblioteca-Online');
    } 

    if (filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
        $comprobarEmail = true;
        $_SESSION['email'] = $_POST['email'];
    }else{
        $_SESSION['error'] = "Formato de email incorrecto";
        header('Location:http://localhost/Practicas/Biblioteca-Online');
    }

    header('Location:http://localhost/Practicas/Biblioteca-Online/php/compRegistro.php');

    if($_SESSION['comprobarUser']){
        if ($comprobarEmail && $comprobarPass) {
            try {
                $conn = require( "../db/conection.php");

                // prepare sql and bind parameters
                $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, passw)
                VALUES (:nombre, :email, :passw)");
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':passw', $password);

                // insert a row
                $nombre = $_POST['name'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_ARGON2ID);
                $stmt->execute();

                $_SESSION['error'] = "Se ha registado correctamente";
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            $conn = null;
            $_SESSION['nombre'] ="";
            $_SESSION['email'] ="";
            header('Location:http://localhost/Practicas/Biblioteca-Online');
        }
    } else {
        $_SESSION['email'] = "";
        $_SESSION['error'] = "Ya existe un usuario con ese email";
        header('Location:http://localhost/Practicas/Biblioteca-Online');
    }