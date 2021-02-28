<?php
namespace framework;
// 模板引擎類
class Tpl
{
    //成員變量
    //  模板文件的路徑
    protected $viewDir = './view/';
    //  生成的緩存文件的路徑
    protected $cacheDir = './cache/';
    //  過期時間
    protected $lifeTime = 3600;
    //  用來存放顯示變變量的數組
    protected $vars = [];

    // 構造方法(建構子)對成員變量進行初始化
    function __construct($viewDir = null, $cacheDir = null, $lifeTime = null)
    {
        // 判斷是否為空，如果為空，使用默認值，反之設置傳入值
        if (!empty($viewDir)) {
            if ($this->checkDir($viewDir)) $this->viewDir = $viewDir;
        }
        if (!empty($cacheDir)) {
            if ($this->checkDir($cacheDir)) $this->cacheDir = $cacheDir;
        }
        if (!empty($lifeTime)) $this->lifeTime = $lifeTime;
    }

    // 判斷目錄路徑是否正確
    protected function checkDir($dirPath)
    {
        if (!file_exists($dirPath) || !is_dir($dirPath)) // 判斷資料夾是否存在，如果路徑不存在，或該路徑不是資料夾，就新建可讀寫的資料夾
            return mkdir($dirPath, 0755, true);
        if (!is_writable($dirPath) || !is_readable($dirPath)) // 判斷目錄是否可以讀寫，如果不行說明權限不足，就修改權限
            return chmod($dirPath, 0755);
        return true;
    }

    // 對外公開方法
    //  分配變量方法
    //  $title = '台灣'; $tpl->assign('title', $title);
    //  $name 鍵
    //  $value 值
    function assign($name, $value)
    {
        $this->vars[$name] = $value;
    }
    //  展示緩存文件方法
    //  $viewName 模板文件名
    //  $isInclude 決定模板文件只需要編譯，或是編譯後要include進來，預設是需要include。 (只有在模板文件中，存在模板的語法，才不需要include)
    //  $uri 範例: index.php?page=1 ，page=1就是uri，為了讓緩存的文件名和uri拼接起來在md5一下，生成緩存文件名
    function display($viewName, $isInclude = true, $uri = null)
    {
        // 拼接模板文件的全路徑
        $viewPath = rtrim($this->viewDir, '/').'/'.$viewName;
        if(!file_exists($viewPath)) die($viewPath.'模板文件不存在');
        // 拼接緩存文件的全路徑
        $cacheName = md5($viewName.$uri).'.php';
        $cachePath = rtrim($this->cacheDir, '/').'/'.$cacheName;
        // 根據緩存文件的全路徑，判斷緩存文件是否存在，
        if(!file_exists($cachePath)) { // 如果緩存文件存在，
            // 編譯模板文件
            $php = $this->compile($viewPath);
            // 寫入文件，生成緩存文件
            file_put_contents($cachePath, $php);
        } else { // 如果緩存文件不存在，
            // 編譯模板文件，生成緩存文件
            // 如果緩存文件存在
            // 1. 判斷緩存文件是否過期
            $isTimeout = (filectime($cachePath) + $this->lifeTime) > time() ? false : true;
            // 2. 判斷緩存文件是否被修改過，如果有，需要重新生成
            $isChange = filemtime($viewPath) > filemtime($cachePath) ? true : false;
            if($isTimeout || $isChange) {
                $php = $this->compile($viewPath);
                file_put_contents($cachePath, $php);
            }
        }
        
        // 3. 判斷緩存文件是否需要包含進來
        if($isInclude) {
            // 將變量解析出來
            extract($this->vars);
            // 展示緩存文件
            include $cachePath;
        }
    }

    // compile方法，編譯html文件
    protected function compile($filePath)
    {
        // 讀取文件內容
        $html = file_get_contents($filePath);
        // 正規替換
        $array = [
            '{$%%}' => '<?=$\1; ?>',
            '{foreach %%}' => '<?php foreach (\1): ?>',
            '{/foreach}' => '<?php endforeach?>',
            '{include %%}' => '',
            '{if %%}' => '<?php if (\1): ?>',
        ];
        // 遍歷數組，將%%全部修改為.+，然後執行正規替換
        foreach ($array as $key => $value) {
            // 生成正規表達式
            $pattern = '#'.str_replace('%%', '(.+?)', preg_quote($key, '#')).'#';
            // 實現正規替換
            if(strstr($pattern, 'include')) {
                $html = preg_replace_callback($pattern, [$this, 'parseInclude'], $html);
            } else {
                // 執行替換
                $html = preg_replace($pattern, $value, $html);
            }
        }
        // 返回替換後的內容
        return $html;
    }

    // 處理include正規表達式，$data就是匹配到的內容
    protected function parseInclude($data)
    {
        // 將文件兩邊的引號去除
        $fileName = trim($data[1], '\'"');
        // 不包含文件生成緩存
        $this->display($fileName, false);
        // 拼接緩存文件全路徑
        $cacheName = md5($fileName).'.php';
        $cachePath = rtrim($this->cacheDir, '/').'/'.$cacheName;
        return '<?php include "'.$cachePath.'"?>';
    }
}
