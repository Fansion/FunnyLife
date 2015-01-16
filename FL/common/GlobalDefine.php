<?php
require_once dirname(__FILE__).'/ErrorCode.php';
define('ROOT_PATH', dirname(__FILE__) . '/../');
define('DEFAULT_CHARSET', 'utf-8');
define('COMPONENT_VERSION', '1.0');
define('COMPONENT_NAME', 'wt');

//关闭NOTICE错误日志
error_reporting(E_ALL ^ E_NOTICE);

define('USERNAME_FUNNYLIFE', 'gh_ec2dc8541cbd');
define('USERNAME_FINDFACE', 'gh_fd4633de8852');
define('USERNAME_MR', 'gh_a8b0ebbe91f5');
define('USERNAME_ES', "gh_ca4d756ab96d");
define('USERNAME_MYZL', "gh_XXX");
define('WX_API_URL', "https://api.weixin.qq.com/cgi-bin/");
define('WX_API_APPID', "");
define('WX_API_APPSECRET', "");

define("WEIXIN_TOKEN", "TOKEN");
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
	'FF' => array(
		'HOST' => 'localhost',
		'DBNAME' => 'findface',
		'USER' => 'USER',
		'PASSWD' => 'PASSWD',
		'PORT' => 3306
	),

	'FL' => array(
		'HOST' => 'localhost',
		'DBNAME' => 'DBNAME',
		'USER' => 'USER',
		'PASSWD' => 'PASSWD',
		'PORT' => 3306
	),
	'DB' => array(
		'HOST' => 'localhost',
		'DBNAME' => 'winestore',
		'USER' => 'USER',
		'PASSWD' => 'PASSWD',
		'PORT' => 3306
	),
	'MR' => array(
		'HOST' => 'localhost',
		'DBNAME' => 'mr',
		'USER' => 'USER',
		'PASSWD' => 'PASSWD',
		'PORT' => 3306
	),
	'MYZL' => array(
		'HOST' => 'localhost',
		'DBNAME' => 'myzl',
		'USER' => 'itil',
		'PASSWD' => 'itil',
		'PORT' => 3306
	)
);

/**config for FunnyLife**/
define('TRANS_URL', 'http://openapi.baidu.com/public/2.0/bmt/translate?client_id={YourApiKey}&q={YourContent}&from={from}&to={to}');
define('TRANS_API_KEY', 'TRANS_API_KEY');

define("HISTORY_URL", "http://api100.duapp.com/history/?appkey=trialuser");
define("HISTORY_RESULT_NUM", 20);

define("JOKE_URL", "http://brisk.eu.org/api/joke.php");

define("IP_URL", "IP_URL");
define("PIC_URL", "PIC_URL");
define("_5S_PICS_URL", "_5S_PICS_URL");

define("MAP_API_URL", "http://api.map.baidu.com/geocoder?");
define("BAIDU_APPKEY", "BAIDU_APPKEY");
define("AIR_AUQLITY_URL", "http://api100.duapp.com/airquality/?appkey=0020130430&appsecert=fa6095e113cd28fd&city=");
define("WEATHER_API_URL", "http://php.weather.sina.com.cn/xml.php?password=DJOYnieT8234jlsK");

define("BAIDULOCALSEARCH", "http://api.map.baidu.com/telematics/v3/local?location={longitude},{latitude}&keyWord={keyword}&output=xml&ak={appkey}&radius={radius}");
define("IPURLFORLOCALSEARCH", "http://http://funnylife.ifanan.com/route.jsp?p1={from_longitute},{from_latitute}&p2={to_longitute},{to_latitute}");
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
#."-跟我聊天\n"
."-<a href=\"http://sharefun.freeshell.ustc.edu.cn\">享乐</a>\n"
."-<a href=\"http://www.duopao.com/games/index\">经典手游</a>\n"
."-查询天气\n"
."-外文翻译\n"
."-<a href=\"http://m.kuaidi100.com/index_all.html\">快递查询</a>\n"
."-在线听歌\n"
#."-历史上的今天\n"
."-听故事读笑话\n"
."-<a href=\"http://www.qiushibaike.com/\">糗事百科</a>\n"
."--查成绩\n"
."--导航找funnylife所在地\n"
."--查周边酒店，KTV，银行等\n"
."--关于我\n"
."To be continued...\n"
."输入h获取详细操作说明。\n");

define('HELP_FL', "详细操作说明：\n"
#." 1.默认咱俩已处于聊天状态，聊天时说话字数尽量少，我听不懂就会给你讲个故事\n"
." -注册并推荐作品\n"
." -即点即玩,保证网络环境良好，最好wifi\n"
." -发送'天气'\n"
." -发送'翻译'或者'fy'\n"
." -选择快递公司并输入包裹单号\n"
." -发送'听歌'\n"
#."-发送'历史'\n"
." -发送'故事'或'笑话'\n"
." -点击直接进入糗百官网\n"
." -发送'ccj'(只针对中科大研究生)\n"
." -输入'dh或导航'\n"
." -输入'ss+酒店（或银行等）'\n"
."备注：查天气|导航|搜索服务之前需发送你当前位置给我，每隔一小时之后需重新发送，另外导航效果比较差待改进\n"
." -我的<a href=\"".HOME_URL."\">个人主页</a>，有问题可联系我\n");

