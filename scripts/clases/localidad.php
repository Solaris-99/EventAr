<?php
    require_once '../scripts/bd_con.php';
    
    class localidad{
        private $id;
        public $nombre;

        function __construct($id){
            $this->id = $id;
            global $bd;
            $res = $bd->query("SELECT nombre from localidad WHERE id_localidad = $id");
            $res = $res->fetch_assoc();
            $this->nombre = $res["nombre"];
        }

        public static function get_nombre($id){
            global $bd;
            $res = $bd->query("SELECT nombre from localidad WHERE id_localidad = $id");
            $res = $res->fetch_assoc();
            return $res["nombre"];
        }

    }

?>