<?php

namespace model;

class Login
{
    private $staffAccountList;
    function __construct()
    {
        $this->staffAccountList = new StaffAccountList();
    }

    function recordLoginStatus($acc, $pw)
    {
        $aging = 3600 * 24; // cookie 的時效
        $data = $this->staffAccountList->getStaffDataByAcc($acc, $pw);
        if (!isset($data)) return "fail";
        if (isset($data["authority"])) $authority = $data["authority"];
        else $authority = "fail";
        if (isset($data["staffCode"])) setcookie("staffCode", $data['staffCode'], time() + $aging, '/');
        if (isset($data["desknum"])) setcookie("desknum", $data['desknum'], time() + $aging, '/');
        if (isset($data["eName"])) setcookie("eName", $data['eName'], time() + $aging, '/');
        if (isset($data["eLastName"])) setcookie("eLastName", $data['eLastName'], time() + $aging, '/');
        if (isset($data["gender"])) setcookie("gender", $data['gender'], time() + $aging, '/');
        if (isset($data["department"])) setcookie("department", $data['department'], time() + $aging, '/');
        if (isset($data["email"])) setcookie("email", $data['email'], time() + $aging, '/');
        return $authority;
    }

    function login($acc, $pw)
    {
        $authority_code = $this->recordLoginStatus($acc, $pw);
        switch (str_split($authority_code)[1] . str_split($authority_code)[2]) {
            case '01':
                return ['main', 'employee'];
            case '11':
                return ['main', 'manager'];
            default:
                return false;
        }
    }
}
