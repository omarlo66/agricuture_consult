<?php

$sql = new mysqli('localhost','root','','elevau');
if($sql->connect_error){
    add_log('Connection failed: '.$sql->connect_error);
    die('Connection failed: '.$sql->connect_error);
}
/*create_tables('CREATE TABLE IF NOT EXISTS users(
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL,
)');
create_tables('CREATE TABLE IF NOT EXISTS farms(
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    owner VARCHAR(255) NOT NULL,
)');
create_tables('CREATE TABLE IF NOT EXISTS plots(
    id INT(11) NOT NULL AUTO_INCREMENT,
    farm_id INT(11) NOT NULL,
    name VARCHAR(255) NOT NULL,
    area VARCHAR(255) NOT NULL,
    crop VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)');
create_tables('CREATE TABLE IF NOT EXISTS options (
    id INT(11) NOT NULL AUTO_INCREMENT,
    option_name VARCHAR(255) NOT NULL,
    option_value VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)');
create_tables('CREATE TABLE IF NOT EXISTS usermeta(
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    meta_key VARCHAR(255) NOT NULL,
    meta_value VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)');
*/
create_tables('CREATE TABLE IF NOT EXISTS plotmeta(
    id INT(11) NOT NULL AUTO_INCREMENT,
    plot_id INT(11) NOT NULL,
    meta_key VARCHAR(255) NOT NULL,
    meta_value VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)');
function create_tables($table){
    global $sql;
    $sql->query($table);
    if($sql->error){
        add_log('Error: '.$sql->error);
    }
    return true;
}
function add_log($log){
    $log = fopen(__DIR__.'/log.txt','a');
    fwrite($log, date('Y-m-d H:i:s').': '.$log);
    fclose($log);
}
//Web options
    function get_options($key){
        global $sql;
        $query = $sql->query('SELECT * FROM options WHERE option_name="'.$key.'"');
        if($query->num_rows > 0){
            $option = $query->fetch_assoc();
            return $option['option_value'];
        }
        else{
            return false;
        }
    }
    function set_options($name,$value){
        global $sql;
        $sql->query("INSERT INTO options(option_name,option_value) VALUES('$name','$value')");
        if($sql->error){
            add_log('Error: '.$sql->error);
            return false;
        }
        return true;
    }
