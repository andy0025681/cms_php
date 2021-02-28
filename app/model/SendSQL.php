<?php

namespace model;

class SendSQL
{
    function sendSQL($sql)
    {
        $sqlLink = new SqlLink();
        $db = $sqlLink->getSQLLink();
        // $db = $sqlLink->getSQLLink_test();
        $buf = mysqli_query($db, $sql);
        mysqli_close($db);
        return $buf;
    }
    
    function sqlSelectStaffWithAccount($acc, $pw)
    {
        $sql = "SELECT *
            FROM staff_list
            INNER JOIN staff_account_list
            ON staff_list.staffCode = staff_account_list.staffCode
            WHERE staff_account_list.account = '$acc' AND staff_account_list.password = '$pw';";
        return $this->sendSQL($sql);
    }
    
    function sqlInsertNewAcc($staffCode, $email)
    {
        $buf = explode('@', $email);
        $sql = "INSERT INTO staff_account_list(staffCode, account, password)
            VALUES ($staffCode, '$buf[0]', '$buf[0]')";
        return $this->sendSQL($sql);
    }

    function sqlUpdatePW($staffCode, $pw)
    {
        $sql = "UPDATE staff_account_list SET password='$pw' WHERE staffCode = $staffCode;";
        return $this->sendSQL($sql);
    }

    function sqlDeleteStaffAccount($staffCode)
    {
        $sql = "DELETE FROM staff_account_list WHERE staffCode = '$staffCode';";
        return $this->sendSQL($sql);
    }

    function sqlSelectAccAuthorityList()
    {
        $sql = "SELECT * FROM acc_authority;";
        return $this->sendSQL($sql);
    }

    function sqlSelectAccAuthorityByExplain($explanation)
    {
        $sql = "SELECT * FROM acc_authority WHERE explanation='$explanation';";
        return $this->sendSQL($sql);
    }

    function sqlSelectAccAuthorityByAuthority($authority)
    {
        $sql = "SELECT * FROM acc_authority WHERE authority='$authority';";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectNewStaffCode()
    {
        $sql = "SELECT MAX(staffCode)+1 AS newStaffCode FROM staff_list;";
        return $this->sendSQL($sql);
    }

    function sqlSelectStaff($staffCode)
    {
        $sql = "SELECT * FROM staff_list WHERE staffCode = $staffCode;";
        return $this->sendSQL($sql);
    }

    function sqlSelectStaffList($exclude, $authority)
    {
        $buf = "WHERE";
        if ($exclude) $buf .= " authority != '$authority' AND"; // 排除特定權限，例如只查詢在職員工，必須排除"封鎖"權限
        else $buf .= " authority = '$authority' AND"; // 必須是特定權限
        $buf = rtrim($buf, 'AND'); // 移除最後的 AND
        $sql = "SELECT * FROM staff_list $buf ORDER BY staffCode;";
        return $this->sendSQL($sql);
    }

    function sqlSelectStaffListByAuthority($authority)
    {
        $sql = "SELECT * FROM staff_list WHERE authority = '$authority' ORDER BY staffCode;";
        return $this->sendSQL($sql);
    }

    function sqlSelectStaffDataAndAccByEmail($email)
    {
        $sql = "SELECT * FROM staff_list 
            LEFT JOIN staff_account_list
            ON staff_list.staffCode = staff_account_list.staffCode
            WHERE staff_list.email = '$email';";
        return $this->sendSQL($sql);
    }

    function sqlSelectDepartmentStaffList($staffCode, $department)
    {
        $buf = explode('/', $department);
        $department = "";
        foreach ($buf as $index => $row) {
            $department .= "department LIKE '%$row%'";
            if (count($buf) > $index + 1) $department .= " OR ";
        }
        $sql = "SELECT *
            FROM staff_list
            WHERE ($department) AND 
                  staffCode != $staffCode;";
        return $this->sendSQL($sql);
    }

    function sqlSelectDepartmentLeader($department)
    {
        $sql =  "SELECT * FROM staff_list WHERE job_title LIKE '%" . $department . "主管%';";
        return $this->sendSQL($sql);
    }

    function sqlInsertNewStaff(
        $staffCode,
        $cName,
        $eName,
        $eLastName,
        $gender,
        $email,
        $cellPhone,
        $firstDay,
        $desknum,
        $official_leave,
        $off_leave_start_date,
        $off_leave_end_date,
        $authority,
        $department,
        $birthday
    ) {
        $sql = "INSERT INTO staff_list(staffCode, cName, eLastName, eName, gender, email, cellPhone, firstDay, resignDay, desknum, 
            official_leave, off_leave_start_date, off_leave_end_date, authority, job_title, department, birthday_leave, birthday)
            VALUES ($staffCode, '$cName', '$eLastName', '$eName', '$gender', '$email', '$cellPhone', '$firstDay', '0000-00-00', $desknum, 
            $official_leave, '$off_leave_start_date', '$off_leave_end_date', '$authority', '職員', '$department', 1.0, '$birthday');";
        return $this->sendSQL($sql);
    }

