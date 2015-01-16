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

__author__ = 'frank'

from bs4 import BeautifulSoup
import re
import logging
import datetime

class Parser:
    """Parser for Parsering the data
    """
    def __init__(self):
        logging.basicConfig(level=logging.INFO)
        pass

    def getTopNews(self, file):
        """
        input:a price home file contains price pics
        output: get top news containing today's price pics
        """
        content = open(file, 'r')
        soup = BeautifulSoup(content)

        topPriceNews = {}
        h4s = soup.find_all('h4')

        year = datetime.date.today().strftime("%Y")
        p = re.compile(r'.*?(\d{1,2})月(\d{1,2})日.*')
        for h4 in h4s:
            content = h4.get_text().encode('utf-8')
            if '置顶' in content and '月' in content:
                time = year + '-' + p.match(content).group(1) + '-' + p.match(content).group(2)
                topPriceNews[time] = h4.find('a').get('href')
        return topPriceNews
    # end getTopNews()

    def getPricePicsUrl(self, file):
        """
        input:a price home file contains price pics url
        output: pics url
        """
        content = open(file, 'r')
        soup = BeautifulSoup(content)

        pricePicsUrl = {}
        entry = soup.find("div", "entry typo")
        imgs = entry.find_all('img')
        if len(imgs) == 1:
            pricePicsUrl['morning'] = imgs[0].get('src')
        else:
            pricePicsUrl['afternoon'] = imgs[0].get('src')
            pricePicsUrl['morning'] = imgs[1].get('src')

        return pricePicsUrl
    # end getTopNews()

# end class Parser
