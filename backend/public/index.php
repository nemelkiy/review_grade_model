<?php

require ('connectionClass.php');

$connection = new connectionClass();



echo <<<'EOF'

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа «Программное обеспечение систем искусственного интеллекта»</title>
    <link rel="stylesheet" href="css/main.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/app.js"></script>
</head>
<script >
    function status_checker(){
        $.ajax({
            url: "statusChecker.php",
            error: function(){
                //Error code
            },
            success: function(data){
                if (data === 'true'){
                    console.log('Still teaching.... ')
                    $( ".teach_status" ).css("display", "flex")
                }else{
                    console.log('teaching finished')
                    $( ".teach_status" ).css("display", "none")
                }
            }
        })
        
        setInterval(function() {
            $.ajax({
            url: "statusChecker.php",
            error: function(){
                //Error code
            },
            success: function(data){
                if (data === 'true'){
                    console.log('Still teaching.... ')
                    $( ".teach_status" ).css("display", "flex")
                }else{
                    console.log('teaching finished')
                    $( ".teach_status" ).css("display", "none")
                }
            }
        })
        }, 5000)
    }
    
    status_checker();
</script>
<body>
    <div class="teach_status">
       <div class="lps_wrap">
       <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
       
       </div>
        Модель обучается
    </div>
    <div class="content_wrapper">
    <div class="headerline">
        <div class="head_container">
        <img src="images/logo.svg" alt="" class="logo">
        <div class="nameBlock">
            <h3>Толов Александр Николаевич</h3>
            <h4>Лабораторная работа по предмету «Программное обеспечение систем искусственного интеллекта»</h4>
        </div>
        </div>
    </div>
        <div class="main_block">
            <div class="hd_line">
                <h3>Для оценки отзыва моделью введите текст в форму ниже</h3>
            </div>
            <div class="from_block">
                <form class="review_form">
                    <textarea class="textarea_txt" id="review" name="review" ></textarea>
                    <div class="submit_button">
                    Отправить отзыв
                    </div>
                </form>
                    <div class="teach_model">
                    Обучить модель
                    </div>
             </div>
             
             <div class="review_block">
             
                
EOF;
           $reviews =  $connection->getAll("SELECT * FROM `reviews` WHERE `teached` != 1 ORDER BY `id` DESC");

           if(count($reviews) > 0) {
               echo '<h3>Список отзывов:</h3>';
               echo '
                   <div class="review_line">
                   <div class="review_text">
                   Текст отзыва
                   </div>
                   <div class="ml_grade" style="background-image: none">
                   Оценка модели
                    </div>
                    <div class="ml_grade" style="background-image: none">
                   Оценка человека
                    </div>
                   </div>
               ';

               foreach ($reviews as $review){
                   if ($review['teached'] == 0){
                       echo '
                        <div class="review_line">
                            <div class="review_text">
                                '.$review['review_text'].'
                            </div>
                            ';
                            if ($review['ml_result'] == 1){
                                echo '
                                <div class="ml_good">
                                    
                                </div>
                                ';
                            }else{
                                echo '
                                <div class="ml_bad">
                                    
                                </div>
                                ';
                            }
                            echo '
                                 <div class="user_grade" review_id="'.$review['id'].'">
                                   ';
                                if ($review['human_result'] == 3){
                                    $opacity = 0.4;
                                    $bGrade = 0.4;
                                    $gGrade = 0.4;
                                }else if ($review['human_result'] == 0){
                                    $bGrade = 1;
                                    $gGrade = 0.4;
                                }else{
                                    $bGrade = 0.4;
                                    $gGrade = 1;
                                }
                                echo '
                                    <div class="bad_human" style="opacity: '.$bGrade.'">
                                    
                                    </div>
                                    <div class="good_human" style="opacity: '.$gGrade.'">
                                    
                                    </div>
                                ';

                                
                            echo '
                                </div>
                            ';
                        echo '
                        </div>
                ';
                   }
               }
           }else{
               echo '<h3>Вы не отправляли отзывы на оценку моделью</h3>';
           }


