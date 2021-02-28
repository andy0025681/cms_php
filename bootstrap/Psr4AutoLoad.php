<?php
class Psr4AutoLoad {
    // 這裡存放命名空間映射
    protected $maps = [];

    function __construct() {
        spl_autoload_register([$this, 'autoload']);
    }

    // 自己寫的自動加載函數
    function autoload($className) {
        // 完整的類名由命名空間名和類名組成
        // 得到命名空間名，根據命名空間名得到其目錄路徑
        $pos = strrpos($className, '\\');
        $namespace = substr($className, 0, $pos);
        // 得到類名
        $realClass = substr($className, $pos + 1);
        // 找到文件並且包含進來
        $this->mapLoad($namespace, $realClass);
    }

    // 根據命名空間名得到目錄路徑，並且拼接真正的文件全路徑
    protected function mapLoad($namespace, $realClass) {
        if(array_key_exists($namespace, $this->maps)) {
            $namespace = $this->maps[$namespace];
        }

        // 處理路徑
        $namespace = rtrim(str_replace('\\/', '/', $namespace), '/').'/';
        $filePath = $namespace.$realClass.'.php';
        // echo $filePath."<br>";
        // 將該文件包含進來即可
        if(file_exists($filePath)) {
            include $filePath;
        } else {
            die($filePath."該文件不存在");
        }
    }

    // 給一個命名空間，給一個路徑，將命名空間和路徑保存到映射數組中
    function addMaps($namespace, $path) {
        if(array_key_exists($namespace, $this->maps)) {
            die("此命名空間已經映射過");
        }
        // 將命名空間和路徑，已鍵值對形式存放到數組中
        $this->maps[$namespace] = $path;
    }
}
?>