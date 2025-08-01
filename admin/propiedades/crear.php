<?php

require '../../includes/funciones.php';
//Codigo para que el admin tenga acceso
$auth = estaAutenticado();

    if(!$auth){
        header('Location: /');
    }

//Base de datos
require '../../includes/config/database.php';
$db= conectarDB();

//Consultar para obtener los vendedores
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

//Arreglo con mensjes de errores
$errores = [];
    $titulo = '';
    $precio = '';
    $descripcion = '';
    $habitaciones = '';
    $wc = '';
    $estacionamiento = '';
    // $vendedores_id = $_POST['vendedor'];
    $vendedores_id = '';

//Ejecutar el codigo despues de que el usuario envie el formulario
if($_SERVER["REQUEST_METHOD"] === 'POST'){
    //  echo "<pre>";
    //      var_dump($_POST);
    //  echo "</pre>";

     echo "<pre>";
         var_dump($_FILES);
     echo "</pre>";

     

    $titulo = mysqli_real_escape_string( $db, $_POST['titulo']);
    $precio = mysqli_real_escape_string( $db, $_POST['precio']);
    $descripcion = mysqli_real_escape_string( $db, $_POST['descripcion']);
    $habitaciones = mysqli_real_escape_string( $db, $_POST['habitaciones']);
    $wc = mysqli_real_escape_string( $db, $_POST['wc']);
    $estacionamiento = mysqli_real_escape_string( $db, $_POST['estacionamiento']);
    // $vendedores_id = mysqli_real_escape_string( $db, $_POST['vendedor']);
    $vendedores_id = mysqli_real_escape_string( $db, $_POST['vendedor'] ?? null);
    $creado = date('Y/m/d');

    //Asignar files hacia una variable
    $imagen = $_FILES['imagen'];

    if(!$titulo){
        $errores[] = "Debes añadir un titulo";
    }
    if(!$precio){
        $errores[] = "El precio es obligatorio";
    }
    if( strlen( $descripcion) < 50){
        $errores[] = "La descripcion es obligatoria y debe tener mas de 50 caracteres";
    }
    if(!$habitaciones){
        $errores[] = "Las habitaciones son obligatorias";
    }
    if(!$wc){
        $errores[] = "Los baños son obligatorios";
    }
    if(!$estacionamiento){
        $errores[] = "Parking son obligatorios";
    }
    if(!$vendedores_id){
        $errores[] = "Elige a un vendedor";
    }
    if(!$imagen['name']){
        $errores[] = "La imagen es obligatoria";
    }

    // Validar por tamaño
    $medida = 1000 * 1000; //1MB
    if($imagen['size'] > $medida){
        $errores[] = "La imagen pesa mucho";
    }

    //  echo "<pre>";
    //     var_dump($errores);
    // echo "</pre>";
    
    //Revisar que el array este vacio
    if(empty($errores)){

        /* Subida de Archivos */
        //Crear carpeta
        $carpetaImagenes = '../../imagenes/';

        if(!is_dir($carpetaImagenes)){
            mkdir($carpetaImagenes);
        }

        //Generar nombre unico 
        $nombreImagen = md5(uniqid(rand(), true));
        

        //Subir la imagen
        move_uploaded_file($imagen['tmp_name'], $carpetaImagenes .  $nombreImagen . ".jpg");
        

        //Insertar en la base de datos
        $query = "INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedores_id ) VALUES('$titulo', '$precio', '$nombreImagen', '$descripcion', '$habitaciones', '$wc', '$estacionamiento', '$creado', '$vendedores_id')"; 
        //echo $query;
    
        $resultado = mysqli_query($db, $query);
        
        if($resultado){
            //Redireccionar al usuario
            header('Location: /admin?resultado=1');
        }

    }

    
}



incluirTemplate('header', $start = null);
?>

    <!-- Main -->
    <main class="container seccion">
        <h1>Crear</h1>

        <a href="/admin" class="button button-green">Volver</a>

        <!-- mostrar errores -->

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error ?>
            </div>
        <?php endforeach ?>

        <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">

        <fieldset>
            <legend>Información General</legend>

            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo propiedad" value="<?php echo $titulo; ?>">

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio propiedad" value="<?php echo $precio; ?>">

            <label for="imagen">imagen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name= "imagen">

            <label for="descripcion">Descripcion:</label>
            <textarea id="descripcion" name="descripcion" ><?php echo $descripcion; ?></textarea>
        </fieldset>

        <fieldset>
            <legend>Información Propiedad</legend>

            <label for="habitaciones">Habitaciones:</label>
            <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo $habitaciones; ?>">
            
            <label for="wc">Baños:</label>
            <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo $wc; ?>">
            
            <label for="estacionamiento">Estacionamiento:</label>
            <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo $estacionamiento; ?>">
        </fieldset>

        <fieldset>
            <legend>Vendedor</legend>

            <select name="vendedor">
                <option value="">--Seleccione--</option>
                <?php while($vendedor = mysqli_fetch_assoc($resultado)): ?>
                    <option <?php echo $vendedores_id === $vendedor['id'] ? 'selected' : ''; ?> value="<?php echo $vendedor['id']; ?>"> <?php echo $vendedor['nombre']. " ". $vendedor['apellido']; ?> </option>
                    <?php endwhile; ?>
            </select>

        </fieldset>

        <input type="submit" value="Crear Propiedad" class="button button-green">
        </form>
    </main>

<!-- Footer -->
<?php

incluirTemplate('footer'); 
?>