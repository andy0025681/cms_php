<?php

namespace model;

class CreateForm
{
    function leaveLogRow($staffCode, $start, $end, $isWebPage)
    {
        $result = '';
        $time = new Time();
        $leaveLog = new LeaveLog();
        $buf = $leaveLog->getLeaveLogByDateStatus($staffCode, $start, $end, $isWebPage);
        foreach ($buf as $index => $row) {
            $result .= '<tr>';
            if ($isWebPage) $result .= '<td>' . ($index + 1) . '</td>';
            $result .= '<td>' . $row["leaveLogNum"] . '</td>';
            $result .= '<td>' . $row["leaveType"] . '</td>';
            $result .= '<td style="width: auto">' . $row["startDate"] . " " . $row["startTime"] . " ~ " . $row["endDate"] . " " . $row["endTime"] . '</td>';
            $result .= '<td>' . $row["duration"] . '小時</td>';
            if ($isWebPage) $result .= '<td>' . $row["status"] . '</td>';
            $datetime_start = date_create($row["startDate"]);
            $datetime_end = date_create($time->todayTW());
            // 按鈕
            $result .= '<td>';
            if ($isWebPage and $row["status"] != '審核中' and $datetime_start->getTimestamp() >= $datetime_end->getTimestamp()) {
                $result .= '<input type="submit" name="editLeaveBtn_' . $row["leaveLogNum"] . '_' . $row["leaveType"] . '" value="修改">
                            <input type="submit" name="delLeaveBtn_' . $row["leaveLogNum"] . '_' . $row["leaveType"] . '" value="刪除">';
            } elseif ($isWebPage and $row["status"] == '審核中') {
                $result .= '<input type="submit" name="sentRequestBtn_' . $row["leaveLogNum"] . '_' . $row["leaveType"] . '" value="重新發送申請信">';
            }
            $result .= ' <input type="submit" name="annexBtn_' . $row["leaveLogNum"] . '_' . $row["leaveType"] . '" value="發送附件">';
            $annuxName = 'annex_' . $row["leaveLogNum"] . '_' . $row["leaveType"] . '附件';
            $result .= ' <button type="button" name="previewBtn_' . $row["leaveLogNum"] . '_' . $row["leaveType"] . '" onclick="showAnnex(\'' . $annuxName . '\');">預覽附件</button>';
            $result .= ' <input style="width: 200px;" type="file" id="' . $annuxName . '" name="' . $annuxName . '">';
            $result .= '</td>';
            $result .= '</tr>';
        }
        return $result;
    }

