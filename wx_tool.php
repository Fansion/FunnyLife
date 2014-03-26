<?php
class Tool{
    var $tpl;
    function Tool($tpl)
    {
	$this->tpl = $tpl;
    }
    //日志起始或终止分界线
    function traceBeginOrEnd($flag)
    {
	if($flag == "1"){
	    //被包含到interface.php中
	    file_put_contents("class/wx_FL/log.html","起始================================\n",FILE_APPEND);
	}else{
	    file_put_contents("class/wx_FL/log.html","终止================================\n",FILE_APPEND);
	}
    }
    //普通记录
    function logger($content)
    {
	file_put_contents("class/wx_FL/log.html",date('Y-m-d H:i:s').$content."\n",FILE_APPEND);
    }
    //记录微信服务器向用户服务器发送的消息
    function traceHttp()
    {
	$this->logger(" "."REMOTE_ADDR: ".$_SERVER["REMOTE_ADDR"]." ".((strpos($_SERVER["REMOTE_ADDR"],"101.226")==0) ? " From Weixin " : " Unknown IP "));
	$this->logger(" "."QUERY_STRING: ".$_SERVER["QUERY_STRING"]);
    }
    function getMenu()
    {
	$contentStr = "目前支持:\n";
	$contentStr .= "-跟我聊天\n";
	$contentStr .= "-<a href=\"http://www.duopao.com/games/index\">经典手游</a>\n";
	$contentStr .= "-查询天气\n";
	$contentStr .= "-外文翻译\n";
	$contentStr .= "-<a href=\"http://m.kuaidi100.com/index_all.html\">快递查询</a>\n";
	$contentStr .= "-人脸识别\n";
	$contentStr .= "-在线听歌\n";
	$contentStr .= "-历史上的今天\n";
	$contentStr .= "-听故事读笑话\n";
	$contentStr .= "-<a href=\"http://www.qiushibaike.com/\">糗事百科</a>\n";
	$contentStr .= "--查成绩\n";
	$contentStr .= "--导航找funnylife所在地\n";
	$contentStr .= "--查周边酒店，KTV，银行等\n";
	$contentStr .= "--关于我\n";
	$contentStr .= "To be continued...\n";
	$contentStr .= "输入h获取详细操作说明。\n";
	return $contentStr;
    }
    function getHelp()
    {
	$contentStr = "详细操作说明：\n";
	$contentStr .= " 1.默认咱俩已处于聊天状态，聊天时说话字数尽量少，我听不懂就会给你讲个故事\n";
	$contentStr .= " 2.即点即玩,保证网络环境良好，最好wifi\n";
	$contentStr .= " 3.发送'天气'\n";
	$contentStr .= " 4.发送'翻译'或者'fy'\n";
	$contentStr .= " 5.选择快递公司并输入包裹单号\n";
	$contentStr .= " 6.发送一张清晰的人脸照给，简单的人脸识别\n";
	$contentStr .= " 7.发送'听歌'\n";
	$contentStr .= " 8.发送'历史'\n";
	$contentStr .= " 9.发送'故事'或'笑话'\n";
	$contentStr .= "10.点击直接进入糗百官网\n";
	$contentStr .= "11.发送'ccj'(只针对xxxxxxxx)\n";
	$contentStr .= "12.输入'dh或导航'\n";
	$contentStr .= "13.输入'ss+酒店（或银行等）'\n";
	$contentStr .= "备注：查天气|导航|搜索服务之前需发送你当前位置给我，每隔一小时之后需重新发送，另外导航效果比较差待改进\n";
	$contentStr .= " 0.我的<a href=\"".$this->tpl->myHomeUrl."\">个人主页</a>(久未更新)，当然有问题可联系我，我个人微信号搜xxxxxxxx请附带验证信息\n";
	return $contentStr;
    }
    function getUsage($seq)
    {
	if($seq == 1){
	    $data = "听歌操作指南：\n";
	    $data .= "输入歌曲(或gq)+歌名\n";
	    $data .= "例如:\n";
	    $data .= "歌曲旋木\n";
	    $data .= "歌曲旋木@王菲\n";
	    $data .= "歌曲1973@james blunt\n";
	}
	else if($seq == 2){
	    $data = "翻译操作指南：\n";
	    $data .= "默认智能翻译支持中->英,英->中,日->中：\n";
	    $data .= "输入翻译+词句\n";
	    $data .= "例如:\n";
	    $data .= "翻译我爱你\n";
	    $data .= "翻译I love you\n";
	    $data .= "翻译さようなら\n";
	    $data .= "高级翻译支持多种语言(中西日韩法俄泰葡)互相转换：\n";
	    $data .= "输入fy+源语言+目的语言+词句\n";
	    $data .= "例如:\n";
	    $data .= "fy中西我爱你\n";
	    $data .= "fy英西I love you\n";
	    $data .= "fy日西さようなら\n";
	}
	else if($seq == 3){
	    $data = "查成绩操作指南：\n";
	    $data .= "默认您的帐号密码未绑定到<a href=\"http://yjs.ustc.edu.cn/\">中科大研究生管理系统</a>。\n";
	    $data .= "输入:\n";
	    $data .= "ccj-userid-userpwd\n";
	    $data .= "绑定帐号密码到系统。\n";
	    $data .= "例如: ccj-xxxxxxxx-xxxxxxxx\n";
	    $data .= "绑定系统后输入:\n";
	    $data .= "ccj-jb\n";
	    $data .= "解除绑定密码帐号到系统。\n";
	    $data .= "绑定系统后输入:\n";
	    $data .= "ccj\n";
	    $data .= "查询所有课程成绩。\n";
	    $data .= "注：如果需要更换帐号密码需先解除绑定旧帐号密码然后重新绑定帐号密码,当然也可直接绑定新的帐号密码覆盖旧的帐号密码。\n";
	}
	return $data;
    }
    function responseText($fromUsername,$toUsername,$contentStr,$textTpl)
    {
	//回复欢迎文字消息
	$msgType = "text";
	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $contentStr);
	echo $resultStr;
    }
    function responseMusic($fromUsername,$toUsername,$title,$description,$musicurl,$hqmusicurl, $musicTpl)
    {
	//回复欢迎文字消息
	$msgType = "music";
	$resultStr = sprintf($musicTpl, $fromUsername, $toUsername, time(), $msgType, $title,$description,$musicurl,$hqmusicurl);
	echo $resultStr;
    }
    function responseNews($fromUsername,$toUsername,$count, $title,$description,$picurl,$url)
    {		      
	$msgType = "news";
	$newsTplBegin = "<xml>
	    <ToUserName><![CDATA[%s]]></ToUserName>
	    <FromUserName><![CDATA[%s]]></FromUserName>
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
	$this->logger(" 用户服务器向微信服务器返回数据 ".$resultStr);
	echo $resultStr;
    }
    function responseTranslate($from, $to, $content)
    {
	//http://openapi.baidu.com/public/2.0/bmt/translate?client_id=YourApiKey&q=today&from=auto&to=aut
	$this->tpl->translate_url = str_replace('{YourApiKey}', $this->tpl->translate_api_key, $this->tpl->translate_url);
	$this->tpl->translate_url = str_replace('{from}', $from, $this->tpl->translate_url);
	$this->tpl->translate_url = str_replace('{to}', $to, $this->tpl->translate_url);
	//$this->tpl->translate_url = str_replace('{YourContent}', urlencode($content), $this->tpl->translate_url);
	$this->tpl->translate_url = str_replace('{YourContent}', $content, $this->tpl->translate_url);
	$this->logger($this->tpl->translate_url);

	$result = file_get_contents($this->tpl->translate_url);
	//{"from":"zh","to":"en","trans_result":[{"src":"\u6211\u7231\u4f60","dst":"I love you"}]}
	$json_result = json_decode($result, true);
	$trans_result = $json_result['trans_result'][0];

	return $trans_result['dst'];	
    }
    function responseFaceRecognize($picUrl)
    {
	//Face++
	$requestUrl = $this->tpl->faceUrl;
	$requestUrl .= 'api_key='.$this->tpl->face_api_key;
	$requestUrl .= '&api_secret='.$this->tpl->face_api_secret;
	$requestUrl .= '&url='.$picUrl;
	$this->logger($requestUrl);
	$result = file_get_contents($requestUrl);
	$this->logger($result);
	$json_result = json_decode($result, true);
	$face_arr = $json_result['face'];
	$resultStr = "人脸检测结果:\n";
	$resultStr_x = "";
	$resultStr_y = "";
	$count = 0;
	$resultArr_x = array();
	$resultArr_y = array();
	foreach($face_arr as $face)
	{
	    $x = $face['position']['center']['x']; 
	    $y = $face['position']['center']['y']; 
	    $resultArr_x[$x] = array($x, $y, $face['attribute']['age']['value'], $face['attribute']['race']['value'], $face['attribute']['gender']['value']);
	    $resultArr_y[$y] = array($x, $y, $face['attribute']['age']['value'], $face['attribute']['race']['value'], $face['attribute']['gender']['value']);
	    $count++;
	}

	if($count == 0)
	{
	    $resultStr .= "上传图像不清晰或不包含人脸,请重新上传图片。\n";
	}elseif($count == 1){
	    foreach($resultArr_x as $value_arr)
	    {
		$resultStr_x .= '该人大概'.$value_arr[2].'岁，'.$value_arr[3].'人，'.$value_arr[4]."性。\n";
	    }
	    $resultStr .= $resultStr_x."\n";
	}else{
	    $resultStr .= "共检测到".$count."个人。\n";
	    ksort($resultArr_x);
	    $resultStr_x = "从左往右看:\n";
	    $seq = 1;
	    foreach($resultArr_x as $value_arr)
	    {
		$resultStr_x .= '第'.$seq.'个人大概'.$value_arr[2].'岁，'.$value_arr[3].'人，'.$value_arr[4]."性。\n";
		$seq++;
	    }
	    ksort($resultArr_y);
	    $resultStr_y = "如果无法判断顺序请从上往下看:\n";
	    $seq = 1;
	    foreach($resultArr_y as $value_arr)
	    {
		$resultStr_y .= '第'.$seq.'个人大概'.$value_arr[2].'岁，'.$value_arr[3].'人，'.$value_arr[4]."性。\n";
		$seq++;
	    }
	    $this->logger(count($resultArr_x));
	    $this->logger(count($resultArr_y));
	    $resultStr .= $resultStr_x."\n".$resultStr_y;
	}
	$ch_word = array('黄种','白种','黑种','男','女');
	$en_word = array('Asian','White','Black','Male','Female');
	$resultStr = str_replace($en_word, $ch_word, $resultStr);
	$this->logger($resultStr);
	return $resultStr;
    }
    //获取图片
    function getPic($picUrl,$filename)
    {
	$output = file_get_contents($picUrl);
	if(!empty($output)){
	    file_put_contents($filename,$output);
	}
    }
    function getJoke()
    {
	$joke_url=$this->tpl->joke_url;
	$joke = file_get_contents($joke_url);
	$joke = substr($joke, 1, -33);//去开始的"符号以及\n\n方倍工作室 技术支持"共33个字节
	$this->logger("\n 接口返回笑话：".$joke);
	return $joke;
    }
    function getHistory()
    {
	$history_url=$this->tpl->history_url;
	$history = file_get_contents($history_url);
	$history = str_replace("\"","",$history);
	$history = str_replace("\\","",$history);
	$history_arr = preg_split("/n/", $history);
	$history_arr_reverse = array_reverse($history_arr);
	$count = 0;
	foreach($history_arr_reverse as $history_item)
	{
	    if($count < $this->tpl->history_result_num){
		$result .= $history_item."\n";
	    }
	    $count++;
	}
	$this->logger("\n 接口返回历史数据：".$result);
	return $result;
    }
    //获取天气信息
    function getWeather($from_Location_X,$from_Location_Y,$from_Location_Scale,$from_Location_Label)
    {			 
	//地址解析使用百度地图API
	$map_api_url=$this->tpl->map_api_url;
	//坐标类型
	$map_coord_type="&coord_type=wgs84";
	//建立抓取地址
	$addrUrl = $map_api_url.$map_coord_type."&location=".$from_Location_X.",".$from_Location_Y;
	$this->logger("\n 百度地图接口返回的地址：".$addrUrl);
	//http://api.map.baidu.com/geocoder?coord_type=wgs84&location=31.836935,117.258301
	$geocoder = file_get_contents($addrUrl);
	$this->logger("\n 百度地图接口返回的地址详情：".$geocoder);
	if(!empty($geocoder))
	{
	    //匹配出城市
	    preg_match_all("/\<city\>(.*?)\<\/city\>/",$geocoder,$city);//默认为PREG_PATTERN_ORDER
	    $city=str_replace(array("市","县","区"),array("","",""),$city[1][0]);
	    $air_quality_url=$this->tpl->air_quality_url;
	    $air_quality_url.=$city;

	    //通过新浪接口查询天气的链接
	    $weather_api_url=$this->tpl->weather_api_url;
	    //新浪接口需要GBK编码
	    $city="&city=".urlencode(iconv("UTF-8","GBK",$city));
	    //查询当天
	    $day="&day=0";
	    //抓取天气
	    $this->logger("\n 新浪接口查天气的地址：".$weather_api_url.$city.$day);
	    $weather = file_get_contents($weather_api_url.$city.$day);
	    $this->logger("\n 新浪接口查天气详情：".$weather);
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
		$weather_res=array(
		    $w_city[1][0]."今天天气预报",
		    "发布：".$w_savedate_weather[1][0],
		    "气候：".$w_status,
		    "气温：".$w_temperature2[1][0]."-".$w_temperature1[1][0],
		    "风向：".$w_direction2[1][0],
		    "风速：".$w_power2[1][0],
		    "穿衣：".$w_chy_shuoming[1][0]
		);
	    }
	    $weather_res=implode("\n",$weather_res);
	    $air_quality = file_get_contents($air_quality_url);
	    $this->logger("\n 接口查天气的url：".$air_quality_url);
	    $this->logger("\n 接口查天气的质量：".$air_quality);
	    $air_quality = str_replace(array("[","]"),array("",""),$air_quality);
	    $air = json_decode($air_quality,true);

	    $weather_res.="\n-------------------------\n".$air['Title']."\n".$air['Description'];

	    $this->logger("\n 显示给用户：\n".$weather_res);
	}else{
	    $weather_res="今天天气获取失败！";
	}
	return $weather_res;
    }
    function getWeatherForNews($from_Location_X,$from_Location_Y,$from_Location_Scale,$from_Location_Label)
    {			 
	//地址解析使用百度地图API
	$map_api_url=$this->tpl->map_api_url;
	//坐标类型
	$map_coord_type="&coord_type=wgs84";
	//建立抓取地址
	$addrUrl = $map_api_url.$map_coord_type."&location=".$from_Location_X.",".$from_Location_Y;
	$this->logger("\n 百度地图接口返回的地址：".$addrUrl);
	//http://api.map.baidu.com/geocoder?coord_type=wgs84&location=31.836935,117.258301
	$geocoder = file_get_contents($addrUrl);
	$this->logger("\n 百度地图接口返回的地址详情：".$geocoder);
	if(!empty($geocoder))
	{
	    //匹配出城市
	    preg_match_all("/\<city\>(.*?)\<\/city\>/",$geocoder,$city);//默认为PREG_PATTERN_ORDER
	    $city=str_replace(array("市","县","区"),array("","",""),$city[1][0]);
	    $weather_res[0] = $city; 

	    //通过新浪接口查询天气的链接
	    $weather_api_url=$this->tpl->weather_api_url;
	    //新浪接口需要GBK编码
	    $city="&city=".urlencode(iconv("UTF-8","GBK",$city));
	    //查询当天
	    $day = 0;
	    for(; $day < 3; $day++){
		//抓取天气
		$this->logger("\n 新浪接口查天气的地址：".$weather_api_url.$city."&day=".$day);
		$weather = file_get_contents($weather_api_url.$city."&day=".$day);
		$this->logger("\n 新浪接口查天气详情：".$weather);
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
	    $this->logger("\n 显示给用户：\n".(implode(" ",$weather_res)));
	}else{
	    $weather_res=array("今天天气获取失败！");
	    $this->logger("\n 显示给用户：\n".$weather_res);
	}
	return $weather_res;
    }
    function getAirQualityForNews($city)
    {
	$air_quality_url=$this->tpl->air_quality_url;
	$air_quality_url.=$city;
	$air_quality = file_get_contents($air_quality_url);
	$this->logger("\n 接口查天气的url：".$air_quality_url);
	$this->logger("\n 接口查天气的质量：".$air_quality);
	$air_quality = str_replace(array("[","]"),array("",""),$air_quality);
	$air = json_decode($air_quality,true);

	$quality = $air['Title']."\n".$air['Description'];
	return $quality;
    }
    //通过官方API调用simsimi，试用期7天,已过期
    function callSimsimi($keyword)
    {
	$params['key'] = "536d3b9c-37bb-47bf-9cb4-ccddeebf01b0";
	$params['lc'] = "ch";
	$params['ft'] = "1.0";
	$params['text'] = $keyword;

	$url = "http://sandbox.api.simsimi.com/request.p?".http_build_query($params);
	$this->logger("\n 调用Simsimi url : --".$url."--");
	$output = file_get_contents($url);
	$message = json_decode($output,true);
	if($message['result'] == 100){
	    $this->logger("\n 调用Simsimi 回答: --".$message['response']."--");
	    return $message['response'];
	}else{
	    //		return $message['result']."-".$message['msg'];
	    //		return getMeaningAnswer();
	    return $this->xiaojo($keyword);
	}
    }
    //网络上免费的小九机器人接口，通过curl模拟post提交查询
    function xiaojo($keyword){
	$curlPost=array("chat"=>$keyword);
	$ch = curl_init();//初始化curl
	$xiaojoUrl = $this->tpl->xiaojoUrl; 
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
		$this->logger("\n 小九的响应中含有deleted或者title: ".$data);
		$data = "-讲文明树新风。\n".($this->getJoke());
	    }elseif(preg_match('(xhjchat|微信)',$data)){
		$this->logger("\n 小九的响应中含有xhjchat或者微信: ".$data);
		$data = "-".($this->getJoke());
	    }else{
		$this->logger("\n 调用小九 回答: --".$data."--");
	    }
	    return $data;
	}else{
	    $this->logger("\n 用户发的数据为：".$keyword."。小九的响应为空。");
	    return "-".($this->getJoke());
	}
    }
    //返回百科结果
    function getEncyclopediaInfo($keyword){
	$keyword_gbk = iconv('utf-8', 'gbk', $keyword); //将字符转换成GBK编码，若文件为GBK编码可去掉本行
	$encode = urlencode($keyword_gbk); //对字符进行URL编码
	$queryUrl = 'http://baike.baidu.com/list-php/dispose/searchword.php?word='.$encode.'&pic=1';
	$this->logger("\n queryUrl :=".$queryUrl);

	$queryContent = file_get_contents($queryUrl); //获取跳转页内容
	$this->logger("\n queryContent:=".$queryContent);
	$queryContent_utf8 = iconv('gbk', 'utf-8', $queryContent); //将获取的网页转换成UTF-8编码，若文件为GBK编码可去掉本行
	preg_match("/URL=(\S+)'>/", $queryContent_utf8, $url);//获取跳转后URL
	$realUrl = 'http://baike.baidu.com'.$url[1];
	$this->logger("\n realUrl1:=".$realUrl);
	$content = file_get_contents($realUrl); //获取跳转页内容
	#$this->logger("\n content1:=".$content);
	$content_utf8 = iconv('gbk', 'utf-8', $content); //将获取的网页转换成UTF-8编码，若文件为GBK编码可去掉本行
	if(preg_match('/_blank href="(\S+)"/',$content_utf8, $subUrl))
	{ 	
	    $realUrl = 'http://baike.baidu.com'.$subUrl[1];
	    $this->logger("\n realUrl2:=".$realUrl);
	    $content = file_get_contents($realUrl); //获取跳转页内容
	    $this->logger("\n content2:=".$content);
	}
	$start = strpos($content_utf8, 'Description');//-----------to modify
	$end = strpos($content_utf8, '" />', $start);
	$this->logger("\n ".$start."\n ".$end);
	$data = substr($content_utf8, $start, $end-$start+1);

	$this->logger("\n ".$data);
	return $data;
    }
    function getMusicUrl($title, $author){
	$flag = false;
	$requestUrl = "http://box.zhangmen.baidu.com/x?op=12&count=1&title={TITLE}$$"."{AUTHOR}$$$$";
	$requestUrl = str_replace('{TITLE}',urlencode($title),$requestUrl);
	$requestUrl = str_replace('{AUTHOR}',urlencode($author),$requestUrl);
	$musicXml = file_get_contents($requestUrl);

	$this->logger('\n music url'.$requestUrl);
	$this->logger('\n music xml'.$musicXml);
	$musicObj = simplexml_load_string($musicXml, 'SimpleXMLElement', LIBXML_NOCDATA);
	$urlEncode = $musicObj->url->encode;
	$urlEncode = substr($urlEncode,0,strrpos($urlEncode, '/')+1);//记录在最后一个/之前的字串
	$urlDecode = $musicObj->url->decode;
	$urlDecode = substr($urlDecode,0,strpos($urlDecode, "&"));//记录在第一个&之前的字串
	$url = $urlEncode.$urlDecode;
	$this->logger('\n music url'.$url);

	$durlEncode = $musicObj->durl->encode;
	$durlEncode = substr($durlEncode,0,strrpos($durlEncode, '/')+1);//记录在最后一个/之前的字串
	$durlDecode = $musicObj->durl->decode;
	$durlDecode = substr($durlDecode,0,strpos($durlDecode, "&"));//记录在第一个&之前的字串
	$durl = $durlEncode.$durlDecode;
	$this->logger('\n music durl'.$durl);
	if(!empty($urlEncode) && !empty($urlDecode) && !empty($durlEncode) && !empty($durlDecode))
	{
	    $flag = true;
	}
	return array($flag, $url, $durl);
    } 
}
?>
