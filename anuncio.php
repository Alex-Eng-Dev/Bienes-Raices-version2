<?php

// Validar el ID de la propiedad
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if(!$id){
        header('Location: /');
    }

    // Importar la conexión a la base de datos
    require 'includes/config/database.php';
    $db = conectarDB();

    // Consultar la base de datos para obtener el anuncio
    $query = "SELECT * FROM propiedades WHERE id = ${id}";

    //Obtener Resultados
    $resultado = mysqli_query( $db, $query);

    // Validar si se obtuvo un resultado
    if(!$resultado->num_rows){
        header('Location: /');
    }

    $propiedad = mysqli_fetch_assoc($resultado);



require 'includes/funciones.php';
incluirTemplate('header', $start = null);
?>

    <!-- Main -->
    <main class="container seccion contenido-centrado">
        <h1><?php echo $propiedad['titulo']; ?></h1>

            <img loading="lazy" src="/imagenes/<?php echo $propiedad['imagen'] . ".jpg"; ?>" alt="imagen de la propiedad">
        

        <div class="resume-propiety">
            <p class="price">$ <?php echo $propiedad['precio']; ?></p>
            <ul class="icons-characteristics">
                <li>
                    <img loading="lazy" src="build/img/icono_wc.svg" alt="Icono WC">
                    <p><?php echo $propiedad['wc']; ?></p>
                </li>
                <li>
                    <img loading="lazy" src="build/img/icono_estacionamiento.svg" alt="Icono icono_estacionamiento">
                    <p><?php echo $propiedad['estacionamiento']; ?></p>
                </li>
                <li>
                    <img loading="lazy" src="build/img/icono_dormitorio.svg" alt="Icono dormitorio">
                    <p><?php echo $propiedad['habitaciones']; ?></p>
                </li>

            </ul>
            <p> <?php echo $propiedad['descripcion']; ?></p>

        </div>
    </main>

    <!-- Footer -->
<?php
//Cerrar la conexión a la base de datos
mysqli_close($db);

incluirTemplate('footer'); 
?>