<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once 'options.php';
    $get_user = current_user();
    if(! $get_user){
        header('Location: /elevau/login.php');
    }
    $user_data = get_user($get_user);
    $name = $user_data['username'];
    $user_id = $user_data['id'];
    ?>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $name;?> dashboard </title>
</head>
<body>
    <?php include_once 'header.php';?>
    <div class="wrap">

    
        <div class="side_bar">
            <div class="main_menu">
                <?php
                    $farms = get_farms($user_id);
                    if($farms && count($farms) > 0){
                        foreach($farms as $farm){
                            echo '<div><a href="/elevau/farm.php?id='.$farm['id'].'">'.$farm['name'].'</a></div>';
                        }
                    }else{
                        echo '<p>you have no farms here add your first!</p><a href="/elevau/farm.php?add_new=0">Add Farm</a>';
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>