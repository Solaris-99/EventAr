<?php
class uri
{
    private $get;
    private $file;
    public $uri;
    public $parameters;

    function __construct($uri)
    {
        $this->uri = $uri;
        $this->get = str_contains($uri, "?");
        if ($this->get) {
            $uri_arr = explode("?", $uri);
            $this->file = $uri_arr[0];
            $parameters = explode("&", $uri_arr[1]);
            foreach ($parameters as $par) {
                $split = explode("=", $par);
                $this->parameters[$split[0]] = $split[1];
            }
        }
    }

    public function updatePar($par, $val)
    {

        if (isset($this->parameters[$par])) {
            $new_uri =  $this->file . "?";
            $this->parameters[$par] = $val;
            $keys = array_keys($this->parameters);
            foreach ($keys as $k) {
                $par = $k . '=' . $this->parameters[$k];
                $new_uri = $new_uri . $par . "&";
            }
            $new_uri = substr($new_uri, 0, -1);
            $this->uri = $new_uri;
            return $this->uri;
        } else {
            $this->appendPar($par, $val);
            return $this->uri;
        }
    }

    private function appendPar($par, $val)
    {
        if ($this->get) {
            $this->uri = $this->uri . "&$par=$val";
        } else {
            $this->uri = $this->uri . "?$par=$val";
            $this->get = TRUE;
        }
        $this->parameters[$par] = $val;
    }
}
