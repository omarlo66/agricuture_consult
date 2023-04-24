<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once 'options.php';?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | <?php echo get_options('title');?></title>
</head>
<body>
    <?php include_once 'header.php'?>
    <h1>Register</h1>
<div class="form">
    <p>username</p>
    <div class="input">
        <i class="fa fa-user"></i>
        <input type="text" name="username" placeholder="Username" id="username">
    </div>
    <p>email</p>
    <div class="input">
    <i class="fa fa-envelope"></i>
        <input type="email" name="email" placeholder="email" id="email">
    </div>
    <p>password</p>
    <div class="input">
        <i class="fa fa-key"></i>
        <input type="password" name="password" placeholder="Password" id="password">
        <div class="show_password"><i class="fas fa-eye"></i></div>
    </div>
    <p>mobile</p>
    <div class="input">
        <i class="fa fa-mobile"></i>
        <input type="tel" id="mobile">
    </div>
    <button id="register">Register</button>
</div>
<script>
    $('#register').click(()=>{
        $.post('user_api/user.php?new=1',{
            username: $('#username').val(),
            email: $('#email').val(),
            password: $('#password').val(),
            mobile: $('#mobile').val()
        },(data)=>{
            if(data === 'success'){
                alert(data);
                window.location.href = '/elevau/dashboard.php';
            }
            else{
                alert(data);
            }
        })
    });
    $('.show_password').click(()=>{
        if($('#password').attr('type') === 'password'){
            $('#password').attr('type','text');
            $('.show_password').html('<i class="fas fa-eye-slash"></i>')
        }
        else{
            $('#password').attr('type','password');
            $('.show_password').html('<i class="fas fa-eye"></i>')
        }
    })
</script>
</body>
</html>