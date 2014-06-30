<?php
class MiniLog{
    private static $_instance;
    private $_path;//日志目录
    private $_pid;//进程id
    private $_handleArr;//保存不同日志级别文件
    function __construct($path){
       $this->_path = $path;
       $this->_pid = getmygid();
   }
   private function __clone(){
   }
    /**
     * 单例函数
     */
    public static function instance($path = '/tmp'){
       if(!(self::$_instance instanceof self)){
           self::$_instance = new self($path);
       }
       return self::$_instance;
   }
    /**
     * 根据文件名获取文件fd
     */
    private function getHandle($fileName){
       if($this->_handleArr[$fileName]){
           return $this->_handleArr[$fileName];
       }
       date_default_timezone_set('PRC');
       $nowTime = time();
       $longSuffix = date('Ymd', $nowTime);
       $handle = fopen($this->_path.'/'.$fileName.$longSuffix.".log", "a");
       $this->_handleArr[$fileName] = $handle;
       return $handle;
   }
    /**
     * 向文件中写日志
     */
    public function log($fileName, $message){
       $handle = $this->getHandle($fileName);
       $nowTime = time();
       $longPreffix = date('Y-m-d H:i:s', $nowTime);
       fwrite($handle, "[$longPreffix][$this->_pid]$message\n");
       return true;
   }
    /**
     * 析构函数，关闭所有fd
     */
    function __destruct(){
       foreach($this->_handleArr as $key=>$item){
           if($item){
              fclose($item);
          }
      }
  }
}
?>
