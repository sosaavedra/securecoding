<?php

function moneyFormat($amount){
    return number_format($amount, 2, ",", ".") . " €";
}

function createPDF($clientName, $accountNumber, $resultset){
    require_once "classes/pdf.php";

    $pdf = new  PDF();
    $pdf->SetProtection(array('copy', 'print'), "123456");
    $pdf->AddPage();
    $pdf->WriteGreetings($clientName);
    $pdf->WriteTanCodes($resultset);
    $pdf->output("tmp_dir/". $accountNumber ."_TanCodes.pdf", "F");
}

?>
