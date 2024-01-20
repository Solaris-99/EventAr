<?php
    require_once '../scripts/bd_con.php';
    require_once '../scripts/clases/user.php';
    require_once '../scripts/clases/uri.php';
    session_start();
    if (!array_key_exists("logged", $_SESSION)) {
        $_SESSION["logged"] = FALSE;
        header("location: home.php");
        exit();
    }
    else if(!$_SESSION['user']->get_mod()){
        header("location: home.php");
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
    <link rel="stylesheet" href="../css/styles.css?v=<?php echo time(); ?>">
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

    <section class='ms-auto me-auto w-75 border border-subtle rounded p-4'>
        <h1 class='text-center'>Listado de usuarios</h1>
        <p>Listado de todos los usuarios en la página. Los usuarios en <span class='text-danger'>rojo</span> son moderadores.</p>
        <?php 
            if(isset($_GET['edit'])){
                echo "<p class='bg-black text-warning rounded p-2 fw-semibold'>AVISO: Borrar una cuenta desde este panel es irreversible y no hay una confirmación.</p>";
            }
        ?>

        <form class="border rounded p-2 bg-white" method='GET' action='user_list.php'>
            <div class='d-flex justify-content-between'>
                <input type="search" name='search_user'  placeholder="Buscar usuarios" class='border-0 outline-0'>
                <input type="submit" class='btn btn-primary' value='Buscar'>
            </div>
            <div class='text-start'>
                <label for="edit">
                    <input type="checkbox" name='edit' class='d-inline w-mx-content'>
                    <span>Habilitar edición</span>
                    
                </label>
            </div>
        </form>
        <?php 
            if(isset($_GET['search_user']) && !empty($_GET['search_user'])){
                $token = $_GET['search_user'];
                $query = "SELECT username, email, id_user, moderador FROM user WHERE username LIKE '%$token%' OR email LIKE '%$token%'";
            }
            else{
                $query = "SELECT username, email, id_user, moderador FROM user;";
            }

            $res = $bd->query($query);
            $users = $res -> fetch_all(MYSQLI_ASSOC);
            $page;
            if (isset($_GET['page_number'])) {
                $page = $_GET["page_number"];
            } else {
                $page = 0;
            }

            $pages = count($users);
            $pages = count($users) / 10;
            $pages = ceil($pages);
            $users = array_slice($users, $page * 10, 10);
            if (($page - 2) < 0) {
                $min_page = 0;
            } else {
                $min_page = $page - 2;
            }

            if (($page + 2) > $pages) {
                $max_page = $pages;
            } else {
                $max_page = $page + 2;
            }

            foreach($users as $user){
                $us_id = $user["id_user"];
                $us_name = $user["username"];
                $us_em = $user["email"];
                echo "
                    <div class='border mt-1 mb-1 p-2 d-flex justify-content between'>
                        
                        <div class='w-100'>
                        <a href='cuenta.php?id_user=$us_id' class='w-100 d-block ";
                        if($user["moderador"]){
                            echo "text-danger";
                        }

                        echo " '>$us_name</a>
                        <small class='d-block m-0'>
                            $us_em
                        </small>
                        </div>";

                        if(isset($_GET['edit']) && $us_id != $_SESSION['user']->get_id()){
                            echo "
                            <div class='d-flex'>
                                <a href='../scripts/validate.php?origin=del_account_mod&id_acc=$us_id&page=user_list.php'><img src='../public/img/icon/trash-bin.png' width='32' height='32' alt='Borrar cuenta'></a>
                                
                                ";
                            if(!$user["moderador"]){
                                echo
                                "<a href='../scripts/validate.php?origin=make_mod&id_acc=$us_id&page=user_list.php'><img src='../public/img/icon/arrow-yl.png' width='32' height='32' alt='Ascender a moderador'></a>
                               ";
                            
                            }else{
                            echo
                                "
                                <a href='../scripts/validate.php?origin=unmake_mod&id_acc=$us_id&page=user_list.php'><img src='../public/img/icon/arrow-yl.png' style='transform: rotate(180deg);' width='32' height='32' alt='Ascender a moderador'></a>";
                                
                            } 

                                echo"

                            </div>";
                        }
                    echo "
                    </div>
                ";
            }
            echo "<p class='text-center fs-4 fw-semibold'>";
            $sv_uri = $_SERVER['REQUEST_URI'];
            for ($p = 0; $p < $pages; $p++) {
                $uri = new uri($sv_uri);
                $page_uri = $uri->updatePar('page_number', $p);

                if ($p == 0 || (($min_page <= $p) && ($max_page >= $p)) || ($p + 1) == $pages) {
                    echo "<a href='$page_uri' class='text-decoration-none me-4 d-inline-block' >";
                    echo ($p + 1) . "";
                    echo "</a>";
                }
            }
            echo "</p>";
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