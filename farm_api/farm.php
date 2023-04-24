<?php

include_once '../options.php';

if(isset($_POST['add_new'])){
    $user_id = $_POST['add_new'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    if(insert_farm($name,$address,$user_id)){
        echo 'success';
    }
    else{
        echo 'error';
    }
}

if(isset($_POST['delete'])){
    $id = $_POST['delete'];
    if(delete_farm($id)){
        echo 'success';
    }
    else{
        echo 'error';
    }
}

if(isset($_POST['edit'])){
    $id = $_POST['edit'];
    $name = $_POST['title'];
    $address = $_POST['address'];
    if(update_farm($id,$name,$address)){
        echo 'success';
    }
    else{
        echo 'error';
    }
}


?>