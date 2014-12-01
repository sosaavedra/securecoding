<?php

require_once "fpdf/fpdf_protection.php";

class PDF extends FPDF_Protection{

    function Header(){
        $this->SetFont("Arial", "B", 15);
        $this->Cell(0,10,"BankSys", 0, 0, "C", false, "http://192.168.101.1/website");
        $this->Ln();
        $this->Cell(0,10,"Transaction Codes", 0, 0, "C");
        $this->Ln();
    }

    function Footer(){
        $this->SetY(-15);
        $this->SetFont("Arial", "I", 8);
        $this->Cell(0,10,"Munich, Germany. Telephone: +49 1234567890. Email: scteam17@gmail.com", 0, 0, "C");
    }

    function WriteGreetings($clientName){
        $text = "     Welcome to the BankSys family. We're glad that you have trust in us to keep your money safe. ";
        $text .= "Below you can find the security transaction codes you need to perform movements within your account. ";
        $text .= "Please keep this file secure and DO NOT share it with anyone else.";
        $this->SetFont("Arial", "", 12);
        $this->Write(10, "Dear $clientName,");
        $this->Ln();
        $this->Write(5, $text);
    }

    function WriteTanCodes($resultset){
        $x = 20;
        $y = 60;
        $row_height = 5;

        $num_rows = $resultset->num_rows;

        $num_rows1 = floor($num_rows / 4) + ($num_rows % 4);
        $num_rowsN = floor($num_rows / 4);

        $this->WriteTanTable(1, $num_rows1, $resultset, $x, $y, $row_height);
        $x += 43;
        $this->WriteTanTable(26, $num_rowsN, $resultset, $x, $y, $row_height);
        $x += 43;
        $this->WriteTanTable(51, $num_rowsN, $resultset, $x, $y, $row_height);
        $x += 43;
        $this->WriteTanTable(76, $num_rowsN, $resultset, $x, $y, $row_height);
    }

    function WriteTanTable($start, $num_rows, $resultset, $x, $y, $row_height){
        $this->setFillColor(58, 149, 159);
        $this->setTextColor(255);
        $this->setDrawColor(128, 0, 0);
        $this->setLineWidth(.3);
        $this->SetFont("Arial", "B", 7);

        $this->SetXY($x, $y);

        $this->Cell(8, $row_height, "#", 0, 0, "C", true);
        $this->Cell(35, $row_height, "TAN Code", 0, 0, "C", true);

        $y += $row_height;

        $this->SetXY($x, $y);

        $this->setFillColor(224, 224, 224);
        $this->setTextColor(0);
        $this->SetFont("Arial", "", 7);

        $fill = 1;

        for($i = $start; $i < ($start + $num_rows); $i++){
            $row = mysqli_fetch_array ( $resultset );

            $this->Cell(8, $row_height, $i, 0, 0, "R", ($fill % 2));
            $this->Cell(35, $row_height, $row ['code'], 0, 0, "C", ($fill % 2));

            $y += $row_height;

            $this->SetXY($x, $y);
            $fill++;
        }
    }
}

?>