    function sqlUpdateStaffData(
        $staffCode,
        $cName,
        $eName,
        $eLastName,
        $gender,
        $email,
        $cellPhone,
        $firstDay,
        $desknum,
        $official_leave,
        $off_leave_start_date,
        $off_leave_end_date,
        $authority,
        $job_title,
        $department,
        $birthday,
        $resignDay
    ) {
        $buf = "";
        if ($cName) $buf .= "cName = '$cName', ";
        if ($eName) $buf .= "eName = '$eName', ";
        if ($eLastName) $buf .= "eLastName = '$eLastName', ";
        if ($gender) $buf .= "gender = '$gender', ";
        if ($email) $buf .= "email = '$email', ";
        if ($cellPhone) $buf .= "cellPhone = '$cellPhone', ";
        if ($firstDay) $buf .= "firstDay = '$firstDay', ";
        if ($desknum) $buf .= "desknum = '$desknum', ";
        if ($official_leave) $buf .= "official_leave = '$official_leave', ";
        if ($off_leave_start_date) $buf .= "off_leave_start_date = '$off_leave_start_date', ";
        if ($off_leave_end_date) $buf .= "off_leave_end_date = '$off_leave_end_date', ";
        if ($authority) $buf .= "authority = '$authority', ";
        if ($job_title) $buf .= "job_title = '$job_title', ";
        if ($department) $buf .= "department = '$department', ";
        if ($birthday) $buf .= "birthday = '$birthday', ";
        if ($resignDay) $buf .= "resignDay = '$resignDay', ";
        $buf = rtrim($buf, ', ');
        $sql = "UPDATE staff_list 
            SET $buf
            WHERE staffCode = '$staffCode';";
        return $this->sendSQL($sql);
    }

    function sqlUpdateStaffResignDay($staffCode, $resignDay)
    {
        $sql = "UPDATE staff_list 
            SET resignDay = '$resignDay' 
            WHERE staffCode = '$staffCode';";
        return $this->sendSQL($sql);
    }

    function sqlUpdateStaffEmail($staffCode, $email)
    {
        $sql = "UPDATE staff_list 
            SET email = '$email' 
            WHERE staffCode = '$staffCode';";
        return $this->sendSQL($sql);
    }

