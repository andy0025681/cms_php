<?php

namespace model;

class Time
{
    function todayTW() {
        date_default_timezone_set("Asia/Taipei");
        return date('Y').'-'.date('m').'-'.date('d');
    }

    function timeTW() {
        date_default_timezone_set("Asia/Taipei");
        return date('H').':'.date('i').':'.date('s');
    }

    /**
     * main date include sub date.
     *
     * @param       string  $mainStart  main start.
     * @param       string  $mainEnd    mainEnd.
     * @param       string  $subStart   subStart.
     * @param       string  $subEnd     subEnd.
     * @return      bool                include / not include.
     * by Andy (2020-01-21)
     */
    function datesInclude($mainStart, $mainEnd, $subStart, $subEnd)
    {
        if (
            strtotime(date($subStart)) >= strtotime(date($mainStart)) and
            strtotime(date($subEnd)) >= strtotime(date($mainStart)) and
            strtotime(date($mainEnd)) >= strtotime(date($subStart)) and
            strtotime(date($mainEnd)) >= strtotime(date($subEnd))
        )
            return true;
        else return false;
    }

    /**
     * Calculation date interval.
     *
     * @param       string  $start  Start date. (format: YYYY-MM-DD)
     * @param       string  $end    End date. (format: YYYY-MM-DD)
     * @return      string          Date interval. (format: Y-M-D)
     * by Andy (2020-12-03)
     */
    function dateInterval($start, $end)
    {
        $datetime_start = date_create($start);
        $datetime_end = date_create($end);
        if ($datetime_start->getTimestamp() > $datetime_end->getTimestamp())
            return "0-0-0";
        $diff = date_diff($datetime_start, $datetime_end);
        return $diff->format("%y-%m-%d");
    }
    
    /**
     * date interval any format.
     *
     * @param       string  $start      Start date. (format: YYYY-MM-DD)
     * @param       string  $end        End date. (format: YYYY-MM-DD)
     * @return      DateInterval|false  The DateInterval object representing the difference between the two dates or FALSE on failure.
     * by Andy (2020-01-21)
     */
    function dateIntervalAnyFormat($start, $end)
    {
        $datetime_start = date_create($start);
        $datetime_end = date_create($end);
        if ($datetime_start->getTimestamp() > $datetime_end->getTimestamp())
            return false;
        return date_diff($datetime_start, $datetime_end);
    }
    
    /**
     * date interval in the year.
     *
     * @param       string  $start  Start date. (format: YYYY-MM-DD)
     * @param       string  $end    End date. (format: YYYY-MM-DD)
     * @return      string          Date interval. (format: M-D)
     * by Andy (2020-01-21)
     */
    function dateIntervalInTheYear($start, $end)
    {
        $start =  date_create($start)->format("0000-m-d");
        $end =  date_create($end)->format("0000-m-d");
        $datetime_start = date_create($start);
        $datetime_end = date_create($end);
        if ($datetime_start->getTimestamp() > $datetime_end->getTimestamp())
            return false;
        $diff = date_diff($datetime_start, $datetime_end);
        return $diff->format("%m-%d");
    }

    /**
     * is workday.
     *
     * @param       string  $date   date.
     * @return      bool|string     is workday / is't workday | 'null'.
     * by Andy (2020-01-21)
     */
    function isWorkday($date)
    {
        $workdayOrWeekend = new WorkdayOrWeekend();
        $buf = $workdayOrWeekend->getWorkdayOrWeekendByDate($date);
        foreach ($buf as $row) {
            if (isset($row['workday']) != null) return $row['workday'];
        }
        return 'null';
    }

