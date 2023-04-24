<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once 'options.php';?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | <?php echo get_options('title');?></title>
</head>
<body>
<?php
if(current_user() && logged_in(current_user()) ){
    header('Location: /elevau/dashboard.php');
}

include_once 'header.php';
?>
<div class="form">
        <div class="input">
            <i class="fa fa-user"></i>
            <input type="text" name="username" placeholder="Username" id="username">
        </div>
        <div class="input">
            <i class="fa fa-key"></i>
            <input type="password" name="password" placeholder="Password" id="password">
             <div id="show_password"><i class="fas fa-eye"></i></div>
        </div>    
        <button id="login">Login</button>
        <div class="links">
            <a href="/elevau/register.php">Register</a>
            <div class="space"></div>
            <a href="/elevau/forgot_password.php">Forgot Password</a>
        </div>
</div>
<?php

?>
    <script>
        $('#login').click(()=>{
            $.post('user_api/user.php',{
                username: $('#username').val(),
                password: $('#password').val()
            },(data)=>{
                if(data == 'success'){
                    window.location.href = '/elevau/dashboard.php';
                }
                else{
                    alert(data);
                }
            });
        });
        $('#show_password').click(()=>{
            if($('input[name="password"]').attr('type') === 'password'){
                $('input[name="password"]').attr('type','text');
                $('#show_password').html('<i class="fas fa-eye-slash"></i>')
            }
            else{
                $('input[name="password"]').attr('type','password');
                $('#show_password').html('<i class="fas fa-eye"></i>')
            }
        });
    </script>

        
</body>
</html>