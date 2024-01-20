<?php
require_once '../scripts/bd_con.php';
require_once '../scripts/clases/user.php';
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
    


    <section class='ms-auto me-auto container-md'>
        <h1>
            Acerca de Nosotros
        </h1>
        <p>
            ¡Bienvenido! ¡EventAr es tu destino en línea para descubrir y compartir eventos locales emocionantes en tu ciudad! Estamos dedicados a conectar a nuestra comunidad con lo que está sucediendo en la ciudad y brindarte una plataforma donde puedas dar a conocer tus eventos y experiencias locales.
        </p>
        <h2>
            ¿Qué Hacemos?
        </h2>
        <p>
            Nosotros creemos en la importancia de mantener viva la vibrante escena de eventos locales. Nuestra misión es simple: facilitar la exploración y promoción de eventos locales para que tú y otros miembros de la comunidad puedan descubrir y participar en experiencias únicas.
        </p>
        <h2>
            Características Destacadas:
        </h2>
        <p>
            Publica tus Eventos: ¿Tienes un evento especial que deseas compartir? Aquí puedes publicar tus eventos de manera sencilla y llegar a una audiencia local apasionada. Desde conciertos y ferias hasta talleres y festivales, tu evento encontrará su lugar en nuestra plataforma.
            Descubre Eventos Locales: Explora una amplia gama de eventos locales que tienen lugar en tu ciudad. Ya sea que estés buscando diversión familiar, entretenimiento nocturno o actividades culturales, tenemos algo para todos los gustos.
            Conéctate con la Comunidad: Únete a una comunidad de amantes de los eventos locales. Interactúa con otros usuarios, comparte tus experiencias y obtén recomendaciones para eventos que no te querrás perder.
        </p>
        <h2>
            ¿Cómo Empezar?
        </h2>
        <p>
            Puedes registrarte fácilmente y comenzar a ser parte de la escena de eventos locales. Regístrate, publica tus eventos o descubre lo que está sucediendo en tu área. ¡La diversión está a solo un clic de distancia!
        </p>
        <h2>
            Normas de Participación en EventAr

        </h2>
        <p>


            Para mantener un ambiente positivo y enriquecedor, te pedimos que sigas estas reglas al participar en nuestro foro:
        </p>
        <ul>
            <li>
                <strong>
                    Respeto Mutuo:
                </strong>
                Trata a todos los miembros con respeto y cortesía. No toleramos insultos, ataques personales o cualquier forma de discriminación.
            </li>
            <li>
                <strong>
                    Contenido Relevante:
                </strong>
                Publica contenido relacionado con eventos en Argentina. Evita desviar las conversaciones hacia temas no relacionados.
            </li>
            <li>
                <strong>
                    No al Spam:

                </strong>
                No hagas spam en el foro. Esto incluye la promoción no solicitada de productos, servicios u otros sitios web.
            </li>
            <li>
                <strong>
                    Lenguaje Apropiado:

                </strong>
                Utiliza un lenguaje respetuoso y apropiado. Evita el uso excesivo de mayúsculas, lenguaje ofensivo o contenido inapropiado.
            </li>
            <li>
                <strong>
                    Siempre seguros:

                </strong>
                No publiques eventos que puedan poner en peligro la integridad física, mental o emocional de sus participantes.
            </li>
            <li>
                <strong>
                    Veracidad de la Información:

                </strong>
                Proporciona información precisa y verificable en tus publicaciones. No difundas información falsa o engañosa.
            </li>
            <li>
                <strong>
                    Privacidad y Seguridad:
                </strong>
                Respeta la privacidad de los demás. No publiques información personal sin consentimiento y evita compartir contenido que pueda comprometer la seguridad de otros usuarios.

            </li>
            <li>
                <strong>
                    Moderación y Cumplimiento:
                </strong>
                Respeta las decisiones de los moderadores. Si se te pide que detengas cierto comportamiento, por favor, coopera para mantener un ambiente armonioso.

            </li>
            <li>
                <strong>
                    Publicaciones Relevantes:
                </strong>
                Antes de publicar, asegúrate de que tu contenido sea relevante y útil para la comunidad. Evita publicaciones repetitivas o irrelevantes.

            </li>
            <li>
                <strong>
                    Notificación de Problemas:
                </strong>
                Si encuentras contenido inapropiado o violaciones a las reglas, por favor, notifícalo a los moderadores para que puedan tomar las medidas necesarias.
            </li>

        </ul>
        <p>
            Recuerda que el objetivo principal de nuestra comunidad es disfrutar y compartir experiencias relacionadas con eventos en Argentina. Al seguir estas reglas, contribuyes a un espacio amigable y enriquecedor para todos. ¡Gracias por ser parte de [Nombre de tu Página]!
        </p>

        <h2>
            Contáctanos
        </h2>
        <p>
            Nos encantaría escucharte. Si tienes alguna pregunta, comentario o sugerencia, no dudes en ponerte en <a href="contacto.php">contacto con nosotros</a>.
            Gracias por ser parte de este sitio, y esperamos que disfrutes explorando, compartiendo y viviendo los mejores eventos locales en tu region.
        </p>


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
            if (id == 'menu-div' || id == 'my-account-text' || id == 'user-avatar') {
                label = 'menu-list';
            } else if (id == 'menu-img') {
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