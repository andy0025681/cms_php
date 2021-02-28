<?php

namespace model;

use phpmailer\MyPHPMailer;

class Mail
{
    function __construct()
    {
        $this->sql = new SendSQL();
        $this->staff = new Staff();
        $GLOBALS['hostEmail'] = "";
        $GLOBALS['hostPW'] = "";
        $GLOBALS['domain'] = "https://andy0025681.com/andy0025681.com/cms/";
        // $GLOBALS['domain'] = "http://localhost/";
    }

    function sentLetter($sender, $senderAccount, $senderPW, $recipient, $recipientAccount, $title, $text, $annex, $cc)
    {
        $mail = new MyPHPMailer(); //建立新物件
        $mail->isSMTP(); //設定使用SMTP方式寄信
        $mail->SMTPAuth = true; //設定SMTP需要驗證
        $mail->Host = "smtp.gmail.com"; //'mail.example.com';  //"ms.mailcloud.com.tw"; //設定SMTP主機
        $mail->Port = 465; //587; //25; //設定SMTP埠位
        $mail->SMTPSecure = 'ssl'; //'tls'; // Enable TLS encryption, `ssl` also accepted 
        $mail->CharSet = "utf-8"; //設定郵件編碼
        
        $mail->Username = $senderAccount; // 設定驗證帳號 SMTP username 
        $mail->Password = $senderPW; // 設定驗證密碼 SMTP password 

        $mail->From = $senderAccount; //設定寄件者信箱   
        $mail->FromName = $sender; //設定寄件者姓名  
        if ($cc) {
            foreach ($cc as $data) {
                $buf = explode('_', $data);
                $mail->AddCC($buf[1], $buf[0]);
            }
        }

        $mail->Subject = $title; //設定郵件標題   
        $mail->Body = $text; //設定郵件內容
        $mail->isHTML(true);         // Set email format to HTML
        if ($annex) $mail->AddAttachment($annex); //添加附件
        $mail->AddAddress($recipientAccount, $recipient); //設定收件者郵件及名稱
        if (!$mail->Send()) {
            // echo "Mailer Error: " . $mail->ErrorInfo . "<br>";
            return false;
        } else {
            // echo "Message sent!" . "<br>";
            return true;
        }
    }

    /**
     * leave decide text.
     *
     * @param       string  $applicant      applicant.
     * @param       string  $leaveType      leave type.
     * @param       string  $leaveLogNum    leave log number.
     * @param       string  $agent          agent.
     * @param       string  $startTime      start time.
     * @param       string  $reason         reason.
     * @return      string                  text.
     * by Andy (2020-01-21)
     */
    function leaveDecideText($applicant, $leaveType, $leaveLogNum, $agent, $startTime, $endTime, $reason)
    {
        $url = $GLOBALS['domain'] . "cms_php/index.php?m=result&a=result";
        return "<p>申請者: $applicant</p>
                <p>假別: $leaveType</p>
                <p>請假編號: $leaveLogNum</p>
                <p>職務代理人: $agent</p>
                <p>起始日: $startTime</p>
                <p>結束日: $endTime</p>
                <div>請假原因: $reason</div>
                <p style='font-size:15px'><a href='$url&leaveLogNum=$leaveLogNum&status=已通過'>同意休假申請</a></p>
                <p style='font-size:15px'><a href='$url&leaveLogNum=$leaveLogNum&status=已駁回'>不同意休假申請</a></p>
                "; //設定郵件內容 
    }
    
    /**
     * leave status notice text.
     *
     * @param       string  $applicant      applicant.
     * @param       string  $leaveType      leave type.
     * @param       string  $leaveLogNum    leave log number.
     * @param       string  $agent          agent.
     * @param       string  $startTime      start time.
     * @param       string  $endTime        end time.
     * @param       string  $reason         reason.
     * @param       string  $status         status.
     * @return      string                  text.
     * by Andy (2020-01-21)
     */
    function leaveStatusNoticeText($applicant, $leaveType, $leaveLogNum, $agent, $startTime, $endTime, $reason, $status)
    {
        return "<p>申請者: $applicant</p>
                <p>假別: $leaveType</p>
                <p>請假編號: $leaveLogNum</p>
                <p>職務代理人: $agent</p>
                <p>起始日: $startTime</p>
                <p>結束日: $endTime</p>
                <div>請假原因: $reason</div>
                <p>狀態: $status</p>";
    }
    
