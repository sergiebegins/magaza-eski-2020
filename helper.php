<?php
function result_array($result)
{
    while ($k=$result -> fetch_array(MYSQLI_ASSOC)){
        $newArr [] = $k;
    }

    if(empty($newArr)){
        return null;
    }else{
        return $newArr;
    }

}