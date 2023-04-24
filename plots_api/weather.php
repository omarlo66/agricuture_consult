<?php

if(isset($_GET['weather'])){
    $plot_id = $_GET['weather'];
    include_once '../options.php';
    $plot = get_plot($plot_id);
    $longitude = $_GET['longitude'];
    $latitude = $_GET['latitude'];
    $url = "https://api.open-meteo.com/v1/forecast?latitude=$latitude&longitude=$longitude&current_weather=true&hourly=temperature_2m,relativehumidity_2m,windspeed_10m";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($output,true);
    echo json_encode($data);
}
