<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once 'options.php';?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farms</title>
</head>
<body>
    <?php include_once 'header.php'; ?>
<div class="farm_page">
    <?php
if(! current_user()){
    header('Location: /elevau/login.php');
}
$user_id = get_user(current_user())['id'];
    if(isset($_GET['add_new'])){
        $status = $_GET['add_new'];
        ?>

        <div class="form">
            <div class="input">
                <i class="fa fa-id-card-o">  title</i>
                <input type="text" name="name" id="name">
            </div>
            <div class="input">
                <i class="fa fa-map-marker">address</i>
                <input type="text" name="address" id="address">
            </div>

            <button id="add_farm">Add Farm</button>
        </div>
        <script>

            $('#add_farm').click(()=>{
                var name = $('#name').val();
                var address = $('#address').val();
                $.post('farm_api/farm.php',{
                    name: name,
                    address: address,
                    add_new: '<?php echo $user_id;?>'
                },(data)=>{
                    if(data === 'success'){
                        window.location.href = '/elevau/dashboard.php';
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
        $farm = get_farm($_GET['id']);
        if(! $farm || $farm['owner'] != $user_id){
            header('Location: /elevau/dashboard.php');
        }
        ?>
            <h1><?php echo $farm['name'];?></h1>
            <button id="edit">Edit</button>
            <button id="delete">Delete</button>
            <div class="card_widget">
                <div class="loaction"><i class="fa fa-location"></i><p><?php echo $farm['address'];?></p></div>
            </div>
            <div class="plots">
                <?php 
                $plots = get_plots_by_farm($value=$farm['id']);
                if(! $plots){
                    echo 'No plots <a href="plots.php?new='.$farm['id'].'"> add new plot</a>';
                }
                ?>
                <div class="farm_plots">
                    <div class="search">
                        <input type="text" name="search" id="search" placeholder="Search">
                    </div>
                    <table>
                        <tr>
                            <th>Plot Name</th>
                            <th>Plot Size</th>
                            <th>Plot Location</th>
                            <th>Plot crop</th>
                            <th>Plot Action</th>
                        </tr>
                        <?php
                        foreach($plots as $plot){
                            ?>
                            <tr>
                                <td><?php echo $plot['name'];?></td>
                                <td><?php echo $plot['area'].' Kirate';?></td>
                                <td><?php echo $plot['longitude'].' : '.$plot['latitude'];?></td>
                                <td><?php echo $plot['crop'];?></td>
                                <td><a href="plots.php?id=<?php echo $plot['id'];?>">View</a></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
            <script>
                $('#edit').click(()=>{
                    window.location.href = '/elevau/farm.php?edit=<?php echo $farm['id'];?>';
                });
                $('#delete').click(()=>{
                    let auth = prompt('Enter your email to delete this farm');
                    console.log(auth,'<?php echo get_user(current_user())['email'];?>')

                    if(auth == '<?php echo get_user(current_user())['email'];?>'.toLowerCase()){
                    $.post('farm_api/farm.php',{
                        delete: '<?php echo $farm['id'];?>'
                    },(data)=>{
                        if(data === 'success'){
                            window.location.href = '/elevau/dashboard.php';
                        }
                        else{
                            alert(data);
                        }
                    })
                }else{
                    alert('Wrong email');
                }
                });
                $('#search').keyup(()=>{
                    var search = $('#search').val();
                    $('.farm_plots table tr').each((index,element)=>{
                        if(index > 0){
                            var name = $(element).find('td').eq(0).text();
                            if(name.toLowerCase().includes(search.toLowerCase())){
                                $(element).show();
                            }
                            else{
                                $(element).hide();
                            }
                        }
                    })
                });
            
            </script>
        <?php
    }
    if(isset($_GET['edit'])){
        $id = $_GET['edit'];
        $farm = get_farm($id);
        if(! $farm || $farm['owner'] != $user_id){
            header('Location: /elevau/dashboard.php');
        }
        ?>
        <div class="form">
            <div class="input">
                <i class="fa fa-id-card-o">  title</i>
                <input type="text" name="title" id="title" value="<?php echo $farm['name'];?>">
            </div>
            <div class="input">
                <i class="fa fa-map-marker">  address</i>
                <input type="text" name="address" id="address" value="<?php echo $farm['address']?>">
            </div>
            <button id="save_farm">Save</button>
        </div>
        <script>
            $('#save_farm').click(()=>{
                var title = $('#title').val();
                var address = $('#address').val();
                $.post('farm_api/farm.php',{
                    edit: '<?php echo $farm['id'];?>',
                    title: title,
                    address: address
                },(data)=>{
                    if(data === 'success'){
                        window.location.href = '/elevau/farm.php?id=<?php echo $farm['id'];?>';
                    }
                    else{
                        alert(data);
                    }
                })
            });
        </script>
        <?php
    }
    ?>
</div>
</body>
</html>