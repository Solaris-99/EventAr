<?php 
    require_once '../scripts/bd_con.php';
    require_once '../scripts/clases/user.php';
    require '../scripts/clases/error.php';
    session_start();
    if(array_key_exists("logged",$_SESSION)){ 
        if(!$_SESSION["logged"]){
            header('location: home.php');
        }
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
    
    <section class='ms-auto me-auto w-mx-content border border-light-subtle rounded p-4 shadow'>
        <h1 class='mb-4'>Modificar mis datos</h1>
        <div>
            <form action="../scripts/validate.php?origin=mod_cuenta" method='POST' enctype="multipart/form-data">
            <?php 

if(isset($_GET['error'])){
    $err = new error_msg($_GET['error']);
    echo $err->html;
}

?>
                <label for="username" class='form-label'>
                    Nombre de usuario
                    <input type="text" name='username' class='border rounded mr-sm-2 d-inline p-2' value='<?php echo $_SESSION['user']->username?>'>
                </label>
                <label for="id_localidad" class='form-label'>
                    Localidad
                    <select name='id_localidad' class='form-select'>
                    <?php 
                        $res = $bd -> query("SELECT * FROM localidad");
                        $res = $res->fetch_all(MYSQLI_ASSOC);
                        foreach($res as $re){
                            $id_local = $re['id_localidad'];
                            $nom =  $re['nombre'];
                            if($id_nom == $_SESSION['user']->localidad){
                                echo "<option value='$id_local' selected>$nom</option>";
                            }
                            echo "<option value='$id_local'>$nom</option>";
                        }
                    ?>
                    </select>
                </label>
                
                <label for="pf_img">
                    Cambia tu imagen de perfil (jpg, o png, hasta 2 MB)
                    <input type="file" name='pf_img' id='pf_img' accept='.jpg,.png,.jpeg' class='form-control'>
                </label>

                <button type='button' onclick='expand(event)' id='email_button' class="btn btn-warning d-block me-auto ms-auto mb-3 mt-3">Cambiar mi email</button>
                <label for="email" class='form-label' id='email-label' style='display: none'>
                    E-mail
                    <input type="email" name='email' class='border rounded mr-sm-2 d-inline p-2' value='<?php echo $_SESSION['user']->email?>'>
                </label>

                <button type='button' onclick='expand(event)' id='pass_button' class="btn btn-warning d-block d-block me-auto ms-auto mb-3 mt-3">Cambiar mi contraseña</button>
                    <div id='pass-label' style='display: none'>

                        <label for="password" class='form-label'>
                            Contraseña Actual
                            <input type="password" name='password' class='border rounded mr-sm-2 d-inline p-2'>
                        </label>
                        <label for="password-new" class='form-label'>
                            Contraseña Nueva
                            <input type="password" name='password-new' class='border rounded mr-sm-2 d-inline p-2'>
                        </label>
                    </div>
                        
                
                <input type="submit" value='Actualizar' class='btn btn-primary mt-4 mb-4 d-block'>
                
            </form>
        </div>
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
        function expand(event){
            const id = event.target.id
            let label;
            if(id == 'email_button'){
                label = 'email-label'
            }
            else if (id == 'pass_button'){
                label = 'pass-label'
            }
            display = document.getElementById(label).style.display
            if(display == 'none'){
                document.getElementById(label).style.display = 'block';
            }
            else{
               document.getElementById(label).style.display = 'none';
            }
        }
    </script>
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