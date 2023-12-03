<?php
$review_id = $_POST['review_id'];
$user_grade = $_POST['user_grade'];

require ('connectionClass.php');

$connection = new connectionClass();

$params[] = $user_grade;
$params[] = $review_id;

$update = $connection->add("UPDATE `reviews` SET `human_result` = ? WHERE `id` = ? ;", $params);

echo $update;