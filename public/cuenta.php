<?php
    require_once '../scripts/bd_con.php';
    require_once '../scripts/clases/user.php';
    require_once '../scripts/clases/publicacion.php';
    require '../scripts/clases/error.php';
    session_start();
    if(isset($_GET['id_user'])){
        $id_user_acc = $_GET['id_user'];
        $user = user::get_from_DB($id_user_acc);
    }
    else{
        $id_user_acc = null;
    }

    if(!$id_user_acc || !$user){
        header('location: 404.php');
        exit();
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
    
    <section class='ms-auto me-auto container-sm border border-light-subtle rounded p-4 shadow'>
    <?php 

if(isset($_GET['error'])){
    $err = new error_msg($_GET['error']);
    echo $err->html;
}

?>
        <?php 
            if($id_user_acc){
                $img_src = $user->get_img_path();
                if(!$img_src){
                    $img_src = './img/icon/user.png';
                }
                $owner = FALSE;
                if(isset($_SESSION['user'])){
                    $id_usses = $_SESSION['user']->get_id();
                    if($id_user_acc == $id_usses){
                        $owner = TRUE;
                    }
                }



                $name = $user->username;
                $email = $user->email;
                $localidad = $user->localidad;
                
                echo "
                <div class='border border-light-subtle p-4 mb-2' >
                    
                    <div class='d-flex justify-content-evenly container-sm'>
                        <img src='$img_src' alt='Foto de perfil' class='profile-pic'>
                        
                        <div class='m-auto'>
                            <h1 class='d-block'>$name</h1>";
                if($user->get_mod()){
                    echo "<p>Este usuario es un moderador.</p>";
                }            

                echo"
                            <div id='user-data'>
                                <p class='me-2'>$email</p>
                                <p class='me-2'>Localidad: $localidad</p>
                            </div>";
                
                if($owner){
                    echo"
                            <a class='btn btn-primary' href='modificar_cuenta.php'>Editar mis datos</a>
                            <button class='btn btn-danger' type='button' id='del_button' data-bs-toggle='modal' data-bs-target='#confirmDeleteModal'>Borrar cuenta</button>
                    
                    <div class='modal fade' id='confirmDeleteModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                        <div class='modal-dialog' role='document'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='exampleModalLabel'>¿Seguro que quieres borrar tu cuenta?</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <div class='modal-body'>
                                    Esta acción es irreversible. ¿Estás seguro de que deseas borrar tu cuenta?
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                                    <a class='btn btn-danger' href='../scripts/validate.php?origin=del_account'>Borrar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    ";
                }
                else if ($_SESSION['user']->get_mod()){
                    echo "
    
                        <button class='btn btn-danger' type='button' id='del_button' data-bs-toggle='modal' data-bs-target='#confirmDeleteModal'>Borrar esta cuenta</button>
                        ";
                        if(!$user->get_mod()){
                            echo "<a class='btn btn-warning d-inline-block' href='../scripts/validate.php?origin=make_mod&id_acc=$id_user_acc'>Ascender a moderador</a>";
                        }
                        else{
                            echo "<a class='btn btn-warning d-inline-block' href='../scripts/validate.php?origin=unmake_mod&id_acc=$id_user_acc'>Revocar permisos</a>";
                        }

                        echo"
                    <div class='modal fade' id='confirmDeleteModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                        <div class='modal-dialog' role='document'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='exampleModalLabel'>¿Seguro que quieres borrar tu cuenta?</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <div class='modal-body'>
                                    Confirma para borrar esta cuenta. Esta acción es irreversible.
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                                    <a class='btn btn-danger' href='../scripts/validate.php?origin=del_account_mod&id_acc=$id_user_acc'>Borrar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    ";
                }
                
                echo"</div>
                    </div>
                    
                </div>
                ";
                
                $res = $bd -> query("SELECT * FROM publicacion WHERE id_user = $id_user_acc LIMIT 10;");
                $res = $res->fetch_all(MYSQLI_ASSOC);

                echo "
                    <div>
                        <h2>Ultimas publicaciones</h2>";
                        
                foreach($res as $re){
                    $pub_obj = new publicacion($re);
                    $pub_id = $pub_obj->get_id();
                    echo "<div class='border w-100 d-flex justify-content-between p-2 mt-1 mb-1'>";
                    echo "<a href='publicacion.php?id=" . $pub_id . "' class='d-inline-block w-100' >" . $pub_obj->title . "</a>";
                    if ($owner) { //validar mas arriba, junto con el boton de editar datos.
                            echo "<div class='d-flex'>
                                    <a href='../scripts/validate.php?origin=del_pub&id_pub=$pub_id'> <img src='../public/img/icon/trash-bin.png' width='32' height='32' alt='Borrar publicacion'> </a>
                                    <a href='crear_publicacion.php?modify=true&id=$pub_id'><img src='../public/img/icon/modify.png' width='32' height'32'></a>
                                    </div>";
                     }
                    echo "</div>";
                
                echo "<p class='text-center fs-4 fw-semibold'>";
                }
                echo "</div>";
                
                
                }
            else{
                echo "<h1>Ocurrió un error</h1>
                        <p>Parece que ese usuario no existe.</p>";
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
