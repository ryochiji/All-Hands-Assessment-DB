<?php

$conn  = new mysqli('localhost', 'root', '', 'assessments');
if ($conn->connect_error){
    echo 'fail';
}else{
    echo 'connected';
}

$conn->close();

?>
