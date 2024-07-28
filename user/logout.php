<?php
require_once '../classes/Authentication.php';

$user = new Authentication();
$user->logout();
header('Location: login.php');
exit();
?>