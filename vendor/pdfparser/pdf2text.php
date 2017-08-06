<?php
 
// Include Composer autoloader if not already done.
include 'vendor/autoload.php';

// Filename
$filename = isset($argv[1])?$argv[1]:'document.pdf';
 
// Parse pdf file and build necessary objects.
$parser  = new \Smalot\PdfParser\Parser();
$pdf     = $parser->parseFile($filename);

// Retrieve all details from the pdf file.
$details = $pdf->getDetails();

echo "Metadata\n\n";

foreach ($details as $property => $value) {
    if (is_array($value)) {
        $value = implode(', ', $value);
    }

    echo $property . ' => ' . $value . "\n";
}

echo "\nText\n\n";
 
$text = $pdf->getText();
echo $text;

