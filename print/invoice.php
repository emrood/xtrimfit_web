<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 6/12/2021
 * Time: 3:49 PM
 */

require_once('../libraries/tcpdf/tcpdf.php');

require_once('../db/User.php');
require_once('../db/Database.php');
require_once('../db/Customer.php');
require_once('../db/Rooms.php');
require_once('../db/Pricing.php');
require_once('../db/Invoice.php');
require_once('../db/Reservation.php');
require_once('../db/Constants.php');

//use Database;
//use User;


session_start();


if (isset($_GET['invoice_id'])) {
    $invoice = Invoice::getById($_GET['invoice_id']);
    $customer = Customer::getById($invoice['customer_id']);
    $pricing = Pricing::getById($invoice['pricing_id']);
    $total = number_format($invoice['total'], 2, '.', ',');
    $from = date('d/m/Y', strtotime($invoice['from_date']));
    $to = date('d/m/Y', strtotime($invoice['to_date']));
//    var_dump($invoice);
//    die();
    $status = '';

    if($invoice['status'] === 'Paid'){
        $status = '<img src="../images/paid_stamp.png" style="width: 150px; height: auto;"/>';
    }

}

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($customer['last_name'] . ' ' . $customer['first_name']);
$pdf->SetTitle($invoice['invoice_number']);
$pdf->SetSubject($pricing['name']);
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData('..\images\logo.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
//$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

// Set some content to print
$html = <<<EOD
    <div class="content">
       
            <div style="display: flex; flex-direction: row;">
                       <img src="../images/logo.gif" style="width: 120px; height: auto;"  class="img-fluid w-25" alt="">
                      
                
                <div style="display: inline-block; width: 60%; text-align: right;">
                    Invoice # $invoice[invoice_number]
                </div>
                <div style="display: inline-block; width: 60%; text-align: right; margin-top: -50px;">
                    $customer[last_name] $customer[first_name]
                </div>
            </div>
      
        <div class="invoice-body">
            <div class="row">
                                        <div class="col-sm-12">
                                            <h5>Summary</h5>
                                            <div class="table-responsive-sm">
                                                <table class="table table-striped" style="border-collapse: collapse; border-top: 1px solid grey;">
                                                    <thead style="border-collapse: collapse; border-top: 1px solid black;">
                                                        <tr>
                                                            <th class="text-center" style="width: 40%; text-align: left;" scope="col">Plan</th>
                                                            <th class="text-center" style="width: 40%; text-align: center;" scope="col">Periode</th>
                                                            <th class="text-center" style="width: 20%; text-align: right;" scope="col">Price</th>
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody style="border-collapse: collapse; border-top: 1px solid black;">
                                                        <tr style="border-top: 1px solid black;">
                                                            <td class="text-center" style="width: 40%; text-align: left;">$pricing[name]</td>
                                                            <td class="text-center" style="width: 40%; text-align: center;">$from au $to</td>
                                                            <td class="text-center" style="width: 20%; text-align: right;">$total $</td>
                                                        </tr>
                                                    </tbody>
                                                    
                                                </table>
                                            </div>
                                           
                                        </div>
                                        <div></div>
                                        <div>
                                            <table class="table table-striped" style="border-collapse: collapse; border-top: 1px solid grey;">
                                                <tbody>
                                                    <tr>
                                                        <th style="width: 70%;">Total</th>
                                                        <th style="width: 30%; text-align: right;">$total $</th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div>
                                            $status
                                        </div>
                                       
                                        <div>
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>
                                                        <th style="width: 60%;"></th>
                                                        <th style="border-collapse: collapse; border-top: 1px solid grey; width: 40%; text-align: right;"></th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <b class="text-danger">Notes:</b>
                                            <p>$invoice[comment].</p>
                                        </div>
                                        
                </div>
        </div>
    </div>
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($invoice['invoice_number'].'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+