//User Functions
    function insert_user($name,$password,$email,$phone,$address,$role){
        global $sql;
        $password = md5($password);
        if(get_user_by('username',$name) || get_user_by('email',$email)){
            return false;
        }
        $sql->query("INSERT INTO users(username,password,email,phone,address,role) VALUES('$name','$password','$email','$phone','$address','$role')");
        if($sql->error){
            add_log('Error: '.$sql->error);
        }
        return true;
    }

    function login($username,$password){
        global $sql;
        $password = md5($password);
        $result = $sql->query("SELECT * FROM users WHERE password='$password'");
        if($result->num_rows > 0){
            $user = $result->fetch_assoc();
            if($user['username'] == $username || $user['email'] == $username){
                logged_in($user['id']);
                return $user['id'];
            }
            else{
                return false;
            }
            }
            else{
                return false;
        }
        if($sql->error){
            add_log('Error: '.$sql->error);
        }
        return false;
    }

    function update_user_meta($user_id,$meta,$value){
        global $sql;
        $Check_meta = $sql->query("SELECT * FROM usermeta WHERE user_id='$user_id' AND meta_key='$meta'");
        if($Check_meta->num_rows > 0){
            $sql->query("UPDATE usermeta SET meta_value='$value' WHERE user_id='$user_id' AND meta_key='$meta'");
        }
        else{
            $sql->query("INSERT INTO usermeta(user_id,meta_key,meta_value) VALUES('$user_id','$meta','$value')");
        }
        if($sql->error){
            add_log('Error: '.$sql->error);
            return false;
        }
        return true;
    }
    function get_user_meta($user_id,$meta = null){
        global $sql;
        if($meta == null){
            $result = $sql->query("SELECT * FROM usermeta WHERE user_id='$user_id'");
            if($result->num_rows > 0){
                $meta = array();
                while($row = $result->fetch_assoc()){
                    $meta[$row['meta_key']] = $row['meta_value'];
                }
                return $meta;
            }
            else{
                return false;
            }
        }
        else{
            $result = $sql->query("SELECT * FROM usermeta WHERE user_id='$user_id' AND meta_key='$meta'");
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                return $row['meta_value'];
            }
            else{
                return false;
            }
        }
    }
    function logged_in($user_id){
        $generate = uniqid();
        setcookie('logged_in',$generate,time() + (86400 * 30),'/');
        update_user_meta($user_id,'logged_in',$generate);
        return true;
    }

    function current_user(){
        if(isset($_COOKIE['logged_in'])){
            $logged_in = $_COOKIE['logged_in'];
            global $sql;
            $result = $sql->query("SELECT * FROM usermeta WHERE meta_key='logged_in' AND meta_value='$logged_in'");
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $user_id = $row['user_id'];
                return $user_id;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    function logout(){
        $user_id = current_user();
        update_user_meta($user_id,'logged_in','');
        setcookie('logged_in','',time() - 3600,'/');
        return true;
    }

    function get_user($user_id){
        global $sql;
        $result = $sql->query("SELECT * FROM users WHERE id='$user_id'");
        if($result->num_rows > 0){
            $user = $result->fetch_assoc();
            return $user;
        }
        else{
            return false;
        }
    }
    function get_user_by($key,$value){
        global $sql;
        $result = $sql->query("SELECT * FROM users WHERE $key='$value'");
        if($result->num_rows > 0){
            $user = $result->fetch_assoc();
            return $user;
        }
        else{
            return false;
        }
    }
//Farms

    function insert_farm($name,$address,$user_id){
        global $sql;
        $sql->query("INSERT INTO farms(name,address,owner) VALUES('$name','$address','$user_id')");
        if($sql->error){
            add_log('Error: '.$sql->error);
            return false;
        }
        return true;
    }

    function get_farms($user_id){
        global $sql;
        $result = $sql->query("SELECT * FROM farms WHERE owner='$user_id'");
        if($result->num_rows > 0){
            $farms = array();
            while($row = $result->fetch_assoc()){
                $farms[] = $row;
            }
            return $farms;
        }
        else{
            return false;
        }
    }

    function get_farm($farm_id){
        global $sql;
        $result = $sql->query("SELECT * FROM farms WHERE id ='$farm_id'");
        if($result->num_rows > 0){
            $farm = $result->fetch_assoc();
            return $farm;
        }
        else{
            return false;
        }
    }

    function update_farm($farm_id,$name,$address){
        global $sql;
        $sql->query("UPDATE farms SET name='$name', address='$address' WHERE id='$farm_id'");
        if($sql->error){
            add_log('Error: '.$sql->error);
            return false;
        }
        return true;
    }

    function delete_farm($farm_id){
        global $sql;
        $sql->query("DELETE FROM farms WHERE id='$farm_id'");
        if($sql->error){
            add_log('Error: '.$sql->error);
            return false;
        }
        return true;
    }

//plots functions
    function get_plots_by_farm($value = 0){
        global $sql;
        $result = $sql->query("SELECT * FROM plots WHERE farm_id ='$value'");
        if($result->num_rows > 0){
            $plots = array();
            foreach($result as $row){
                $area = explode(':', $row['area']);
                $row['longitude'] = $area[1];
                $row['latitude'] = $area[2];
                $row['area'] = $area[0];
                $plots[] = $row;
            }
            return $plots;
        }
        else{
            return false;
        }
    }

    function insert_Plot($farm_id,$name,$space,$longitude,$latitude,$crop){
        global $sql;
        $area = implode(':',array($space,$longitude,$latitude));
        $sql->query("INSERT INTO plots(farm_id,name,area,crop) VALUES('$farm_id','$name','$area','$crop')");
        if($sql->error){
            add_log('Error: '.$sql->error);
            return false;
        }
        return true;
    }

    function update_plot($plot_id,$name,$space,$longitude,$latitude,$crop){
        global $sql;
        $area = implode(':',array($space,$longitude,$latitude));
        $sql->query("UPDATE plots SET name='$name', area='$area', crop='$crop' WHERE id='$plot_id'");
        if($sql->error){
            add_log('Error: '.$sql->error);
            return false;
        }
        return true;
    }

    function delete_plot($plot_id){
        global $sql;
        $sql->query("DELETE FROM plots WHERE id='$plot_id'");
        if($sql->error){
            add_log('Error: '.$sql->error);
            return false;
        }
        return true;
    }

    function get_plot($plot_id){
        global $sql;
        $result = $sql->query("SELECT * FROM plots WHERE id ='$plot_id'");
        if($result->num_rows > 0){
            $plot = $result->fetch_assoc();
            $area = explode(':', $plot['area']);
            $plot['longitude'] = $area[1];
            $plot['latitude'] = $area[2];
            $plot['area'] = $area[0];
            return $plot;
        }
        else{
            return false;
        }
    }

    function update_plot_meta($plot_id,$key,$value){
        global $sql;
        $meta_tag = get_plot_meta($plot_id,$key);
        if($meta_tag){
            $sql->query("UPDATE plotmeta SET meta_value='$value' WHERE plot_id='$plot_id' AND meta_key='$key'");
            if($sql->error){
                add_log('Error: '.$sql->error);
                return false;
            }
            return true;
        }
        $sql->query("INSERT INTO plotmeta(plot_id,meta_key,meta_value) VALUES('$plot_id','$key','$value') ON DUPLICATE KEY UPDATE meta_value='$value'");
        if($sql->error){
            add_log('Error: '.$sql->error);
            return false;
        }
        return true;
    }

    function get_plot_meta($plot_id,$key = null){
        if($key == null){
            global $sql;
            $result = $sql->query("SELECT * FROM plotmeta WHERE plot_id ='$plot_id'");
            if($result->num_rows > 0){
                $plotmeta = array();
                while($row = $result->fetch_assoc()){
                    $plotmeta[$row['meta_key']] = $row['meta_value'];
                }
                return $plotmeta;
            }
            else{
                return false;
            }
        }else{
            global $sql;
            $result = $sql->query("SELECT * FROM plotmeta WHERE plot_id ='$plot_id' AND meta_key='$key'");
            if($result->num_rows > 0){
                $plotmeta = $result->fetch_assoc();
                return $plotmeta['meta_value'];
            }
            else{
                return false;
            }
        }

    }

    function delete_plot_meta($plot_id,$meta){
        global $sql;
        $sql->query("DELETE FROM plotmeta WHERE plot_id='$plot_id' AND meta_key='$meta'");
        if($sql->error){
            add_log('Error: '.$sql->error);
            return false;
        }
        return true;
    }




?>