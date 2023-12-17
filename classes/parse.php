<?php
class PC
    {
    var $vars     = array();
    var $template;
    var $tplpath = "tpl/";
    var $expludeFromSnippets=array();
    var $tname="";
    function AddExceptSnippet($name){
    	$this->expludeFromSnippets[] = $name;
    }
	function __construct($tpl_name="", $path="tpl/", $change=array()){
	    $this->tname = $tpl_name;
	    if ($path=="") $path= DEFAULT_TPL_PATH;
		if ($tpl_name!="")
			$this->get($tpl_name, $path);
		foreach ($change as $key=>$value)
			$this->set($key, $value);
	}
    function get($tpl_name, $path="tpl/")
      {
      	$this->tplpath = $path;
      if(empty($tpl_name) || !file_exists($this->tplpath.$tpl_name.".htm"))
        {
        	print "нет такого шаблона:".$this->tplpath.$tpl_name.".htm";
        return false;
        }
      else
        {
        $this->template  = file_get_contents($this->tplpath.$tpl_name.".htm");
        }
      }
      function setArray($array){
          $this->vars =  array_merge($array,$this->vars);
      }
    function set($key,$var)
      {
      $this->vars[$key] = $var;
      }

    function parse()
      {
             $this->template = str_replace("[rp]", PC::GetRootPath(), $this->template);
      	
      	foreach($this->vars as $find => $replace)
             {
             $this->template = str_replace($find, $replace, $this->template);
             }
           return $this->template;
      }
    static function GetRootPath(){
		return 'http://localhost/cats/';
	}


    }

?>