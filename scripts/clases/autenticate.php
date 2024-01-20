<?php 

    require_once '../scripts/bd_con.php';
    require_once 'user.php';
    
    class autenticate{
        private $user;
        
        private function autenticate($email, $pass){
            $auth = FALSE;
            global $bd;
            $res = $bd -> query("SELECT * FROM user WHERE email = '$email' AND pass = '$pass'");
            if(!empty($res) && $res->num_rows > 0){
                $user_arr = $res -> fetch_assoc();
                $this->user = new user($user_arr);
                $auth = TRUE;
            }
            $res -> close();
            return $auth;
        }

        public function set_user($user){
            $this->user = $user;
        }

        public function get_user (){
            return $this->user;
        }

        public function login($email, $pass){
            if($this->autenticate($email, $pass)){
                session_start();
                $_SESSION['user'] = $this->user;
            }
            else{
                header('location: ../public/login.php?error=2');
                exit();
            }
        }

        public function register($email, $username,$pass,$id_localidad){
            global $bd;
            $res = $bd-> query("SELECT email FROM user WHERE email == '$email'");
            
            if(!empty($res) && $res->num_rows > 0){
                exit('El email ya está registrado');
            }
            $bd -> query("INSERT INTO user(email, username, pass, id_localidad, moderador) VALUES('$email', '$username', '$pass', '$id_localidad', 0)");
            $id_user = $bd -> insert_id;
            $user_arr = array("id_user"=> $id_user, "email" => $email, "username" => $username, "pass" => $pass, "id_localidad" => $id_localidad);
            $this->user = new user($user_arr);
            session_start();
            $_SESSION['user'] = $this->user;
        }

        public function logout(){
            session_start();
            session_destroy();
        }


    }


?>