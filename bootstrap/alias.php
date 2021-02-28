<?php
// 添加命名空間映射
Start::$auto->addMaps('controller', 'app/controller');
Start::$auto->addMaps('model', 'app/model');
Start::$auto->addMaps('framework', 'vendor/lib');
Start::$auto->addMaps('phpmailer', 'vendor/lib/PHPMailer/src');
Start::$auto->addMaps('tcpdf', 'vendor/lib/tcpdf');
?>