    /**
     * del leave text.
     *
     * @param       string  $applicant      applicant.
     * @param       string  $leaveType      leave type.
     * @param       string  $leaveLogNum    leave log number.
     * @return      string                  text.
     * by Andy (2020-01-21)
     */
    function delLeaveText($applicant, $leaveType, $leaveLogNum)
    {
        return "<p>取消請假人員: $applicant</p>
                <p>假別: $leaveType</p> 
                <p>請假編號: $leaveLogNum</p>
                <p>請假已刪除</p>";
    }
    
    /**
     * special leave request text.
     *
     * @param       string  $applicant          applicant.
     * @param       string  $duration           duration.
     * @param       string  $reason             reason.
     * @param       string  $specialLeaveNum    specialLeaveNum.
     * @return      string                      text.
     * by Andy (2020-01-21)
     */
    function specialLeaveRequestText($applicant, $duration, $reason, $specialLeaveNum)
    {
        $url = $GLOBALS['domain'] . "cms_php/index.php?m=result&a=result";
        return "<p>獲得特別假人員: $applicant</p>
                <p>時數: $duration 小時</p>
                <div>獲假原因: $reason</div>
                <p style='font-size:15px'><a href='$url&specialLeaveNum=$specialLeaveNum&status=已通過'>同意</a></p>
                <p style='font-size:15px'><a href='$url&specialLeaveNum=$specialLeaveNum&status=已駁回'>不同意</a></p>
                "; //設定郵件內容 
    }
    
    /**
     * special leave status notice text.
     *
     * @param       string  $staffName  staffName.
     * @param       string  $duration   duration.
     * @param       string  $reason     reason.
     * @param       string  $status     status.
     * @return      string              text.
     * by Andy (2020-01-21)
     */
    function specialLeaveStatusNoticeText($staffName, $duration, $reason, $status)
    {
        return "<p>獲得特別假人員: $staffName</p>
                <p>時數: $duration 小時</p>
                <div>獲假原因: $reason</div>
                <p>狀態: $status</p>
                "; //設定郵件內容 
    }
    
    /**
     * check automatic reply text.
     *
     * @param       string  $staffName  staffName.
     * @param       string  $gender     gender.
     * @return      string              text.
     * by Andy (2020-01-21)
     */
    function checkAutomaticReplyText($staffName)
    {
        return "<p>Hi $staffName,</p>
                <p>這是自動回覆測試信</p>
                <p>祝您假期愉快!</p>";
    }
    
    /**
     * leave list text.
     *
     * @param       string  $date   date.
     * @return      string          text.
     * by Andy (2020-01-21)
     */
    function leaveListText($date)
    {
        $_leaveLog = new LeaveLog();
        $result = "Dear All, <br><br>";
        $leaveLog = $_leaveLog->getLeaveLogByDate($date);
        if ($leaveLog->num_rows > 0) {
            $th = "<th style='border-bottom: 1px solid black; width: 10%;' align='center'>";
            $td = "<td style='line-height: 1.5;' align='center'>";
            $result .= '今日請休假人員如下:<br>';
            $result .= '<table class="table table-striped">';
            $result .= '
                <thead class="thead bg-primary text-white">
                    <tr>
                        ' . $th . '編號</th>
                        ' . $th . '員工資訊</th>
                        ' . $th . '請假類型</th>
                        <th style="border-bottom: 1px solid black; width: 20%;" align="center">期間</th>
                        ' . $th . '時長</th>
                    </tr>
                </thead>
                ';
            $result .= '<tbody>';
            foreach ($leaveLog as $index => $row) {
                $leaveStaff = $this->staff->getstaffData($row['staffCode']);
                $result .= '<tr>';
                $result .= $td . ($index + 1) . '</td>';
                $result .= $td . $leaveStaff['desknum'] . '/' . $leaveStaff['eName'] . '</td>';
                $result .= $td . $row['leaveType'] . '</td>';
                $result .= $td . $row['startDate'] . ' ' . $row['startTime'] . ' ~ ' . $row['endDate'] . ' ' . $row['endTime'] . '</td>';
                $result .= $td . $row['duration'] . '</td>';
                $result .= '</tr>';
            }
            $result .= '</tbody>';
            $result .= '</table>';
        } else {
            $result .= '今日無人請假';
        }
        return $result;
    }
    
