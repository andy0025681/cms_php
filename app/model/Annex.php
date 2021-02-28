<?php

namespace model;

class Annex
{
    function uploadFile($file, $path)
    {
        if (move_uploaded_file($file["tmp_name"], $path)) {
            echo "檔案名稱: " . $file["name"] . "<br>";
            echo "檔案類型: " . $file["type"] . "<br>";
            echo "檔案大小: " . $file["size"] . "<br>";
            echo "上傳成功<br>";
            return true;
        } else {
            echo "上傳失敗<br>";
            return false;
        }
    }
    /**
     * update leave log annex.
     *
     * @param       string  $leaveLogNum    leaveLogNum.
     * @param       array   $file           file.
     * @param       string  $path           path.
     * @return      bool                    update success / update fail.
     * by Andy (2020-01-21)
     */
    function updateLeaveLogAnnex($leaveLogNum, $file, $path)
    {
        $leaveLog = new LeaveLog();
        if ($this->uploadFile($file, $path)) {
            if ($leaveLog->updateLeaveLogAnnexPath($leaveLogNum, $path)) return true;
            else return false;
        }
        return false;
    }
}
