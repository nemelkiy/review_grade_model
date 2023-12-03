<?php

class learnModelClass
{
    public function sendGradeRequest($gradeText)
    {

        $review_text = str_replace(' ', '%20', $gradeText);
        $url = 'lab-get-review:5000/get_grade?review='.$review_text;

        $headers = ['Content-Type: application/json']; // создаем заголовки

        $curl = curl_init(); // создаем экземпляр curl

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_POST, false); //
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_VERBOSE, true);

        return curl_exec($curl);
    }
}