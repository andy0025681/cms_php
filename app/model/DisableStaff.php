<?php

namespace model;

class DisableStaff
{
    function __construct()
    {
        $this->staff = new Staff();
        $this->extraAgentRelation = new ExtraAgentRelation();
        $this->leaveWithoutPay = new LeaveWithoutPay();
        $this->staffAccountList = new StaffAccountList();
        if (isset($_GET['staffCode'])) {
            $this->extraAgentRelation->deleteExtraAgentRelationByStaffCode($_GET['staffCode']);
            $this->staff->disableStaffAcc($_GET['staffCode']);
            $this->leaveWithoutPay->deleteLeaveWithoutPay($_GET['staffCode']);
            $this->staffAccountList->deleteStaffAccount($_GET['staffCode']);
        }
        echo "<script>
                    window.history.back();
                    location.reload();
                </script>";
    }
}
?>