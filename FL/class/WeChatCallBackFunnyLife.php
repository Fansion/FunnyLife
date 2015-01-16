<?php
require_once dirname(__FILE__) . '/WeChatCallBack.php';

/**
 * FunnyLife implemention
 * @author pacozhong
 * modified by Frank
 *
 */

class WeChatCallBackFunnyLife extends WeChatCallBack{

  private $_oTable;
  public function init($postObj) {
    if(false == parent::init($postObj)) {
      interface_log ( ERROR, EC_OTHER, "init fail!" );
      return false;
    }
    try {
      $this->_oTable = new SingleTableOperation("student","FL");//表操作模板类
    } catch (Exception $e) {
      interface_log ( ERROR, EC_DB_OP_EXCEPTION, $e->getMessage () );
      return $this->makeHint ( FF_HINT_INNER_ERROR );
    }
    return true;
  }
  public function process() {
    switch($this->_msgType)
    {
    case "event":
      //获取事件类型
      $form_Event = $this->_postObject->Event;
      //订阅事件
      if($form_Event=="subscribe")
      {
        $retStr = "你终于找到组织了！[愉快]\n\n".MENU_FL;
        $retStr = sprintf ( TEXT_TPL_FL, $this->_toUserName, $this->_fromUserName, time(), "text", $retStr);
      }
      break;
    case "text":
      //获取消息内容
      $contents = ( string ) trim ( $this->_postObject->Content );
      if (preg_match('(ccj|CCJ|Ccj)', $contents)) {
        $retStr = $this->_searchScore($contents);
      }else if (preg_match('(歌|曲|音|gq|乐)', $contents)) {
        if(preg_match('[^(歌曲|gq)]', $contents))
        {
          $arr = array('歌曲','gq','+','-','|','#','%','_','=','！','!','^',']','[','+','~');
          $contents = str_replace($arr, '', $contents);
          $contents_arr = explode('@', $contents);
          $music_title = $contents_arr[0];
          $music_author = '';
          if(count($contents_arr) == 2){
            $music_author = $contents_arr[1];
          }
          if(trim($music_title) == ""){
            $retStr = sprintf ( TEXT_TPL_FL, $this->_toUserName, $this->_fromUserName, time(), "text", MUSIC_MENU_FL);
          }else{
            $description = $music_title." ".$music_author;
            $url = $this->_getMusicUrl($music_title, $music_author);
            //如果找到 歌曲结果则播放，否则提示重新输入。
            if($url[0]){
              $musicurl = $url[1];
              //添加则无法播放
              //$hqmusicurl = $url[2];
              //
              $hqmusicurl = '';
              $retStr = sprintf( MUSIC_TPL_FL, $this->_toUserName, $this->_fromUserName,  time(), "music", $music_title,$description,$musicurl,$hqmusicurl);
            }else{
              $retStr = "没找到这首歌，换首点播吧。\n";
              $retStr .= MUSIC_MENU_FL;
              $retStr = sprintf ( TEXT_TPL_FL, $this->_toUserName, $this->_fromUserName, time(), "text", $retStr);
            }
          }
        }else{
          $retStr = sprintf ( TEXT_TPL_FL, $this->_toUserName, $this->_fromUserName, time(), "text", MUSIC_MENU_FL);
        }
      }else if (preg_match('(翻译|fy|Fy|fY|FY)', $contents)) {
        $ch_country_name = array('中','西','日','韩','法','俄','泰','葡');
        $en_country_name = array('zh','spa','jp','kor','fra','ru','th','pt');
        if(preg_match('[^(翻译|fy|Fy|fY|FY)]', $contents))
        {
          if(preg_match('[^(fy|Fy|fY|FY)]',$contents)){
            $from = mb_substr($contents,2,1,'UTF-8');
            $to = mb_substr($contents,3,1, 'UTF-8');
            $from = str_replace($ch_country_name, $en_country_name, $from);
            $to = str_replace($ch_country_name, $en_country_name, $to);
            $contents = mb_substr($contents, 4, mb_strlen($contents), 'UTF-8');
          }else{
            $arr = array('翻译','-','|','#','%','_','=','！','!','^',']','[','+','~');
            $contents = str_replace($arr, '', $contents);
          }
          if(empty($contents))
          {
            $retStr = "请按规则输入要翻译的内容。\n";
            $retStr .= TRANS_MENU_FL;
            $retStr = sprintf ( TEXT_TPL_FL, $this->_toUserName, $this->_fromUserName, time(), "text", $retStr);
          }
          else{
            if(empty($from) || empty($to)){
              $retStr = sprintf ( TEXT_TPL_FL, $this->_toUserName, $this->_fromUserName, time(), "text", $this->_responseTranslate('auto', 'auto', $contents));
            }else{
              $retStr = sprintf ( TEXT_TPL_FL, $this->_toUserName, $this->_fromUserName, time(), "text", $this->_responseTranslate($from, $to, $contents));
            }
          }
        }
        else{
          $retStr = "请按规则输入要翻译的内容。\n";
          $retStr .= TRANS_MENU_FL;
          $retStr = sprintf ( TEXT_TPL_FL, $this->_toUserName, $this->_fromUserName, time(), "text", $retStr);
        }
      }else if(preg_match('(历史|ls)', $contents)) {
        $retStr = $this->_getHistory();
        $retStr = trim($retStr);
        if(empty($retStr))
        {
          $retStr = "查询历史数据出错！\n";
          $retStr .= $this->_getJoke();
        }
        $retStr = sprintf ( TEXT_TPL_FL, $this->_toUserName, $this->_fromUserName, time(), "text", $retStr);
      }else if(preg_match('(笑话|故事|xh|gs)',$contents)) {
        $retStr = $this->_getJoke();
        $retStr = sprintf ( TEXT_TPL_FL, $this->_toUserName, $this->_fromUserName, time(), "text", $retStr);
      }else if(preg_match('(5s)',$contents)) {
        // 失效
        $title0 = "小闷水果店 报价";
        $description0 = "IPhone 5S";
        $picurl0 = "";
        $url0 = "http://appled.cc/board/price";
        $ynd = date('Y-n-d', time());
        $title1 = "morning";
        $description1 = "IPhone 5S上午报价";
        $picurl1 = IP_URL._5S_PICS_URL.$ynd."-morning";
        $url1 = $picurl1;
        $title2 = "afternoon";
        $description2 = "IPhone 5S下午报价";
        $picurl2 = IP_URL._5S_PICS_URL.$ynd."-afternoon";
        $url2 = $picurl2;
        $title = array($title0, $title1,$title2);
        $description = array($description0, $description1,$description2);
        $url = array($url0, $url1,$url2);
        $picurl = array($picurl0, $picurl1,$picurl2);
        $count=count($title);
        $retStr = $this->_responseNews($this->_toUserName,$this->_fromUserName,$count, $title,$description,$picurl,$url);
      }else if(preg_match('(电影|推荐)',$contents)) {
        $title1 = "横道世之介";
        $description1 = "";
        $picurl1 = IP_URL.PIC_URL."hengdao.jpg";
        $url1 = "http://movie.douban.com/subject/10484041/";
        $title2 = "天使爱美丽";
        $description2 = "";
        $picurl2 = IP_URL.PIC_URL."amelia.jpg";
        $url2 = "http://movie.douban.com/subject/1292215/";
        $title = array($title1,$title2);
        $description = array($description1,$description2);
        $url = array($url1,$url2);
        $picurl = array($picurl1,$picurl2);
        $count=count($title);
        $retStr = $this->_responseNews($this->_toUserName,$this->_fromUserName,$count, $title,$description,$picurl,$url);
      }else if(preg_match('(DH|Dh|dh|导航|ss|SS|Ss|天气|tq|TQ|Tq)',$contents)) {
        $contents = str_replace('ss', '', $contents);
        $_oTable = new SingleTableOperation("person","FL");//表操作模板类

        $args = array('openid' => $this->_fromUserName);
        $ret = $_oTable->getObject($args);
        $newsTag = false;//标志是否发图文
        $count = 0;
        $titleArr = array();
        $descriptionArr = array();
        $urlArr = array();
        $picurlArr = array();
        $in = count($ret)-1;
        if(!empty($ret)){
          $oldTime = substr($ret[$in]['time'], 0, -6);
          $newTime = date('Y-m-d H', time());
          $from_Location_X = trim($ret[$in]['latitude'], ' \r\n');
          $from_Location_Y = trim($ret[$in]['longitude'], ' \r\n');
          $dst_Location_X='31.838976';
          $dst_Location_Y='117.257591';
          $from_Location_Scale = trim($ret[$in]['scale'], ' \r\n');
          $from_Location_Label = trim($ret[$in]['label'], ' \r\n');
          if($oldTime != $newTime){
            $retStr = '你的位置信息为一小时以前的旧数据，请发送新的位置数据。';
          }else{
            if($contents == 'dh' || $contents == '导航' || $contents == 'DH' || $contents == 'Dh'){
              $dst_Location_Label='中国科技大学(西校区)';
              $retStr = "<a href=\"http://api.map.baidu.com/direction?origin=latlng:{$from_Location_X},{$from_Location_Y}|name:我所在地&destination=latlng:{$dst_Location_Label}&mode=driving&region=合肥&output=html&src=USTC|FunnyLife\">点击导航到FunnyLife所在地</a>";
              $paras = array('{from_longitute}','{from_latitute}','{to_longitute}','{to_latitute}');
              $values = array($from_Location_Y, $from_Location_X, $dst_Location_Y, $dst_Location_X);
              //$retStr = "<a href=\"".str_replace($paras, $values, $this->tpl->ipUrlForLocalSearch)."\">点击导航至FunnyLife所在地</a>";
            }else if($contents == 'tq' || $contents == 'Tq' || $contents == 'TQ' || $contents == '天气'){
              //$weather_res = $this->tool->getWeather($from_Location_X,$from_Location_Y,$from_Location_Scale,$from_Location_Label);
              //$this->tool->responseText($fromUsername,$toUsername,$weather_res, $this->tpl->textTpl);
              //今明后三天天气情况
              $weather_res = $this->_getWeatherForNews($from_Location_X,$from_Location_Y,$from_Location_Scale,$from_Location_Label);
              if(count($weather_res) != 1){
                $title1 = $weather_res[0].'天气预报';
                $title2 = mb_substr($weather_res[1], 0, mb_strlen($weather_res[1])-6);
                $title3 = mb_substr($weather_res[2], 0, mb_strlen($weather_res[2])-6);
                $title4 = mb_substr($weather_res[3], 0, mb_strlen($weather_res[3])-6);
                $title5 = $this->_getAirQualityForNews($weather_res[0]);
                $title = array($title1, $title2,$title3, $title4,  $title5);
                $description = array('','','','','');

                $url = array('','','','','');
                $picurl = array('','','','','');
                //去掉首位空格
                $sta2 = trim(mb_substr($weather_res[1], mb_strlen($weather_res[1])-6, 6));
                $sta3 = trim(mb_substr($weather_res[2], mb_strlen($weather_res[2])-6, 6));
                $sta4 = trim(mb_substr($weather_res[3], mb_strlen($weather_res[3])-6, 6));
                $sta_arr = array($sta2, $sta3, $sta4);
                $index = 1;
                $url_prefix = IP_URL.PIC_URL."weather/";
                $picurl[0] = $url_prefix."weather_background.jpg";
                $picurl[4] = $url_prefix."weather_quality.jpg";
                $wea_arr = array("暴雨","小雨","中雨","大雨","多云","晴","小雪","中雪","大雪","阴");
                $pic_arr = array("weather_xiaoyu.png", "weather_xiaoyu.png","weather_xiaoyu.png","weather_xiaoyu.png","weather_duoyun.png","weather_qing.png","weather_xiaoxue.png","weather_xiaoxue.png","weather_xiaoxue.png","weather_yin.png");
                foreach($sta_arr as $sta)
                {
                  $picurl[$index] = str_replace($wea_arr, $pic_arr, $url_prefix.$sta);
                  $index++;
                }
                $count = count($title);
                $retStr = $this->_responseNews($this->_toUserName,$this->_fromUserName,$count, $title,$description,$picurl,$url);
                $newsTag = true;
              }else{
                $retStr = $weather_res;
              }
            }else{
              $serviceUrl = BAIDULOCALSEARCH;
              $paras = array('{longitude}','{latitude}','{keyword}','{appkey}','{radius}');
              $values = array($from_Location_Y, $from_Location_X, $contents, BAIDU_APPKEY, '500');
              $serviceUrl = str_replace($paras, $values, $serviceUrl);
              $services = file_get_contents($serviceUrl);
              $serviceObj = simplexml_load_string($services);

              $paras = array('{from_longitute}','{from_latitute}','{to_longitute}','{to_latitute}');
              if(isset($serviceObj->poiList)){
                $count = 0;
                $numOfPoint = count($serviceObj->poiList->point);
                $titleArr[$count] = "共有".($numOfPoint>5?5:$numOfPoint)."个与".$contents."相关的结果";
                $descriptionArr[$count] = "点击进入对应导航";
                $urlArr[$count] = "";
                $picurlArr[$count] = "";
                for($count=1; $count < 6 && $count < $numOfPoint+1; $count++){
                  $h_name = $serviceObj->poiList->point[$count-1]->name;
                  $h_addr = $serviceObj->poiList->point[$count-1]->address;
                  $h_tel = $serviceObj->poiList->point[$count-1]->telephone;
                  $h_distance = $serviceObj->poiList->point[$count-1]->distance;
                  $h_longitute = $serviceObj->poiList->point[$count-1]->location->lng;
                  $h_latitute = $serviceObj->poiList->point[$count-1]->location->lat;
                  $retStr = "地址:{$h_addr} 电话:{$h_tel} 距离:{$h_distance}米 \n";

                  $title = "{$h_name}\n".$retStr;
                  $description = "";
                  $picurl = "";
                  $values = array($from_Location_Y, $from_Location_X, $h_longitute, $h_latitute);
                  //需进行位置修正后再将经度纬度值传过去
                  $url = str_replace($paras, $values, IPURLFORLOCALSEARCH);
                  $titleArr[$count] = $title;
                  $descriptionArr[$count] = $description;
                  $urlArr[$count] = $url;
                  $picurlArr[$count] = $picurl;
                }
                $count=count($titleArr);
                $retStr = $this->_responseNews($this->_toUserName,$this->_fromUserName,$count,$titleArr,$descriptionArr,$picurlArr,$urlArr);
                $newsTag = true;
              }else{
                $retStr = "无对应名称的位置服务被搜索到，请更改后重新输入进行搜索。\n";
              }
            }
          }
        }else{
          $retStr = "请先发送您的位置给我，基于您发的位置数据进行周边搜索。";
        }
        if(!$newsTag){
          $retStr = sprintf ( TEXT_TPL_FL, $this->_toUserName, $this->_fromUserName, time(), "text", $retStr);
        }
      }else if(preg_match('(H|h|帮助|help)',$contents)) {
        $retStr = MENU_FL."\n".HELP_FL;
        $retStr = sprintf ( TEXT_TPL_FL, $this->_toUserName, $this->_fromUserName, time(), "text", $retStr);
      }else{
        $retStr = "感谢留言，我会尽快给您回复！";
        //调用免费的xiaojo机器人接口回复文字消息
        #			$tempStr = $this->_xiaojo($contents);
        #			//调用小九无回答，回复故事或目录代替
        #			if(strpos($tempStr,"-") !== false || empty($tempStr)){
        #				$retStr = "NB的人类你的话显然已经超出了我的理解范围，请减少字数或者换一个话题呗。\n\n".HELP_FL."-/::(";
        #			}else{
        #				$retStr = $tempStr."-/::)";
        #			}
        $retStr = sprintf ( TEXT_TPL_FL, $this->_toUserName, $this->_fromUserName, time(), "text", $retStr);
      }
      break;
    case "location":
      $from_Location_X=$this->_postObject->Location_X;
      $from_Location_Y=$this->_postObject->Location_Y;
      $from_Location_Scale=$this->_postObject->Scale;
      $from_Location_Label=$this->_postObject->Label;
      // $dst_Location_X='31.838976';
      // $dst_Location_Y='117.257591';
      // $dst_Location_Label='中国科技大学(西校区)';
      $_oTable = new SingleTableOperation("person","FL");//表操作模板类
      $args = array('openid' => $this->_fromUserName, 'longitude' => $from_Location_Y, 'latitude' => $from_Location_X, 'scale' => $from_Location_Scale, 'label' => $from_Location_Label);
      #replace into
      $_oTable->_addObject($args, 'replace');
      $retStr = '位置数据已更新，请查询天气或导航或周边搜索。';
      $retStr = sprintf ( TEXT_TPL_FL, $this->_toUserName, $this->_fromUserName, time(), "text", $retStr);

      break;
    case "image":
      //获取图片地址
      $from_PicUrl = $this->_postObject->PicUrl;
      //创建图片名称
      $filename = $fromUsername.date("YmdHis").".jpg";//i有前导0的分钟数
      $this->_getPic($from_PicUrl, $filename);
      $retStr = sprintf ( TEXT_TPL_FL, $this->_toUserName, $this->_fromUserName, time(), "text", "pics has been saved on the server!");
      break;
    }
    echo $retStr;
    interface_log ( INFO, EC_OK, "retStr:".$retStr );
  }
  //获取图片
  private function _getPic($picUrl,$filename)
  {
    $output = file_get_contents($picUrl);
    if(!empty($output)){
      file_put_contents($filename,$output);
    }
  }
  private function _searchScore($contents) {
    $arr = array('ccj', 'CCJ', 'Ccj');
    $contents = str_replace($arr, '', $contents);

    $args = array('openid' => $this->_fromUserName);
    $ret = $this->_oTable->getObject($args);

    $needToGetScore = true; //是否需要查询成绩
    $userid = '';
    $userpwd = '';
    $paras = explode('-', trim($contents,'-'));
    if(!empty($ret) ){//已经注册 规定一个openid对应一个yjs账户
      $userid = $ret[0]['userid'];
      $userpwd = decrypt($ret[0]['userpwd'], $this->_fromUserName);
      if(count($paras) == 1){
        if($paras[0] == 'jb'){//输入为ccj-jb
          //解除绑定
          $args = array('userid' => $userid);
          $this->_oTable->delObject($args);
          $retStr = "您的账户密码已经解除绑定到系统！\n";
          $retStr .= SEARCHSCORE_MENU_FL;
          $needToGetScore = false;
        }else{
          $retStr = "您的账户已经绑定账户密码到系统！\n";
          $retStr .= "成绩如下：\n";
        }
      }else if(count($paras) == 2){//输入为ccj-u-p格式
        if(!preg_match('/[A-Z]{2}\d{8}/', $paras[0])) {
          $retStr = "您的账户已经绑定账户密码到系统,输入格式错误,请按格式输入进行账户密码重新绑定！\n";
          $retStr .= SEARCHSCORE_MENU_FL;
          $needToGetScore = false;
        }else{
          $args = array('userid' => $paras[0], 'userpwd' => encrypt($paras[1], $this->_fromUserName));
          $wheres = array('openid' => $fromUsername);
          $this->_oTable->updateObject($args,$wheres);
          $userid = $paras[0];
          $userpwd = $paras[1];
          $retStr = "您成功绑定新的帐号密码到系统！\n";
          $retStr .= "成绩如下：\n";
        }
      }else{
        $retStr = "您的账户已经绑定账户密码到系统！\n";
        $retStr .= "成绩如下：\n";
      }
    }else{//未注册
      if(count($paras) == 2){//输入为ccj-u-p
        if(!preg_match('/[A-Z]{2}\d{8}/', $paras[0])) {
          $retStr = "您的输入格式错误,请按格式输入进行账户密码重新绑定！\n";
          $retStr .= SEARCHSCORE_MENU_FL;
          $needToGetScore = false;
        }else{
          $args = array('openid' => $this->_fromUserName,'userid' => $paras[0], 'userpwd' => encrypt($paras[1], $this->_fromUserName));
          $this->_oTable->addObject($args);
          $userid = $paras[0];
          $userpwd = $paras[1];
          $retStr = "您成功绑定帐号密码到系统！\n";
          $retStr .= "成绩如下：\n";
        }
      }else{
        $retStr = "您尚未绑定账户密码到系统,请先按格式输入进行绑定！\n";
        $retStr .= SEARCHSCORE_MENU_FL;
        $needToGetScore = false;
      }
    }
    interface_log(INFO, EC_OK, 'userid:'.$userid."userpwd:".$userpwd);
    if($needToGetScore){
      $tempStr = 'try';
      //识别验证码，当返回正确成绩（验证码正确用户名密码正确）跳出循环
      while(preg_match('(try)', $tempStr)){
        unset($ret);
        //传参数执行获取成绩的py文件
        exec(escapeshellcmd('python script/crawlScore/getScore '.escapeshellarg($userid).'  '.escapeshellarg($userpwd)), $ret, $status);
        //调试时2>&1将标准错误输出到标准输出
        //exec('python script/crawlScore/getScore '.$userid.'  '.$userpwd.' 2>&1', $ret, $status);
        $tempStr = '';
        interface_log(INFO, EC_OK, "exec 返回ret:".var_dump($ret));
        interface_log(INFO, EC_OK, "exec 返回ret:".$ret[0]);
        foreach($ret as $item){
          $tempStr .= $item;
          $tempStr .= "\n";
        }
        interface_log(INFO, EC_OK, "exec 执行status:".$status);
        interface_log(INFO, EC_OK, "exec 返回retStr:*".$tempStr."-");
        //或者error（用户名或密码错误）跳出循环
        if(preg_match('(error)',$tempStr)){
          $retStr = "输入的用户名或密码有误，请输入正确的用户名或密码进行绑定！\n";
          $tempStr = SEARCHSCORE_MENU_FL;
          $args = array('userid' => $userid);
          $this->_oTable->delObject($args);//删除保存的用户
          break;
        }
      }
      $retStr .= $tempStr;
    }
    $retStr = sprintf ( TEXT_TPL_FL,  $this->_toUserName, $this->_fromUserName, time(), "text", $retStr);
    return $retStr;
  }
  private function _getMusicUrl($title, $author){
    $flag = false;
    $requestUrl = "http://box.zhangmen.baidu.com/x?op=12&count=1&title={TITLE}$$"."{AUTHOR}$$$$";
    $requestUrl = str_replace('{TITLE}',urlencode($title),$requestUrl);
    $requestUrl = str_replace('{AUTHOR}',urlencode($author),$requestUrl);

    // xml
    interface_log(INFO, EC_OK, "requestUrl:".$requestUrl);
    $musicXml = file_get_contents($requestUrl);
    if(empty($musicXml)) {
      interface_log(INFO, EC_OK, "musicXml: empty");
    }else{
      // interface_log(INFO, EC_OK, "musicXml: ".iconv("gb2312", "utf-8", $musicXml));
      interface_log(INFO, EC_OK, "musicXml: ".mb_convert_encoding($musicXml, "UTF-8", "gb2312"));
    }
    # failed to get musicObj, donnot know why, in the test.php everything is ok with same code.
    $musicObj = simplexml_load_string($musicXml, 'SimpleXMLElement', LIBXML_NOCDATA);
    if(empty($musicObj)) {
      interface_log(INFO, EC_OK, "musicObj: empty");
    }

    #		$musicXml = iconv("gb2312", "utf-8", file_get_contents($requestUrl));
    #		$musicObj = simplexml_load_string($musicXml, 'SimpleXMLElement', LIBXML_NOCDATA);

    $urlEncode = $musicObj->url[0]->encode;
    $urlEncode = substr($urlEncode,0,strrpos($urlEncode, '/')+1);//记录在最后一个/之前的字串
    interface_log(INFO, EC_OK, "urlEncode:".$urlEncode);
    $urlDecode = $musicObj->url[0]->decode;
    if(strpos($urlDecode, "&") != FALSE) {
      $urlDecode = substr($urlDecode,0,strpos($urlDecode, "&"));//记录在第一个&之前的字串
    }
    interface_log(INFO, EC_OK, "urlDecode:".$urlDecode);
    $url = $urlEncode.$urlDecode;
    interface_log(INFO, EC_OK, "url:".$url);

    $durlEncode = $musicObj->durl[0]->encode;
    $durlEncode = substr($durlEncode,0,strrpos($durlEncode, '/')+1);//记录在最后一个/之前的字串
    interface_log(INFO, EC_OK, "durlEncode:".$durlEncode);
    $durlDecode = $musicObj->durl[0]->decode;
    if(strpos($urlDecode, "&") != FALSE) {
      $durlDecode = substr($durlDecode,0,strpos($durlDecode, "&"));//记录在第一个&之前的字串
    }
    interface_log(INFO, EC_OK, "durlDecode:".$durlDecode);
    $durl = $durlEncode.$durlDecode;
    interface_log(INFO, EC_OK, "durl:".$durl);

    if(!empty($urlEncode) && !empty($urlDecode) && !empty($durlEncode) && !empty($durlDecode))
    {
      $flag = true;
    }
    interface_log(INFO, EC_OK, "flag:".$flag);
    interface_log(INFO, EC_OK, "url:".$url);
    interface_log(INFO, EC_OK, "durl:".$durl);
    return array($flag, $url, $durl);
  }
  private function _responseTranslate($from, $to, $content){
    //http://openapi.baidu.com/public/2.0/bmt/translate?client_id=YourApiKey&q=today&from=auto&to=auto
    $url = TRANS_URL;
    $url = str_replace('{YourApiKey}', TRANS_API_KEY, $url);
    $url = str_replace('{from}', $from, $url);
    $url = str_replace('{to}', $to, $url);
    //$url = str_replace('{YourContent}', urlencode($content), $url);
    $url = str_replace('{YourContent}', $content, $url);

    $result = file_get_contents($url);
    //{"from":"zh","to":"en","trans_result":[{"src":"\u6211\u7231\u4f60","dst":"I love you"}]}
    $json_result = json_decode($result, true);
    $trans_result = $json_result['trans_result'][0];

    return $trans_result['dst'];
  }
  private function _getJoke() {
    $joke_url = JOKE_URL;
    $joke = file_get_contents($joke_url);
    $joke = str_replace("\r", "", $joke);
    $joke = str_replace("\n", "", $joke);
    $joke = str_replace(" ", "", $joke);
    interface_log(INFO, EC_OK, "joke:".$joke_url);
    interface_log(INFO, EC_OK, "joke:".$joke);
    return $joke;
  }
  private function _getHistory() {
    $history_url = HISTORY_URL;
    $history = file_get_contents($history_url);
    $history = str_replace("\"","",$history);
    $history = str_replace("\\","",$history);
    $history_arr = preg_split("/n/", $history);
    $history_arr_reverse = array_reverse($history_arr);
    $count = 0;
    foreach($history_arr_reverse as $history_item)
    {
      if($count < HISTORY_RESULT_NUM){
        $result .= $history_item."\n";
      }
      $count++;
    }
    return $result;
  }

