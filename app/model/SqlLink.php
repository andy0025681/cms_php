<?php

namespace model;

class SqlLink
{
    function getSQLLink()
    {
        $db = mysqli_connect("127.0.0.1", "", "");
        if (!$db) die("Error: 無法連接MySQL伺服器!" . mysqli_connect_error());
        mysqli_select_db($db, "andy0681_cms") or
            die("Error: 無法選擇資料庫!" . mysqli_error($db));
        mysqli_query($db, "SET NAMES utf8");
        return $db;
    }

    function getSQLLink_test()
    {
        $db = mysqli_connect("127.0.0.1", "root", "");
        if (!$db) die("Error: 無法連接MySQL伺服器!" . mysqli_connect_error());
        mysqli_select_db($db, "qatransportcom") or
            die("Error: 無法選擇資料庫!" . mysqli_error($db));
        mysqli_query($db, "SET NAMES utf8");
        return $db;
    }
}