define('SEARCHSCORE_MENU_FL',"查成绩操作指南：\n"
."默认您的帐号密码未绑定到<a href=\"http://yjs.ustc.edu.cn/\">中科大研究生管理系统</a>。\n"
."输入:\n"
."ccj-userid-userpwd\n"
."绑定帐号密码到系统。\n"
."例如: ccj-SA13011001-xxxx\n"
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

/**config for meiri10futu**/
define('MR_HINT_HELLO', "***每日十幅内涵图
***meiri10futu
1.输入?获取下一张内涵图");
define('MR_HINT_INPUT', "***每日十幅内涵图
***meiri10futu
1.输入?获取下一张内涵图");
define('MR_HINT_NO_NEW_PIC', "你已经看完了所有的内涵图，请等待更新");
define('MR_HINT_LIMITED', "您是受限用户，一天只能看10幅内涵图。若要变成非受限用户:推荐好友添加本账号（FunnyLife），并让他发送以下验证码到本帐号为您激活：");
define('MR_HINT_NO_QUOTA', "你的激活名额已经使用完，如需更多的名额，请联系微信号：lmfansion");
define('MR_HINT_ALREADY_ACTIVE', "该用户已经激活");
define('MR_HINT_ACTIVE_SUCC', "激活成功");
define('MR_HINT_INNER_ERROR', "内部错误");
define('MR_HINT_ACTIVE_SELF', "不能激活自己");

define('PIC_OF_DAY', 10);

define('SUCC_TPL_MR', "<xml>
 <ToUserName><![CDATA[%s]]></ToUserName>
 <FromUserName><![CDATA[%s]]></FromUserName>
 <CreateTime>%s</CreateTime>
 <MsgType><![CDATA[news]]></MsgType>
 <ArticleCount>1</ArticleCount>
 <Articles>
 <item>
 <Title><![CDATA[内涵图**序号:%d**]]></Title>
 <Description><![CDATA[如果图片没有完全展示，轻触图片查看全图]]></Description>
 <PicUrl><![CDATA[%s]]></PicUrl>
 <Url><![CDATA[%s]]></Url>
 </item>
 </Articles>
 <FuncFlag>1</FuncFlag>
 </xml>");



/**config for findface**/
define('API_KEY', '228fa2e6a29e8bfc33389277e6a6c742');
define('API_SECRET', 'qRppqmMJCXr2Ge5ps-6K-VcIDLgODbjD');
define('FACE_URL', "https://api.faceplusplus.com/");
define('FACE_TIMEOUT', 5);
define('GROUP_NAME', 'findface');
define('SUCC_TPL_FINDFACE', "<xml>
 <ToUserName><![CDATA[%s]]></ToUserName>
 <FromUserName><![CDATA[%s]]></FromUserName>
 <CreateTime>%s</CreateTime>
 <MsgType><![CDATA[news]]></MsgType>
 <ArticleCount>1</ArticleCount>
 <Articles>
 <item>
 <Title><![CDATA[Findface！]]></Title>
 <Description><![CDATA[如果照片没有完全展示，轻触图片查看全图]]></Description>
 <PicUrl><![CDATA[%s]]></PicUrl>
 <Url><![CDATA[%s]]></Url>
 </item>
 </Articles>
 <FuncFlag>1</FuncFlag>
 </xml>");
define('SUCC_TPL_TEXT_FINDFACE', "<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[%s]]></MsgType>
  <Content><![CDATA[%s]]></Content>
  <FuncFlag>0</FuncFlag>
</xml>
");
/**
 * hints
 */
define('FF_HINT_HELLO', "请自拍一张您的正面大头照发给我们，我们将为您找到微信世界里和你最像的人。
请注意：自拍时不要佩戴眼镜，否则我们不保证能完成任务。");
define('FF_HINT_INPUT_ERROR', "内部错误，请稍后再试。");
define('FF_HINT_TYPE_ERROR', "您发的不是照片。");
define('FF_HINT_FACE_ERROR', '内部错误，请稍后再试。');
define('FF_HINT_MULTIPLE_FACE', '请确保照片里只有您自己，否则我们无法确定要找和谁相似的脸。');
define('FF_HINT_NO_FACE', '在您发的照片中没有检测到脸。***请您在自拍时摘掉眼镜。');
define('FF_HINT_FACE_NO_CANDIDATE', '抱歉，在微信世界里还没有和您长得像的人。每秒有5个人加入微信，也许你要找的就是他们，请稍后再试。');
define('FF_HINT_INNER_ERROR', '内部错误，请稍后再试。');

/**
 * myzl defines
 */

define("CHIP_IN", "CHIP_IN");
define("PUT_MAGIC", "PUT_MAGIC");
define("SHOOT", "SHOOT");
define("FIRST_END", "FIRST_END");
define("SECOND_END", "SECOND_END");
define("START", "START");


define("XSFT" , "XSFT");
define("HDCX" , "HDCX");
define("CHXS" , "CHXS");
define("SSZM" , "SSZM");
$GLOBALS['constants'] = array(
		"MAGIC_LIST" => array(XSFT, HDCX, CHXS, SSZM, ""),
		"stepName" => array(
				CHIP_IN =>  "下注",
				PUT_MAGIC => "使用道具",
				SHOOT => "开枪",
				FIRST_END =>"上半局结束",
				SECOND_END => "下半局结束",
				START => "开始游戏"
				),
		"magicName" => array(
				XSFT => "邪神附体",
				HDCX => "壶底抽薪",
				CHXS => "重获新生",
				SSZM => "死神之门"
				)
);

define('MYZL_HINT', "欢迎关注MYZL");
define('MYZL_HINT_ADDUSER_SUC', "添加用户成功");
define('MYZL_HINT_CHIPIN_SUC', "你下注【%d金币】，等待对方【%s】");
define('MYZL_HINT_PUTMAGIC_SUC', "道具【%s】已释放，等待对方【%s】");
define('MYZL_HINT_PUTMAGIC_SUC_NO', "你没有使用道具，等待对方【%s】");
define('MYZL_HINT_READY_SUC', "已加入等待队列");
define('MYZL_HINT_START_SUC', "成功开始%s半局游戏");
?>
