<?php

function moneyFormat($amount){
    return number_format($amount, 2, ",", ".") . " €";
}

?>
