<?php

class Cat
{
    public $id;
    public $name;
    public $age;
    public $sex;
    public $mother;


    function addCat($data)
    {
      
        $this->name = $data['name'];
        $this->sex = $data['sex'];
        $this->age =$data['age'];
        $this->mother = $data['mother'];
        $q = sprintf("insert into cats (name, age, sex, mother) values('%s', %d, %d, %d)", $this->name, $this->age, $this->sex, $this->mother);
        Connection::ExexQuery($q);
        $this->id = Connection::GetLastInsertedId();

        foreach ($data as $key=>$value)
        {
            if (is_int($key))
            {
                $q = sprintf("insert into fathers_connect (father_id, child_id) values(%d, %d)", $key, $this->id);
                Connection::ExexQuery($q);
            }
        }
    }


    public function __construct()
    {
    
    }

    public function updateFathers($dataf)
    {
        $q = sprintf("delete from fathers_connect where id=%d", $this->id);
        Connection::ExexQuery($q);
        foreach ($dataf as $key=>$value)
        {
            $q = sprintf("insert into fathers_connect (father_id, child_id) values(%d, %d)", $value, $this->id);
            Connection::ExexQuery($q);
        }
    }


    public function save()
    {
        $q = sprintf('update cats set name="%s", age=%d, sex=%d, mother=%d   where id=%d', $this->name, $this->age, $this->sex, $this->mother, $this->id);
        Connection::ExexQuery($q);
    }

    public function delCat()
    {
        $q = sprintf('delete from cats where cats.id=%d', $this->id);
        Connection::ExexQuery($q);

        if ($this->fathers)
        {
            $q = sprintf("delete from fathers_connect where child_id=%d", $this->id);
            Connection::ExexQuery($q);
        }

    }


    public function getFathers()
    {
        $q = sprintf("select father_id from fathers_connect where child_id=%d ", $this->id);
        $result = [];
        foreach (Connection::GetResultInArray($q) as $value)
        {
            $result[] = $value['father_id'];
        }
        return $result;
    }

    public function getMother()
    {
        $q = sprintf('select name from cats where id=%d', $this->motherid);
        $data = Connection::GetResultInArray($q)[0]['name']; 
        return $data;
    }

    static function LoadData($id = -1, $addWhere="", $sort="")
    {
        if ($id > 0) $addWhere .= sprintf(' and cats.id=%d', $id); 
        $q = sprintf("select cats.id, cats.name, cats.age, cats.sex as sex,  cats.mother as motherid from cats where 1=1 %s %s
        ", $addWhere, $sort);
       
        $data = Connection::GetAllResultInObjects($q, 'Cat'); 
        foreach ($data as $value)
        {
            $value->fathers = $value->getFathers();
            $value->mothername = $value->getMother();
        }    
        return $data;
    }
}