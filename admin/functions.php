<?php

function query($query){
    global $connection;
    $result = mysqli_query($connection, $query);
    comfirm($result);
    return $result;
}

function imagePlaceholder($image='') {

    if(!$image) {
        return 'image_4.jpg';
    } else {
        return $image;
    }
}



function escape($string) {
global $connection;
return mysqli_real_escape_string($connection, trim($string));


}
function comfirm($result) {
    global $connection;
    if(!$result) {
        die("QUERY FAILED" . mysqli_error($connection));
    }
}; 
function insert_categories() {

  global $connection;
    if(isset($_POST['submit'])) {
        $cat_title = $_POST['cat_title'];
    
        if($cat_title == "" || empty($cat_title)) {

        echo "<h1> Enter the category name</h1>";

        } else {
        $query = "INSERT INTO categories (cat_title) ";
        $query .= "VALUE ('{$cat_title}') ";
        $insert_cat = mysqli_query($connection, $query);
     

         if (!$insert_cat) {
            die ("Failed" . mysqli_error($connection));
    }
}
}



}

function findAllCategories() {
    global $connection;
    
    $query = "SELECT * FROM categories";
    $querySelect_connection = mysqli_query($connection, $query);
    while($row = mysqli_fetch_assoc($querySelect_connection)) {
    $cat_id = $row['cat_id'];
     $cat_title = $row['cat_title'];
    echo "<tr>";
    echo "<td>{$cat_id}</td>";
    echo "<td>{$cat_title}</td>";
    echo "<td><a href='categories.php?delete={$cat_id}'>Delete</a></td>";
    echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";           
    echo "</tr>";                        
                    
}
}


function deleteCategories() {        
    global $connection;
    if(isset($_GET['delete'])){
        $the_cat_id = $_GET['delete'];
        $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id} ";
        $delete_query = mysqli_query($connection,$query);
        header("Location: categories.php");
    }
}

function users_online() {

    if(isset($_GET['onlineusers'])) {
    global $connection;
    if(!$connection) {
        session_start();
        include("../includes/db.php");
        $session = session_id();
$time = time();
$time_out_in_seconds = 05;
$time_out = $time - $time_out_in_seconds;

$query = "SELECT * FROM users_online WHERE session = '$session'";
$send_query = mysqli_query($connection, $query);
$count = mysqli_num_rows($send_query);

if($count == NULL) {
    mysqli_query($connection, "INSERT INTO users_online(session, time) VALUES('$session','$time')");

} else {

    mysqli_query($connection, "UPDATE users_online SET time = '$time' WHERE session = '$session'");

}
   $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > '$time_out'");
   echo  $count_user = mysqli_num_rows($users_online_query);

}
}
}
users_online();


function recordCount($table) {

    global $connection;

    $query = "SELECT * FROM " . $table;
    $select_all_posts = mysqli_query($connection,$query);

    $result = mysqli_num_rows($select_all_posts);
    comfirm($result);

    return $result;
}


function checkStatus($table,$column,$status) {
    global $connection;
    $query = "SELECT * FROM $table WHERE $column = '$status' ";
    $results = mysqli_query($connection,$query);
    comfirm($results);
   return  mysqli_num_rows($results);

}

function get_user_name() {
    if(isset($_SESSION['username'])) {
        return isset($_SESSION['username']) ? $_SESSION['username'] : null;
    }
}

function is_admin() {

if(isLoggedIn()) {
    $result = query("SELECT user_role FROM users WHERE user_id = ".$SESSION['user_id']."");
    
    $row = fetchRecords($result);
    
    if($row['user_role'] == 'admin') {
    
        return true;
    } else {
        return false;
    }
    
}

return false;
}

function fetchRecords($result) {
    return mysqli_fetch_array($result);
}

function username_exists($username){

    global $connection;

    $query = "SELECT username FROM users WHERE username = '$username'";
$result = mysqli_query($connection, $query);
comfirm($result);

if(mysqli_num_rows($result) > 0) {
    return true;
} else {

    return false;
}
}



function email_exists($email){

    global $connection;

    $query = "SELECT user_email FROM users WHERE user_email = '$email'";
$result = mysqli_query($connection, $query);
comfirm($result);

if(mysqli_num_rows($result) > 0) {
    return true;
} else {

    return false;
}
}

function redirect($location){
    return header("Location:" . $location);
    exit;
}
function ifItIsMethod($method=null){

    if($_SERVER['REQUEST_METHOD'] == strtoupper($method)){
        return true;
    } else {
        return false;
    }

}

function isLoggedIn(){
    if(isset($_SESSION['user_role'])){
        return true;
    }
return false;
}

function loggedInUserId() {
    if(isLoggedIn()) {
        $result = query("SELECT * FROM users WHERE username='" . $_SESSION['username'] ."'");
        comfirm($result); 
        $user = mysqli_fetch_array($result);
            return mysqli_num_rows($result) >=1 ? $user['user_id'] : false;
        }
        return false;
    }
    


function userLikedThisPost($post_id) {
    $result = query("SELECT * FROM likes WHERE user_id=" .loggedInUserId() . " AND post_id={$post_id}");
    comfirm($result); 
    return mysqli_num_rows($result) >= 1 ? true : false;
}

function getPostlikes($post_id){
    $result = query("SELECT * FROM likes WHERE post_id=$post_id");
    comfirm($result);
    echo mysqli_num_rows($result);
}

function checkIfUserIsLoggedInAndRedirect($redirectLocation=null) {
    if(isLoggedIn()){
        redirect($redirectLocation);
    }
}

function register_user($username, $email, $password) {

global $connection;

$username  = mysqli_real_escape_string($connection, $username);
$email  = mysqli_real_escape_string($connection, $email);
$password  = mysqli_real_escape_string($connection, $password);

$password = password_hash($password , PASSWORD_DEFAULT, array('cost' => 12) );


$query = "INSERT INTO users (username,user_password,user_email,user_role) ";
$query .= "VALUES('{$username}','{$password}','{$email}', 'subscriber' )";
$register_user_query = mysqli_query($connection, $query);

comfirm($register_user_query);
} 



function login_user($username, $password){
    global $connection;
    $username = trim($username);
    $password = trim($password);
 
    $username = mysqli_real_escape_string($connection, $username);
    $password = mysqli_real_escape_string($connection, $password);
 
    $query = "SELECT * FROM users WHERE username = '{$username}' ";
    $select_user_query = mysqli_query($connection, $query);
    if(!$select_user_query) {
         die("Query FIILED" . mysqli_error($connection));
    }
 
    while($row = mysqli_fetch_array($select_user_query)) {
      $db_user_id = $row['user_id'];
      $db_username = $row['username'];
      $db_user_password = $row['user_password'];
      $db_user_firstname = $row['user_firstname'];
      $db_user_lastname = $row['user_lastname'];
      $db_user_role = $row['user_role'];

      if(password_verify($password,$db_user_password)) {
        //  header("Location: ../admin ");
        $_SESSION['user_id'] = $db_user_id;
         $_SESSION['username'] = $db_username;
         $_SESSION['firstname'] = $db_user_firstname;
         $_SESSION['lastname'] = $db_user_lastname;
         $_SESSION['user_role'] = $db_user_role;
     
         redirect("/cms/admin/index.php");
     
        } else {
    
            redirect("/cms/index.php");
        }
    
    }
return true;
    }
 
   //  $password = crypt($password, $db_user_password);
 
 
?>

