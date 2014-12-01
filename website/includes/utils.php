<?php

function moneyFormat($amount){
    return number_format($amount, 2, ",", ".") . " €";
}

function createPDF($clientName, $accountNumber, $password, $resultset){
    require_once "classes/pdf.php";
    
    $filename = "tmp_dir/". $accountNumber ."_TanCodes.pdf";

    $pdf = new  PDF();
    $pdf->SetProtection(array('copy', 'print'), $password);
    $pdf->AddPage();
    $pdf->WriteGreetings($clientName);
    $pdf->WriteTanCodes($resultset);
    $pdf->output($filename, "F");

    return $filename;
}

?>