$reviews =  $connection->getAll("SELECT * FROM `reviews` WHERE `teached` != 0 ORDER BY `id` DESC");

if(count($reviews) > 0) {
    echo '<h3>Список отзывов которыми обучили модель:</h3>';
    echo '
                   <div class="review_line">
                   <div class="review_text">
                   Текст отзыва
                   </div>
                   <div class="ml_grade" style="background-image: none">
                   Оценка модели
                    </div>
                    <div class="ml_grade" style="background-image: none">
                   Оценка человека
                    </div>
                   </div>
               ';

    foreach ($reviews as $review){
        if ($review['teached'] == 1){
            echo '
                        <div class="review_line">
                            <div class="review_text">
                                '.$review['review_text'].'
                            </div>
                            ';
            if ($review['ml_result'] == 1){
                echo '
                                <div class="ml_good">
                                    
                                </div>
                                ';
            }else{
                echo '
                                <div class="ml_bad">
                                    
                                </div>
                                ';
            }
            echo '
                                 <div class="user_grade" review_id="'.$review['id'].'">
                                   ';
            if ($review['human_result'] == 3){
                $opacity = 0.4;
                $bGrade = 0.4;
                $gGrade = 0.4;
            }else if ($review['human_result'] == 0){
                $bGrade = 1;
                $gGrade = 0.4;
            }else{
                $bGrade = 0.4;
                $gGrade = 1;
            }
            echo '
                                    <div class="bad_human_2" style="opacity: '.$bGrade.'">
                                    
                                    </div>
                                    <div class="good_human_2" style="opacity: '.$gGrade.'">
                                    
                                    </div>
                                ';


            echo '
                                </div>
                            ';
            echo '
                        </div>
                ';
        }
    }
}else{
    echo '<h3>Вы не отправляли отзывы дообучение модели</h3>';
}

echo <<<'EOF'
                
             </div>
        </div>
        
    </div>
    </body>
    </html>

    <script >
    // Обработка события нажатия на кнопку
    $( ".submit_button" ).click(function() {
      let parent = this.parentElement
      let data = parent.getElementsByClassName('textarea_txt')
      let review = $(data).val()
      
      if (review.length === 0){
          alert('Вы не написали текст отзыва')
          return 0
      }
      
      let request = $.ajax({
          url: "getReviewGrade.php",
          method: "POST",
          data: { review : review },
          dataType: "html"
      })
        
      request.done(function( msg ) {
          location.reload()
      });
      
    });
    
    // Плохая оценка юзверя
    $( ".bad_human" ).click(function() {
      let parent = this.parentElement
      let id = parent.getAttribute('review_id')
      
      let request = $.ajax({
          url: "setReviewGrade.php",
          method: "POST",
          data: { review_id : id, user_grade: 0 },
          dataType: "html"
      })
        
      request.done(function( msg ) {
          location.reload()
      });
      
    });
    
    // Хорошая оценка юзверя
    $( ".good_human" ).click(function() {
      let parent = this.parentElement
      let id = parent.getAttribute('review_id')
      
      let request = $.ajax({
          url: "setReviewGrade.php",
          method: "POST",
          data: { review_id : id, user_grade: 1 },
          dataType: "html"
      })
        
      request.done(function( msg ) {
        location.reload()
      });
      
    });
    
    // обучение модели
    $( ".teach_model" ).click(function() {
      let parent = this.parentElement
      let id = parent.getAttribute('review_id')
      
      let request = $.ajax({
          url: "runTeaching.php",
          method: "POST",
          data: { review_id : id, user_grade: 1 },
          dataType: "html",
          timeout: 1500000, // Ставлю таймат побольше для дебага
      })
        
      request.done(function( msg ) {
        console.log(msg)
      });
      
    });
    
    </script>


EOF;
