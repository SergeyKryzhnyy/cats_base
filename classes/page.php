<?php

class Page
{
    public $content;
    public $catid;
    public $isSearch = false;
    public $searchParams;

    function __construct($alias, $search)
    {
        if (!$alias) 
        {
            if ($search['sexsort'] > 0 || $search['agesort'] > 0) $this->isSearch = true;
            $this->searchParams = $search;
            $this->content=  $this->getMainPage();
        }
        if ($alias == 'editcat')
        {
            $cat = Cat::LoadData($_GET['id'])[0];;
            $this->content = $this->getEditPage($cat); 
        } 
    }

    private function getEditpage($cat)
    {
        $pc = new PC('editform');
        $pc->set("[name]", $cat->name);
        $pc->set("[age]", $cat->age);
        $pc->set("[id]", $cat->id);
        $mothers = Cat::LoadData(-1, ' and cats.sex=2 ');
        $fathers = Cat::LoadData(-1, ' and cats.sex=1 ');
        $str = "";

        $farr = [];
        foreach ($cat->fathers as $val)
        {
            $farr[] = $val;
        }

        $strSex = sprintf('<option value="1" %s>male</option>
        <option value="2" %s>female</option>', $cat->sex == 1 ? "selected" : "", $cat->sex == 2 ? "selected" : "" );
        $pc->set('[sex]', $strSex);

        foreach($mothers as $value)
        {
            $str .= sprintf("<option %s value=%d>%s</option>", $value->id == $cat->mother ? "selected" : "", $value->id, $value->name);
        }
        $str2 = "<label for='fathers'>Возможные отцы</label><ul class='fathers' name='fathers'>";
        foreach($fathers as $value)
        {
            if (count($farr) > 0)
            $str2 .= sprintf('<label><input type="checkbox" %s name="%s" id=ch%d >%s</label>', in_array($value->id, $farr) ? "checked" : "",  $value->id, $value->id, $value->name);
            else $str2 .= sprintf('<label><input type="checkbox" name="%s" id=%d >%s</label>', $value->id, $value->id, $value->name);
        }
        $str2 .="</ul>";

        $pc->set("[motherlist]", $str);
        $pc->set("[fatherslist]", $str2);
        return ($pc->parse());
        return $this->catid;
    }

    private function getAddForm()
    {
        $pc = new PC('addform');
        $mothers = Cat::LoadData(-1, ' and cats.sex=2 ');
        $fathers = Cat::LoadData(-1, ' and cats.sex=1 ');
        $str = "";
        foreach($mothers as $value)
        {
            $str .= sprintf("<option value=%d>%s</option>", $value->id, $value->name);
        }
        $str2 = "<label for='fathers'>Возможные отцы</label><ul class='fathers' name='fathers'>";
        foreach($fathers as $value)
        {
            $str2 .= sprintf('<label><input type="checkbox" name="%s" id=%d >%s</label>', $value->id, $value->id, $value->name);
        }
        $str2 .="</ul>";

        $pc->set("[motherlist]", $str);
        $pc->set("[fatherslist]", $str2);
        return ($pc->parse());
    }


    function getSearchForm()
    {
        $str = sprintf( '<form action="" method="get">
        <label> <h2>Сортировка </h2></label>
        <label for="agesort">Возраст</label>
        <select name="agesort" id="agesort">
            <option value=0>Нет</option>
            <option %s value=1>По возрастанию</option>
            <option %s value=2>По убыванию</option>
        </select>
        <label for="sexsort">Пол</label>
        <select name="sexsort" id="sexsort">
            <option value=0>Нет</option>
            <option %s value=1>Только male</option>
            <option %s value=2>Только female</option>
    </select>
    <button type="submit">Применить</button>
    </form>', $this->searchParams['agesort'] == 1 ? "selected" : "",$this->searchParams['agesort'] == 2 ? "selected" : "",
    $this->searchParams['sexsort'] == 1 ? "selected" : "", $this->searchParams['sexsort'] == 2 ? "selected" : "" );
    return $str;
    }


    function getMainPage()
    {      
        $addSort = "";
        $addwhere = "";
        if ($this->isSearch)
        {
            $addSort = 'order by ';
            if ($this->searchParams['agesort'] == 2) $addSort .= sprintf(' cats.age desc');
            if ($this->searchParams['agesort'] == 1) $addSort .= sprintf(' cats.age asc');
            if ($this->searchParams['sexsort'] > 0) $addWhere = sprintf(" and cats.sex=%d", $this->searchParams['sexsort']);
        }
        $cats = Cat::LoadData(-1, $addWhere, $addSort);
        $str = '<div class="container">';
        $str .= $this->getSearchForm();
        $str .= sprintf('<div class="addform">%s</div>', $this->getAddForm());
        $str .= "<ul class='catalog'>";
        foreach($cats as $cat)
        { 
            $fathers = '';
            if (count($cat->fathers))
            {
                $q = "select id, name from cats where id in(";
                
                for ($i=0; $i<count($cat->fathers); $i++)
                {
                    if ($i < count($cat->fathers)-1) $q .= sprintf('%d,', $cat->fathers[$i]);
                    if ($i == count($cat->fathers)-1) $q .= sprintf('%d)', $cat->fathers[$i]);
                }
                $res = Connection::GetResultInArray($q);
                foreach($res as $key=>$value)
                {
                    $fathers .= $value['name'] . '; ';
                }
            }

        $str .= sprintf('<div class="catdata"><ul>
                <li class="id">ID: %s</li>
                <li class="name">Кличка: %s</li>
                <li class="age">возраст: %s</li>
                <li class="sex">пол: %s</li>
                <li class="age">мать: %s</li>
                <li class="father">Возможные отцы: %s </li>
                </ul>
                <a href="editcat/?id=%d">Изменить</a>
                <a href="?action=del&id=%d">Удалить</a>
                </div>
            ', $cat->id, $cat->name, $cat->age, $cat->sex == 1 ? "male" : "female", $cat->mothername, $fathers, $cat->id, $cat->id);
        }
        $str .= "</ul></div>";
        return $str;
    }
}
