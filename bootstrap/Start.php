<?php
class Start {
    // 用來保存自動加對象
    static public $auto;
    // 啟動方法，即創建自動加載對象方法
    static function init() {
        self::$auto = new Psr4AutoLoad();
    }

    // 路由方法
    static function router() {
        // 從url中獲取要執行哪個控制器，種的哪個方法
        // 從get參數中獲取，如果沒有，默認都是index
        $m = empty($_GET['m']) ? 'index' : $_GET['m'];
        $a = empty($_GET['a']) ? 'index' : $_GET['a'];

        // 始終保證get參數中有默認值
        $_GET['m'] = $m;
        $_GET['a'] = $a;

        // 將index 處理
        $m = ucfirst($m);

        // 拚接帶有命名空間的類名
        $controller = 'controller\\'.$m.'Controller';
        // 創建對象並且執行對應方法
        $obj = new $controller();
        call_user_func([$obj, $a]);
    }
}

Start::init();
