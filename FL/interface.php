<?php
$startTime = microtime(true);
require_once dirname(__FILE__) . '/common/Common.php';

function checkSignature()
{
  $signature = $_GET["signature"];
  $timestamp = $_GET["timestamp"];
  $nonce = $_GET["nonce"];

  $token = WEIXIN_TOKEN;
  $tmpArr = array($token, $timestamp, $nonce);
  sort($tmpArr, SORT_STRING);
  $tmpStr = implode( $tmpArr );
  $tmpStr = sha1( $tmpStr );

  if( $tmpStr == $signature ){
    return true;
  }else{
    return false;
  }
}
function getIp(){
  if(isset($_SERVER)){
    if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
      $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }else if(isset($_SERVER["HTTP_CLIENT_IP"])){
      $realip = $_SERVER["HTTP_CLIENT_IP"];
    }else{
      $realip = $_SERVER["REMOTE_ADDR"];
    }
  }else{
    if(getenv("HTTP_X_FORWARDED_FOR")){
      $realip = getenv("HTTP_X_FORWARDED_FOR");
    }else if(getenv("HTTP_CLIENT_IP")){
      $realip = getenv("HTTP_CLIENT_IP");
    }else{
      $realip = getenv("REMOTE_ADDR");
    }
  }
  return $realip;
}
/**
 * 服务器配置（用于接收用户信息）
 */
if(checkSignature()) {
  if($_GET["echostr"]) {
    echo $_GET["echostr"];
    interface_log( 	INFO, EC_OK, 'success verfied!');
    exit(0);
  }
} else {
  //恶意请求：获取来来源ip，并写日志
  $ip = getIp();
  echo "illegal request!";
  interface_log( 	ERROR, EC_ILLEGAL_VERIFY_REQUEST, 'malicious: ' . $ip);
  exit(0);
}

function getWeChatObj($toUserName) {
/*	if($toUserName == USERNAME_FINDFACE) {
    require_once dirname(__FILE__) . '/class/WeChatCallBackFindFace.php';
    return new WeChatCallBackFindFace();
  }
  if($toUserName == USERNAME_MR) {
    require_once dirname(__FILE__) . '/class/WeChatCallBackMeiri10futu.php';
    return new WeChatCallBackMeiri10futu();
  }
  if($toUserName == USERNAME_ES) {
    require_once dirname(__FILE__) . '/class/WeChatCallBackEchoServer.php';
    return new WeChatCallBackEchoServer();
  }
  if($toUserName == USERNAME_MYZL) {
    require_once dirname(__FILE__) . '/class/WeChatCallBackMYZL.php';
    return new WeChatCallBackMYZL();
  }
 */
  if($toUserName == USERNAME_FUNNYLIFE) {
    //		require_once dirname(__FILE__) . '/class/WeChatCallBackMeiri10futu.php';
    //		return new WeChatCallBackMeiri10futu();

    //		require_once dirname(__FILE__) . '/class/WeChatCallBackFindFace.php';
    //		return new WeChatCallBackFindFace();
    interface_log(INFO, EC_OK, "***** 调用      FunnyLife实现 *****");

    require_once dirname(__FILE__) . '/class/WeChatCallBackFunnyLife.php';
    return new WeChatCallBackFunnyLife();
  }
  require_once dirname(__FILE__) . '/class/WeChatCallBack.php';
  return  new WeChatCallBack();
}
function exitErrorInput(){

  echo 'error input!';
  interface_log(INFO, EC_OK, "***** interface request end *****");
  interface_log(INFO, EC_OK, "*********************************");
  interface_log(INFO, EC_OK, "");
  exit ( 0 );
}

$postStr = file_get_contents ( "php://input" );

interface_log(INFO, EC_OK, "");
interface_log(INFO, EC_OK, "***********************************");
interface_log(INFO, EC_OK, "***** interface request start *****");
interface_log(INFO, EC_OK, 'request:' . $postStr);
interface_log(INFO, EC_OK, 'get:' . var_export($_GET, true));

if (empty ( $postStr )) {
  interface_log ( ERROR, EC_INVALID_INPUT, "error input!" );
  exitErrorInput();
}
// 获取参数
$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
if(NULL == $postObj) {
  interface_log(ERROR, EC_INVALID_XML_DECODE, "can not decode xml");	
  exit(0);
}

$toUserName = ( string ) trim ( $postObj->ToUserName );
if (! $toUserName) {
  interface_log ( ERROR, EC_INVALID_INPUT, "error input!" );
  exitErrorInput();
} else {
  $wechatObj = getWeChatObj ( $toUserName );
}
$ret = $wechatObj->init ( $postObj );
if (! $ret) {
  interface_log ( ERROR, EC_INVALID_INPUT, "error input!" );
  exitErrorInput();
}
$wechatObj->process ();

interface_log(INFO, EC_OK, "***** interface request end *****");
interface_log(INFO, EC_OK, "*********************************");
interface_log(INFO, EC_OK, "");

$useTime = microtime(true) - $startTime;
interface_log ( INFO, EC_TIME_EXCEED, "cost time:" . $useTime . " " . ($useTime > 4 ? "warning" : "") );
?>
