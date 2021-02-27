<?php

$fecha=$_POST['fecha'];
$establecimiento = $_POST['establecimiento'];
$punto_emision = $_POST['punto_emision'];
$sec_factura = $_POST['sec_factura'];
$cliente = $_POST['cliente'];
$empresa = $_POST['empresa'];
$subtotal = $_POST['subtotal'];
$impuestos = $_POST['iva'];
$total = $_POST['total'];
$productos = json_decode($_POST["listaProductos"], true);

// $establecimiento = 'A';
// $punto_emision = 'A';
// $empresa ='A';
// $cliente = 'A';
// $subtotal = 'A';
// $impuestos = 'A';
// $total = 'A';

// foreach ($productos as $key => $value) {
//   echo $value['ID'];
// }

require_once('C:\xampp\htdocs\proyecto_nextcode\tcpdf\tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('me');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setFontSubsetting(true);
$pdf->SetFont('helvetica', '', 12, '', true);
$pdf->AddPage();
$html = <<<EOD
<p style="text-align: center;"><span style="font-size: 18pt;"><strong>Detalle Factura</strong></span></p>
<p style="text-align: center;"><span style="font-size: 18pt;"><strong>$establecimiento</strong></span></p>
<p style="text-align: right;"> Fecha: $fecha</p>
<p style="text-align: left;"> Punto Emision: $punto_emision</p>
<p style="text-align: left;"> Cliente: $cliente</p>
<p style="text-align: left;"> Empresa: $empresa</p>
<p></p>
EOD;
$pdf->writeHTML($html, false, false, false, false, '');

$html1 = <<<EOD
<p style="text-align: center;"><span style="font-size: 14pt;"><strong>Productos</strong></span></p>
<p style="text-align: center;">Descripcion--------------------Cantidad-------------------Precio</p>
<p style="text-align: center;"></p>
EOD;
$pdf->writeHTML($html1, false, false, false, false, '');

foreach ($productos as $key => $value) {
$html2 = <<<EOF
<table style="font-size:16px; text-align:right;width:400px;margin: auto;padding: 10px">
<tr>
<td>$value[DESCRIPCION]</td>
<td>$value[CANTIDAD]</td>
<td>$value[PRECIO]</td>
</tr>
</table>
EOF;
$pdf->writeHTML($html2, false, false, false, false, '');
}

$html4 = <<<EOD
<br><br><br>
<p style="text-align: left;"> Subtotal: $subtotal</p>
<p style="text-align: left;"> Impuestos: $impuestos</p>
<p style="text-align: left;"> Total: $total</p>
EOD;
$pdf->writeHTML($html4, false, false, false, false, '');

$pdf->Output('detalle_factura.pdf', 'I');
