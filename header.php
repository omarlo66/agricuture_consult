<?php include_once 'options.php';?>
<link rel="stylesheet" href="assets/style.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script><script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/brands.min.css" integrity="sha512-9YHSK59/rjvhtDcY/b+4rdnl0V4LPDWdkKceBl8ZLF5TB6745ml1AfluEU6dFWqwDw9lPvnauxFgpKvJqp7jiQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<header>
<div>
    <a href="/"><img src="<?php echo get_options('logo');?>" alt="<?php echo get_options('title');?>"><h1>Elevaueg</h1></a>    
</div>
<div class="navbar-menu">
        <a href="index.php" id="menu-item">Home</a>
        <a href="about" id="menu-item">About</a>
        <a href="contact" id="menu-item">Contact</a>
        <?php
        if(current_user()){
            $user = current_user();
            $user_data = get_user($user);
            $name = $user_data['username'];
            $user_id = $user_data['id'];
            $role = $user_data['role'];
            if($role === 'admin'){
                ?>
                <a href="admin" id="menu-item">Admin</a>
                <?php
            }
            ?>
            <a href="dashboard.php" id="menu-item">Dashboard</a>
            <a href="logout.php" id="menu-item">Logout</a>
            <?php
        }
        else{
            ?>
            <a href="login.php" id="menu-item">Login</a>
            <a href="register.php" id="menu-item">Register</a>
            <?php
        }
        ?>
</div>
</header>
<div class="space"></div>
<script>
    const url = window.location.href;
    const menu = document.querySelectorAll('.navbar-menu a');
    menu.forEach((item)=>{
        if(item.href === url){
            item.classList.add('active-meu');
        }
    })
</script>