    /**
     * annex repost text.
     *
     * @param       string  $applicant      applicant.
     * @param       string  $leaveType      leave type.
     * @param       string  $leaveLogNum    leave log number.
     * @param       string  $agent          agent.
     * @param       string  $startTime      start time.
     * @param       string  $endTime        end time.
     * @param       string  $reason         reason.
     * @param       string  $status         status.
     * @return      string                  text.
     * by Andy (2020-01-21)
     */
    function annexRepostText($applicant, $leaveType, $leaveLogNum, $agent, $startTime, $endTime, $reason, $status)
    {
        $buf = "<p>系統通知:</p>";
        $buf .= "<p>這是請假同仁 $applicant 補寄的請假附件</p>";
        $buf .= "<p>假別: $leaveType</p>
                <p>請假編號: $leaveLogNum</p>
                <p>職務代理人: $agent</p>
                <p>起始日: $startTime</p>
                <p>結束日: $endTime</p>
                <div>請假原因: $reason</div>
                <p>狀態: $status</p>";
        return $buf;
    }

    /**
     * cc to leader.
     *
     * @param       string  $department department.
     * @param       string  $job_title  job title.
     * @return      array               ["name_email", ...].
     * by Andy (2020-01-21)
     */
    function ccToLeader($department, $job_title)
    {
        $departmentList = explode('/', $department); // 有些員工跨部門
        $leader = array();
        foreach ($departmentList as $department) {
            array_push($leader, $this->staff->getDepartmentLeaderNameEmail($department));
        }
        if (!$leader[0] or strpos($job_title, "主管")) {
            $leader = [$this->staff->getSupremeLeaderNameEmail()];
        }
        return $leader;
    }
    
    /**
     * cc to hr.
     *
     * @return      array       ["name_email", ...].
     * by Andy (2020-01-21)
     */
    function ccToAllHR()
    {
        $accAuthority = new AccAuthority();
        $buf = $this->staff->getStaffListByAuthority($accAuthority->getAccAuthority("人資主管"));
        $hr = array();
        foreach ($buf as $row) {
            if (
                $row['desknum'] != null and
                $row['eName'] != null and
                $row['eLastName'] != null and
                $row['email'] != null
            ) {
                array_push($hr, $row['desknum'] . ' ' . $row['eName'] . ' ' . $row['eLastName'] . " / QA Transport" . '_' . $row['email']);
            } else {
                // echo "can't get HR data<br>";
            }
        }
        return $hr;
    }

    /**
     * leave notice decide.
     *
     * @param       string  $mailTitle      mail title.
     * @param       string  $leaveLogNum    leave log number.
     * @param       string  $annex          annex.
     * @return      bool                    success / fail.
     * by Andy (2020-01-21)
     */
    function leaveNoticeDecide($mailTitle, $leaveLogNum, $annex)
    {
        $_leaveLog = new LeaveLog();
        $result = true;
        $leaveLog = $_leaveLog->getLeaveLog($leaveLogNum);
        $leaveStaff = $this->staff->getstaffData($leaveLog["staffCode"]);
        $agentStaff = $this->staff->getstaffData($leaveLog["agentStaffCode"]);
        $applicant = $leaveStaff['desknum'] . ' ' . $leaveStaff['eName'] . ' ' . $leaveStaff['eLastName'] . " / QA Transport";
        $agent = $agentStaff['desknum'] . ' ' . $agentStaff['eName'] . ' ' . $agentStaff['eLastName'] . " / QA Transport";
        $startTime = $leaveLog['startDate'] . ' ' . $leaveLog['startTime'];
        $endTime = $leaveLog['endDate'] . ' ' . $leaveLog['endTime'];
        $leader = $this->ccToLeader($leaveStaff["department"], $leaveStaff["job_title"]);
        $firstleaderName = explode('_', $leader[0])[0];
        $firstleaderEmail = explode('_', $leader[0])[1];
        if ($this->sentLetter(
            "請假系統",
            $GLOBALS['hostEmail'],
            $GLOBALS['hostPW'],
            $firstleaderName,
            $firstleaderEmail,
            $mailTitle . " 審核信",
            $this->leaveDecideText(
                $applicant,
                $leaveLog['leaveType'],
                $leaveLogNum,
                $agent,
                $startTime,
                $endTime,
                $leaveLog['reason']
            ),
            $annex,
            array_slice($leader, 1)
        )) {
            $text = $this->leaveStatusNoticeText($applicant, $leaveLog['leaveType'], $leaveLogNum, $agent, $startTime, $endTime, $leaveLog['reason'], '申請中');
            $cc = false;
            if (!$this->sentLetter("請假系統", $GLOBALS['hostEmail'], $GLOBALS['hostPW'], $applicant, $leaveStaff['email'], $mailTitle . " 通知信", $text, $annex, $cc)) {
                $result = false;
                // echo 'send Notice fail<br>';
            }
            return $result;
        } else {
            // echo '請假審核信送信失敗<br>';
            return false;
        }
    }
    
