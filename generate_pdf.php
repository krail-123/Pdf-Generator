<?php
// Include autoloader
require_once 'vendor/autoload.php';

// Use Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;
use Picqer\Barcode\BarcodeGeneratorHTML;

// Get form data
$sap_weighment_no = isset($_POST['sap_weighment_no']) ? $_POST['sap_weighment_no'] : '';
$vehicle_no = isset($_POST['vehicle_no']) ? $_POST['vehicle_no'] : '';
$product = isset($_POST['product']) ? $_POST['product'] : '';
$po_no = isset($_POST['po_no']) ? $_POST['po_no'] : '';
$destination = isset($_POST['destination']) ? $_POST['destination'] : '';
$transporter = isset($_POST['transporter']) ? $_POST['transporter'] : '';
$first_weight = isset($_POST['first_weight']) ? $_POST['first_weight'] : '';
$second_weight = isset($_POST['second_weight']) ? $_POST['second_weight'] : '';
$net_weight = isset($_POST['net_weight']) ? $_POST['net_weight'] : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';

// Generate barcode images for SAP weighment number and vehicle number
$generator = new BarcodeGeneratorHTML();
$sapBarcodeImage = $generator->getBarcode($sap_weighment_no, $generator::TYPE_CODE_128);
$vehicleBarcodeImage = $generator->getBarcode($vehicle_no, $generator::TYPE_CODE_128);

// HTML content for PDF
$html = "
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<title>PDF Document</title>
<style>
    body {
        font-family: Arial, sans-serif;
    }
    h2 {
        text-align: center;
    }
    table {
        width: 100%;
        
    }
    th, td {
        padding: 10px;
        text-align: left;
       
    }
    th {
      
    }
    .barcode {
        text-align: center;
    }
</style>
</head>
<body>
<h2>DALMIA CEMENT(BHARAT) LTD. [1301]</h2>
<center><h4>Tamil Nadu</h4>
<h4>WEIGHTMENT CERTIFICATE</h4></center>
<table>
    <tr>
        <th>SAP WEIGHMENT NO:</th>
        <td>$sap_weighment_no</td>
        <td class='barcode'>$sapBarcodeImage</td>
    </tr>
    <tr>
        <th>VEHICLE NO:</th>
        <td>$vehicle_no</td>
        <td class='barcode'>$vehicleBarcodeImage</td>
    </tr>
    <tr>
        <th>PRODUCT:</th>
        <td>$product</td>
    </tr>
    <tr>
        <th>PO NO:</th>
        <td>$po_no</td>
    </tr>
    <tr>
        <th>DESTINATION:</th>
        <td>$destination</td>
    </tr>
    <tr>
        <th>TRANSPORTER:</th>
        <td>$transporter</td>
    </tr>
    <tr>
        <th>FIRST WEIGHT:</th>
        <td>$first_weight</td>
    </tr>
    <tr>
        <th>SECOND WEIGHT:</th>
        <td>$second_weight</td>
    </tr>
    <tr>
        <th>NET WEIGHT:</th>
        <td>$net_weight</td>
    </tr>
    <tr>
        <th>DATE:</th>
        <td>$date</td>
    </tr>
</table>
<h5>Signature of Operator</h5>...............................<h5>Signature of Driver</h5>


</body>
</html>
";

// Create a new instance of Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

// Load HTML content
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render PDF (optional: to display errors, you can add ->setOptions(['isPhpEnabled' => true]))
$dompdf->render();

// Output PDF to browser
$dompdf->stream('weighment_certificate.pdf', ['Attachment' => false]);