    function leaveStatsRow($date)
    {
        $staff = new Staff();
        $leaveLog = new LeaveLog();
        $leaveType = new LeaveType();
        $specialLeave = new SpecialLeave();
        $accAuthority = new AccAuthority();
        $buf = $leaveType->getLeaveType();
        $form = array();
        $form += ["員工姓名" => array()];
        $col = '<th style="border-bottom: 1px solid black; width: 7.69%;" align="center">員工姓名</th>';
        foreach ($buf as $row) {
            if (isset($row["leaveType"])) {
                $form += [$row["leaveType"] => array()];
                $col .= '<th style="border-bottom: 1px solid black;" align="center">' . $row["leaveType"] . '</th>';
                if ($row["leaveType"] == "特休假" or $row["leaveType"] == "特別假") {
                    $form += ["剩餘" . $row["leaveType"] => array()];
                    $col .= '<th style="border-bottom: 1px solid black;" align="center">剩餘' . $row["leaveType"] . '</th>';
                }
            }
        }
        if (count($date) > 1) { // 長度1是年報，大於1目前只有月報
            $form += ["附註" => array()];
            $col .= '<th style="border-bottom: 1px solid black;" align="center">附註</th>';
        }
        $buf = $leaveLog->getLeaveLogStats($date);
        $staffCode = false;
        foreach ($buf as $row) {
            if ($accAuthority->accDisable($row['authority'])) continue; // 如果員工封鎖代表離職了，不納入統計。
            if ($staffCode != $row["staffCode"]) { // 如果接下來換不同的員工資料
                if ($staffCode) $staffData = $staff->getstaffData($staffCode);
                foreach ($form as $key => $val) { // 補齊上一個員工假別的空位
                    if (count($form["員工姓名"]) > count($val)) { // 補空位
                        if ($key == "附註") {
                            if ($staffCode) array_push($form[$key], $leaveLog->leaveLogToText($staffCode, $date, "特休假", '已通過'));
                        } elseif ($key == "剩餘特休假") array_push($form[$key], ($staffData["official_leave"] * 7.5));
                        elseif ($key == "剩餘特別假") {
                            array_push($form[$key], $specialLeave->getSpecialLeaveQuota($staffCode, $date));
                        } else array_push($form[$key], 0);
                    }
                }
                array_push($form["員工姓名"], $row["name"]);
                $staffCode = $row["staffCode"];
            }
            if (isset($row["leaveType"])) array_push($form[$row["leaveType"]], $row["duration"]);
        }
        foreach ($form as $key => $val) { // 補齊最後一行假別的空位
            if (count($form["員工姓名"]) > count($val)) {
                if ($key == "附註") {
                    if ($staffCode) array_push($form[$key], $leaveLog->leaveLogToText($staffCode, $date, "特休假", '已通過'));
                } elseif ($key == "剩餘特休假") array_push($form[$key], ($staffData["official_leave"] * 7.5));
                elseif ($key == "剩餘特別假") array_push($form[$key], $specialLeave->getSpecialLeaveQuota($staffCode, $date));
                else array_push($form[$key], 0);
            }
        }
        $data = '';
        for ($i = 0; $i < count($form["員工姓名"]); $i++) {
            $data .= '<tr>';
            foreach ($form as $key => $val) {
                $data .= '<td style="line-height: 1.5;">' . $val[$i] . '</td>';
            }
            $data .= '</tr>';
        }
        return [$col, $data];
    }

    function staffListRow($isWebPage, $staffState)
    {
        $staff = new Staff();
        $accAuthority = new AccAuthority();
        $leaveWithoutPay = new LeaveWithoutPay();
        if ($staffState == '在職') $buf = $staff->getStaffList(true, $accAuthority->getAccAuthority("封鎖")); // 排除封鎖帳號
        else $buf = $staff->getStaffList(false, $accAuthority->getAccAuthority("封鎖")); // 只搜尋封鎖帳號
        $result = "";
        $style = "style='width: 80px'";
        foreach ($buf as $row) {
            if ($staffState == '離職' and $leaveWithoutPay->haveLeaveWithoutPay($row["staffCode"])) continue;
            if ($staffState == '留職停薪' and !$leaveWithoutPay->haveLeaveWithoutPay($row["staffCode"])) continue;
            $result .= "<tr>";
            if (isset($row["cName"]) and isset($row["eName"]))
                $result .= "<td " . $style . ">" . $row['cName'] . " ( " . $row['eName'] . " )" . "</td>";
            if (isset($row["desknum"])) $result .= "<td " . $style . ">" . $row['desknum'] . "</td>";
            if (isset($row["birthday"])) {
                $birthday = explode('-', $row['birthday']);
                $result .= "<td " . $style . ">" . $birthday[1] . "-" . $birthday[2] . "</td>";
            }
            if (isset($row["firstDay"])) $result .= "<td " . $style . ">" . $row['firstDay'] . "</td>";
            if (isset($row["department"])) $result .= "<td " . $style . ">" . $row['department'] . "</td>";
            if ($isWebPage) {
                if ($staffState == '在職') {
                    $btn = "editStaffBtn_";
                    $value = "修改";
                } else {
                    $btn = "reinstatementStaffBtn_";
                    $value = "復職";
                }
                $result .= "<td " . $style . ">";
                $result .= '<input type="submit" name="' . $btn . $row['staffCode'] . '" value="' . $value . '"> ';
                if ($row['authority'] != $accAuthority->getAccAuthority("封鎖") and $row['authority'] != $accAuthority->getAccAuthority("人資主管") and $row['authority'] != $accAuthority->getAccAuthority("最高權限")) {
                    $result .= '<input type="submit" name="deleteStaffBtn_' . $row['staffCode'] . '" value="離職">';
                    $result .= ' <input type="submit" name="disableStaffBtn_' . $row['staffCode'] . '" value="留職停薪">';
                }
                $result .= "</td>";
            }
            $result .= "</tr>";
        }
        return $result;
    }