    /**
     * leave status letter.
     *
     * @param       string  $mailTitle      mail title.
     * @param       string  $leaveLogNum    leave log number.
     * @return      bool                    success / fail.
     * by Andy (2020-01-21)
     */
    function leaveStatusLetter($mailTitle, $leaveLogNum)
    {
        $_leaveLog = new LeaveLog();
        $result = true;
        $leaveLog = $_leaveLog->getLeaveLog($leaveLogNum);
        $leaveStaff = $this->staff->getstaffData($leaveLog['staffCode']);
        $agentStaff = $this->staff->getstaffData($leaveLog['agentStaffCode']);
        $applicant = $leaveStaff['desknum'] . ' ' . $leaveStaff['eName'] . ' ' . $leaveStaff['eLastName'] . " / QA Transport";
        $leaveType =  $leaveLog['leaveType'];
        $startTime =  $leaveLog['startDate'] . ' ' . $leaveLog['startTime'];
        $endTime =  $leaveLog['endDate'] . ' ' . $leaveLog['endTime'];
        $reason =  $leaveLog['reason'];
        $status =  $leaveLog['status'];
        $text = $this->leaveStatusNoticeText(
            $applicant,
            $leaveType,
            $leaveLogNum,
            $agentStaff['desknum'] . ' ' . $agentStaff['eName'] . ' ' . $agentStaff['eLastName'] . " / QA Transport",
            $startTime,
            $endTime,
            $reason,
            $status
        );
        $leader = $this->ccToLeader($leaveStaff["department"], $leaveStaff["job_title"]);
        $cc = array_merge([$agentStaff['desknum'] . ' ' . $agentStaff['eName'] . ' ' . $agentStaff['eLastName'] . " / QA Transport" . '_' . $agentStaff['email']], $leader, $this->ccToAllHR());
        if (!$this->sentLetter("請假系統", $GLOBALS['hostEmail'], $GLOBALS['hostPW'], $applicant, $leaveStaff['email'], $mailTitle, $text, false, $cc)) {
            $result = false;
            // echo 'send leaveStatusLetter fail<br>';
        }
        return $result;
    }
    
    /**
     * leave status letter.
     *
     * @param       array  $leaveLog   leave log.
     * @return      bool                success / fail.
     * by Andy (2020-01-21)
     */
    function leaveCancelLetter($leaveLog)
    {
        $result = true;
        $leaveStaff = $this->staff->getstaffData($leaveLog['staffCode']);
        $agentStaff = $this->staff->getstaffData($leaveLog['agentStaffCode']);
        $applicant = $leaveStaff['desknum'] . ' ' . $leaveStaff['eName'] . ' ' . $leaveStaff['eLastName'] . " / QA Transport";
        $leader = $this->ccToLeader($leaveStaff["department"], $leaveStaff["job_title"]);
        $text = $this->delLeaveText($applicant, $leaveLog['leaveType'], $leaveLog['leaveLogNum']);
        $cc = array_merge([$agentStaff['desknum'] . ' ' . $agentStaff['eName'] . ' ' . $agentStaff['eLastName'] . " / QA Transport" . '_' . $agentStaff['email']], $leader, $this->ccToAllHR());
        if (!$this->sentLetter("請假系統", $GLOBALS['hostEmail'], $GLOBALS['hostPW'], $applicant, $leaveStaff['email'], "取消請假 通知信", $text, false, $cc)) {
            $result = false;
            // echo 'send leaveCancelLetter fail<br>';
        }
        return $result;
    }
    
