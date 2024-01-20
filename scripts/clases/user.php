<?php 
    require_once 'localidad.php';

    class user{
        private $id;
        public $username;
        private $pass;
        private $id_localidad;
        public $localidad;
        public $email;
        private $img_path = null;
        private $mod = FALSE;

        function __construct($arr){
            $this->id = $arr['id_user'];
            $this->username = $arr['username'];
            $this->pass =  $arr['pass'];
            $this->id_localidad = $arr['id_localidad'];
            $this->email =$arr['email'];
            $this-> localidad = localidad::get_nombre($this->id_localidad);
            if(isset($arr['img_path'])){
                $this -> img_path = $arr['img_path'];    
            }

            if (array_key_exists("moderador",$arr)){
                $this->mod = $arr['moderador'];
            }
        }

        public static function get_from_DB($id){
            global $bd;
            $res = $bd->query("SELECT * FROM user WHERE id_user = $id");
            $res = $res->fetch_assoc();
            if(!$res){
                return FALSE;
            }
            return new user($res);
        }

        public function get_pass(){
            return $this->pass;
        }
        
        public function get_img_path(){
            return $this-> img_path;
        }

        public function get_id(){
            return $this -> id;
        }

        public function get_mod(){
            return $this->mod;
        }


    }

?>