<?php 
    session_start(); 

   
$target_dir = "uploads/";

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