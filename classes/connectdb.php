<?php
class Connection
{
    protected $auth;
    protected static $_instance;
    protected $link;

    private function __construct() {
        $this->Open();
    }

    function Open()
    {
        $this->auth["hostname"]='localhost';
        $this->auth["database"]='cats_db';
        $this->auth["login"]='root';
        $this->auth["pwd"]='root';
        $error=0;
        $this->link = mysqli_connect($this->auth["hostname"],$this->auth["login"],$this->auth["pwd"]);
        if(!$this->link)
        {
            echo "can't connetc to host ".$this->auth." ".$this->hostname."!<br>";
            echo mysqli_error($this->link);
            die();
        }
        
        if(!mysqli_select_db($this->link, $this->auth["database"]))
            $error=1;
            
            if($error==1)
            {
                print mysqli_error($this->link);
                die();
            }
            mysqli_query ($this->link, "SET SQL_BIG_SELECTS=1");
            mysqli_query ($this->link, "SET names utf8mb4");
    }
    

    static function GetResultInArray($q, $ident="")
    {
        $res = Connection::ExexQuery($q,$ident);
        $data = array();
        
        for($i=0;$i<$res->num_rows;$i++)
        {
            $data[] = mysqli_fetch_array($res);
        }
        return $data;
    }

    function GetObjects($q, $className){
        $res = $this->Query($q);
        $data = array();
        for($i=0;$i<mysqli_num_rows($res);$i++)
            $data[] = mysqli_fetch_object($res, $className);
            return $data;
    }

    function GetInsertedId(){
        return  mysqli_insert_id($this->link);
    }
    static function GetLastInsertedId(){
        return Connection::getInstance()->GetInsertedId();
    }
    static function GetAllResultInObjects($q, $className){
        return Connection::getInstance()->GetObjects($q, $className);
    }


    function Close()
    {
        mysqli_close($this->link);
    }
    
    public static function getInstance() {
        if (self::$_instance === null) {self::$_instance = new self;}
        return self::$_instance;
    }
    static function ExecS($query)
    {
        Connection::getInstance()->Query($query);
        return true;
    }

    static function ExexQuery($query, $ident="")
    {
        return Connection::getInstance()->Query($query);
        
    }
    function Query($query){ 
        try{

                $result = mysqli_query($this->link, $query);
                if (mysqli_errno($this->link)!=0)
                {
                    $errorStr = mysqli_error($this->link);
                }
                
                if (isset($_GET["showallquery"]) && $_GET["showallquery"]==1)
                {
                    $time = microtime(true) - $start;
                    printf('<table class="table table-striped"><tr><td  onclick="$(this).find(\'div\').attr(\'style\',\'\');"><div style="width:800px;overflow:hidden;white-space:nowrap;text-overflow: ellipsis">%s<div></td><td>Запрос выполнился за выполнялся %.4F сек.</td></tr></table>', $query, $time);
                    
                }
                return $result;
    }
    catch (Exception $ex)
    {
        throw new Exception($ex->getMessage(), mysqli_errno($this->link));
        
    }
}


}

?>