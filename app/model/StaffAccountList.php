<?php
namespace model;

class StaffAccountList {
    function __construct()
    {
        $this->sql = new SendSQL();
    }

    function getStaffDataByAcc($acc, $pw)
    {
        $buf = $this->sql->sqlSelectStaffWithAccount($acc, $pw);
        foreach ($buf as $row) {
            return $row;
        }
        return false;
    }

    function addNewAcc($staffCode, $email) {
        return $this->sql->sqlInsertNewAcc($staffCode, $email);
    }

    function updatePW($staffCode, $pw) {
        return $this->sql->sqlUpdatePW($staffCode, $pw);
    }

    function deleteStaffAccount($staffCode) {
        return $this->sql->sqlDeleteStaffAccount($staffCode);
    }
}