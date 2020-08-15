<?php
require_once 'include/user.php';
$username = "";
$password = "";
$email = "";
if(isset($_POST['username'])){
$username = $_POST['username'];
}
if(isset($_POST['password'])){
$password = $_POST['password'];
}
if(isset($_POST['email'])){
$email = $_POST['email'];
}
// Instance of a User class
$userObject = new User();

// User Login
if(!empty($username) && !empty($password)){
$json_array = $userObject->loginUsers($username, $password);
echo json_encode($json_array);
}
?>