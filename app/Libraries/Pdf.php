<?php

namespace App\Libraries;

ini_set('max_execution_time', 0);
ini_set('memory_limit', '900M');

require_once APPPATH . 'ThirdParty/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf extends DOMPDF {

    // генериране на PDF
    // =================
    public function create($view, $oferName = 'pdf') {

        $options = new Options();

        $options -> set('defaultMediaType', 'all');
        $options -> set('isFontSubsettingEnabled', true);
        $options -> set('isRemoteEnabled', TRUE);
        $options -> set('isHtml5ParserEnabled', true);
        $options -> set('isJavascriptEnabled', TRUE);
        $options -> set('isPhpEnabled', TRUE);
        $options -> set('fontHeightRatio', 1);
        $options -> set('dpi', 100);

        $dompdf = new Dompdf($options);

        $dompdf -> set_protocol(isset($_SERVER["HTTPS"]) ? "https://" : "http://");
        $dompdf -> set_host($_SERVER["SERVER_NAME"]);

        $dompdf -> setPaper('A4', 'portret');

        $css = '<style>' . file_get_contents(base_url() . '/assets/css/printPreview/dompdf.css') . '
                        
		</style>';

        $js = '	<script type="text/php">
                if ( isset($pdf) ) {
                        $x = 500;
                        $y = 810;
                        $text = "Page {PAGE_NUM} - {PAGE_COUNT}";
                        $font = $fontMetrics->get_font("Helvetica", "bold");
                        $size = 10;
                        $color = array(0,0,0);
                        $word_space = 0.0;  //  default
                        $char_space = 0.0;  //  default
                        $angle = 0.0;   //  default
                        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
                }
  
	</script>';

        //~<body[^>]*>(.*?)</body>~si'
        //'{<div\s+id="wrapper"\s*>((?:(?:(?!<div[^>]*>|</div>).)++|<div[^>]*>(?1)</div>)*)</div>}si' 
        // започване от този div таг
        if (preg_match('{<div\s+id="printArea"\s*>((?:(?:(?!<div[^>]*>|</div>).)++|<div[^>]*>(?1)</div>)*)</div>}si', $view, $body)) {
            // $body[1] = str_replace("trShow_dostavchik no-print", "", $body[1]);
            //$dompdf -> loadHtml($css . $body[1] . $js);
        }

        $dompdf -> loadHtml($css . $view . $js);
        $dompdf -> render();
        $dompdf -> stream($oferName, array("Attachment" => false));

        exit(0);

        //header('Content-Type: application/octet-stream; charset=utf-8');
    }
}
