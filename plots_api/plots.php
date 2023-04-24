<?php

include_once '../options.php';
if(isset($_POST['add_plot'])){
    $farm_id = $_POST['farm_id'];
    $name = $_POST['name'];
    $space = $_POST['space'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $crop = $_POST['crop'];
    if(insert_Plot($farm_id,$name,$space,$longitude,$latitude,$crop)){
        echo 'success';
    }
    else{
        echo 'error';
    }
} 
if(isset($_POST['edit'])){
    $plot_id = $_POST['edit'];
    $name = $_POST['name'];
    $space = $_POST['space'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $crop = $_POST['crop'];
    if(update_plot($plot_id,$name,$space,$longitude,$latitude,$crop)){
        echo 'success';
    }
    else{
        echo 'error';
    }
}
?>