    /**
     * annex repost letter.
     *
     * @param       string  $leaveLogNum    leave log number.
     * @param       string  $annexPath      annex path.
     * @return      bool                    success / fail.
     * by Andy (2020-01-21)
     */
    function annexRepostLetter($leaveLogNum, $annexPath)
    {
        $_leaveLog = new LeaveLog();
        $result = true;
        $leaveLog = $_leaveLog->getLeaveLog($leaveLogNum);
        $leaveStaff = $this->staff->getstaffData($leaveLog['staffCode']);
        $agentStaff = $this->staff->getstaffData($leaveLog["agentStaffCode"]);
        $applicant = $leaveStaff['desknum'] . ' ' . $leaveStaff['eName'] . ' ' . $leaveStaff['eLastName'] . " / QA Transport";
        $agent = $agentStaff['desknum'] . ' ' . $agentStaff['eName'] . ' ' . $agentStaff['eLastName'] . " / QA Transport";
        $startTime = $leaveLog['startDate'] . ' ' . $leaveLog['startTime'];
        $endTime = $leaveLog['endDate'] . ' ' . $leaveLog['endTime'];
        $hr = $this->ccToAllHR();
        $cc = array_slice($hr, 1);
        $firstHrName = explode('_', $hr[0])[0];
        $firstHrEmail = explode('_', $hr[0])[1];
        $text = $this->annexRepostText($applicant, $leaveLog['leaveType'], $leaveLogNum, $agent, $startTime, $endTime, $leaveLog['reason'], $leaveLog['status']);
        if (!$this->sentLetter("請假系統", $GLOBALS['hostEmail'], $GLOBALS['hostPW'], $firstHrName, $firstHrEmail, "請假附件更新 通知信", $text, $annexPath, $cc)) {
            $result = false;
            // echo 'send annexRepostLetter fail<br>';
        }
        return $result;
    }

    /**
     * annex repost letter.
     *
     * @param       string  $email  email.
     * @return      bool            success / fail.
     * by Andy (2020-01-21)
     */
    function changePasswordRequestLetter($email)
    {
        $url = $GLOBALS['domain'] . "cms_php/index.php?m=result&a=result";
        $time = new Time();
        $staffData = $this->staff->getstaffDataAndAccByEmail($email);
        if (isset($staffData['staffCode'])) $staffCode = $staffData['staffCode'];
        else return false;
        $ac = $staffData['account'];
        $pw = $staffData['password'];
        if ($this->sentLetter(
            "系統通知",
            $GLOBALS['hostEmail'],
            $GLOBALS['hostPW'],
            $email,
            $email,
            "重新設定密碼",
            "<div>系統在 " . $time->todayTW() . " " . $time->timeTW() . " 收到修改 " . $staffData['desknum'] . "/" . $staffData['eName'] . " 密碼的請求，如果同意修改請點選下方連結繼續。</div>
            <p style='font-size:15px'><a href='$url&staffCode=$staffCode&ac=$ac&pw=$pw'>修改密碼</a></p>",
            false,
            false
        )) {
            // echo "已發送通知" . "<br>";
            return true;
        } else {
            // echo "發送通知失敗" . "<br>";
        }
        return false;
    }

