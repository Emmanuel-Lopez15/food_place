<?php
require 'conexion.php';
require './INVOICE-main/code128.php';

// Generar factura en PDF
        $pdf = new PDF_Code128('P','mm','Letter');
        $pdf->SetMargins(17,17,17);
        $pdf->AddPage();
    
        # Logo de la empresa formato png #
        $pdf->Image('./INVOICE-main/img/logo.png',165,12,35,35,'PNG');
    
        # Encabezado y datos de la empresa #
        $pdf->SetFont('Arial','B',16);
        $pdf->SetTextColor(32,100,210);
        $pdf->Cell(150,10,iconv("UTF-8", "ISO-8859-1",strtoupper("FoodPlace")),0,0,'L');
    
        $pdf->Ln(9);
    
        $pdf->SetFont('Arial','',10);
        $pdf->SetTextColor(39,39,51);
        $pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1","RFC: LASJ058455K80"),0,0,'L');
    
        $pdf->Ln(5);
    
        $pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1","victory, tamaulipas, mexico"),0,0,'L');
    
        $pdf->Ln(5);
    
        $pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1","Restaurant phone: 8765456789"),0,0,'L');
    
        $pdf->Ln(10);

        $pdf->Cell(30,7,iconv("UTF-8", "ISO-8859-1","Reservation date: 29393823"),0,0);

        $pdf->Ln(5);
    
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(116,7,iconv("UTF-8", "ISO-8859-1","Reservation time: 493043"),0,0);
        $pdf->SetTextColor(39,39,51);
        $pdf->Cell(80,7,iconv("UTF-8", "ISO-8859-1",strtoupper("Factura Nro. 003")),0,0,"C");
    
        $pdf->Ln(10);
    
        $pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1","Email: jkldsk@gmail.com"),0,0,'L');
    
        $pdf->Ln(7);
    
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(12,7,iconv("UTF-8", "ISO-8859-1","ATM:"),0,0,'L');
        $pdf->SetTextColor(97,97,97);
        $pdf->Cell(134,7,iconv("UTF-8", "ISO-8859-1","Banamex"),0,0,'L');
        $pdf->SetFont('Arial','B',10);
        $pdf->SetTextColor(97,97,97);
        $pdf->Cell(35,7,iconv("UTF-8", "ISO-8859-1",strtoupper("")),0,0,'C');
    
        $pdf->Ln(10);
    
        $pdf->SetFont('Arial','',10);
        $pdf->SetTextColor(39,39,51);
        $pdf->Cell(13,7,iconv("UTF-8", "ISO-8859-1","Client:"),0,0);
        $pdf->SetTextColor(97,97,97);
        $pdf->Cell(70,7,iconv("UTF-8", "ISO-8859-1","Jesus emmanuel lopez zuñiga"),0,0,'L');
        $pdf->SetTextColor(39,39,51);
        $pdf->Cell(8,7,iconv("UTF-8", "ISO-8859-1","Doc: "),0,0,'L');
        $pdf->SetTextColor(97,97,97);
        $pdf->Cell(50,7,iconv("UTF-8", "ISO-8859-1","DNI: xxxxxxxx"),0,0,'L');
        $pdf->SetTextColor(39,39,51);
        $pdf->Cell(8,7,iconv("UTF-8", "ISO-8859-1","Phone: "),0,0,'L');
        $pdf->SetTextColor(97,97,97);
        $pdf->Cell(35,7,iconv("UTF-8", "ISO-8859-1","8342523826"),0,0);
        $pdf->SetTextColor(39,39,51);
    
        $pdf->Ln(7);
    
        $pdf->SetTextColor(39,39,51);
        $pdf->Cell(6,7,iconv("UTF-8", "ISO-8859-1","Dir:"),0,0);
        $pdf->SetTextColor(97,97,97);
        $pdf->Cell(109,7,iconv("UTF-8", "ISO-8859-1","victory, tamaulipas, mexico"),0,0);
    
        $pdf->Ln(9);
    
        # Tabla de productos #
        $pdf->SetFont('Arial','',8);
        $pdf->SetFillColor(23,83,201);
        $pdf->SetDrawColor(23,83,201);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(90,8,iconv("UTF-8", "ISO-8859-1","Description"),1,0,'C',true);
        $pdf->Cell(15,8,iconv("UTF-8", "ISO-8859-1","Reserve"),1,0,'C',true);
        $pdf->Cell(25,8,iconv("UTF-8", "ISO-8859-1","Price"),1,0,'C',true);
        $pdf->Cell(19,8,iconv("UTF-8", "ISO-8859-1","Offert."),1,0,'C',true);
        $pdf->Cell(32,8,iconv("UTF-8", "ISO-8859-1","Subtotal"),1,0,'C',true);
    
        $pdf->Ln(8);
    
        
        $pdf->SetTextColor(39,39,51);
    
    
    
        /*----------  Detalles de la tabla  ----------*/
        $pdf->Cell(90,7,iconv("UTF-8", "ISO-8859-1","Reserve in la cabñas"),'L',0,'C');
        $pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1","1"),'L',0,'C');
        $pdf->Cell(25,7,iconv("UTF-8", "ISO-8859-1","$ 934798.00"),'L',0,'C');
        $pdf->Cell(19,7,iconv("UTF-8", "ISO-8859-1","$0.00 USD"),'L',0,'C');
        $pdf->Cell(32,7,iconv("UTF-8", "ISO-8859-1","$4939.00"),'LR',0,'C');
        $pdf->Ln(7);
        /*----------  Fin Detalles de la tabla  ----------*/
    
        
        $pdf->SetFont('Arial','B',9);
    
    
        
        # Impuestos & totales #
        $pdf->Cell(100,7,iconv("UTF-8", "ISO-8859-1",''),'T',0,'C');
        $pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",''),'T',0,'C');
        $pdf->Cell(32,7,iconv("UTF-8", "ISO-8859-1","SUBTOTAL"),'T',0,'C');
        $pdf->Cell(34,7,iconv("UTF-8", "ISO-8859-1","+ $84795.00 USD"),'T',0,'C');
    
        $pdf->Ln(7);
    
        
    
        $pdf->Cell(100,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
        $pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
        $pdf->Cell(32,7,iconv("UTF-8", "ISO-8859-1","IVA (16%)"),'',0,'C');
        $pdf->Cell(34,7,iconv("UTF-8", "ISO-8859-1","+ $943849 .00 USD"),'',0,'C');
    
        $pdf->Ln(7);
    
        $pdf->Cell(100,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
        $pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
    
        
    
        $pdf->Cell(32,7,iconv("UTF-8", "ISO-8859-1","TOTAL TO PAY"),'T',0,'C');
        $pdf->Cell(34,7,iconv("UTF-8", "ISO-8859-1","$4839.00 USD"),'T',0,'C');
    
        $pdf->Ln(7);
    
        $pdf->Ln(12);
        $pdf->Ln(12);
        $pdf->Ln(12);
    
    
        $pdf->SetFont('Arial','',9);
    
        $pdf->SetTextColor(39,39,51);
        $pdf->MultiCell(0,9,iconv("UTF-8", "ISO-8859-1","*** Reservation prices include taxes. In order to make a claim or return you must present this invoice. ***"),0,'C',false);
    
        $pdf->Ln(9);
    
        # Codigo de barras #
        $pdf->SetFillColor(39,39,51);
        $pdf->SetDrawColor(23,83,201);
        $pdf->Code128(72,$pdf->GetY(),"COD000001V0034",70,20);
        $pdf->SetXY(12,$pdf->GetY()+21);
        $pdf->SetFont('Arial','',12);
        $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","COD000001V0001"),0,'C',false);