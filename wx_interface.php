<?php
//装载模板文件
require_once("wx_tpl.php");
require_once("wx_tool.php");
require_once("wx_wechat.php");

//define your token
define("TOKEN", "fansion");
$echoStr = trim($_get["echostr"]);
if(empty($echoStr))
{
//    去掉注释用于微信公众平台开发模式认证
//    $echoStr = "Authentication passed!";
}
//声明模板对象
$tpl = new Tpl();
//声明工具对象
$tool = new Tool($tpl);
$wechatObj = new Wechat($tool,$tpl);
$tool->tracebeginorend("1");
$tool->tracehttp();

if(!empty($echoStr)){
    $wechatObj->valid(TOKEN);
}else{
    $wechatObj->response(); 
}
?>
