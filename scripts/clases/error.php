<?php 

    class error_msg{
        private $code;
        private $msg = '';
        public $html = '';

        function __construct($code){
            $this->code = $code;
            $this->setMsg();
            $this->constructHTML();
        }

        private function constructHTML(){
            $msg = $this->msg;
            $this->html = "<div class='w-100 bg-danger border border-danger rounded p-2 text-center text-white fw-bold'><p>$msg</p></div>";
        }

        private function setMsg(){
            switch($this->code){
                case 1:
                    $this->msg = 'Email invalido.';
                    break;
                case 2:
                    $this->msg = 'Email o Password invalidos.';
                    break;
                case 3:
                    $this->msg = 'Parece que ha ocurrido un error con la localidad que introduciste.';
                    break;
                case 4:
                    $this->msg = 'Faltan datos obligatorios.';
                    break;
                case 5:
                    $this->msg = 'No tienes permisos para realizar esa acción.';
                    break;
                case 6:
                    $this->msg = 'Este mail ya está en uso.';
                    break;
                case 7:
                    $this->msg = 'La contraseña es demasiado corta';
                    break;
                case 8:
                    $this->msg = 'Contraseña incorrecta';
                    break;
                case 9:
                    $this->msg = 'Ocurrió un error subiendo la imagen. Por favor intentalo de nuevo.';
                    break;
                case 10:
                    $this->msg = 'Chequeá que la imagen sea png o jpg, y que no pese más de 2 MB.';
                    break;
                case 11:
                    $this->msg = 'Parece que ocurrio un error interno. Por favor, vuelve a intentarlo más tarde';
                    break;
            }
        }

    }

?>