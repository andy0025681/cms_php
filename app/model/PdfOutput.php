<?php
namespace model;

use tcpdf\Base_form;

class PdfOutput
{
    function __construct()
    {
        $this->createForm = new CreateForm();
    }

    function leaveReportPDF($type, $year, $month, $dest) {
        if ($type == 'month') {
            $title = $year.'年'.$month.'月請假報表';
            $name = $year.'_'.$month.'_month_report';
            $buf = $this->createForm->leaveStatsRow([$year, $month]);
        } elseif ($type == 'year') {
            $title = $year.'年'.'請假報表';
            $name = $year.'_annual_report';
            $buf = $this->createForm->leaveStatsRow([$year]);
        }
        $pdf = new Base_form();
        // 類別中的變數必須先賦值
        $pdf->reportTitle = $title;
        $pdf->column = $buf[0];
        $pdf->interval = 40;
        // set
        $pdf->AddPage('P', 'LETTER'); // 版面配置：P 直向 | L 橫向, 紙張大小 (必須大寫字母)
        $pdf->SetFont('msungstdlight', '', 10); // 字形與字體
        $pdf->SetMargins(4, 0, 4); // 內文空隙設定
        // $pdf->SetMargins(1, 1, 1);
        // context
        $html = '<table cellpadding="1">'.$buf[1].'</table>';
        $pdf->writeHTMLCell(0, 0, '', '50', $html, 0, 1, false, true, 'C', true);
        // show results
        ob_end_clean();
        $pdf->Output($name.'.pdf', $dest);
        // $pdf->Output($name.'.pdf', 'I');
    }
    
    function leaveLogPDF($staffCode, $start, $end, $dest)
    {
        $name = 'leave_log_'.$start.'_to_'.$end;
    
        $pdf = new Base_form();
        // 類別中的變數必須先賦值
        $pdf->reportTitle = $start.' ~ '.$end."請假紀錄";
        $style = 'style="border-bottom: 1px solid black;"';
        $pdf->column = '<td '.$style.'>編號</td>
                        <td '.$style.'>類型</td>
                        <td style="border-bottom: 1px solid black; width: 40%">期間</td>
                        <td '.$style.'>時長</td>';
        $pdf->interval = 35;
        // set
        $pdf->AddPage('P', 'LETTER'); // 版面配置：P 直向 | L 橫向, 紙張大小 (必須大寫字母)
        $pdf->SetFont('msungstdlight', '', 10); // 字形與字體
        $pdf->SetMargins(4, 0, 4); // 內文空隙設定
        // context
        $buf = $this->createForm->leaveLogRow($staffCode, $start, $end, false);
        $html = '<table cellpadding="1">'.$buf.'</table>';
        $pdf->writeHTMLCell(0, 0, '', '45', $html, 0, 1, false, true, '', true);
        // show results
        ob_end_clean();
        $pdf->Output($name.'.pdf', $dest);
        // $pdf->Output($name.'.pdf', 'I');
    }

    function staffListPDF($dest, $staffState)
    {
        $pdf = new Base_form();
        // 類別中的變數必須先賦值
        if($staffState == '在職') {
            $name = 'staff_list';
            $pdf->reportTitle = "在職員工名單";
        } elseif($staffState == '離職') {
            $name = 'resigned_staff_list';
            $pdf->reportTitle = "離職員工名單";
        } elseif($staffState == '留職停薪') {
            $name = 'Leave_without_pay_staff_list';
            $pdf->reportTitle = "留職停薪員工名單";
        }
        $style = 'style="border-bottom: 1px solid black;"';
        $pdf->column = '<td '.$style.'>員工姓名</td>
                        <td '.$style.'>分機</td>
                        <td '.$style.'>生日</td>
                        <td '.$style.'>到職日期</td>
                        <td '.$style.'>部門</td>';
        $pdf->interval = 35;
        // set
        $pdf->AddPage('P', 'LETTER'); // 版面配置：P 直向 | L 橫向, 紙張大小 (必須大寫字母)
        $pdf->SetFont('msungstdlight', '', 10); // 字形與字體
        $pdf->SetMargins(4, 0, 4); // 內文空隙設定
        // context
        $buf = $this->createForm->staffListRow(false, $staffState);
        $html = '<table cellpadding="3">'.$buf.'</table>';
        $pdf->writeHTMLCell(0, 0, '', '45', $html, 0, 1, false, true, '', true);
        // show results
        ob_end_clean();
        $pdf->Output($name.'.pdf', $dest);
        // $pdf->Output($name.'.pdf', 'I');
    }
}