    function staffListRowResignOnly($isWebPage, $staffState)
    {
        $staff = new Staff();
        $accAuthority = new AccAuthority();
        $leaveWithoutPay = new LeaveWithoutPay();
        $buf = $staff->getStaffList(false, $accAuthority->getAccAuthority("封鎖")); // 只搜尋封鎖帳號
        $result = "";
        $style = "style='width: 80px'";
        foreach ($buf as $row) {
            $result .= "<tr>";
            if ($staffState == '離職' and $leaveWithoutPay->haveLeaveWithoutPay($row["staffCode"])) continue;
            elseif ($staffState == '留職停薪' and !$leaveWithoutPay->haveLeaveWithoutPay($row["staffCode"])) continue;
            $result .= "<td " . $style . ">" . $row['staffCode'] . "</td>";
            $result .= "<td " . $style . ">" . $row['cName'] . " ( " . $row['eName'] . " )" . "</td>";
            $result .= "<td " . $style . ">" . $row['firstDay'] . "</td>";
            $result .= "<td " . $style . ">" . $row['resignDay'] . "</td>";
            $result .= "<td " . $style . ">" . $row['cellPhone'] . "</td>";
            if ($isWebPage) {
                $btn = "reinstatementStaffBtn_";
                $value = "復職";
                $result .= "<td " . $style . ">";
                $result .= '<input type="submit" name="' . $btn . $row['staffCode'] . '" value="' . $value . '"> ';
                $result .= "</td>";
            }
            $result .= "</tr>";
        }
        return $result;
    }

    function officialLeaveForm($staffCode)
    {
        $_staff = new Staff();
        $result = '';
        $style = "style=''";
        $staff = $_staff->getstaffData($staffCode);
        $result .= '<table>';
        $result .= '<thead>
                        <tr>
                            <th scope="col">起始日</th>
                            <th scope="col">到期日</th>
                            <th scope="col">天數</th>
                        </tr>
                    </thead>';
        $result .= '<tbody>';
        $result .= '<tr>';
        if (isset($staff["off_leave_start_date"])) $result .= "<td " . $style . ">" . $staff["off_leave_start_date"] . "</td>";
        if (isset($staff["off_leave_end_date"])) $result .= "<td " . $style . ">" . $staff["off_leave_end_date"] . "</td>";
        if (isset($staff["official_leave"])) $result .= "<td " . $style . ">" . $staff["official_leave"] . "</td>";
        $result .= '</tr>';
        $result .= '</tbody>';
        $result .= '</table>';
        return $result;
    }

    function birthdayLeaveForm($staffCode)
    {
        $_staff = new Staff();
        $style = 'style=""';
        $staff = $_staff->getstaffData($staffCode);
        $result = '<table>';
        $result .= '<thead>
                        <tr>
                            <th scope="col">起始日</th>
                            <th scope="col">到期日</th>
                            <th scope="col">天數</th>
                        </tr>
                    </thead>';
        $result .= '<tbody>';
        $result .= '<tr>';
        if (isset($staff["birthday"])) {
            $birthday = explode('-', $staff["birthday"]);
            $result .= '<td ' . $style . '>' . $birthday[1] . '-' . $birthday[2] . '</td>';
            $result .= '<td ' . $style . '>' . date("m-d", strtotime($staff["birthday"] . "+30 day")) . '</td>';
        }
        if (isset($staff["birthday_leave"]))
            $result .= '<td ' . $style . '>' . $staff["birthday_leave"] . '</td>';
        $result .= '</tr>';
        $result .= '</tbody>';
        $result .= '</table>';
        return $result;
    }

