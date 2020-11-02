<?php

     function curlPost($validUrl,$content){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$validUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS,
//        "postvar1=value1&postvar2=value2&postvar3=value3");


        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($content));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        curl_close ($ch);

        return $server_output;
    }
