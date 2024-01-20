<?php 

    require_once 'bd_con.php';
    require_once './clases/autenticate.php';
    require_once './clases/user.php';


    function sanitize($str,$sql_scape = TRUE,$trim = TRUE, $stripslash = TRUE, $htmlspecial = FALSE,){
        $san = $str;
        if($sql_scape){
            global $bd;
            $san = $bd -> real_escape_string($san);
        }
        if($trim){
            $san = trim($san);
        }
        if($stripslash){
            $san = stripslashes($san);
        }
        if($htmlspecial){
            $san = htmlspecialchars($san);
        }
        return $san;
    }

    function validateImage($img){
        $fileExtension = strtolower(pathinfo($img["name"], PATHINFO_EXTENSION));
        $allowedExtensions = array("jpg", "jpeg", "png");
        if($img["size"] <= 2097152 && in_array($fileExtension, $allowedExtensions)){
            return $fileExtension;
        }
        else{
            return FALSE;
        }
    }

    $page = 'home.php';
    $auth = new autenticate();
    $origin = $_GET['origin'];

    if ($origin == 'login'|| $origin == 'registro'){

        if(isset($_POST['password']) && isset($_POST['email'])){
            if(strlen($_POST['password']) < 6){
                if($origin == 'registro'){
                    $page = 'login.php?error=7';
                    header('location: ../public/'.$page);
                    exit();
                }
            }
            $pass = sanitize($_POST['password']);
            $email = $_POST['email'];
            if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                $email = sanitize($email);
            }
            else{
                //error validando email
                if($origin == 'login'){
                    $page = 'login.php?error=1';
                }
                else{
                    $page = 'registro.php?error=1';
                }
                header('location: ../public/'.$page);
                exit();
            }
        }
        else{
            //error en email o password
            if($origin == 'login'){
                $page = 'login.php?error=2';
            }
            else{
                $page = 'registro.php?error=2';
            }
            header('location: ../public/'.$page);
            exit();
        }
        

    }
    else if ($origin == 'crear_pub' || $origin == 'modificar_pub'){

        if(isset($_POST["title"]) && isset($_POST["desc"]) && isset($_POST["inicio"]) && isset($_POST["fin"]) && isset($_POST["fecha_evento"]) && isset($_POST['id_localidad'])) {
            $title = sanitize($_POST['title'],TRUE,FALSE,FALSE,TRUE);
            $desc = sanitize($_POST['desc'],TRUE,FALSE,FALSE,TRUE);
            $inicio = sanitize($_POST['inicio']);
            $fin = sanitize($_POST['fin']);
            $fecha_evento = sanitize($_POST['fecha_evento']);
            if(filter_var($_POST['id_localidad'], FILTER_VALIDATE_INT)){
                $id_localidad = $_POST['id_localidad'];
            }
            else{
                if($origin == 'crear_pub'){
                    $page = 'crear_publicacion.php?error=3';
                    header('location: ../public/'.$page);
                    exit();
                }
            }

            if($_FILES['event_img']['error'] !== UPLOAD_ERR_NO_FILE){
                $img_ext = validateImage($_FILES['event_img']);
            }
        } else {
            $page = 'crear_publicacion.php?error=4';
            header('location: ../public/'.$page);
            exit();
        }        


    }

    if($origin == 'login'){
        $auth -> login($email,$pass);
        $_SESSION["logged"] = TRUE;
    }
    else if ($origin == 'registro'){
        if(isset($_POST['username']) &&  isset($_POST['id_localidad'])){
            $username = sanitize($_POST['username']);
            if(filter_var($_POST['id_localidad'], FILTER_VALIDATE_INT)){
                $id_localidad = $_POST['id_localidad'];
            }
            else{

                $page = 'login.php?error=3'; //localidad mala
                header('location: ../public/'.$page);
                exit();

            }
        }
        else{

            $page = 'login.php?error=4'; //falta dato
            header('location: ../public/'.$page);
            exit();

        }
        $auth -> register($email,$username,$pass,$id_localidad);
        $_SESSION["logged"] = TRUE;

    }
    else if ($origin == 'logout'){
        $auth -> logout();
        $_SESSION["logged"] = FALSE;
    }
    else if ($origin == 'crear_pub'){
        session_start();

        $fecha_post = new DateTime('now', new DateTimeZone('America/Argentina/Buenos_Aires'));
        $fecha_post = $fecha_post->format('Y-m-d H:i:s');
        $id_user = $_SESSION['user']->get_id();



        if($_FILES['event_img']['error'] !== UPLOAD_ERR_NO_FILE){

            $pub_id = $bd-> query("SELECT MAX(id_publicacion) as id FROM publicacion");
            $pub_id = $pub_id->fetch_assoc();
            $pub_id = $pub_id["id"] + 1;

            $img = $_FILES['event_img'];

            if($img['error'] === UPLOAD_ERR_OK){
                $img_name = '../public/img/publicacion/'. $pub_id.".$img_ext";
                if(move_uploaded_file($img['tmp_name'],$img_name)){
                    //upload to db
                    $bd->query("INSERT INTO publicacion(title, publicacion.desc, id_localidad, inicio, fin, fecha_evento, img_path, fecha_post, id_user) VALUES('$title', '$desc', $id_localidad, '$inicio', '$fin', '$fecha_evento', '$img_name', '$fecha_post', $id_user)");
                    
                }
           }
            else{

            $page = "home.php?error=9";
            header('location: ../public/'.$page);
            exit();

            }
        }
        else{
            $bd->query("INSERT INTO publicacion(title, publicacion.desc, id_localidad, inicio, fin, fecha_evento, img_path, fecha_post, id_user) VALUES('$title', '$desc', $id_localidad, '$inicio', '$fin', '$fecha_evento', null, '$fecha_post', $id_user)");
        }
    }
    else if ($origin == 'modificar_pub'){
        session_start();


        $id = $_GET['id'];
        $res_id_user = $bd -> query("SELECT id_user FROM publicacion WHERE id_publicacion =$id");
        $res_id_user = $res_id_user->fetch_assoc();
        $res_id_user = $res_id_user['id_user'];
        $id_user = $_SESSION['user']->get_id();
        if($res_id_user != $id_user){
            $page = "home.php?error=5";
            header('location: ../public/'.$page);
            exit();
        }


        if($_FILES['event_img']['error'] !== UPLOAD_ERR_NO_FILE){

            $img = $_FILES['event_img'];

            if($img['error'] === UPLOAD_ERR_OK){
                $img_name = '../public/img/publicacion/'. $id .".$img_ext";
                if(move_uploaded_file($img['tmp_name'],$img_name)){
                    $bd->query("UPDATE publicacion SET title='$title', publicacion.desc='$desc', id_localidad=$id_localidad, inicio='$inicio', fin='$fin', fecha_evento='$fecha_evento', img_path='$img_name' WHERE id_publicacion = $id");
                    
                }
           }
            else{

                $page = "publicacion.php?error=9&modify=true&id=$id";
                header('location: ../public/'.$page);
                exit();

            }
        }
        else{
            $bd->query("UPDATE publicacion SET title='$title', publicacion.desc='$desc', id_localidad=$id_localidad, inicio='$inicio', fin='$fin', fecha_evento='$fecha_evento' WHERE id_publicacion = $id");
        }
    }
    else if ($origin == 'del_pub'){
        session_start();
        $id_user = $_SESSION["user"]->get_id();
        $id_pub = $_GET['id_pub'];
        $res = $bd -> query("SELECT id_user FROM publicacion WHERE id_publicacion = $id_pub;");
        $res = $res -> fetch_assoc();
        if ($id_user == $res['id_user'] || $_SESSION['user']->get_mod()){
            $bd -> query("DELETE FROM publicacion WHERE id_publicacion = $id_pub;");
        }
        else {
            $page = 'home.php?error=5';
            header('location: ../public/'.$page);
            exit();
        }
    }
    else if ($origin == 'coment'){
        session_start();

        $pub_id = $_GET['id'];
        $id_user = $_SESSION["user"]->get_id();
        if(isset($_POST['coment'])){
            $msg = sanitize($_POST['coment'],TRUE,FALSE,FALSE,TRUE);
        }
        else{
            $page = "publicacion.php?error=4&id=$pub_id";
            header('location: ../public/'.$page);
            exit();
        }

        $fecha_post = new DateTime('now',new DateTimeZone('America/Argentina/Buenos_Aires'));
        $fecha_post = $fecha_post->format('Y-m-d H:i:s');
        global $bd;
        $bd -> query("INSERT INTO comentario(id_publicacion, id_user, mensaje, fecha_post) VALUES($pub_id, $id_user, '$msg', '$fecha_post')");
        $page = "publicacion.php?id=$pub_id";
        
    }
    else if($origin == 'mod_cuenta'){
        session_start();
        $id_user = $_SESSION["user"]->get_id();
        $qname = '';
        $qlocalidad ='';
        $qpass = '';
        $qemail = '';
        $qimg = '';

        if(isset($_POST['username'])){
            $username = sanitize($_POST['username']);
            $qname = "username = '$username'";
        }

        if(isset($_POST['email'])){
            if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
                $email = sanitize($_POST['email']); //curar email filter var validate email
                $qemail = "email = '$email'";
            }
            else{
                $page = 'modificar_cuenta.php?error=1';
                header('location: ../public/'.$page);
                exit();
            }
        }
        
        if(isset($_POST['localidad'])){
            if (filter_var($_POST['localidad'],FILTER_VALIDATE_INT)){
                $localidad = $_POST['localidad']; // validate int
                $qlocalidad = "id_localidad = '$localidad'";
            }
            else{
                $page = 'modificar_cuenta.php?error=3';
                header('location: ../public/'.$page);
                exit();
            }
        }


        if(isset($_POST['password']) && $_POST['password-new']){
            
            $curr_pass = $_SESSION['user']->get_pass();
            $pass = sanitize($_POST['password']);
            $new_pass = sanitize($_POST['password-new']);

            
            if($pass == $curr_pass){
                if(strlen($new_pass)>=6){
                    $qpass = "pass = '$new_pass'";
                }
                else{
                    $page = 'modificar_cuenta.php?error=7';
                    header('location: ../public/'.$page);
                    exit();
                }

            }//devolver un error si falla
            else{
                $page = 'modificar_cuenta.php?error=8';
                header('location: ../public/'.$page);
                exit();
            }
        }
        if(isset($_FILES['pf_img'])){
            if($_FILES['pf_img']['error'] == 1){
                $page = 'modificar_cuenta.php?error=10';
                header('location: ../public/'.$page);
                exit();
            }
            if($_FILES['pf_img']['error'] === UPLOAD_ERR_OK){
                $ext = validateImage($_FILES['pf_img']);

                if($ext){
                    
                    $img =  $_FILES['pf_img'];
                    $img_name = '../public/img/user/'.$id_user.'.'. $ext;
                    if(move_uploaded_file($img['tmp_name'],$img_name)){
                        $qimg = "img_path = '$img_name'";
                    }//devolver un error si falla
                    else{
                        $page = 'modificar_cuenta.php?error=9';
                        header('location: ../public/'.$page);
                        exit();
                    }
                }
                else{
                    $page = 'modificar_cuenta.php?error=10';
                    header('location: ../public/'.$page);
                    exit();
                }
           }
        }
        
        $fcols = array_filter([$qname, $qemail, $qlocalidad,$qpass,$qimg]);
        $cols = implode(', ', $fcols);
        if($cols){
            $query = "UPDATE user SET $cols WHERE id_user = $id_user";
            $bd -> query($query);
            $us_data = user::get_from_DB($id_user);
            $_SESSION['user'] = $us_data;
            $page = "cuenta.php?id_user=$id_user";             
        }
    }
    else if ($origin == 'del_account'){
        session_start();
        $id_user = $_SESSION["user"]->get_id();
        $bd -> query("DELETE FROM user WHERE id_user = $id_user");
        session_destroy();
    }
    else if($origin == 'contact'){
        if(isset($_POST['motive']) && isset($_POST['ebody']) && isset($_POST['email']) ){
            $motivo = sanitize($_POST['motive'],FALSE,FALSE);
            $msg = sanitize($_POST['ebody'],FALSE, FALSE);
            $email = sanitize($_POST['email'],FALSE);
            mail('emanuel.cifuentes@davinci.edu.ar',"Foro Eventos: $motivo",$msg,"From: $email"); //hay que configurar mailserver
        }
        else{
            $page = 'contacto.php?error=4';
            header('location: ../public/'.$page);
            exit();
        }
    }
    else if($origin == 'del_com'){

         if(isset($_GET['id_com'])){
            session_start();
            $id_com = $_GET['id_com'];
            $res = $bd->query("SELECT id_user, id_publicacion FROM  comentario WHERE id_comentario = $id_com");
            $res = $res->fetch_assoc();
            $id_user_com = $res['id_user'];
            $id_user = $_SESSION['user']->get_id();
            $id_publi = $res['id_publicacion'];
            $page = "publicacion.php?id=$id_publi";

            if($id_user_com == $id_user || $_SESSION['user']->get_mod()){
                $bd->query("DELETE FROM comentario WHERE id_comentario = $id_com");
            }
            else{
                $page = $page . '&error=5';
                header('location: ../public/'.$page);
                exit();
            }

         }
         else{
            $page = $page . '&error=11';
                header('location: ../public/'.$page);
                exit();
         }
    }
    else if ($origin == 'del_account_mod'){
        session_start();
        if( isset($_GET['id_acc']) && $_SESSION['user']->get_mod() ){
            $id_acc = $_GET['id_acc'];
            $bd->query("DELETE FROM user WHERE id_user = $id_acc");
        }
        else{
            $page = $page . '&error=5';
            header('location: ../public/'.$page);
            exit();
        }
        if(isset($_GET['page'])){
            $page = $_GET['page'] . '?edit=on';
        }

    }
    else if($origin == 'make_mod'){
        session_start();
        if( isset($_GET['id_acc']) && $_SESSION['user']->get_mod() ){
            $id_acc = $_GET['id_acc'];
            $bd->query("UPDATE user SET moderador=1 WHERE id_user = $id_acc");
        }
        else{
            $page = $page . '&error=5';
            header('location: ../public/'.$page);
            exit();
        }

        if(isset($_GET['page'])){
            $page = $_GET['page'] . '?edit=on';
        }

    }
    else if($origin == 'unmake_mod'){
        session_start();
        if( isset($_GET['id_acc']) && $_SESSION['user']->get_mod() ){
            $id_acc = $_GET['id_acc'];
            $bd->query("UPDATE user SET moderador=0 WHERE id_user = $id_acc");
        }
        else{
            $page = $page . '&error=5';
            header('location: ../public/'.$page);
            exit();
        }
        if(isset($_GET['page'])){
            $page = $_GET['page'] . '?edit=on';
        }
    }

    header('location: ../public/'.$page);
