<?php

namespace tcpdf;

use TCPDF;

require_once(dirname(__FILE__).'/tcpdf.php');

class Base_form extends TCPDF
{
    /**
     * page header
     * 
     * by Andy (2020-01-21)
     */
    public function Header()
    {
        // Set header font style & size
        $this->SetFont('msungstdlight', '', 10);
        // title
        $title = '<h4 style="font-size: 20pt; font-weight: normal; text-align: center;">XX股份有限公司</h4>
                    <table>
                        <tr>
                            <td style="font-size: 20pt; font-weight: normal; text-align: center; width: auto;">'.$this->reportTitle.'</td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                        </tr>
                    </table>';
        // column
        $fields = '<table cellpadding="1">
                        <tr>
                            '.$this->column.'
                        </tr>
                    </table>';
        // set local time
        date_default_timezone_set("Asia/Taipei");    
        // every page different set
        $interval = $this->interval;
        switch ($this->getPage()) {
            case '1':
                // 設定資料與頁面上方的間距 (依需求調整第二個參數即可)
                $this->SetMargins(5, $interval+10, 5);
                // 增加列印日期的資訊
                $html = $title . '
                <table cellpadding="1">
                    <tr>
                        <td>輸出日期：' . date('Y-m-d') . ' ' . date('H:i') . '</td>
                        <td></td>
                        <td></td>        
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                    </tr>
                </table>' .  $fields;
                break;
            default: // 其它頁
                $this->SetMargins(5, $interval, 5);
                $html = $title . $fields;
        }
        // output title
        $this->writeHTMLCell(0, 0, '', '0', $html, 0, 1, 0, true, '', true);
    }
    
    /**
     * page footer
     * 
     * by Andy (2020-01-21)
     */
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}