  private function _responseNews($fromUsername,$toUsername,$count, $title,$description,$picurl,$url){
    $msgType = "news";
    $newsTplBegin = "<xml>
      <FromUserName><![CDATA[%s]]></FromUserName>
      <ToUserName><![CDATA[%s]]></ToUserName>
      <CreateTime>%s</CreateTime>
      <MsgType><![CDATA[%s]]></MsgType>
      <ArticleCount>%s</ArticleCount>
      <Articles>";
    $newsTplEndStr = "</Articles>
    <FuncFlag>1</FuncFlag>
    </xml>";
    $newsTplBeginStr = sprintf($newsTplBegin,$fromUsername,$toUsername,time(),$msgType,$count);
    $newsTplBodyStr = "";
    for($i = 0; $i < $count; $i++){
      $newsTplBodyStr.="<item>
        <Title><![CDATA[".$title[$i]."]]></Title>
        <Description><![CDATA[".$description[$i]."]]></Description>
        <PicUrl><![CDATA[".$picurl[$i]."]]></PicUrl>
        <Url><![CDATA[".$url[$i]."]]></Url>
        </item>";
    }
    $resultStr = $newsTplBeginStr.$newsTplBodyStr.$newsTplEndStr;
    return $resultStr;
  }
  private function _getWeatherForNews($from_Location_X,$from_Location_Y,$from_Location_Scale,$from_Location_Label) {
    //地址解析使用百度地图API
    $map_api_url = MAP_API_URL;
    //坐标类型
    $map_coord_type="&coord_type=wgs84";
    //建立抓取地址
    $addrUrl = $map_api_url.$map_coord_type."&location=".$from_Location_X.",".$from_Location_Y;
    //http://api.map.baidu.com/geocoder?coord_type=wgs84&location=31.836935,117.258301
    $geocoder = file_get_contents($addrUrl);
    if(!empty($geocoder))
    {
      //匹配出城市
      preg_match_all("/\<city\>(.*?)\<\/city\>/",$geocoder,$city);//默认为PREG_PATTERN_ORDER
      $city=str_replace(array("市","县","区"),array("","",""),$city[1][0]);
      $weather_res[0] = $city;

      //通过新浪接口查询天气的链接
      $weather_api_url = WEATHER_API_URL;
      //新浪接口需要GBK编码
      $city="&city=".urlencode(iconv("UTF-8","GBK",$city));
      //查询当天
      $day = 0;
      for(; $day < 3; $day++){
        //抓取天气
        $weather = file_get_contents($weather_api_url.$city."&day=".$day);
        if(!empty($weather) && strstr($weather,"Weather"))
        {
          //用正则表达式获取数据
          preg_match_all("/\<city\>(.*?)\<\/city\>/",$weather,$w_city);
          preg_match_all("/\<status2\>(.*?)\<\/status2\>/",$weather,$w_status2);
          preg_match_all("/\<status1\>(.*?)\<\/status1\>/",$weather,$w_status1);
          preg_match_all("/\<temperature2\>(.*?)\<\/temperature2\>/",$weather,$w_temperature2);
          preg_match_all("/\<temperature1\>(.*?)\<\/temperature1\>/",$weather,$w_temperature1);
          preg_match_all("/\<direction2\>(.*?)\<\/direction2\>/",$weather,$w_direction2);
          preg_match_all("/\<power2\>(.*?)\<\/power2\>/",$weather,$w_power2);
          preg_match_all("/\<chy_shuoming\>(.*?)\<\/chy_shuoming\>/",$weather,$w_chy_shuoming);
          preg_match_all("/\<savedate_weather\>(.*?)\<\/savedate_weather\>/",$weather,$w_savedate_weather);
          //如果天气变化一致
          if($w_status2[1][0]==$w_status1[1][0])
          {
            $w_status = $w_status2[1][0];
          }else{
            $w_status = $w_status2[1][0]."转".$w_status1[1][0];
          }
          //将获取到的数据拼接起来
          $weather_temp=array(
            " ".$w_savedate_weather[1][0]."\n",
            "气候:".$w_status,
            "气温:".$w_temperature2[1][0]."-".$w_temperature1[1][0]."\n",
            "风向:".$w_direction2[1][0],
            "风速:".$w_power2[1][0]."   ",
            "".$w_status1[1][0],
          );
          $weather_res[$day+1] = implode(" ", $weather_temp);
        }
      }
    }else{
      $weather_res=array("今天天气获取失败！");
    }
    return $weather_res;
  }
  private function _getAirQualityForNews($city) {
    $air_quality_url = AIR_AUQLITY_URL;
    $air_quality_url.=$city;
    $air_quality = file_get_contents($air_quality_url);
    $air_quality = str_replace(array("[","]"),array("",""),$air_quality);
    $air = json_decode($air_quality,true);

    $quality = $air['Title']."\n".$air['Description'];
    return $quality;
  }
  //网络上免费的小九机器人接口，通过curl模拟post提交查询
  private function _xiaojo($keyword){
    $curlPost=array("chat"=>$keyword);
    $ch = curl_init();//初始化curl
    $xiaojoUrl = XIAOJO_URL;
    curl_setopt($ch, CURLOPT_URL, $xiaojoUrl);//抓取指定网页
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = trim(curl_exec($ch));//运行curl,返回数据或者""，"XX"或""
    curl_close($ch);
    //trim()如果不指定，默认删除字符串首尾的空白和其它字符
    //empty() 变量为空或为0的值均返回true,包括""/0/"0"/null/false/array()/var $var/没有任何属性的对象
    //empty只检测变量不检测函数
    if(!empty($data)){
      if(preg_match('(deleted|title)',$data))
      {
        $data = "-讲文明树新风。\n".($this->_getJoke());
      }elseif(preg_match('(xhjchat|微信)',$data)){
        $data = "-".($this->_getJoke());
      }
      return $data;
    }else{
      return "-".($this->_getJoke());
    }
  }
}
