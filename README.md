FunnyLife
=========


Introduction
---------
Here is a backup of programs wrote for a wechat public account named **FunnyLife**(can be search at wechat public account). 

It mainly consists of serval *.php* files and a dir for pics used for the service. Also there is a *.py* file(getScore) crawing scores from [USTC](http://yjs.ustc.edu.cn/).

You can use whatever you want here under GPL.

Environment
---------
Programs are deployed on a virtual machine(only 1G mem) on [USTC CLOUD](http://cloud.ustc.edu.cn/) with a public IP address(thx for admins). **Nginx**, **php5-fpm**, and **mysql** are needed for running the service. 


Reference
---------
I got the idea of writing programs for an account at [mp.weixin.qq.com](https://mp.weixin.qq.com) since 2013-10(or later). I mainly learned from [方倍工作室](http://www.cnblogs.com/txw1958/p/wechat-tutorial.html) ,[柳峰](http://blog.csdn.net/lyq8479/article/category/1366622/) ,[青龙老贼](http://www.zhongyaofang.cn/combat_power/weixin_jiaocheng_vol1.html)  as well as some other ways. Later I referred to two books, [钟志勇](http://www.weixingon.com/wechat/wechatappdev.php) (more userful for me) and [易伟](http://book.douban.com/subject/25710483/) (for introduction) . 

At first I did not use framework in my programs. Then I learned how to deal with mysql in php roundly. Codes here are wrote before referring to framework talked in [钟志勇](http://www.weixingon.com/wechat/wechatappdev.php).

In addtion, I will choose to read [柳峰](http://book.douban.com/subject/25838708/) (implemented in a Java way) if time is convenient. Actually there are so much to learn to write good codes.


NOTES
---------
Personal data are replaced by XXX. CCJ(crawing scores) module is implemented mainly for the convenience of checking scores at [USTC](http://yjs.ustc.edu.cn/).  Correlative modules are needed to run codes above, such as tesseract ocr, 	beautiful soup and so on. Remember to check the log files to get these codes running, contact me if necessary. 


