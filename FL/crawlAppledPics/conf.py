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

# constants for Spider
#------------------------------
timeout = 5
fetched_url = 0
failed_url = 0
timeout_url = 0
trytoomany_url =0
other_url = 0
max_try_times = 5
total_url = 0

DNSCache = {}				# cache dns(domain name->IP addr) to reduce time cost

RESULTOTHER = 0 			#Other faults
RESULTFETCHED = 1 			#success
RESULTCANNOTFIND = 2 		#can not find 404
RESULTTIMEOUT = 3 			#timeout
RESULTTRYTOOMANY = 4 		#too many tries
#------------------------------




