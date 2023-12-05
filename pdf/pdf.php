<?php
require '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

if (isset($_GET['submit'])) {
    $reportType = $_GET['report_type'];
    $reportData = [];

    include('../conn.php');

    switch ($reportType) {
        case 'all':
            $whereConditions = [];
            $query = "SELECT * FROM orders";

            $result = $con->query($query);

            while ($row = $result->fetch_assoc()) {
                // Convert image to base64 and add to the row
                $path = '../images/updated.png'; // Adjust the path to your image
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $row['image_base64'] = $logo;

                $reportData[] = $row;
            }
            break;
    }

    $con->close();

    ob_start();

    // Include your HTML template, and add an image tag
    include 'pdfFile.php';

    $html = ob_get_clean();

    $dompdf->loadHtml($html);

    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();

    $dompdf->stream('document.pdf', ['Attachment' => false]);
} else {
    die("Form not submitted");
}
?>