    function sqlUpdateAccAuthority($staffCode, $authority)
    {
        $sql = "UPDATE staff_list 
            SET authority = '$authority' 
            WHERE staffCode = $staffCode;";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectDepartmentList()
    {
        $sql = "SELECT * FROM department_list;";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectExtraAgentRelationList($staffCode)
    {
        $sql = "SELECT * FROM extra_agent_relation WHERE staffCode='$staffCode';";
        return $this->sendSQL($sql);
    }
    
    function sqlDeleteExtraAgentRelationByStaffCode($staffCode)
    {
        $sql = "DELETE FROM extra_agent_relation 
            WHERE staffCode = '$staffCode' OR colleagueStaffCode = '$staffCode';";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectLeaveType()
    {
        $sql = "SELECT * FROM leave_type";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectLeaveTypeByLeaveType($leaveType)
    {
        $sql =  "SELECT * FROM leave_type WHERE leaveType = '$leaveType';";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectLeaveTypeByGender($gender)
    {
        if ($gender == 'male') $sql = "SELECT * FROM leave_type WHERE genderSpecific = 'no';";
        else $sql = "SELECT * FROM leave_type;";
        return $this->sendSQL($sql);
    }
    
    function sqlUpdateOfficialLeave($staffCode, $officialLeave)
    {
        $sql = "UPDATE staff_list SET official_leave = '$officialLeave' WHERE staffCode = $staffCode;";
        return $this->sendSQL($sql);
    }
    
    function sqlUpdateOffLeaveReset($staffCode, $officialLeave, $startDate, $endDate)
    {
        $sql = "UPDATE staff_list SET official_leave = '$officialLeave', 
            off_leave_start_date = '$startDate', off_leave_end_date = '$endDate' 
            WHERE staffCode = $staffCode;";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectSpecialLeaveBySpecialLeaveNum($specialLeaveNum)
    {
        $sql = "SELECT * FROM special_leave WHERE specialLeaveNum  = $specialLeaveNum;";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectUnexpireSpecialLeave($staffCode, $date)
    {
        $sql = "SELECT * FROM special_leave 
            WHERE staffCode = $staffCode AND
            status = '已通過' AND
            DATE(startDate) <= '$date' AND
            DATE(endDate) >= '$date' AND
            duration > 0;";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectUsableSpecialLeave($staffCode, $startDate, $endDate, $duration)
    {
        $sql = "SELECT * FROM special_leave 
            WHERE staffCode = $staffCode AND
            status = '已通過' AND
            $startDate BETWEEN DATE(startDate) AND DATE(endDate) AND
            $endDate BETWEEN DATE(startDate) AND DATE(endDate) AND
            duration >= $duration;";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectSpecialLeaveOverlap($staffCode, $startDate, $endDate)
    {
        $sql = "SELECT * FROM special_leave 
            WHERE staffCode = $staffCode AND
            status = '已通過' AND
            DATE(startDate) BETWEEN '$startDate' AND '$endDate' AND
            DATE(endDate) BETWEEN '$startDate' AND '$endDate' AND
            '$startDate' BETWEEN DATE(startDate) AND DATE(endDate) AND
            '$endDate' BETWEEN DATE(startDate) AND DATE(endDate) AND
            duration > 0;";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectSpecialLeaveWithinTimeLimit($staffCode, $date)
    {
        $buf = (count($date) > 1) ?
            "'$date[0]-$date[1]-01' AND '$date[0]-$date[1]-31'" : "'$date[0]-01-01' AND '$date[0]-12-31'";
        $sql = "SELECT * FROM special_leave 
            WHERE staffCode = $staffCode AND
            status = '已通過' AND
            (
                DATE(startDate) BETWEEN $buf OR
                DATE(endDate) BETWEEN $buf
            );";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectNewSpecialLeaveNum()
    {
        $sql =  "SELECT MAX(specialLeaveNum)+1 AS newSpecialLeaveNum FROM special_leave;";
        return $this->sendSQL($sql);
    }
    
    function sqlInsertSpecialLeave($specialLeaveNum, $staffCode, $startDate, $endDate, $duration, $reason)
    {
        $sql = "INSERT INTO special_leave(specialLeaveNum, staffCode, startDate, endDate, duration, status, reason)
            VALUES ($specialLeaveNum, $staffCode, '$startDate', '$endDate', $duration, '審核中', '$reason');";
        return $this->sendSQL($sql);
    }
    
    function sqlUpdateSpecialLeaveStatus($specialLeaveNum, $status)
    {
        $sql = "UPDATE special_leave SET status = '$status' WHERE specialLeaveNum = $specialLeaveNum;";
        return $this->sendSQL($sql);
    }
    
    function sqlUpdateSpecialLeaveDuration($specialLeaveNum, $duration)
    {
        $sql = "UPDATE special_leave SET duration = $duration WHERE specialLeaveNum = $specialLeaveNum;";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectSpecialLeaveUseReference($leaveLogNum)
    {
        $sql = "SELECT * FROM special_leave_use_reference 
            INNER JOIN special_leave
            ON special_leave_use_reference.specialLeaveNum = special_leave.specialLeaveNum
            WHERE special_leave_use_reference.leaveLogNum  = '$leaveLogNum';";
        return $this->sendSQL($sql);
    }
    
    function sqlInsertSpecialLeaveReference($leaveLogNum, $specialLeaveNum)
    {
        $sql = "INSERT INTO special_leave_use_reference(leaveLogNum, specialLeaveNum)
            VALUES ('$leaveLogNum', $specialLeaveNum);";
        return $this->sendSQL($sql);
    }
    
    function sqlDeleteSpecialLeaveReferenceByLeaveLogNum($leaveLogNum)
    {
        $sql = "DELETE FROM special_leave_use_reference WHERE leaveLogNum = '$leaveLogNum';";
        return $this->sendSQL($sql);
    }
    
    function sqlResetBirthdayLeave()
    {
        $sql = "UPDATE staff_list SET birthday_leave = 1;";
        return $this->sendSQL($sql);
    }
    
    function sqlUpdateBirthdayLeave($staffCode, $value)
    {
        $sql = "UPDATE staff_list SET birthday_leave = $value WHERE staffCode = $staffCode;";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectLeaveLog($leaveLogNum)
    {
        $sql = "SELECT * FROM leave_log WHERE leaveLogNum = '$leaveLogNum';";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectLeaveLogDateStatus($staffCode, $start, $end, $isWebPage)
    {
        if ($isWebPage) $buf = "";
        else $buf = "AND status = '已通過'";
        $sql = "SELECT * FROM leave_log WHERE staffCode = $staffCode AND DATE(startDate) BETWEEN '$start-01' AND '$end-31' $buf;";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectLeaveLogByDateLeaveType($staffCode, $date, $leaveType, $status)
    {
        $buf = (count($date) > 1) ?
            "'$date[0]-$date[1]-01' AND '$date[0]-$date[1]-31'" : "'$date[0]-01-01' AND '$date[0]-12-31'";
        if ($status == '') $statusBuf = "";
        else $statusBuf = "status = '$status' AND";
        $sql = "SELECT * FROM leave_log 
            WHERE staffCode = $staffCode AND
            leaveType = '$leaveType' AND
            $statusBuf
            DATE(startDate) BETWEEN $buf;";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectLeaveLogStats($date)
    {
        $buf = (count($date) > 1) ?
            "'$date[0]-$date[1]-01' AND '$date[0]-$date[1]-31'" : "'$date[0]-01-01' AND '$date[0]-12-31'";
        $sql = "SELECT staff_list.staffCode AS staffCode, staff_list.cName AS name, leave_log.leaveType AS leaveType, SUM(leave_log.duration) AS duration, staff_list.authority AS authority
            FROM   staff_list
            LEFT JOIN (
                SELECT * FROM leave_log 
                WHERE status = '已通過' AND DATE(startDate) BETWEEN
                $buf
            ) as leave_log
            ON staff_list.staffCode = leave_log.staffCode
            GROUP BY staff_list.staffCode, leave_log.leaveType
            ORDER BY staff_list.staffCode;";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectNewestLeaveLogNum($staffCode, $leaveTypeNum)
    {
        $sql =  "SELECT MAX(leaveLogNum) FROM leave_log WHERE leaveLogNum LIKE '$staffCode-$leaveTypeNum-%';";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectOverlapLeaveLog($staffCode, $startDate, $endDate, $startTime, $endTime)
    {
        $sql = "SELECT * FROM leave_log 
            WHERE staffCode = $staffCode AND
            (
                ('$startDate' BETWEEN startDate AND endDate AND '$startTime' BETWEEN startTime AND endTime) OR
                ('$endDate' BETWEEN startDate AND endDate AND '$endTime' BETWEEN startTime AND endTime) OR
                ('$startDate' BETWEEN startDate AND endDate AND startTime BETWEEN '$startTime' AND '$endTime') OR
                ('$endDate' BETWEEN startDate AND endDate AND endTime BETWEEN '$startTime' AND '$endTime') OR
                (startDate BETWEEN '$startDate' AND '$endDate' AND '$startTime' BETWEEN startTime AND endTime) OR
                (endDate BETWEEN '$startDate' AND '$endDate' AND '$endTime' BETWEEN startTime AND endTime) OR
                (startDate BETWEEN '$startDate' AND '$endDate' AND startTime BETWEEN '$startTime' AND '$endTime') OR
                (endDate BETWEEN '$startDate' AND '$endDate' AND endTime BETWEEN '$startTime' AND '$endTime')
            );";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectLeaveLogByDate($date)
    {
        $sql = "SELECT * FROM leave_log 
            WHERE '$date' BETWEEN startDate AND endDate AND status = '已通過'
            ORDER BY staffName ASC, startDate ASC, startTime ASC;";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectLeaveLogOfPeriod($startDate, $endDate)
    {
        $sql = "SELECT * FROM leave_log
            WHERE DATE(startDate) BETWEEN '$startDate' AND '$endDate' AND
                  DATE(endDate) BETWEEN '$startDate' AND '$endDate';";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectLeaveLogInTime($date, $startTime, $endTime)
    {
        $sql = "SELECT * FROM leave_log 
            WHERE '$date' BETWEEN startDate AND endDate AND status = '已通過' AND
                    startTime BETWEEN '$startTime' AND '$endTime'
            ORDER BY staffName ASC, startDate ASC, startTime ASC;";
        return $this->sendSQL($sql);
    }
    
    function sqlInsertLeaveLog(
        $leaveLogNum,
        $staffCode,
        $staffName,
        $agentStaffCode,
        $leaveType,
        $startDate,
        $startTime,
        $endDate,
        $endTime,
        $duration,
        $reason,
        $annexPath
    ) {
        $sql = "INSERT INTO leave_log(leaveLogNum, staffCode, staffName, agentStaffCode, leaveType, 
            startDate, startTime, endDate, endTime, duration, reason, status, annexPath)
            VALUES ('$leaveLogNum', $staffCode, '$staffName', $agentStaffCode, '$leaveType', 
            '$startDate', '$startTime:00', '$endDate', '$endTime:00', $duration, '$reason', '審核中', '$annexPath');";
        return $this->sendSQL($sql);
    }
    
    function sqlUpdateLeaveLog(
        $leaveLogNum,
        $agentStaffCode,
        $leaveType,
        $startDate,
        $startTime,
        $endDate,
        $endTime,
        $duration,
        $reason,
        $annexPath,
        $oldLeaveLogNum
    ) {
        $sql = "UPDATE leave_log 
            SET leaveLogNum='$leaveLogNum', agentStaffCode=$agentStaffCode, leaveType='$leaveType', 
            startDate='$startDate', startTime='$startTime', endDate='$endDate', endTime='$endTime', 
            duration=$duration, reason='$reason', annexPath='$annexPath', status='審核中' 
            WHERE leaveLogNum='$oldLeaveLogNum'";
        return $this->sendSQL($sql);
    }
    
    function sqlUpdateLeaveLogStatus($leaveLogNum, $status)
    {
        $sql = "UPDATE leave_log SET status = '$status' WHERE leaveLogNum = '$leaveLogNum';";
        return $this->sendSQL($sql);
    }
    
    function sqlUpdateLeaveLogAnnexPath($leaveLogNum, $annexPath)
    {
        $sql = "UPDATE leave_log SET annexPath = '$annexPath' WHERE leaveLogNum = '$leaveLogNum';";
        return $this->sendSQL($sql);
    }
    
    function sqlDeleteLeaveLog($leaveLogNum)
    {
        $sql = "DELETE FROM leave_log WHERE leaveLogNum = '$leaveLogNum'";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectWorkdayOrWeekendByDate($date)
    {
        $sql = "SELECT * FROM workday_or_weekend 
            WHERE '$date' BETWEEN startDate AND endDate";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectNewWorkdayOrWeekendID()
    {
        $sql =  "SELECT MAX(id)+1 AS newId FROM workday_or_weekend;";
        return $this->sendSQL($sql);
    }
    
    function sqlInsertworkdayOrWeekend($id, $startDate, $endDate, $reason, $workday)
    {
        $sql = "INSERT INTO workday_or_weekend(id, startDate, endDate, reason, workday)
            VALUES ($id, '$startDate', '$endDate', '$reason', $workday);";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectLeaveWithoutPay($staffCode)
    {
        $sql = "SELECT * FROM leave_without_pay WHERE staffCode = '$staffCode';";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectNewLeaveWithoutPayID()
    {
        $sql =  "SELECT MAX(id)+1 AS newId FROM leave_without_pay;";
        return $this->sendSQL($sql);
    }
    
    function sqlInsertLeaveWithoutPay($id, $staffCode, $resignDay, $returnDay)
    {
        $sql = "INSERT INTO leave_without_pay(id, staffCode, resignDay, returnDay)
            VALUES ($id, $staffCode, '$resignDay', '$returnDay');";
        return $this->sendSQL($sql);
    }
    
    function sqlDeleteLeaveWithoutPay($staffCode)
    {
        $sql = "DELETE FROM leave_without_pay WHERE staffCode = $staffCode;";
        return $this->sendSQL($sql);
    }
    
    function sqlSelectEmailAddressBookByName($name)
    {
        $sql = "SELECT * FROM email_address_book WHERE name = '$name';";
        return $this->sendSQL($sql);
    }
}
