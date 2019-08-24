<?php
//            header('Content-Type: text/html; charset=UTF-8');
//            include("C:/xampp/htdocs/wallchart/application/third_party/mpdf/mpdf.php");
//
//            $mpdf=new \mPDF('+aCJK','A4','','',15,10,16,10,10,10);
            $mpdf = $this->m_pdf->load('+aCJK', 'A4', '', '', 0, 0, 0, 0, 0, 0);

            $html = '<html>
        Most of this text is in English, but has occasional words in Chinese:其貢獻在 or 
        Vietnamese: Một khảo sát mới cho biết, or maybe even Arabic: البرادعی

        البرادعی -12- البرادعی
กรณีสัมผัสทางผิวหนัง
        其貢獻在國際間亦備受肯定，2005年</html>';


//            $mpdf = $this->m_pdf->load('-aCJK', 'A4', '', '', 0, 0, 0, 0, 0, 0);

        $mpdf->charset_in='UTF-8';
       $mpdf->SetAutoFont(AUTOFONT_ALL);
       $mpdf->allow_charset_conversion = true;
       $mpdf->autoScriptToLang = true;
       $mpdf->autoLangToFont = true;
       $mpdf->useLang = true;

        $mpdf->WriteHTML($html);
        $mpdf->Output("test","I");
?>
