<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Online</title>
</head>
<body>
    <form action="subirLibro" method = "POST">
        <h3>Subir un nuevo libro</h3>
        ISBN: <input type="text" name="isbn">
        Titulo: <input type="text" name="titulo">
        Autor: <input type="text" name="autor">
        Categoría: <input type="text" name="categoria">
        Fecha de publicación(aaaa-mm-dd): <input type="date" name="fecha"> 
        Link de compra: <input type="url" name="link">
        Sinopsis: <input type = "text" name="descripcion">
        <input type="sumit" name="subir" value = "Registrar libro">
    </form>
</body>
</html>