    /**
     * special leave request letter.
     *
     * @param       string  $specialLeaveNum    special leave number.
     * @param       string  $annex              annex.
     * @return      bool                        success / fail.
     * by Andy (2020-01-21)
     */
    function specialLeaveRequestLetter($specialLeaveNum, $annex)
    {
        $_specialLeave = new SpecialLeave();
        $result = true;
        $specialLeave = $_specialLeave->getSpecialLeave($specialLeaveNum);
        $staff = $this->staff->getstaffData($specialLeave['staffCode']);
        $applicant = $staff['desknum'] . ' ' . $staff['eName'] . ' ' . $staff['eLastName'] . " / QA Transport";
        $leader = $this->staff->getSupremeLeaderNameEmail();
        $firstleaderName = explode('_', $leader)[0];
        $firstleaderEmail = explode('_', $leader)[1];
        $mailTitle = "特別假新增";
        if ($this->sentLetter(
            "請假系統",
            $GLOBALS['hostEmail'],
            $GLOBALS['hostPW'],
            $firstleaderName,
            $firstleaderEmail,
            $mailTitle . " 審核信",
            $this->specialLeaveRequestText($applicant, $specialLeave['duration'], $specialLeave['reason'], $specialLeaveNum),
            $annex,
            false
        )) {
            $text = $this->specialLeaveStatusNoticeText($applicant, $specialLeave['duration'], $specialLeave['reason'], $specialLeave['status']);
            $cc = $this->ccToAllHR();
            if (!$this->sentLetter("請假系統", $GLOBALS['hostEmail'], $GLOBALS['hostPW'], $applicant, $staff['email'], $mailTitle . " 通知信", $text, $annex, $cc)) {
                $result = false;
                // echo '新增特別假通知信送信失敗<br>';
            }
            return $result;
        } else {
            // echo '新增特別假審核信送信失敗<br>';
            return false;
        }
    }
    
    /**
     * special leave request status letter.
     *
     * @param       string  $specialLeave    special leave.
     * @return      bool                        success / fail.
     * by Andy (2020-01-21)
     */
    function specialLeaveRequestStatusLetter($specialLeave)
    {
        $staff = $this->staff->getstaffData($specialLeave['staffCode']);
        $applicant = $staff['desknum'] . ' ' . $staff['eName'] . ' ' . $staff['eLastName'] . " / QA Transport";
        $text = $this->specialLeaveStatusNoticeText($applicant, $specialLeave['duration'], $specialLeave['reason'], $specialLeave['status']);
        $cc = $this->ccToAllHR();
        if (!$this->sentLetter("請假系統", $GLOBALS['hostEmail'], $GLOBALS['hostPW'], $applicant, $staff['email'], "特別假結果通知信", $text, false, $cc)) {
            return false;
        }
        return true;
    }

    /**
     * check automatic reply letter.
     *
     * @param       string  $today      today.
     * @param       string  $startTime  start time.
     * @param       string  $time       time.
     * @return      bool                success / fail.
     * by Andy (2020-01-21)
     */
    function checkAutomaticReplyLetter($today, $startTime, $time)
    {
        $leaveLog = new LeaveLog();
        $result = true;
        $leaveLogBuf = $leaveLog->getLeaveLogInTime($today, $startTime, $time);
        foreach ($leaveLogBuf as $row) {
            $staff = $this->staff->getstaffData($row['staffCode']);
            $applicant = $staff['desknum'] . ' ' . $staff['eName'] . ' ' . $staff['eLastName'] . " / QA Transport";
            $text = $this->checkAutomaticReplyText($staff['eName']);
            for ($i = 1; $i <= 5; $i++) {
                if ($this->sentLetter("請假系統", $GLOBALS['hostEmail'], $GLOBALS['hostPW'], $applicant, $staff['email'], "自動回覆測試", $text, false, false)) {
                    $result = true;
                    break;
                } else {
                    $result = false;
                    // echo "等待" . $i . "秒重新寄送...";
                    sleep($i);
                }
            }
        }
        return $result;
    }

    /**
     * daily leave notice letter.
     *
     * @param       string  $date   date.
     * @return      bool            success / fail.
     * by Andy (2020-01-21)
     */
    function dailyLeaveNoticeLetter($date)
    {
        $emailAddressBook = new EmailAddressBook();
        $text = $this->leaveListText($date);
        $email_address = $emailAddressBook->getEmailAddressBookByName("所有同仁");
        if ($this->sentLetter("請假系統", $GLOBALS['hostEmail'], $GLOBALS['hostPW'], $email_address['name'], $email_address['email'], "請假通知信", $text, false, false)) return true;
        return false;
    }
}
