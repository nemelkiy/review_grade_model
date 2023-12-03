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
<body>
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
             </div>
             
             <div class="review_block">
             
                
EOF;
           $reviews =  $connection->getAll("SELECT * FROM `reviews`");

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
                                 <div class="user_grade">
                                   ';
                                if ($review['human_result'] == 3){
                                    $opacity = 0.6;
                                }else{
                                    $opacity = 1;
                                }

                                
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
    </script>
EOF;
