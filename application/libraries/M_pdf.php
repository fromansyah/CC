<?php
class m_pdf {
    
    function m_pdf()
    {
        $CI = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }
 
    function load($param=NULL)
    {
        include_once APPPATH.'/third_party/mpdf/mpdf.php';
        //include_once APPPATH.'/third_party/mpdf-7.1.9/src/mpdf.php';

        if ($params == NULL)
        {
            $param = '"+aCJK","A4-L","","",10,10,10,10,6,3,"L"';          		
        }
         
        //return new mPDF($param);
        return new mPDF();
    }
}
?>
