<?php
$review_text = $_POST['review'];

require ('learnModelClass.php');
require ('connectionClass.php');

$model = new learnModelClass();
$connection = new connectionClass();

$ml_result = $model->sendGradeRequest($review_text);

$params[] = $review_text;
$params[] = (int)$ml_result;
$params[] = 3;

$connection->add("INSERT INTO `reviews` SET `review_text` = ?, ml_result = ?, human_result = ?", $params);

echo $ml_result;