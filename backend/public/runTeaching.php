<?php

require ('learnModelClass.php');
require ('connectionClass.php');

$model = new learnModelClass();
$connection = new connectionClass();

// Получаю данные об обучении человеком
$items = $connection->getAll("SELECT `human_result` as `Grade`, `review_text` as `Review` FROM `reviews` WHERE `human_result` != 3 AND `teached` != 1");

if(count($items) != 0){
    // Преобразую в JSON
    $json_items = json_encode($items, JSON_UNESCAPED_UNICODE);
    $json_items = str_replace('\'', ' ', $json_items);

    $params[] = 1;
// Ставлю флаг занятости модели
    $update = $connection->add("UPDATE `model_status` SET `teach_in_progress` = ? WHERE `id` = 1 ;", $params);

// Отправляю в класс на обучение
    $model_result = $model->runTeaching($json_items);

// Если модель ответила 1 - то она обучилась
    if ($model_result == 1){
        // Параметры обновления данных в БД
        $upd_params[] = 0;
        $upd_item_params[] = 1;

        // Меняем статус модели
        $update = $connection->add("UPDATE `model_status` SET `teach_in_progress` = ? WHERE `id` = 1 ;", $upd_params);

        // Проходимся по таблице отзывов и меняем флаг обучения, чтобы снова не накармливать модель
        $items = $connection->getAll("SELECT `id`, `human_result` as `Grade`, `review_text` as `Review` FROM `reviews` WHERE `human_result` != 3 AND `teached` != 1");

        foreach ($items as $item){
            $update_item = $connection->add("UPDATE `reviews` SET `teached` = 1 WHERE `id` = ".$item['id'].";");
        }
    }
}else{
    echo 'nothing';
}