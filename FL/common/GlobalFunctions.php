<?php

/**
 * FunnyLife implemention
 * @author pacozhong
 * modified by Frank
 * 
 */
require_once dirname(__FILE__).'/GlobalDefine.php';
require_once dirname(__FILE__).'/MiniLog.php';

//定义日志级别
define("DEBUG", "DEBUG");
define("INFO", "INFO");
define("ERROR", "ERROR");
define("STAT", "STAT");
/**
 *默认打开所有的日志文件
 */
function isLogLevelOff($logLevel){
    //ROOT_PATH在Globaldefine.php中定义为当前文件的上一级目录
    $swithFile = ROOT_PATH . 'log/' . 'NO_' .$logLevel;
    if(file_exists($swithFile)){
       return true;
   }else{
       return false;
   }
}

/**
 * 日志函数的入口
 * 
 */
function wt_log($confName, $logLevel, $errorCode, $logMessage="no error msg"){
    if(isLogLevelOff($logLevel)){
       return;
   }
   $st = debug_backtrace();

    $function = '';//调用wt_log的函数名
    $file = '';
    $line = '';
    foreach($st as $item){
       if($file){
           $function = $item['function'];
           break;
       }
       if($item['function'] == 'interface_log'){
           $file = $item['file'];
           $line = $item['line'];
       }
   }
   $function = $function ? $function : 'main';

    //为了缩短日志的输出, file 只取最后一部分文件名 
   $file = explode("/", rtrim($file, '/'));
   $file = $file[count($file) - 1];
    //组装日志的头部
   $prefix = "[$file][$function][$line][$logLevel][$errorCode] ";
   if($logLevel == INFO || $logLevel == STAT){
       $prefix = "[$logLevel]";
   }
   $logFileName = $confName."_".strtolower($logLevel);
   MiniLog::instance(ROOT_PATH . "log")->log($logFileName, $prefix.$logMessage);
   if(isLogLevelOff("DEBUG") || $logLevel == "DEBUG"){
       return;
   }else{
       MiniLog::instance(ROOT_PATH."log")->log($confName."_"."debug", $prefix.$logMessage);
   }
}
/**
 * 实际用到的日志函数
 */
function interface_log($logLevel, $errorCode, $logMessage="no error msg"){
    wt_log('interface', $logLevel, $errorCode, $logMessage);
}


/**
 *
 *  * @desc 封装curl的调用接口，post的请求方式
 *
 *   */
function doCurlPostRequest($url, $requestString, $timeout = 5) {   

    if($url == "" || $requestString == "" || $timeout <= 0){
       return false;
   }
   $con = curl_init((string)$url);
   curl_setopt($con, CURLOPT_HEADER, false);
   curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);
   curl_setopt($con, CURLOPT_POST, true);
   curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
   curl_setopt($con, CURLOPT_TIMEOUT, (int)$timeout);

   return curl_exec($con);
}  

/**
 *  * @desc 封装curl的调用接口，get的请求方式
 *   */
function doCurlGetRequest($url, $data = array(), $timeout = 10) {
    if($url == "" || $timeout <= 0){
       return false;
   }

   if($data != array()) {
       $url = $url . '?' . http_build_query($data);	
   }

   $con = curl_init((string)$url);
   curl_setopt($con, CURLOPT_HEADER, false);
   curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
   curl_setopt($con, CURLOPT_TIMEOUT, (int)$timeout);

   return curl_exec($con);
}
//获取当前时间，毫秒级别,如果startTime传入，则计算当前时间与startTime的时间差
function getMillisecond($startTime = false) {
    $endTime = microtime(true) * 1000;

    if($startTime !== false) {
       $consumed = $endTime - $startTime;
       return round($consumed);
   }
   return $endTime;
}
/**
 * 对data进行可逆加密
 * @param  data为用户密码
 * @param  key为用户的userid
 * @return  加密后的字符串
 */
function encrypt($data, $key)
{
  $key = md5($key);
  $x  = 0;
  $len = strlen($data);
  $l = strlen($key);
  $char = $str = '';
  for ($i = 0; $i < $len; $i++)
  {
    if ($x == $l) 
    {
      $x = 0;
    }
    $char .= $key{$x};
    $x++;
  }
  for ($i = 0; $i < $len; $i++)
  {
    $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
  }
  return base64_encode($str);
}
/**
 * 对data进行可解密
 * @param  data为加密后的密码
 * @param  key为用户的userid
 * @return  原密码
 */
function decrypt($data, $key)
{
  $key = md5($key);
  $x = 0;
  $data = base64_decode($data);
  $len = strlen($data);
  $l = strlen($key);
  $char = $str = '';
  for ($i = 0; $i < $len; $i++)
  {
    if ($x == $l) 
    {
      $x = 0;
    }
    $char .= substr($key, $x, 1);
    $x++;
  }
  for ($i = 0; $i < $len; $i++)
  {
    if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1)))
    {
      $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
    }
    else
    {
      $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
    }
  }
  return $str;
}
?>
