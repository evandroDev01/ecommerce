<?php

namespace Hcode;

use Rain\Tpl;

class Page{

    private $tpl;
    private $options = [];
    private $defaults = [
        'header' => true,
        'footer' => true,
        'data' => []
    ]; 

    public function __construct($opts = array(), $tpl_dir = "/views/")
    {
        if (session_status() == PHP_SESSION_NONE) { /// inicializando a session 
            session_start();
        }

        $this->defaults["data"]["session"] = $_SESSION;
        $this->options = array_merge($this->defaults,$opts);

        /// configurando o templete ///
        $config = array(
            "tpl_dir"    => $_SERVER["DOCUMENT_ROOT"].$tpl_dir,
            "cache_dir"  => $_SERVER["DOCUMENT_ROOT"]."/views-cache/"
        );
        
        Tpl::configure($config);

        $this->tpl = new Tpl;
        
        $this->setData($this->options["data"]);

        if($this->options['header'] === true)
        {
            $this->tpl->draw("header");   
        }
    }

    private function setData($data = array())
    {
        foreach($data as $key => $values)
        {
            $this->tpl->assign($key,$values);
        }
    }


    public function setTpl($name,$data = array(),$returnHTML = false)
    {
        $this->setData($data);
        
        return $this->tpl->draw($name,$returnHTML);
    }


    public function __destruct()
    {

        if($this->options['footer'] == true)
        {
            $this->tpl->draw("footer");
        }
        
    }

}


?>