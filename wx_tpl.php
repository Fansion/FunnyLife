<?php
class Tpl{
    //content内容最长2047个字符，对应最多682个汉字+1个字节标点
    var $textTpl = "<xml>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[%s]]></MsgType>
	<Content><![CDATA[%s]]></Content>
	<FuncFlag>0</FuncFlag>
	</xml>";   
    var $newsTplBegin = "<xml>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[%s]]></MsgType>
	<ArticleCount>%s</ArticleCount>
	<Articles>";
    var $newsTplEnd = "</Articles>
	<FuncFlag>1</FuncFlag>
	</xml>";
    var $musicTpl = "<xml>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[%s]]></MsgType>
	<Music>
	<Title><![CDATA[%s]]></Title>
	<Description><![CDATA[%s]]></Description>
	<MusicUrl><![CDATA[%s]]></MusicUrl>
	<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
	</Music>
	<FuncFlag>0</FuncFlag>
	</xml>";
    var $ipUrl = "domain name";
    var $ipUrlForLocalSearch = "damain name/xxxx.jsp?p1={from_longitute},{from_latitute}&p2={to_longitute},{to_latitute}";
    var $picUrl = "xxxx";
    var $codeimageUrl = "xxxx";
    var $myHomeUrl = "homepage url";
    var $translate_api_key = 'key';
    var $translate_url = 'http://openapi.baidu.com/public/2.0/bmt/translate?client_id={YourApiKey}&q={YourContent}&from={from}&to={to}';
    var $face_api_key = 'key';
    var $face_api_secret = 'secret';
    var	$faceUrl = 'http://apicn.faceplusplus.com/v2/detection/detect?';
    var $joke_url="http://api100.duapp.com/joke/?appkey=0020130430&appsecert=fa6095e113cd28fd";
    var $history_result_num = 20;
    var $history_url="http://api100.duapp.com/history/?appkey=trialuser";
    var $map_api_url="http://api.map.baidu.com/geocoder?";
    var $baidu_appkey="key";
    var $air_quality_url="http://api100.duapp.com/airquality/?appkey=0020130430&appsecert=fa6095e113cd28fd&city=";     var $weather_api_url="http://php.weather.sina.com.cn/xml.php?password=DJOYnieT8234jlsK";
    var $xiaojoUrl = 'http://www.xiaojo.com/bot/chata.php';
    var $baiduLocalSearchUrl = "http://api.map.baidu.com/telematics/v3/local?location={longitude},{latitude}&keyWord={keyword}&output=xml&ak={appkey}&radius={radius}";
}
?>
