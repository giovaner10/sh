<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('create_pdf')) {
    /**
     * Gerar PDF
     * @param  String $html_data View que vai compor o PDF
     * @param  String $file_name Nome do arquivo
     */
    function create_pdf($html_data, $file_name = "") {
        if ($file_name == "") {
            $file_name = 'meu-pdf-' . date('d-m-Y');
        }
        require 'MPDF54/mpdf.php';
        $mypdf = new mPDF();
        $mypdf->SetDisplayMode('fullpage');
        $hyphenate->mypdf = true;
		$mypdf->KeepColumns = true;
        $mypdf->WriteHTML($html_data);
        $mypdf->Output($file_name . '.pdf', 'D');
    }

    /**
     * Gerar PDF com colunas
     * @param  String $html_data View que vai compor o PDF
     * @param  String $file_name Nome do arquivo
     */
    function create_pdf_col($html_data, $file_name = "") {
        if ($file_name == "") {
            $file_name = 'meu-pdf-' . date('d-m-Y');
        }
        require 'MPDF54/mpdf.php';
        $mypdf = new mPDF();
        $mypdf->SetDisplayMode('fullpage');
        $hyphenate->mypdf = true;
        $mypdf->KeepColumns = true;
        $mypdf->SetColumns(2,'J',5);
        $mypdf->WriteHTML($html_data);
        $mypdf->Output($file_name . '.pdf', 'D');
    }

    /**
     * Gerar PDF
     * @param  String $html_data View que vai compor o PDF
     * @param  String $file_name Nome do arquivo
     */
    function create_pdf_web($html_data, $file_name = "") {
        error_reporting(0);
        ini_set('display_errors', 0);

        header("Content-type:application/pdf");

        if ($file_name == "") {
            $file_name = 'meu-pdf-' . date('d-m-Y');
        }
        require 'MPDF54/mpdf.php';
        $mypdf = new mPDF();
        $mypdf->SetDisplayMode('fullpage');
        $hyphenate->mypdf = true;
		$mypdf->KeepColumns = true;
		// $mypdf->ignore_invalid_utf8 = true; 
        $mypdf->WriteHTML($html_data, 2, false, false);
        ob_clean();
        $mypdf->Output($file_name . '.pdf', 'I');
        exit;
    }
}