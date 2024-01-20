<?php 
    require_once '../scripts/bd_con.php';
    require_once 'user.php';
    require_once 'localidad.php';

    class publicacion{
        private $id;
        public $title;
        public $desc;
        private $id_localidad;
        public $localidad;
        public $fecha_evento;
        public $inicio;
        public $fin;
        public $fecha_post;
        private $img_path;
        private $user;

        function __construct($arr){
            $this->id = $arr['id_publicacion'];
            $this->title = $arr['title'];
            $this->desc = $arr['desc'];
            $this->id_localidad = $arr['id_localidad'];
            $this->fecha_evento = $arr['fecha_evento'];
            $this->inicio = $arr['inicio'];
            $this->fin = $arr['fin'];
            $this->fecha_post = $arr['fecha_post'];
            $this->img_path = $arr['img_path'];
            global $bd;
            $user_res = $bd-> query("SELECT * FROM user WHERE id_user = ".$arr['id_user']);
            $user_arr = $user_res -> fetch_assoc();
            $user = new user($user_arr);
            $this-> localidad = localidad::get_nombre($this->id_localidad);
            $this->user = $user;
        }

        public function get_id(){
            return $this->id;
        }

        public function get_user(){
            return $this->user;
        }
        
        public function get_img_path(){
            return $this->img_path;
        }

        public static function get_from_DB($id){
            global $bd;
            $res = $bd->query("SELECT * FROM publicacion WHERE id_publicacion = $id");
            $res = $res->fetch_assoc();
            return new publicacion($res);
        }

        public function get_coments_from_DB(){
            global $bd;
            $res = $bd->query("SELECT * FROM comentario WHERE id_publicacion = ".$this->id ." ORDER BY fecha_post DESC");
            $coms = $res->fetch_all(MYSQLI_ASSOC);
            return $coms;
        }
    }



?>