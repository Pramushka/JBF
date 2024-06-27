<?php 

date_default_timezone_set('Asia/Colombo');

//  DB credentials.
// define('DB_HOST','localhost');
// define('DB_USER','root');
// define('DB_PASS','');
// define('DB_NAME','elms');
//  Establish database connection.
// try
// {
// $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
// }
// catch (PDOException $e)
// {
// exit("Error: " . $e->getMessage());
// }
?>

<?php
$dbhost="localhost";
$dbuser="root";
$dbpass="";
$dbname="jobforce";

$conn=mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
 if (!$conn) 
 {
     die("Connection Failed".mysqli_connect_error());
 }
 else
 {
    //  echo "<span style='background-color:green ; width:100%;color:white; display:block;padding:25px 0; transition:ease 0.5s; text-align:center; font-weight:bold;'>Connection Successful</span>";
 }
?>
<!-- <style>
    span:hover
    {
        cursor:pointer;
        transition:ease 0.5s;
        background-color: red !important;
        color: black !important;
    }
</style>  -->