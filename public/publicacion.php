<?php

    require_once '../scripts/bd_con.php';
    require_once '../scripts/clases/user.php';
    require_once '../scripts/clases/publicacion.php';
    require_once '../scripts/clases/comentario.php';
    require '../scripts/clases/error.php';
    
    session_start();
    $id = $_GET['id'];
    $res = $bd -> query("SELECT * FROM publicacion WHERE id_publicacion = $id");
    $pub = $res -> fetch_assoc();
    if(!$pub){
        header('location: 404.php');
        exit();
    }
    $pub = new publicacion($pub);
    if (!array_key_exists("logged", $_SESSION)) {
        $_SESSION["logged"] = FALSE;
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventAr</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="icon" type="image/x-icon" href="./img/icon/icon-sm.png">
</head>
<body>
    
<nav class="navbar navbar-expand-lg navbar-dark bg-primary justify-content-between" id='navbar'>
        <ul class='nav-desk'>
            <li><a href="home.php"><img src="./img/icon/icon-sm.png" alt="Ir al inicio"></a></li>
            <li><a href="acerca.php">Acerca de</a></li>
            <li><a href="contacto.php">Contacto</a></li>
        </ul>

        <form class="d-flex justify-content-between border rounded p-2 bg-white nav-desk" method='GET' action='home.php'>
            <input class="d-inline" type="search" name='search' placeholder="Buscar..." aria-label="Buscar" id='search-bar'>
            <?php
            if (isset($_GET['filter'])) {
                if ($_GET['filter'] == 'mis_pubs') {
                    echo "<input class='d-none' name='filter' value='mis_pubs' id='filter-input' type='hidden'>";
                }
            }
            ?>
            <button class="btn btn-outline-success my-sm-0 d-inline" type="submit">Buscar</button>
        </form>

        <?php
        echo "
            <div class='nav-responsive user-menu'>
                <img src='../public/img/icon/menu-hamburguesa.png' id='menu-img' onclick='toggleMenu(event)' class='me-3'>
                <div id='nav-responsive-lists'>
                <ul class='me-4 d-block'>
                    <li><a class='btn btn-secondary w-100' href='home.php'>EventAr</a></li>
                    <li><a class='btn btn-secondary w-100' href='acerca.php'>Acerca de</a></li>
                    <li><a class='btn btn-secondary w-100' href='contacto.php'>Contacto</a></li>
                </ul>
                <hr>";
            if($_SESSION['logged']){
                $id_user = $_SESSION["user"]->get_id();
                echo "
                
                <ul class='me-4 d-block'>
                    <li><a class='btn btn-secondary w-100' href='cuenta.php?id_user=$id_user' role='button'>Perfil</a></li>
                    <li><a class='btn btn-secondary w-100' href='home.php?filter=mis_pubs' role='button'>Mis publicaciones</a></li>
                    <li><a class='btn btn-secondary w-100' href='modificar_cuenta.php' role='button'>Configuración</a></li>";

                    if($_SESSION['user']->get_mod()){

                        echo"
                            <li><a class='btn btn-warning w-100' href='user_list.php' role='button'>Listado de usuarios</a></li>";
                    }
                
                    
                echo"
                    <li><a class='btn btn-danger w-100' href='../scripts/validate.php?origin=logout' role='button'>Salir</a></li>
                </ul>";
            }
            else{
                echo    "<ul class='me-4 d-block'>
                            <li><a class='btn btn-secondary w-100' href='registro.php'>Registrarse</a></li>
                            <li><a class='btn btn-secondary w-100' href='login.php'>Ingresar</a></li>
                        </ul>";
            }
        echo    "
            </div>
        </div>
        ";


        if ($_SESSION["logged"]) {
            $id_user = $_SESSION["user"]->get_id();
            $username = $_SESSION["user"]->username;
            if ($_SESSION["user"]->get_img_path()) {
                $img_path = $_SESSION["user"]->get_img_path();
            } else {
                $img_path = '../public/img/icon/user.png';
            }

            echo "<div class='user-menu me-4 nav-desk'>
                    <div class='user-info' onclick='toggleMenu(event)' id='menu-div'>
                        <img src='$img_path' alt='User Avatar' id='user-avatar' class='avatar d-inline-block' >
                        <p id='my-account-text' class='my-account-text d-inline-block text-white fw-semibold ms-2'>$username</p>
                    </div>

                    <ul id='menu-list' class='me-4'>
                        <li><a class='btn btn-secondary w-100' href='cuenta.php?id_user=$id_user' role='button'>Perfil</a></li>
                        <li><a class='btn btn-secondary w-100' href='home.php?filter=mis_pubs#all_events' role='button'>Mis publicaciones</a></li>
                        <li><a class='btn btn-secondary w-100' href='modificar_cuenta.php' role='button'>Configuración</a></li>";

                        if($_SESSION['user']->get_mod()){

                            echo"
                                <li><a class='btn btn-warning w-100' href='user_list.php' role='button'>Listado de usuarios</a></li>";
                        }
                        
                        echo"
                        <li><a class='btn btn-danger w-100' href='../scripts/validate.php?origin=logout' role='button'>Salir</a></li>

                    </ul>
                </div>";
        } else 
        {
            echo "<ul class='nav-desk me-4'>";
            echo    "<li><a href='registro.php'>Registrarse</a></li>";
            echo    "<li><a href='login.php'>Ingresar</a></li>";
            echo "</ul>";
        }
        ?>
    </nav>
    
    
    <section class='container-md me-auto ms-auto'>
    <?php 

if(isset($_GET['error'])){
    $err = new error_msg($_GET['error']);
    echo $err->html;
}

?>
        <?php
        $pub_username = $pub->get_user()->username;
        $pub_userimg = $pub->get_user()->get_img_path();
        if(!$pub_userimg){
            $pub_userimg = '../public/img/icon/user.png';
        }
        echo "<h1> $pub->title </h1>";
        echo "<div class='d-flex justify-content-between'>";
        echo "<p>En ". $pub->localidad .", el día ".date_format(date_create($pub -> fecha_evento),"d/m/Y").", desde las ".date_format(date_create($pub->inicio),"H:i")." hasta las ".date_format(date_create($pub->fin),"H:i")." </p>";
            
        echo "<p>Por <a href='cuenta.php?id_user=".$pub->get_user()->get_id() ."'> <img src='$pub_userimg' alt='$pub_username' width='32' height='32' class='rounded-5'> ".$pub_username."</a>, a las ".date_format(date_create($pub->fecha_post), "H:i d/m/Y") ."</p>";
        
        echo "</div>";
        
        if($pub->get_img_path() != null){
            echo "<img style='max-width: 100%;' src='". $pub->get_img_path() ."'>";
        }
        
        echo "<p> ".$pub -> desc." </p>";

        if($_SESSION['logged']){

            if($pub->get_user()->get_id() == $_SESSION['user']->get_id()){
                echo "
                <div class='d-flex justify-content-center'>
                <a href='crear_publicacion.php?modify=true&id=$id' class='btn btn-secondary p-2 me-2'>Editar publicación</a>
                <a href='../scripts/validate.php?origin=del_pub&id_pub=$id' class='btn btn-danger p-2'>Borrar publicación</a>
                </div>
                ";
            }
            else if($_SESSION['user']->get_mod()){
                echo "
                <div class='d-flex justify-content-center'>
                    <a href='../scripts/validate.php?origin=del_pub&id_pub=$id' class='btn btn-danger p-2'>Borrar publicación</a>
                </div>
                ";
            }
            
        }

        ?>
        <hr>
        
        <h2>Comentarios</h2>
        <?php
        if($_SESSION["logged"]){
            
            echo "
            <form action='../scripts/validate.php?origin=coment&id=".$pub->get_id()."' method='POST'>          
            Deja un comentario...
            <label for='coment'>
            <textarea name='coment' id='coment' cols='10' rows='5' class='border rounded mr-sm-2 d-block p-2 resize-none w-100'></textarea>
            </label>
            <input type='submit' value='Comentar' class='btn btn-primary mb-2 mt-2 d-block'>
            </form>
            ";
        }
        
        ?>
        
        
        <!-- Prototipo de comentario -->

        <?php
            $coms = $pub->get_coments_from_DB();
            if(empty($coms)){
                if($_SESSION["logged"]){
                    echo 'No hay comentarios ¡Se el primero!'; 
                }
                else{
                    echo 'Aún no hay comentarios para mostrar.';
                }

            }
            else{
                foreach($coms as $com){
                    $coment = new comentario($com);
                    if($coment->author_img){
                        $img_src = $coment->author_img;
                    }
                    else{
                        $img_src ='../public/img/icon/user.png';
                    }
                    echo "
                    <div class='border w-100 p-2 mt-1 mb-2'>
                        <div class='d-flex justify-content-between'>
                            <div>
                                <img src='$img_src' class='d-inline' width='32' height='32'>
                                <a href='cuenta.php?id_user=".$coment->get_id_user()."' class ='d-inline'>". $coment->author_name."</a>
                            </div>
                            <div class='d-flex'>
                                <p class='me-1'>".
                                date_format(date_create($coment->fecha), "H:i d/m/Y")

                                ."</p>";
                    if($_SESSION["logged"]){

                        if($_SESSION["user"]->get_id() == $coment->get_id_user() || $_SESSION['user']->get_mod()){
                            $id_com = $coment->get_id();
                            echo "<a href='../scripts/validate.php?origin=del_com&id_com=$id_com'><img src='./img/icon/trash-bin.png'></a>";
                        }
                    }
                    
                    echo"
                            </div>
                        </div>
                        <hr class='mt-0 mb-0 p-0'>
                    <p class='mt-2 ms-2 mt-1 mb-1 fs-6 fw-light '>". $coment->msg."</p>
                    </div>
                    ";
                }
            }

        ?>
    </section>
    
    <footer id='web-footer'>
        <p>
            <strong>
                EventAr
            </strong>
            <small>
            &#169 2023
            </small>

            <a href="contacto.php" class='text-white'>Contactanos</a>

        </p>
        <div class='bg-secondary-subtle rounded-3 p-1'>
            <a href="https:\\www.facebook.com\eventar" target="_blank"><img src="./img/icon/fb.png" alt="Visita nuestra página en Facebook" width="32" height="32"></a>
            <a href="https:\\www.instagram.com\eventar" target="_blank"><img src="./img/icon/ins.png" alt="Visita nuestra página en Instagram" width="32" height="32"></a>
            <a href="https:\\www.twitter.com\eventar" target="_blank"><img class='mega-rounded' src="./img/icon/x.png" alt="Visita nuestra página en X" width="32" height="32"></a>
        </div>
        
    </footer>

    
    <script>
        function toggleMenu(event) {
            const id = event.target.id;
            let label;
            if(id == 'menu-div' || id=='my-account-text' || id == 'user-avatar'){
                label = 'menu-list';
            }
            else if( id == 'menu-img'){
                label = 'nav-responsive-lists';
            }
            let menuList = document.getElementById(label);
            if (menuList.style.display === 'none') {
                menuList.style.display = 'block';
            } else {
                menuList.style.display = 'none';
            }
        }

    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
