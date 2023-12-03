<?php

require ('connectionClass.php');

$connection = new connectionClass();

$item = $connection->getRow("SELECT * FROM `model_status` ");

if ($item['teach_in_progress'] == 1){
    echo 'true';
}else{
    echo 'false';
}
