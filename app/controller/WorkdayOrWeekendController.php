<?php

namespace controller;

use \model\Time;
use \model\WorkdayOrWeekend;

class WorkdayOrWeekendController extends Controller
{
    function workdayOrWeekend()
    {
        $time = new Time();
        $workdayOrWeekend = new WorkdayOrWeekend();
        if (isset($_POST["workday_or_weekend_send"])) {
            $type = $_POST['type'];
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            $reason = $_POST['reason'];
            if ($workdayOrWeekend->addWorkdayOrWeekend($startDate, $endDate, $reason, $type)) $this->assign('notices', '新增成功<br>');
            else $this->assign('notices', '新增失敗<br>');
        }
        $this->assign('today', $time->todayTW());
        $this->display();
    }
}
