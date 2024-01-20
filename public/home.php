<?php
require_once '../scripts/bd_con.php';
require_once '../scripts/clases/user.php';
require_once '../scripts/clases/publicacion.php';
require_once '../scripts/clases/uri.php';
require '../scripts/clases/error.php';
session_start();
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

    <header id='header'>
        <div class='me-auto ms-auto pb-4 pt-4 justify-content-center align-items-center' id='header-comp'>

            <img src="./img/icon/icon-xl.png" class='me-2' alt="Mapa de Argentina" height='400'>

            <div id='header-text'>
                <h2>Los mejores eventos de Argentina</h2>
                <p>
                    ¡Bienvenido a EventAr, el epicentro virtual de los eventos en Argentina! Sumérgete en una comunidad apasionada donde podrás explorar, compartir y estar al tanto de los eventos más emocionantes en todo el país. Desde festivales culturales hasta encuentros deportivos, aquí encontrarás el lugar perfecto para conectar con otros amantes de los eventos y descubrir las experiencias que Argentina tiene para ofrecer. Únete a la conversación, comparte tus eventos favoritos y crea recuerdos inolvidables en cada rincón del país. ¡Tu próxima aventura comienza aquí!
                </p>
                <a href="#all_events" class='btn btn-primary'>Comenzar a navegar</a>
            </div>
            
        </div>

    </header>
    <section class='container-md me-auto ms-auto'>
        <h2 class='mb-2'>Eventos destacados</h2>
        <div class='d-flex justify-content-between mt-3 mb-3 flex-wrap'>

        <?php
            $last_pubs = $bd->query("SELECT * FROM publicacion ORDER BY fecha_post DESC LIMIT 4");
            $last_pubs = $last_pubs->fetch_all(MYSQLI_ASSOC);
        
            foreach($last_pubs as $pub){
                $pub_card = new publicacion($pub);
                if($pub_card->get_img_path() != null){
                    $img = $pub_card->get_img_path();
                }
                else{
                    $img = '../public/img/publicacion/bandera.jpg';
                }
                echo 
                "<div class='card mb-2' style='max-width: 18rem;'>
                <a href='publicacion.php?id=".$pub_card->get_id()."'>
                    <img src='$img' class='card-img-top' alt='".$pub_card->title."'>
                </a>
                <div class='card-body'>
                    <h5 class='card-title'>".$pub_card->title."</h5>
                    <div class='d-flex justify-content-between'>
                        <small class='m-0'>". date_format(date_create($pub_card-> fecha_evento),"d M")." </small>
                        <small class='m-0'>".$pub_card->localidad ." </small>
                    </div>
                        <p class='card-text m-h-48px'>".substr($pub_card->desc,0,50); if(strlen($pub_card->desc)>50){echo "...";}  
                        echo "</p>
                        <a href='publicacion.php?id=".$pub_card->get_id()."' class='btn btn-primary' >Ver más</a>
                    </div>
                </div>";
            }
            ?>

        </div>
        <h2 class='mb-3' id='all_events'>Todos los eventos</h2>
        <?php

        if (isset($_GET['error'])) {
            $err = new error_msg($_GET['error']);
            echo $err->html;
        }

        ?>
        <!-- Eventos recientes? -->

        <?php
        if ($_SESSION["logged"]) {
            echo "<div class='border border-primary-subtle w-100 p-2 mt-1 mb-1'>";
            echo    "<a href='crear_publicacion.php' class='d-inline-block w-100'>+ Nueva publicación</a>";
            echo "</div>";
        }

        $page;
        if (isset($_GET['page_number'])) {
            $page = $_GET["page_number"];
        } else {
            $page = 0;
        }

        if (isset($_GET['filter']) && isset($_GET['search'])) {
            if ($_GET['filter'] == 'mis_pubs') {
                $id_user = $_SESSION["user"]->get_id();
                $search_val = $_GET['search'];
                $query = "SELECT * FROM publicacion WHERE id_user = $id_user AND (publicacion.desc LIKE '%$search_val%' OR publicacion.title LIKE '%$search_val%') ORDER BY fecha_post DESC;";
            }
        } else if (isset($_GET['filter'])) {
            if ($_GET['filter'] == 'mis_pubs') {
                $id_user = $_SESSION["user"]->get_id();
                $query = "SELECT * FROM publicacion WHERE id_user = $id_user ORDER BY fecha_post DESC;";
            }
        } else if (isset($_GET['search'])) {
            $search_val = $_GET['search'];
            $query = "SELECT * FROM publicacion WHERE publicacion.desc LIKE '%$search_val%' OR publicacion.title LIKE '%$search_val%';";
        } else {
            $query = "SELECT * FROM publicacion ORDER BY fecha_post DESC;";
        }



        $res = $bd->query($query);
        if (!$res) {
            echo "<div class='w-100 p-2 mt-1 mb-1'>";
            echo "No hay publicaciones para mostrar";
            echo "</div>";
        } else {

            $pubs = $res->fetch_all(MYSQLI_ASSOC);
            $pages = count($pubs) / 10;
            $pages = ceil($pages);
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

            setlocale(LC_TIME, 'es_AR.utf8', 'es_AR');
            $pubs = array_slice($pubs, $page * 10, 10);
            foreach ($pubs as $pub) {
                $pub_obj = new publicacion($pub);
                $pub_id = $pub_obj->get_id();
                $fecha = date_create($pub_obj->fecha_evento);
                //$dia = date_format($fecha, 'd');
                //$mes = date_format($fecha,"M");
                $dia = IntlDateFormatter::formatObject($fecha,"dd",'es');
                $mes = ucfirst(IntlDateFormatter::formatObject($fecha,"MMM",'es'));
                $lugar = $pub_obj->localidad;
                echo "<div class='border w-100 d-flex justify-content-between p-2 mt-1 mb-1'>";
                echo "<div class='me-3 text-secondary mt-0 mb-0'>
                    <p class='fs-3 m-0'>$dia</p>
                    <p class='m-0'>$mes</p>
                ";
                echo "</div>";
                echo "<div class='d-inline-block w-100'>";
                echo "<a href='publicacion.php?id=" . $pub_id . "' class='d-inline-block w-100 mt-auto mb-auto fs-4' >" . $pub_obj->title . "</a>";
                echo "
                      <p class='m-0 text-secondary'>
                      $lugar
                      </p>
                      </div>";
                
                
                
                if (isset($_SESSION["user"])) {
                    $pub_id_user = $pub_obj->get_user()->get_id();
                    $id_user = $_SESSION["user"]->get_id();
                    if ($pub_id_user == $id_user) {
                        echo "<div class='d-flex'>
                                <a href='../scripts/validate.php?origin=del_pub&id_pub=$pub_id' class='me-2'> <img src='../public/img/icon/trash-bin.png' width='32' height='32' alt='Borrar publicacion'> </a>
                                <a href='crear_publicacion.php?modify=true&id=$pub_id'><img src='../public/img/icon/modify.png' width='32' height='32'></a>
                                </div>";
                    }
                    else if($_SESSION['user']->get_mod()){
                        echo "<div class='d-flex'>
                                <a href='../scripts/validate.php?origin=del_pub&id_pub=$pub_id' class='me-2'> <img src='../public/img/icon/trash-bin.png' width='32' height='32' alt='Borrar publicacion'> </a>
                                </div>";
                    }
                }
                echo "</div>";
            }
            echo "<p class='text-center fs-4 fw-semibold'>";
            $sv_uri = $_SERVER['REQUEST_URI'];



            for ($p = 0; $p < $pages; $p++) {
                $uri = new uri($sv_uri);
                $page_uri = $uri->updatePar('page_number', $p);

                if ($p == 0 || (($min_page <= $p) && ($max_page >= $p)) || ($p + 1) == $pages) {
                    echo "<a href='$page_uri#all_events' class='text-decoration-none me-4 d-inline-block' >";
                    echo ($p + 1) . "";
                    echo "</a>";
                }
            }
            echo "</p>";
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