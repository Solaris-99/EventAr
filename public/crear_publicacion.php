<?php
    require_once '../scripts/bd_con.php';
    require_once '../scripts/clases/user.php';
    require_once '../scripts/clases/publicacion.php';
    require '../scripts/clases/error.php';
    session_start();
    $modify = FALSE;
    if(array_key_exists("logged",$_SESSION)){ 
        if(!$_SESSION["logged"]){
            header('location: home.php');
        }
    }
    else{
        header('location: home.php');
    }  

    if(isset($_GET['modify']) && isset($_GET['id']) ){
        $modify =TRUE;
        $pub = publicacion::get_from_DB($_GET['id']);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        <h1>Crear un evento</h1>
        <form class="d-block w-100" method='POST'  <?php 
        if ($modify){
            $action = "modificar_pub&id=".$_GET['id'];
        }
        else{
            $action = "crear_pub";
        }
        echo "action='../scripts/validate.php?origin=$action'" ?> enctype="multipart/form-data">
                <?php 

if(isset($_GET['error'])){
    $err = new error_msg($_GET['error']);
    echo $err->html;
}

?>
            <label for='title'>
                Titulo
                <input type='text' name='title' class='border rounded mr-sm-2 d-inline p-2' required   <?php if($modify){echo "value='".$pub->title ."'";} ?> >
            </label>
            <div class='row justify-content-between'>
            <label for="id_localidad" class='col-md-3 col-sm-2 form-label'>
                <p class='p-0 m-0' style='height:2em'>
                    Localidad
                </p>
                    <select name='id_localidad' class='form-select'>
                    <?php 
                        $res = $bd -> query("SELECT * FROM localidad");
                        $res = $res->fetch_all(MYSQLI_ASSOC);
                        foreach($res as $re){
                            echo "<option value='".$re['id_localidad']."'>". $re['nombre']."</option>";
                        }
                    ?>
                    </select>
                </label>
                <label for="fecha"  class='col-md-3 col-sm-3 form-label'>
                    <p class='p-0 m-0' style='height:2em'>
                        Fecha del evento
                    </p>
                    <input type="date" name='fecha_evento' id='fecha_evento' class='input-date border rounded mr-sm-2 d-inline p-2' required <?php if($modify){echo "value='".$pub->fecha_evento ."'";} ?>>
                </label>
                <label for="inicio"  class='col-md-3 col-sm-3 form-label'>
                    <p class='p-0 m-0' style='height:2em'>
                        Hora de inicio

                    </p>
                    <input type="time" name='inicio' class='input-time border rounded mr-sm-2 d-inline p-2' required <?php if($modify){echo "value='".$pub->inicio ."'";} ?>>
                </label>
                <label for="fin" class='col-md-3 col-sm-4 form-label'>
                    <p class='p-0 m-0' style='height:2em'>
                        Hora de finalización 
                    </p>
                    <input type="time" class='input-time border rounded mr-sm-2 d-inline p-2' name='fin' required <?php if($modify){echo "value='".$pub->fin ."'";} ?>>
                </label>
            </div>
            <label for="event_img">
                Sube una imagen (jpg, o png, hasta 2 MB)
                <input type="file" name='event_img' id='event_img' accept='.jpg,.png,.jpeg' class='form-control'>
            </label>
            <label for="desc">
                Descripción del evento
                <textarea name="desc" cols="100" rows="10" class='border rounded mr-sm-2 d-block p-2 resize-none w-100'> <?php if($modify){echo $pub->desc;} ?></textarea>
            </label>
            <input class='btn btn-primary d-block me-auto ms-auto' type="submit" value='Publicar'>
        </form>
        
        
    </section>
    
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


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
    <script>
        flatpickr(".input-date",{
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            minDate:"today",
            static:true,
            'locale': 'es'
        })
        flatpickr(".input-time",{
            noCalendar:true,
            enableTime:true,
            time_24hr:true,
            static:true,
            locale:es
        })

    </script>

</body>
</html>
