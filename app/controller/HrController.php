<?php

namespace controller;

class HrController extends Controller
{
    function hr()
    {
        if (!$this->click()) {
        }
        $this->display();
    }

    function click()
    {
        if (isset($_POST["addEmployee"])) {
            header("Location:index.php?m=staffEditor&a=staffEditor&state=add");
        } else if (isset($_POST["staffList"])) {
            header("Location:index.php?m=form&a=form&form=staffList");
        } else if (isset($_POST["resignedStaffList"])) {
            header("Location:index.php?m=form&a=form&form=resignedStaffList");
        } else if (isset($_POST["LeaveWithoutPayStaffList"])) {
            header("Location:index.php?m=form&a=form&form=LeaveWithoutPayStaffList");
        } else return false;
        exit();
        return true;
    }
}
