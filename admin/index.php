<?php
require '../includes/funciones.php';
    $auth = estaAutenticado();

    if(!$auth){
        header('Location: /');
    }

//importar la connexion
require '../includes/config/database.php';
$db= conectarDB();
//Escribir el query para obtener las propiedades
$query = "SELECT * FROM propiedades";
//consultar la base de datos
$resultadoQuery = mysqli_query($db, $query);

/* Mostrar mensaje condicional*/
$resultado = $_GET['resultado'] ?? null;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if($id){
        //Eliminar el archivo
        $query = "SELECT imagen FROM propiedades WHERE id = ${id}";
        $resultado = mysqli_query($db, $query);
        $propiedad = mysqli_fetch_assoc($resultado);
        unlink('../imagenes/' . $propiedad['imagen'] . '.jpg');
    

        //Eliminar la propiedad
        $query = "DELETE FROM propiedades WHERE id = ${id}";
        $resultado = mysqli_query($db, $query);
        if($resultado){
            header('location: /admin?resultado=3');
        }
    }
    
}

//Incluiye el template de header
incluirTemplate('header', $start = null);
?>

    <!-- Main -->
    <main class="container seccion">
        <h1>Administrador de Bienes Raices</h1>
        <!-- mostrar mensaje -->
        <?php if( intval($resultado) === 1): ?>
            <p class="alerta exito">Anuncio Creado Correctamente</p>
            <?php elseif(intval($resultado) === 2): ?>
            <p class="alerta exito">Anuncio Actualizado Correctamente</p>
            <?php elseif(intval($resultado) === 3): ?>
            <p class="alerta exito">Anuncio Eliminado Correctamente</p>
            <?php endif?>

        <a href="/admin/propiedades/crear.php" class="button button-green">Nueva Propiedad</a>
            <table class="propiedades">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titulo</th>
                        <th>Imagen</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody> <!-- Mostrar los resultados -->
                    <?php while($propiedad = mysqli_fetch_assoc($resultadoQuery)): ?>
                    
                    <tr>
                        <td> <?php echo $propiedad['id']; ?> </td>
                        <td><?php echo $propiedad['titulo']; ?></td>
                        <td><img src="/imagenes/<?php echo $propiedad['imagen'] . ".jpg"; ?>" class="imagen-tabla" alt=""></td>
                        <td>$ <?php echo $propiedad['precio']; ?></td>
                        <td>
                            <form method="POST" class="w-100" accion=>
                                <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>" >
                                <input type="submit" class="button-red-block" value="Borrar">
                            </form>
                            <a class="button-yellow-block" href="/admin/propiedades/actualizar.php?id=<?php echo $propiedad['id']; ?>">Actualizar</a>
                        </td>
                    </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
    </main>

<!-- Footer -->
<?php
//Cerrar la conexion a la base de datos
mysqli_close($db);

//Incluir el template de footer
incluirTemplate('footer'); 
?>