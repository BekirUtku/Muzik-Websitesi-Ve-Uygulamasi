<?php

$baglan = mysqli_connect("localhost","root","","spotify");
if(!$baglan){
    die("connection failed: ".mysqli_connect_error());
}
else {
    echo "Bağlandııı";
}
?>