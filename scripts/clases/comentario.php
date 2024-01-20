<?php 
    require_once '../scripts/bd_con.php';

    class comentario{
        private $id;
        private $id_user;
        private $id_pub;
        public $msg;
        public $author_name;
        public $author_img;
        public $fecha;

        function __construct($arr){
            $this->id = $arr['id_comentario'];
            $this->id_user = $arr['id_user'];
            $this->id_pub = $arr['id_publicacion'];
            $this->msg = $arr['mensaje'];
            $this->fecha = $arr['fecha_post'];
            global $bd;
            $res = $bd-> query("SELECT username, img_path FROM user WHERE id_user = ".$this->id_user);
            $res = $res->fetch_assoc();
            $this->author_name = $res["username"];
            $this->author_img = $res["img_path"];
        }
        
        public function get_id(){
            return $this->id;
        }

        public function get_id_user(){
            return $this->id_user;
        }

        public function get_id_pub(){
            return $this->id_pub;
        }



    }

?>