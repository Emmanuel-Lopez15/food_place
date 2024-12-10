<?php

require 'conexion.php';
require './INVOICE-main/code128.php';
session_start();


require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';
require __DIR__ . '/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    
    //PDF---------------------------------------------------------------------------------------------------

    if (!isset($_SESSION['reservation_id'])) {
        // Si no hay un ID de reserva, redirige al home
        header("Location: home.php");
        exit();
    }
    
    $reservationId = $_SESSION['reservation_id']; // Recuperar el ID de la reserva
    
    // Consulta para obtener los datos de la reserva
    $query = "SELECT * FROM reserva WHERE id = $reservationId";
    $result = $conexion->query($query);
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    
        // Extraer datos de la reserva
        $restaurantName = $data['name_restaurant'];
        $city = $data['city_restaurant'];
        $state = $data['state_restaurant'];
        $country = $data['country_restaurant'];
        $restaurantPhone = $data['phone_restaurant'];
        $clientName = $data['name_reserve']; //Nombres del cliente
        $clientLastName = $data['lastname_reserve']; //Nombres del apellido del cliente
        $clientPhone = $data['phone_reserve']; //Numero del cliente
        $clientEmail = $data['email_reserve']; //Mail del cliente
        $reservationDate = $data['date_reserve'];
        $reservationTime = $data['time_reserve'];
        $totalCost = $data['cost_restaurant'];
    
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
    
        $pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1","$city, $state, $country"),0,0,'L');
    
        $pdf->Ln(5);
    
        $pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1","Restaurant phone: $restaurantPhone"),0,0,'L');
    
        $pdf->Ln(10);

        $pdf->Cell(30,7,iconv("UTF-8", "ISO-8859-1","Reservation date: $reservationDate"),0,0);

        $pdf->Ln(5);
    
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(116,7,iconv("UTF-8", "ISO-8859-1","Reservation time: $reservationTime"),0,0);
        $pdf->SetTextColor(39,39,51);
        $pdf->Cell(80,7,iconv("UTF-8", "ISO-8859-1",strtoupper("Factura Nro. $reservationId")),0,0,"C");
    
        $pdf->Ln(10);
    
        $pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1","Email: $clientEmail"),0,0,'L');
    
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
        $pdf->Cell(70,7,iconv("UTF-8", "ISO-8859-1","$clientName"." "."$clientLastName"),0,0,'L');
        $pdf->SetTextColor(39,39,51);
        $pdf->Cell(8,7,iconv("UTF-8", "ISO-8859-1","Doc: "),0,0,'L');
        $pdf->SetTextColor(97,97,97);
        $pdf->Cell(50,7,iconv("UTF-8", "ISO-8859-1","DNI: xxxxxxxx"),0,0,'L');
        $pdf->SetTextColor(39,39,51);
        $pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1","Phone: "),0,0,'L');
        $pdf->SetTextColor(97,97,97);
        $pdf->Cell(35,7,iconv("UTF-8", "ISO-8859-1","$clientPhone"),0,0);
        $pdf->SetTextColor(39,39,51);
    
        $pdf->Ln(7);
    
        $pdf->SetTextColor(39,39,51);
        $pdf->Cell(6,7,iconv("UTF-8", "ISO-8859-1","Dir:"),0,0);
        $pdf->SetTextColor(97,97,97);
        $pdf->Cell(109,7,iconv("UTF-8", "ISO-8859-1","$city, $state, $country"),0,0);
    
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
        $pdf->Cell(90,7,iconv("UTF-8", "ISO-8859-1","Reserve in $restaurantName "),'L',0,'C');
        $pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1","1"),'L',0,'C');
        $pdf->Cell(25,7,iconv("UTF-8", "ISO-8859-1","$".$totalCost),'L',0,'C');
        $pdf->Cell(19,7,iconv("UTF-8", "ISO-8859-1","$0.00 USD"),'L',0,'C');
        $pdf->Cell(32,7,iconv("UTF-8", "ISO-8859-1","$".$totalCost),'LR',0,'C');
        $pdf->Ln(7);
        /*----------  Fin Detalles de la tabla  ----------*/
    
        
        $pdf->SetFont('Arial','B',9);
    
    
        
        # Impuestos & totales #
        $pdf->Cell(100,7,iconv("UTF-8", "ISO-8859-1",''),'T',0,'C');
        $pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",''),'T',0,'C');
        $pdf->Cell(32,7,iconv("UTF-8", "ISO-8859-1","SUBTOTAL"),'T',0,'C');
        $pdf->Cell(34,7,iconv("UTF-8", "ISO-8859-1","+ $".$totalCost ."USD"),'T',0,'C');
    
        $pdf->Ln(7);
    
        $iva= $totalCost * 0.16;
    
        $pdf->Cell(100,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
        $pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
        $pdf->Cell(32,7,iconv("UTF-8", "ISO-8859-1","IVA (16%)"),'',0,'C');
        $pdf->Cell(34,7,iconv("UTF-8", "ISO-8859-1","+ $".$iva ."USD"),'',0,'C');
    
        $pdf->Ln(7);
    
        $pdf->Cell(100,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
        $pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
    
        $totalPagar = $iva + $totalCost;
    
        $pdf->Cell(32,7,iconv("UTF-8", "ISO-8859-1","TOTAL TO PAY"),'T',0,'C');
        $pdf->Cell(34,7,iconv("UTF-8", "ISO-8859-1","$".$totalPagar."USD"),'T',0,'C');
    
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
        $pdf->Code128(72,$pdf->GetY(),"COD000001V00$reservationId",70,20);
        $pdf->SetXY(12,$pdf->GetY()+21);
        $pdf->SetFont('Arial','',12);
        $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","COD000001V00$reservationId"),0,'C',false);
        
        //Guardo el PFD
        $pdfFileName = "./FACTURAS/Factura_Reserva_$reservationId.pdf";
        $pdf->Output('F', $pdfFileName); // Guardar en una carpeta


        //Enviar correo
        if (!$clientEmail || !$clientName) {
            throw new Exception('Datos inválidos.');
        }
    
        // servidor SMTP
        $mail->isSMTP(); 
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true; 
        $mail->Username = 'foodplace.2005@gmail.com'; 
        $mail->Password = 'ynwq izyg qlev rekl'; // Contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 587;
    
        // correo
        $mail->setFrom('foodplace.2005@gmail.com', 'FoodPlace');
        $mail->addAddress($clientEmail, $clientName); 
        $mail->Subject = "Reserve to the name of $clientName";
        $mail->Body = "Hi $clientName "." "."$clientLastName ,\n\nWe've received your reserve. And we have attached the bill for your reservation.\n\nGreeting,\nFoodPlace.";
        $mail->addAttachment($pdfFileName);
    
        $mail->send();
        echo '¡Correo enviado correctamente!';

    } else {
        $_SESSION['error_message'] = "No se encontró la reservación";
        header("Location: home.php");
        exit();
    }


    //------------------------------------------------------------------------------------------------------
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
?>

