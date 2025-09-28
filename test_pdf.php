<?php
require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('<h1>Test PDF</h1><p>Si tu vois ce PDF, tout fonctionne !</p>');
$mpdf->Output();
