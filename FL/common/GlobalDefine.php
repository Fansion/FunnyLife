<?php 

/**
 * FunnyLife implemention
 * @author pacozhong
 * modified by Frank
 * 
 */
require_once dirname(__FILE__).'/ErrorCode.php';
define('ROOT_PATH', dirname(__FILE__) . '/../');
define('DEFAULT_CHARSET', 'utf-8');
define('COMPONENT_VERSION', '1.0');
define('COMPONENT_NAME', 'wt');

//关闭NOTICE错误日志
error_reporting(E_ALL ^ E_NOTICE);

define('USERNAME_FUNNYLIFE', 'XXX');
define('WX_API_URL', "https://api.weixin.qq.com/cgi-bin/");
define('WX_API_APPID', "");
define('WX_API_APPSECRET', "");

define("WEIXIN_TOKEN", "fansion");
define("HINT_NOT_IMPLEMEMT", "待实现");
define('HINT_TPL', "<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[%s]]></MsgType>
  <Content><![CDATA[%s]]></Content>
  <FuncFlag>0</FuncFlag>
</xml>
");

$GLOBALS['DB'] = array(
	'FL' => array(
		'HOST' => 'XXX',
		'DBNAME' => 'fl',
		'USER' => 'XXX',
		'PASSWD' => 'XXX',
		'PORT' => XXX 
	),
);

/**config for FunnyLife**/
define('TRANS_URL', 'XXX');
define('TRANS_API_KEY', 'XXX');

define("HISTORY_URL", "http://api100.duapp.com/history/?appkey=trialuser");
define("HISTORY_RESULT_NUM", 20);

define("JOKE_URL", "http://brisk.eu.org/api/joke.php");

define("IP_URL", "XXX");
define("PIC_URL", "XXX");

define("MAP_API_URL", "http://api.map.baidu.com/geocoder?");
define("BAIDU_APPKEY", "XXX");
define("AIR_AUQLITY_URL", "XXX");
define("WEATHER_API_URL", "XXX");

define("BAIDULOCALSEARCH", "http://api.map.baidu.com/telematics/v3/local?location={longitude},{latitude}&keyWord={keyword}&output=xml&ak={appkey}&radius={radius}");
define("IPURLFORLOCALSEARCH", "http://XXX/route.jsp?p1={from_longitute},{from_latitute}&p2={to_longitute},{to_latitute}");
define("HOME_URL", "http://ifanan.com");

define("XIAOJO_URL", 'http://www.xiaojo.com/bot/chata.php');


//content内容最长2047个字符，对应最多682个汉字+1个字节标点
define('TEXT_TPL_FL', "<xml>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[%s]]></MsgType>
	<Content><![CDATA[%s]]></Content>
	<FuncFlag>0</FuncFlag>
	</xml>");
define('MUSIC_TPL_FL', "<xml>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[%s]]></MsgType>
	<Music>
	<Title><![CDATA[%s]]></Title>
	<Description><![CDATA[%s]]></Description>
	<MusicUrl><![CDATA[%s]]></MusicUrl>
	<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
	</Music>
	<FuncFlag>0</FuncFlag>
	</xml>");
define('MENU_FL',"目前支持:\n"
."-跟我聊天\n"
."-<a href=\"http://www.duopao.com/games/index\">经典手游</a>\n"
."-查询天气\n"
."-外文翻译\n"
."-<a href=\"http://m.kuaidi100.com/index_all.html\">快递查询</a>\n"
."-在线听歌\n"
."-历史上的今天\n"
."-听故事读笑话\n"
."-<a href=\"http://www.qiushibaike.com/\">糗事百科</a>\n"
."--查成绩\n"
."--导航找funnylife所在地\n"
."--查周边酒店，KTV，银行等\n"
."--关于我\n"
."To be continued...\n"
."输入h获取详细操作说明。\n");

define('HELP_FL', "详细操作说明：\n"
." 1.默认咱俩已处于聊天状态，聊天时说话字数尽量少，我听不懂就会给你讲个故事\n"
." 2.即点即玩,保证网络环境良好，最好wifi\n"
." 3.发送'天气'\n"
." 4.发送'翻译'或者'fy'\n"
." 5.选择快递公司并输入包裹单号\n"
." 6.发送'听歌'\n"
." 7.发送'历史'\n"
." 8.发送'故事'或'笑话'\n"
." 9.点击直接进入糗百官网\n"
."10.发送'ccj'(只针对中科大研究生)\n"
."11.输入'dh或导航'\n"
."12.输入'ss+酒店（或银行等）'\n"
."备注：查天气|导航|搜索服务之前需发送你当前位置给我，每隔一小时之后需重新发送，另外导航效果比较差待改进\n"
." 0.我的<a href=\"".HOME_URL."\">个人主页</a>，有问题可联系我\n");

define('SEARCHSCORE_MENU_FL',"查成绩操作指南：\n"
."默认您的帐号密码未绑定到<a href=\"http://yjs.ustc.edu.cn/\">中科大研究生管理系统</a>。\n"
."输入:\n"
."ccj-userid-userpwd\n"
."绑定帐号密码到系统。\n"
."例如: ccj-SA13011045-xxxx\n"
."绑定系统后输入:\n"
."ccj-jb\n"
."解除绑定密码帐号到系统。\n"
."绑定系统后输入:\n"
."ccj\n"
."查询所有课程成绩。\n"
."注：如果需要更换帐号密码需先解除绑定旧帐号密码然后重新绑定帐号密码,当然也可直接绑定新的帐号密码覆盖旧的帐号密码。\n");
define('MUSIC_MENU_FL', "听歌操作指南：\n"
."输入歌曲(或gq)+歌名\n"
."例如:\n"
."歌曲旋木\n"
."歌曲旋木@王菲\n"
."歌曲1973@james blunt\n");
define('TRANS_MENU_FL', "翻译操作指南：\n"
."默认智能翻译支持中->英,英->中,日->中：\n"
."输入翻译+词句\n"
."例如:\n"
."翻译我爱你\n"
."翻译I love you\n"
."翻译さようなら\n"
."高级翻译支持多种语言(中西日韩法俄泰葡)互相转换：\n"
."输入fy+源语言+目的语言+词句\n"
."例如:\n"
."fy中西我爱你\n"
."fy英西I love you\n"
."fy日西さようなら\n");

?>
