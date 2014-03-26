<?php
//调用数据库操作框架类，参考zzy
require_once dirname(__FILE__)."/../../common/SingleTableOperation.php";

class Wechat{
    //类内部使用工具类提供的函数
    var $tool;
    //使用常用模板变量
    var $tpl;
    function Wechat($tool, $tpl)
    {
	$this->tool = $tool;
	$this->tpl = $tpl;
    }
    //创建工具对象
    function valid($token, $echoStr)
    {
	if($this->checkSignature($token)){
	    $this->tool->logger("开发验证通过!");
	    echo $echoStr;
	    $this->tool->traceBeginOrEnd("0");
	    exit;
	}else{
	    $this->tool->logger("开发验证未通过!");
	    exit;
	}
    }
    function checkSignature($token)
    {
	$signature = $_GET["signature"];
	$timestamp = $_GET["timestamp"];
	$nonce = $_GET["nonce"];	

	$tmpArr = array($token, $timestamp, $nonce);
	sort($tmpArr, SORT_STRING);
	//sort($tmpArr);
	$tmpStr = implode( $tmpArr );
	$tmpStr = sha1( $tmpStr );

	if( $tmpStr == $signature ){
	    return true;
	}else{
	    return false;
	}
    }
    function response()
    {
	//获取微信发送数据
	$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
	//返回回复数据
	if(!empty($postStr)){
	    //记录用户向微信服务器上发送的数据       
	    $this->tool->logger("\n 用户发给公众帐号的数据: \n".$postStr);
	    //解析数据
	    $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
	    //发送消息方ID
	    $fromUsername = $postObj->FromUserName;
	    //接收消息方ID
	    $toUsername = $postObj->ToUserName;
	    //消息类型
	    $form_MsgType = $postObj->MsgType; 
	    $resultStr = "";
	    switch($form_MsgType)
	    {
	    case "event":
		//获取事件类型
		$form_Event = $postObj->Event;
		//订阅事件
		if($form_Event=="subscribe")
		{
		    $contentStr = "你终于找到组织了！[愉快]\n\n";
		    $contentStr .= $this->tool->getMenu();
		    $this->tool->responseText($fromUsername,$toUsername,$contentStr,$this->tpl->textTpl);
		}
		break;
	    case "text":
		//获取消息内容
		$form_content = trim($postObj->Content);
		if(!empty($form_content))
		{
		    //针对yjs.ustc.edu.cn进行爬取成绩
		    if(preg_match('(ccj|CCJ|Ccj)',$form_content)){
			$arr = array('ccj', 'CCJ', 'Ccj');
			$form_content = str_replace($arr, '', $form_content);
			$objSTO = new SingleTableOperation("student","FL");//表操作模板类
			$objSTO->setTableName('student');//设置表名称
			$args = array('openid' => $fromUsername);
			$ret = $objSTO->getObject($args);
			$needToGetScore = true; //是否需要查询成绩
			$userid = '';
			$userpwd = '';
			$paras = explode('-', trim($form_content,'-'));
			if(!empty($ret) ){//已经注册 规定一个openid对应一个yjs账户,openid主键
			    $userid = $ret[0]['userid'];
			    $userpwd = $ret[0]['userpwd'];
			    if(count($paras) == 1){
				if($paras[0] == 'jb'){//输入为ccj-jb
				    //解除绑定
				    $args = array('userid' => $userid);
				    $objSTO->delObject($args);  
				    $contentStr = "您的账户密码已经解除绑定到系统！\n";
				    $contentStr .= $this->tool->getUsage(3);
				    $needToGetScore = false;
				}else{
				    $contentStr = "您的账户已经绑定账户密码到系统！\n";
				    $contentStr .= "成绩如下：\n";
				}
			    }else if(count($paras) == 2){//输入为ccj-u-p格式
				$args = array('userid' => $paras[0], 'userpwd' => $paras[1]);
				$wheres = array('openid' => $fromUsername);
				$objSTO->updateObject($args,$wheres);
				$userid = $paras[0];
				$userpwd = $paras[1];
				$contentStr = "您成功绑定新的帐号密码到系统！\n";
				$contentStr .= "成绩如下：\n";
			    }else{
				$contentStr = "您的账户已经绑定账户密码到系统！\n";
				$contentStr .= "成绩如下：\n";
			    }
			}else{//未注册
			    if(count($paras) == 2){//输入为ccj-u-p
				//此处没有对userpwd加密处理，简单起见
				$args = array('openid' => $fromUsername,'userid' => $paras[0], 'userpwd' => $paras[1]);
				$objSTO->addObject($args);
				$userid = $paras[0];
				$userpwd = $paras[1];
				$contentStr = "您成功绑定帐号密码到系统！\n";
				$contentStr .= "成绩如下：\n";
			    }else{
				$contentStr = "您尚未绑定账户密码到系统,请先按格式输入进行绑定！\n";
				$contentStr .= $this->tool->getUsage(3);
				$needToGetScore = false;
			    }
			}
			$this->tool->logger('userid:'.$userid."userpwd:".$userpwd);
			if($needToGetScore){
			    $tempStr = 'try';
			    //识别验证码，当返回正确成绩（验证码正确用户名密码正确）跳出循环
			    while(preg_match('(try)', $tempStr)){
				$this->tool->logger("xxxxxxxxx");
				unset($ret);
				//传参数执行获取成绩的py文件
				exec('python ./script/crawlScore/getScore '.$userid.'  '.$userpwd, $ret, $status);
				$tempStr = '';
				foreach($ret as $item){
				    $tempStr .= $item;
				    $tempStr .= "\n";
				}
				$this->tool->logger("exec 执行status:".$status);
				$this->tool->logger("exec 返回contentStr:".$tempStr);
				$this->tool->logger("yyyyyyyyyy");
				//或者error（用户名或密码错误）跳出循环
				if(preg_match('(error)',$tempStr)){
				    $contentStr = "输入的用户名或密码有误，请输入正确的用户名或密码进行绑定！\n";
				    $tempStr = $this->tool->getUsage(3);
				    $args = array('userid' => $userid);
				    $objSTO->delObject($args);//删除保存的用户
				    break;
				}
			    }
			    $contentStr .= $tempStr;
			}
			$this->tool->responseText($fromUsername,$toUsername,$contentStr,$this->tpl->textTpl);
		    }
		    else if(preg_match('(歌|曲|音|gq|乐)',$form_content))
		    {
			if(preg_match('[^(歌曲|gq)]',$form_content))
			{
			    $arr = array('歌曲','gq','+','-','|','#','%','_','=','！','!','^',']','[','+','~');
			    $form_content = str_replace($arr, '', $form_content);
			    $form_content_arr = explode('@', $form_content);
			    $music_title = $form_content_arr[0];
			    $this->tool->logger("\n music_title:".$music_title);
			    $music_author = '';
			    if(count($form_content_arr) == 2){
				$music_author = $form_content_arr[1];
				$this->tool->logger("\n music_author:".$music_author);
			    }
			    $description = $music_title." ".$music_author;
			    $url = $this->tool->getMusicUrl($music_title, $music_author);
			    //如果找到制定歌曲结果则播放，否则提示重新输入。
			    if($url[0]){
				$musicurl = $url[1];
				$hqmusicurl = $url[2];
				$this->tool->responseMusic($fromUsername,$toUsername,$music_title,$description,$musicurl,$hqmusicurl,$this->tpl->musicTpl);
			    }else{
				$contentStr = "没找到这首歌，换首点播吧。\n";
				$contentStr .= $this->tool->getUsage(1);
				$this->tool->responseText($fromUsername,$toUsername,$contentStr,$this->tpl->textTpl);
			    }
			}
			else{
			    $contentStr = $this->tool->getUsage(1);
			    $this->tool->responseText($fromUsername,$toUsername,$contentStr,$this->tpl->textTpl);
			}
		    }
		    else if(preg_match('(翻译|fy|Fy|fY|FY)',$form_content))
		    {
			$ch_country_name = array('中','西','日','韩','法','俄','泰','葡');
			$en_country_name = array('zh','spa','jp','kor','fra','ru','th','pt');
			if(preg_match('[^(翻译|fy|Fy|fY|FY)]',$form_content))
			{
			    if(preg_match('[^(fy|Fy|fY|FY)]',$form_content)){
				$this->tool->logger("form_content".$form_content);
				$from = mb_substr($form_content,2,1,'UTF-8');
				$to = mb_substr($form_content,3,1, 'UTF-8');
				$this->tool->logger("from ch_name".$from);
				$from = str_replace($ch_country_name, $en_country_name, $from);
				$this->tool->logger("from en_name".$from);
				$this->tool->logger("form_content".$form_content);
				$this->tool->logger("to ch_name".$to);
				$to = str_replace($ch_country_name, $en_country_name, $to);
				$this->tool->logger("to en_name".$to);
				$form_content = mb_substr($form_content, 4, mb_strlen($form_content), 'UTF-8');
			    }else{
				$arr = array('翻译','-','|','#','%','_','=','！','!','^',']','[','+','~');
				$form_content = str_replace($arr, '', $form_content);
			    }
			    if(empty($form_content))
			    {
				$contentStr = "请按规则输入要翻译的内容。\n";
				$contentStr = $this->tool->getUsage(2);
				$this->tool->responseText($fromUsername,$toUsername,$contentStr,$this->tpl->textTpl);
			    }
			    else{
				if(empty($from) || empty($to)){
				    $this->tool->responseText($fromUsername,$toUsername,$this->tool->responseTranslate('auto', 'auto', $form_content),$this->tpl->textTpl);
				}else{
				    $this->tool->responseText($fromUsername,$toUsername,$this->tool->responseTranslate($from, $to, $form_content),$this->tpl->textTpl);
				}
			    }
			}
			else{
			    $contentStr = "请按规则输入要翻译的内容。\n";
			    $contentStr = $this->tool->getUsage(2);
			    $this->tool->responseText($fromUsername,$toUsername,$contentStr,$this->tpl->textTpl);
			}
		    }
		    else if(preg_match('(历史|ls)',$form_content))
		    {   
			$contentStr = $this->tool->getHistory();
			if(empty($contentStr))
			{
			    $contentStr = $this->tool->getJoke();
			}
			$this->tool->responseText($fromUsername,$toUsername,$contentStr,$this->tpl->textTpl);
		    }
		    else if(preg_match('(笑话|故事)',$form_content))
		    {   
			$contentStr = $this->tool->getJoke();
			$this->tool->responseText($fromUsername,$toUsername,$contentStr,$this->tpl->textTpl);
		    }
		    else if(preg_match('(电影|推荐)',$form_content))
		    {
			$title1 = "横道世之介";
			$description1 = "";
			$picurl1 = $this->tpl->ipUrl.$this->tpl->picUrl."hengdao.jpg";
			$url1 = "http://movie.douban.com/subject/10484041/";
			$title2 = "天使爱美丽";
			$description2 = "";
			$picurl2 = $this->tpl->ipUrl.$this->tpl->picUrl."amelia.jpg";
			$url2 = "http://movie.douban.com/subject/1292215/";
			$title = array($title1,$title2);
			$description = array($description1,$description2);
			$url = array($url1,$url2);
			$picurl = array($picurl1,$picurl2);
			$count=count($title);
			$this->tool->responseNews($fromUsername,$toUsername,$count, $title,$description,$picurl,$url);
		    }
		    else if(preg_match('(DH|Dh|dh|导航|ss|SS|Ss|天气|tq|TQ|Tq)',$form_content))
		    {
			$form_content = str_replace('ss', '', $form_content);
			$objSTO = new SingleTableOperation("person","FL");//表操作模板类
			$objSTO->setTableName('person');//设置表名称
			$args = array('openid' => $fromUsername);
			$ret = $objSTO->getObject($args);
			$newsTag = false;//标志是否发图文
			$count = 0;
			$titleArr = array();
			$descriptionArr = array();
			$urlArr = array();
			$picurlArr = array();
			if(!empty($ret)){
			    $oldTime = substr($ret[0]['time'], 0, -6);
			    $newTime = date('Y-m-d H', time());
			    $from_Location_X = trim($ret[0]['latitude'], ' \r\n');
			    $from_Location_Y = trim($ret[0]['longitude'], ' \r\n');
			    $dst_Location_X='xxxxxxxxx';
			    $dst_Location_Y='xxxxxxxxx';
			    $from_Location_Scale = trim($ret[0]['scale'], ' \r\n');
			    $from_Location_Label = trim($ret[0]['label'], ' \r\n');
			    if($oldTime != $newTime){
				$contentStr = '你的位置信息为一小时以前的旧数据，请发送新的位置数据。';
			    }else{
				if($form_content == 'dh' || $form_content == '导航' || $form_content == 'DH' || $form_content == 'Dh'){
				    $dst_Location_Label='xxxxxxxxx';
				    $paras = array('{from_longitute}','{from_latitute}','{to_longitute}','{to_latitute}');
				    $values = array($from_Location_Y, $from_Location_X, $dst_Location_Y, $dst_Location_X);
				    $contentStr = "<a href=\"".str_replace($paras, $values, $this->tpl->ipUrlForLocalSearch)."\">点击导航至FunnyLife所在地</a>";
				}
				else if($form_content == 'tq' || $form_content == 'Tq' || $form_content == 'TQ' || $form_content == '天气'){
				    //$weather_res = $this->tool->getWeather($from_Location_X,$from_Location_Y,$from_Location_Scale,$from_Location_Label);
				    //$this->tool->responseText($fromUsername,$toUsername,$weather_res, $this->tpl->textTpl);
				    //今明后三天天气情况
				    $weather_res = $this->tool->getWeatherForNews($from_Location_X,$from_Location_Y,$from_Location_Scale,$from_Location_Label);
				    if(count($weather_res) != 1){
					$title1 = $weather_res[0].'天气预报';
					$title2 = mb_substr($weather_res[1], 0, mb_strlen($weather_res[1])-6);
					$title3 = mb_substr($weather_res[2], 0, mb_strlen($weather_res[2])-6);
					$title4 = mb_substr($weather_res[3], 0, mb_strlen($weather_res[3])-6);
					$title5 = $this->tool->getAirQualityForNews($weather_res[0]);
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
					$url_prefix = $this->tpl->ipUrl.$this->tpl->picUrl."weather/";
					$picurl[0] = $url_prefix."weather_background.jpg"; 
					$picurl[4] = $url_prefix."weather_quality.jpg"; 
					$wea_arr = array("小雨","中雨","大雨","多云","晴","小雪","中雪","大雪","阴");
					$pic_arr = array("weather_xiaoyu.png","weather_xiaoyu.png","weather_xiaoyu.png","weather_duoyun.png","weather_qing.png","weather_xiaoxue.png","weather_xiaoxue.png","weather_xiaoxue.png","weather_yin.png");
					foreach($sta_arr as $sta)
					{
					    $picurl[$index] = str_replace($wea_arr, $pic_arr, $url_prefix.$sta);
					    $index++;
					}
					$count = count($title);
					$this->tool->responseNews($fromUsername,$toUsername,$count, $title,$description,$picurl,$url);
				    }else{
					$this->tool->responseText($fromUsername,$toUsername,$weather_res, $this->tpl->textTpl);
				    }
				}else{
				    $serviceUrl = $this->tpl->baiduLocalSearchUrl;
				    $paras = array('{longitude}','{latitude}','{keyword}','{appkey}','{radius}');
				    $values = array($from_Location_Y, $from_Location_X, $form_content, $this->tpl->baidu_appkey, '500');
				    $serviceUrl = str_replace($paras, $values, $serviceUrl);
				    $services = file_get_contents($serviceUrl);
				    $serviceObj = simplexml_load_string($services);

				    $paras = array('{from_longitute}','{from_latitute}','{to_longitute}','{to_latitute}');
				    if(isset($serviceObj->poiList)){
					$count = 0;
					$numOfPoint = count($serviceObj->poiList->point);
					$titleArr[$count] = "共有".($numOfPoint>5?5:$numOfPoint)."个与".$form_content."相关的结果";
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
					    $contentStr = "地址:{$h_addr} 电话:{$h_tel} 距离:{$h_distance}米 \n";

					    $title = "{$h_name}\n".$contentStr;
					    $description = "";
					    $picurl = "";
					    $values = array($from_Location_Y, $from_Location_X, $h_longitute, $h_latitute);
					    //需进行位置修正后再将经度纬度值传过去
					    $url = str_replace($paras, $values, $this->tpl->ipUrlForLocalSearch);
					    $titleArr[$count] = $title;
					    $descriptionArr[$count] = $description;
					    $urlArr[$count] = $url;
					    $picurlArr[$count] = $picurl;
					}
					$count=count($titleArr);
					$newsTag = true;
				    }else{
					$contentStr = "无对应名称的位置服务被搜索到，请更改后重新输入进行搜索。\n";
				    }
				}
			    }
			}else{
			    $contentStr = "请先发送您的位置给我，基于您发的位置数据进行周边搜索。";
			}
			$this->tool->logger("contentStr".$contentStr);
			if($newsTag){
			    $this->tool->responseNews($fromUsername,$toUsername,$count, $titleArr,$descriptionArr,$picurlArr,$urlArr);
			}else{
			    $this->tool->responseText($fromUsername,$toUsername,$contentStr,$this->tpl->textTpl);
			}
		    }
		    else if(preg_match('(H|h|帮助|help)',$form_content))
		    {
			$contentStr = ($this->tool->getMenu())."\n".($this->tool->getHelp());
			$this->tool->responseText($fromUsername,$toUsername,$contentStr,$this->tpl->textTpl);
		    }
		    else
		    {
			//$ran = mt_rand(1,1000)%2;
			$ran = 1;
			if($ran == 0){ 
			    //调用simsimi接口回复文字消息
			    $tempStr = $this->tool->callSimsimi($form_content);
			    $contentStr = $tempStr."-/::)";
			}else if($ran == 1){
			    //调用免费的xiaojo机器人接口回复文字消息
			    $tempStr = $this->tool->xiaojo($form_content);
			    $random = mt_rand(1,1000)%2;
			    //调用小九无回答，回复故事或目录代替 
			    if(strpos($tempStr,"-") !== false){
				if($random == 1){
				    $contentStr = "NB的人类你的话显然已经超出了我的理解范围，请减少字数或者换一个话题呗。\n\n".($this->tool->getHelp())."-/::(";
				}else{
				    $contentStr = $tempStr."-/::(";
				}
			    }else{
				$contentStr = $tempStr."-/::)";
			    }
			}else{
			    //$this->logger("\n 用户发的数据为：".$keyword."。然后调用getEncyclopediaInfo()方法。");
			    //return $this->getEncyclopediaInfo($keyword);
			    $contentStr = "感谢留言，我会尽快给您回复！";
			}
			$this->tool->responseText($fromUsername,$toUsername,$contentStr,$this->tpl->textTpl);
		    }
		}
		break;
	    case "image":
		//获取图片地址
		$from_PicUrl = $postObj->PicUrl;
		//创建图片名称
		$filename = $fromUsername.date("YmdHis").".jpg";//i有前导0的分钟数
		$picUrl = $postObj->PicUrl;
		$this->tool->responseText($fromUsername,$toUsername, $this->tool->responseFaceRecognize($picUrl), $this->tpl->textTpl);
		//$this->tool->getPic($from_PicUrl, $filename); 
		break;
	    case "location":
		$from_Location_X=$postObj->Location_X;
		$from_Location_Y=$postObj->Location_Y;
		$from_Location_Scale=$postObj->Scale;
		$from_Location_Label=$postObj->Label;
		$dst_Location_X='xxxxxxxxx';
		$dst_Location_Y='xxxxxxxxx';
		$dst_Location_Label='xxxxxxxxx';

		$objSTO = new SingleTableOperation("person","FL");//表操作模板类
		$objSTO->setTableName('person');//设置表名称
		$args = array('openid' => $fromUsername, 'longitude' => $from_Location_Y, 'latitude' => $from_Location_X, 'scale' => $from_Location_Scale, 'label' => $from_Location_Label);
		#replace into
		$objSTO->_addObject($args, 'replace');
		$this->tool->logger("\n".'x:'.$from_Location_X."\n".'y:'.$from_Location_Y);
		$contentStr = '位置数据已更新，请查询天气或导航或周边搜索。';
		$this->tool->responseText($fromUsername,$toUsername, $contentStr, $this->tpl->textTpl);


		break;
	    default:
		$contentStr = "感谢留言，我会尽快给您回复！";
		$this->tool->responseText($fromUsername,$toUsername,$contentStr,$this->tpl->textTpl);
		break;
	    }
	}else{
	    $this->tool->logger("\n 微信服务器未发送任何数据至用户服务器。");
	}
	$this->tool->traceBeginOrEnd("0");
	exit;
    }
}
?>