    /**
     * Calculate the number of hours worked during a day.
     *
     * @param       string  $startTime  Start time. (format: HH:MM:SS)
     * @param       string  $endTime    End time. (format: HH:MM:SS)
     * @return      float               Working hours.
     * by Andy (2020-12-03)
     */
    function workingHours_day($startTime, $endTime)
    {
        $res = "0:0:0";
        $st = date_create($startTime);
        $et = date_create($endTime);
        $noon = date_create('12:00:00');
        $afterNoon = date_create('13:30:00');
        $evening = date_create('18:00:00');
        if ($st->getTimestamp() < $noon->getTimestamp()) { // 如果上午開始請假
            if ($et->getTimestamp() <= $noon->getTimestamp()) { // 如果上午就回來上班
                $res = date_diff($st, $et)->format("%h:%i:%s"); // 上午請假時間
            } elseif ($et->getTimestamp() < $evening->getTimestamp()) { // 如果下班前回來上班
                $a = date_diff($st, $noon); // 上午請假時間
                $b = date_diff($afterNoon, $et); // 下午請假時間
                // echo $a->format("%h") . "-" . $a->format("%i") . "-" . $a->format("%s") . "<br>";
                // echo $b->format("%h") . "-" . $b->format("%i") . "-" . $b->format("%s") . "<br>";
                $res =  ($a->format("%h") + $b->format("%h")) . ":" . ($a->format("%i") + $b->format("%i")) . ":" . ($a->format("%s") + $b->format("%s"));
            } else { // 請假直到下班
                $a = date_diff($st, $noon); // 上午請假時間
                $b = date_diff($afterNoon, $evening); // 下午請假時間
                // echo $a->format("%h")."-".$a->format("%i")."-".$a->format("%s")."<br>";
                // echo $b->format("%h")."-".$b->format("%i")."-".$b->format("%s");
                $res =  ($a->format("%h") + $b->format("%h")) . ":" . ($a->format("%i") + $b->format("%i")) . ":" . ($a->format("%s") + $b->format("%s"));
            }
        } else { // 如果下午開始請假
            if ($st->getTimestamp() < $afterNoon->getTimestamp()) $st = $afterNoon; // 如果開始時間是午休期間，從午休結束開始算。
            if ($et->getTimestamp() < $evening->getTimestamp()) { // 如果下班前回來上班
                $res = date_diff($st, $et)->format("%h:%i:%s"); // 下午請假時間
            } else { // 請假直到下班
                $res = date_diff($st, $evening)->format("%h:%i:%s"); // 下午請假時間
            }
        }
        // echo $res."<br>";
        return explode(":", $res)[0] + (explode(":", $res)[1] / 60) + (explode(":", $res)[2] / 3600);
    }
    
    /**
     * Hours worked during calculation.
     *
     * @param       string  $startDate  Start time. (format: YYYY-MM-DD)
     * @param       string  $startTime  End time. (format: HH:MM:SS)
     * @param       string  $endDate    Start time. (format: YYYY-MM-DD)
     * @param       string  $endTime    End time. (format: HH:MM:SS)
     * @return      float               Working hours.
     * by Andy (2020-12-03)
     */
    function workingHours_days($startDate, $startTime, $endDate, $endTime)
    {
        $result = 0;
        $ed = date_create($endDate);
        $thatDay = function ($date, $startDate, $startTime, $endDate, $endTime) { // 計算當天的請假時數
            $result = 0;
            if ($date == $startDate) { // 第一天要算清當天時數
                if ($date == $endDate) { // 同一天結束
                    $result += $this->workingHours_day($startTime, $endTime); // $startTime ~ $endTime
                } else { // 不同天結束
                    $result += $this->workingHours_day($startTime, '18:00:00'); // $startTime ~ 18:00:00
                }
            } else { // 第一天以外的時間
                if ($date == $endDate) { // 最後一天要算清當天時數
                    $result += $this->workingHours_day('09:00:00', $endTime); // 09:00:00 ~ $endTime
                } else { // 如果不是第一天、最後一天和假日，直接算7.5小時
                    $result += 7.5;
                }
            }
            return $result;
        };
        for (
            $date = $startDate;
            date_create($date)->getTimestamp() <= $ed->getTimestamp();
            $date = date('Y-m-d', strtotime($date . ' + 1 days'))
        ) {
            // $result = 0; // 歸零累計的時數，用來測試查看每天時數
            // echo $date." | ".date_create($date)->format('N')." | ";
            $status = $this->isWorkday($date); // 當日有特別規定嗎?
            if ($status == 'null') { // 如果該天沒有特別規定。
                if (date_create($date)->format('N') < 6) // 如果該天不是假日，就算請假。
                    $result += $thatDay($date, $startDate, $startTime, $endDate, $endTime);
            } elseif ($status) { // 如果該天特別規定是工作天。
                $result += $thatDay($date, $startDate, $startTime, $endDate, $endTime);
            }
            // echo $result."<br>";
        }
        return round($result, 2);
    }
}
