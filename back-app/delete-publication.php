<?php
//Connect Database
$connection = mysqli_connect('localhost','root','','public_teacher');
if($connection){
    echo "we are connected";
}else{
    die('Database Connection fail');
}
$pub_id = $_REQUEST['pub_id'];
//Delete selected id
$sql = "delete from publication where pub_id = $pub_id";
$result = mysqli_query($connection,$sql);

//Java Script show all available customers
if($result){
echo "<script type = 'text/JavaScript'>";
echo "alert('Delete Succesfully');";
echo "window.location = '/pub_teacher/front-app/user-role-index/teacher/public.php'";
echo "</script>";
}

?>