
<?php

include_once 'options.php';
$get_user = current_user();
if(! $get_user){
    header('Location: /elevau/login.php');
}
include_once 'header.php';
$user_data = get_user($get_user);
$name = $user_data['username'];
$user_id = $user_data['id'];

if(isset($_GET['new'])){
    $farm_id = $_GET['new'];
    ?>

    <div class="form">
        <input type="hidden" id="farm_id" value="<?php echo $farm_id;?>">
        <div class="input">
            <i class="fa fa-id-card-o">  title</i>
            <input type="text" name="name" id="name">
        </div>
    <div class="input">
        <i class="fa fa-map-marker">space Fedan</i>
        <input type="text" name="space" id="space">
    </div>
    <div class="input">
        <i class="fa fa-map-marker">longitude</i>
        <input type="text" name="longitude" id="longitude">
    </div>
    <div class="input">
        <i class="fa fa-map-marker">latitude</i>
        <input type="text" name="latitude" id="latitude">
    </div>
    <div class="input">
        <i class="fa fa-map-marker">crop</i>
        <input type="text" name="crop" id="crop">
    </div>
    <button id="add_plot">Add Plot</button>
    </div>
    <script>
            
            $('#add_plot').click(()=>{
                var name = $('#name').val();
                var space = $('#space').val();
                var longitude = $('#longitude').val();
                var latitude = $('#latitude').val();
                var crop = $('#crop').val();
                var farm_id = $('#farm_id').val();
                $.post('plots_api/plots.php',{
                    add_plot: 1,
                    farm_id: farm_id,
                    name: name,
                    space: space,
                    longitude: longitude,
                    latitude: latitude,
                    crop: crop,
                    add_new: '<?php echo $user_id;?>'
                },(data)=>{
                    if(data === 'success'){
                        window.location.href = '/elevau/farm.php?id='+farm_id;
                    }
                    else{
                        alert(data);
                    }
                })
            });
    </script>
    <?php
    

}

if(isset($_GET['id'])){
    $plot = get_plot($_GET['id']);
    if(! $plot){
        header('Location: /elevau/dashboard.php');
    }
    echo json_encode($plot);
    ?>

    <h1><?php echo $plot['name'];?></h1>
    <button id="delete">Delete</button>
    <div class="plot_data">
        <div class="form">
            <input type="hidden" name="plot_id" id="plot_id" value="<?php echo $plot['id']?>">
            <div class="input">
                <i class="fa fa-id-card-o">  title</i>
                <input type="text" name="name" id="name" value="<?php echo $plot['name'];?>" disabled>
            </div>
            <div class="input">
                <i class="fa fa-map-marker">space Fedan</i>
                <input type="text" name="space" id="space" value="<?php echo $plot['area'];?>" disabled>
                </div>
            <div class="input">
                <i class="fa fa-map-marker">longitude</i>
                <input type="text" name="longitude" id="longitude" value="<?php echo $plot['longitude'];?>" disabled>
            </div>
            <div class="input">
                <i class="fa fa-map-marker">latitude</i>
                <input type="text" name="latitude" id="latitude" value="<?php echo $plot['latitude'];?>" disabled>
            </div>
            <button id="get_location">get live location</button>
            <div class="input">
                <i class="fa fa-map-marker">crop</i>
                <input type="text" name="crop" id="crop" value="<?php echo $plot['crop'];?>" disabled>
            </div>
            <button id="Edit_plot">Edit Plot</button>
            <button id="save" disabled>Save changes</button>
        </div>
    </div>  
    <div class="card_widget">
        <div class="loaction"><i class="fa fa-location"></i><p><?php echo $plot['area'];?></p></div>
    </div>
    <div class="weather">
        <div class="tempreture"><p>Tempreture:</p></div>
        
        <div class="wind_speed"><p>Wind Speed:</p></div>
        
        <div class="wind_direction"><p>wind direction:</p></div>

        <p>Weahter in the next days and hours</p>
        <table>
            <tr>
                <th>data</th>
                <th>time</th>
                <th>Tempreture</th>
                <th>Wind Speed</th>
                <th>Humidity</th>
            </tr>
        </table>
    </div>
    <script>
        var plot_id = $('#plot_id').val();
        var lonitude = $('#longitude').val();
        var latitude = $('#latitude').val();
        var longitude = '<?php echo $plot['longitude'];?>';
        $.get('plots_api/weather.php?weather='+plot_id+'&longitude='+longitude+'&latitude='+latitude,(data)=>{
            data = JSON.parse(data);
            let current_weather = data.current_weather;
            $('.tempreture').append(current_weather.temperature);
            $('.wind_speed').append(current_weather.windspeed);
            $('.wind_direction').append(current_weather.winddirection);
            let next_days = data.hourly;
            
        });
        $('#Edit_plot').click(()=>{
            $('#edit_plot').attr('disabled','disabled');
            $('#save').removeAttr('disabled');
            $('#name').removeAttr('disabled');
            $('#space').removeAttr('disabled');
            $('#longitude').removeAttr('disabled');
            $('#latitude').removeAttr('disabled');
            $('#crop').removeAttr('disabled');
        });

        $('#save').click(()=>{
            $('#save').attr('disabled','disabled');
            $('#edit_plot').removeAttr('disabled');
            $('#name').attr('disabled','disabled');
            $('#space').attr('disabled','disabled');
            $('#longitude').attr('disabled','disabled');
            $('#latitude').attr('disabled','disabled');
            $('#crop').attr('disabled','disabled');
            var name = $('#name').val();
            var space = $('#space').val();
            var longitude = $('#longitude').val();
            var latitude = $('#latitude').val();
            var crop = $('#crop').val();
            var plot_id = $('#plot_id').val();
            $.post('plots_api/plots.php',{
                edit: plot_id,
                name: name,
                space: space,
                longitude: longitude,
                latitude: latitude,
                crop: crop
            },(data)=>{
                if(data === 'success'){
                    window.location.href = '/elevau/plots.php?id='+plot_id;
                }
                else{
                    alert(data);
                }
            })
        });

        $('#get_location').click(()=>{
            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition((position)=>{
                    var longitude = position.coords.longitude;
                    var latitude = position.coords.latitude;
                    $('#longitude').val(longitude);
                    $('#latitude').val(latitude);
                });
            }
            else{
                alert('Your browser does not support geolocation');
            }
        });
    </script>
    <?php
}

