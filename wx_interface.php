<?php
//装载模板文件
require_once("wx_tpl.php");
require_once("wx_tool.php");
require_once("wx_wechat.php");

//define your token
define("TOKEN", "fansion");
//声明模板对象
$tpl = new Tpl();
//声明工具对象
$tool = new Tool($tpl);
$wechatObj = new Wechat($tool,$tpl);
$tool->tracebeginorend("1");
$tool->tracehttp();

$echoStr = $_GET["echostr"];
if($echoStr){
    $wechatObj->valid(TOKEN, $echoStr);
}else{
    $wechatObj->response(); 
}
?>
