<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca online</title>
</head>
<body>
    <form action="php/registro.php" method = "POST">
    <h3>Registro de nuevo usuario</h3>        
    Nombre: <input type="text" name="name" value = "<?php if(isset($_SESSION['nombre'])) echo $_SESSION['nombre']?>"> <br>   
    Email: <input type="text" name="email" value = "<?php  if(isset($_SESSION['email'])) echo $_SESSION['email']?>"><br>       
    Contraseña: <input type="password" name="password" required > <br>
    Repite la contraseña: <input type="password" name="compPassword" required> <br>
    <input type="submit" name= "abrir" value="Registrarse" >
    </form>
    
    <form action="php/inicio.php" method = "POST">
    <h3>Inicio de Sesión</h3>
    Email: <input type="text" name="emailI" value = "<?php  if(isset($_SESSION['emailI'])) echo $_SESSION['emailI']?>"><br>       
    Contraseña: <input type="password" name="contra" required > <br>
    <input type="submit" name= "abrir" value="Iniciar Sesión" >
    </form>
    <?php        
        if (isset($_SESSION['error'])){
            echo "<h3>" . $_SESSION['error'] . "<h3>";
        }        
    ?>
    
</body>
</html>
<?php session_destroy(); ?>
