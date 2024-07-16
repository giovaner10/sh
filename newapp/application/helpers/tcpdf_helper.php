<?php
// error_reporting(0);

if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('tcpdf/tcpdf.php');
class Tcpdf_helper{
    
    public function __construct($title, $file_name, $page_orientation) { 
        $this->title = $title;
        $this->file_name = $file_name;
        $this->page_orientation = $page_orientation == 'landscape' ? 'L' : 'P';
    }


    
    function export_table_as_pdf($header,$data){
        // create new PDF document
        $pdf = new TCPDF($this->page_orientation, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetTitle($this->title);

        // set default header data
        $pdf->SetHeaderData('', 0, $this->title, '');

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

        // set font
        $pdf->SetFont('helvetica', '', 9);

        // add a page
        $pdf->AddPage();

        // Adiciona style
        $html = '<style>
                    table.first {
                        color: #003300;
                        font-family: helvetica;
                        font-size: 8pt;
                        border-left: 3px solid red;
                        border-right: 3px solid #FF00FF;
                        border-top: 3px solid green;
                        border-bottom: 3px solid blue;
                        background-color: #ccffcc;
                    }
                    td {
                        border: 2px solid blue;
                        background-color: #ffffee;
                    }
                </style>';
        $pdf->writeHTML($html, true, false, true, false, '');

        // Adiciona thead
        $html = '<table class="first" cellpadding="4" cellspacing="6" >
        <thead>
            <tr>';
        foreach ($header as $key => $value) {
            $html .= '<th align="center"><b>'.$value.'</b></th>';
        }
                
        $html .= '</tr></thead></table>';
        $pdf->writeHTML($html, true, false, true, false, '');

        /**
         * PARTICIONA A RENDERIZAÇÃO DO BODY PARA NÃO OCORRER ERRO
         */
        $slice_data = 500;
        $parte = 1;
        $partes = ceil(count($data) / $slice_data);
        while($parte <= $partes){
            $data_aux = array_slice($data, ($parte - 1) * $slice_data, $parte * $slice_data);
            // Adiciona tbody
            $html = '<table cellpadding="4" cellspacing="6"><tbody>';
            foreach ($data_aux as $key => $row) {
                $html .= '<tr>';
                foreach ($row as $key => $cell) {
                    $html .= '<td align="center">'.$cell.'</td>';
                }
                $html .= '</tr>';                
            }
            $html .= '</tbody></table>';

            $pdf->writeHTML($html, true, false, true, false, '');

            $parte += 1;
        }

        // Exporta documento
        $pdf->Output($this->file_name.'.pdf');
    }    

}