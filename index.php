<?php

include("include.php");
//  echo "<pre>";
$alias = $_GET['alias']; 
if (isset($_GET['agesort']) || isset($_GET['sexsort'])) $sarchP = $_GET;

$page = new Page($alias, $sarchP);

$action = $_GET['action'];
if ($action)
{
    if ($action == "update")
    {

        $cat = Cat::LoadData($_GET['id'])[0];
        $cat->name = $_POST['name'];
        $cat->age = $_POST['age'];
        $cat->mother = $_POST['mother'];
        $farr = [];///массив с новыми отцами
        foreach ($_POST as $key=>$value)
        {
            if (is_int($key))
            {
                $farr[] = $key;
            }
        }

        $cat->save();
        $cat->updateFathers($farr);
    }

    if ($action == 'save')
    {
        $newCat = new Cat();
        $newCat->addCat($_POST);
        header('Location: index.php');
    }
    if ($action == 'del')
    {
        $cat = Cat::LoadData($_GET['id'])[0];
        $cat->delCat();
    }
    header('Location: index.php');
}


$pc = new PC;
$pc->get('index');
$pc->set("[content]", $page->content);
print($pc->parse());


?>