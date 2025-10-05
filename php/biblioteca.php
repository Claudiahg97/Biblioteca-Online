<?php
session_start();

// Mostrar mensajes de éxito o error
if (isset($_SESSION['exito'])) {
    echo "<div style='color: green; padding: 10px; border: 1px solid green; margin: 10px;'>" . 
         htmlspecialchars($_SESSION['exito']) . "</div>";
    unset($_SESSION['exito']);
}

$conn = require("conection.php");

// Obtener todos los libros de la base de datos
$sql = "SELECT * FROM libros ORDER BY titulo";
$stmt = $conn->query($sql);
$libros = $stmt->fetchAll(PDO::FETCH_ASSOC); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
</head>
<body>
    <h1>Biblioteca Online</h1>
    
    <?php if(isset($_SESSION['nombre'])): ?>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></p>
        <form action="formularioLibro.php" method="GET">
            <button type="submit">Registrar un nuevo libro</button>
        </form>
    <?php endif; ?>
    
    <hr>
    
    <h2>Catálogo de Libros</h2>
    
    <?php if (count($libros) > 0): ?>
        <div class="libros-container">
            <?php foreach ($libros as $libro): ?>
                <div class="libro-card">
                    <?php if (!empty($libro['img'])): 
                        $imagen = "../" . $libro['img'];?>
                        <img src="<?php echo htmlspecialchars($imagen); ?>" alt="Portada" width="100" height="150">
                    <?php endif; ?>
                    
                    <h3><?php echo htmlspecialchars($libro['titulo']); ?></h3>
                    <p><strong>Autor:</strong> <?php echo htmlspecialchars($libro['autor']); ?></p>
                    
                    <?php if (!empty($libro['isbn'])): ?>
                        <p><strong>ISBN:</strong> <?php echo htmlspecialchars($libro['isbn']); ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($libro['fecha'])): ?>
                        <p><strong>Fecha:</strong> <?php echo htmlspecialchars($libro['fecha']); ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($libro['descripcion'])): ?>
                        <p><?php echo htmlspecialchars(substr($libro['descripcion'], 0, 150)); ?>...</p>
                    <?php endif; ?>
                    
                    <?php if (!empty($libro['link'])): ?>
                        <a href="<?php echo htmlspecialchars($libro['link']); ?>" target="_blank">Comprar</a>
                    <?php endif; ?>
                    <?php
                        if($libro['id_usuario'] === $_SESSION['id_usuario']): ?>
                        <form action="editarLibro.php" method="GET">
                            <button type="submit">Editar el libro</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-libros">
            <h3> No hay libros registrados todavía</h3>
            <p>Sé el primero en añadir un libro a la biblioteca</p>
        </div>
    <?php endif; ?>
    <form action="cierre.php" method = "POST">
    <input type="submit" name= "cerrar" value="Cerrar Sesión" >
    </form>
</body>
</html>