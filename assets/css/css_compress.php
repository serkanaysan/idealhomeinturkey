<?php
ob_start('ob_gzhandler');
header("Content-type: text/css;");
header('Cache-Control: public');
//header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');

echo file_get_contents('https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&subset=latin-ext');

// Libraries
include('library/libraries.bootstrap.css');
include('library/libraries.fa.css');
include('library/sweetalert.css');

// General css codes.
include('default.default.css');

// Responsives
include('responsive/responsive.lg.css');
include('responsive/responsive.md.css');
include('responsive/responsive.sm.css');
include('responsive/responsive.xs.css');
include('responsive/responsive.xxs.css');

$LastCss = ob_get_clean();

// Compress
$LastCss = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $LastCss);
$LastCss = str_replace(': ', ':', $LastCss);
$LastCss = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $LastCss);

echo $LastCss;
// Create combined.css
$CreateCombined = fopen('combined.css','w+');
fwrite($CreateCombined,$LastCss);
fclose($CreateCombined);
?>