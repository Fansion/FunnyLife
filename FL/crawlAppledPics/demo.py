#!/usr/bin/env python
# -*- coding: utf-8 -*-
# # Copyright 2014 by Frank Fu <ustcfrank@icloud.com>
#
# All Rights Reserved
#
# Permission to use, copy, modify, and distribute this software
# and its documentation for any purpose and without fee is hereby
# granted, provided that the above copyright notice appear in all
# copies and that both that copyright notice and this permission
# notice appear in supporting documentation, and that the name of
# Frank Fu  not be used in advertising or publicity
# pertaining to distribution of the software without specific, written
# prior permission.
#
# Frank Fu DISCLAIMS ALL WARRANTIES WITH REGARD TO THIS
# SOFTWARE, INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY
# AND FITNESS, IN NO EVENT SHALL Frank Fu BE LIABLE FOR
# ANY SPECIAL, INDIRECT OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
# WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS,
# WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS
# ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR
# PERFORMANCE OF THIS SOFTWARE.
#

# 失效

__author__ = 'frank'

import Spider, Parser
import os
import shutil


def remove(path):
    if os.path.exists(path):
        if os.path.isdir(path):
            shutil.rmtree(path)
        if os.path.isfile(path):
            os.remove(path)


__websiteBase = 'http://appled.cc'
priceHomeUrl = __websiteBase + '/board/price'   # 每日报价,小闷的水果店
__picsDir = "/usr/share/nginx/html/FunnyLife/crawlAppledPics/pics/"

if not os.path.exists(__picsDir):
    os.mkdir(__picsDir)

spider = Spider.Spider()
spider.downWebPage(priceHomeUrl, 'priceHome.html', 'w')

parser = Parser.Parser()
for time, topNewsUrl in parser.getTopNews(
        'priceHome.html').iteritems():			# get top news containing today's price pics
    topNewsUrl = __websiteBase + topNewsUrl
    spider.downWebPage(topNewsUrl, time + '-pricePics.html', 'w')
    for mora, picUrl in parser.getPricePicsUrl(time + '-pricePics.html').iteritems():
        spider.downWebPage(picUrl, __picsDir + time + '-' + mora, 'w')
    remove(time + '-pricePics.html')
remove('priceHome.html')