    function specialLeaveForm($staffCode)
    {
        $result = '';
        $time = new Time();
        $specialLeave = new SpecialLeave();
        $style = "style=''";
        $buf = $specialLeave->getUnexpireSpecialLeave($staffCode, $time->todayTW());
        $result .= '<table>';
        $result .= '<thead>
                        <tr>
                            <th scope="col">起始日</th>
                            <th scope="col">到期日</th>
                            <th scope="col">時數</th>
                        </tr>
                    </thead>';
        $result .= '<tbody>';
        foreach ($buf as $row) {
            $result .= "<tr>";
            if (isset($row["startDate"])) $result .= "<td " . $style . ">" . $row["startDate"] . "</td>";
            if (isset($row["endDate"])) $result .= "<td " . $style . ">" . $row["endDate"] . "</td>";
            if (isset($row["duration"])) $result .= "<td " . $style . ">" . $row["duration"] . "</td>";
            $result .= "</tr>";
        }
        $result .= '</tbody>';
        $result .= '</table>';
        return $result;
    }

    function showLeaveLogForm($staffCode, $start, $end)
    {
        return '<table>
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">編號</th>
                            <th scope="col">類型</th>
                            <th scope="col">期間</th>
                            <th scope="col">時長</th>
                            <th scope="col">狀態</th>
                            <th scope="col">更動</th>
                        </tr>
                    </thead>
                    <tbody>
                    ' . $this->leaveLogRow($staffCode, $start, $end, true) . '
                    </tbody>
                </table>';
    }

    function showLeaveReportForm($type, $startDay)
    {
        if ($type == 'month') {
            $year = explode("-", $startDay)[0];
            $month = explode("-", $startDay)[1];
            $title = $year . '年' . $month . '月請假報表(小時)';
            $buf = $this->leaveStatsRow([$year, $month]);
        } elseif ($type == 'year') {
            $title = $startDay . '年' . '請假報表(小時)';
            $buf = $this->leaveStatsRow([$startDay]);
        }
        return '<div style="font-size: 30px" align="center">' . $title . '</div>
                <table>
                    <thead>
                        <tr>
                            ' . $buf[0] . '
                        </tr>
                    </thead>
                    <tbody>
                        ' . $buf[1] . '
                    </tbody>
                </table>';
    }

    function showLeaveQuotaForm($staffCode)
    {
        return '<div>剩餘特休假</div>
                ' . $this->officialLeaveForm($staffCode) . '
                <div>剩餘生日特別假</div>
                ' . $this->birthdayLeaveForm($staffCode) . '
                <div>剩餘特別假</div>
                ' . $this->specialLeaveForm($staffCode);
    }

    function showStaffListForm($staffState)
    {
        if ($staffState == '在職') {
            $buf = '<tr>
                        <th>姓名</th>
                        <th>分機</th>
                        <th>生日</th>
                        <th>到職日期</th>
                        <th>部門</th>
                        <th>編輯</th>
                    </tr>
                    </thead>
                    <tbody>' . $this->staffListRow(true, $staffState);
        } else {
            $buf = '<tr>
                        <th>員工編號</th>
                        <th>姓名</th>
                        <th>到職日期</th>
                        <th>離職日期</th>
                        <th>手機</th>
                        <th>編輯</th>
                    </tr>
                    </thead>
                    <tbody>' . $this->staffListRowResignOnly(true, $staffState);
        }
        $result = '<table>';
        $result .= '<thead>
                    ' . $buf;
        $result .= '</tbody>';
        $result .= '</table>';
        return $result;
    }
}
