<?php
    session_start();

    if(isset($_POST['cerrar'])) {
        session_destroy();
        header('Location:http://localhost/Practicas/Biblioteca-Online');
    }
    else header('Location:http://localhost/Practicas/Biblioteca-Online/biblioteca.php');