# -*- coding: utf-8 -*-
import urllib2
import re
import urlparse
import os


def findUrls(arg):
    ret = []
    url = arg[0]
    callback = arg[1]
    try:
        request = urllib2.Request(url)
        request.add_header('User-Agent', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.95 Safari/537.11')
        response = urllib2.urlopen(request)
        lines = response.readlines()
    except:
        return ret
    if(callback):
        callback(url,lines)
    p = url.rfind('/')
    upath = url[0:p]
    r = re.compile("href\s*=\s*['\"](.+?)['\"]")
    lines=" ".join(lines)
    lines=lines.replace("\n" ," ")
    line_matched = r.findall(lines)
    up = urlparse.urlparse(url)
    uhost = up.netloc

    for lm in line_matched:
        lup = urlparse.urlparse(lm)
        if(lup.scheme == 'mailto' or lup.scheme == 'javascript' or (lup.netloc =='' and  lup.path == '') or lup.netloc != uhost):
            continue
        url_tmp = ""
        if lup.netloc == "" :
            url_tmp = upath+'/'+lup.path
        else:
            url_tmp = lm
        ret.append(url_tmp)
    return ret

class Spider:
    def __init__(self, url, level,callback = None):
        self.url = url
        self.level = level
        self.urlpool = {}
        self.callback = callback
        
    def walk(self):
        url = (self.url,self.callback)
        level = 0
        #result = self.pool.apply_async(findUrls, [20]) 
        #result.get(timeout=1)  
        #url = []
        #gevent.spawn(findUrls,url)

        while(level < self.level):
            #print(findUrls)
            ret = self.pool.map(findUrls, url)
            for x in ret:
                for y in x:
                    if(not self.urlpool.has_key(y)):
                        url.append((y,self.callback))
                        self.urlpool[y] = True
                        
            level  = level + 1
            
def parseRuleFile(filename):
    c = open(filename,"r").read()
    c_s = c.split("\n")
    ret = {}
    for x in c_s:
        t = x.split(" ")
        tmp = urlparse.urlparse(t[0])
        if(len(t) < 6):
            continue
        ret[tmp.netloc] = {"url":t[0],"encoding":t[1],"url_re":re.compile(t[2]),"title_re":re.compile(t[3]),"content_re":re.compile(t[4]),"cata":t[5].replace("\r","")}
    return ret


import move
import time

def cb(url,content):
    if(not content):
        return
    u = urlparse.urlparse(url)
    if( len(config[u.netloc]["url_re"].findall(url)) == 0):
        return
    content_raw = "\n".join(content)
    content = " ".join(content)
    content = content.replace("\r"," ")
    content = content.replace("\n"," ")
    title_match = config[u.netloc]["title_re"].findall(content)
    if(len(title_match) == 0):
        print(config[u.netloc]["title_re"].pattern)
        return
    title = title_match[0]
    content_scanner = config[u.netloc]["content_re"].scanner(content)
    content_search = content_scanner.search()
    if(not content_search):
        return
    content_regs = content_search.regs
    content = content_raw[content_regs[1][0]:content_regs[1][1]]
    topic = move.ch_article.getByTitle(title)
    if(topic):
        return
    
    #增加图片处理
    p = url.rfind('/')
    upath = url[0:p]
    img_re = re.compile("<img[^>]+?src=[\"'](.+?)[\"']",re.IGNORECASE)
    scanner = img_re.scanner(content)
    p = scanner.search()
    new_content = content;
    while(p):
        regs = p.regs
        p = scanner.search()
        if(len(regs) == 0):
            continue
        img = content[regs[0][0]:regs[0][1]]
        img_url = content[regs[1][0]:regs[1][1]]
        lup = urlparse.urlparse(img_url)
        if(lup.scheme == 'mailto' or lup.scheme == 'javascript' or (lup.netloc =='' and  lup.path == '') or lup.netloc != u.netloc):
            continue
        url_tmp = ""
        if lup.netloc == "" :
            url_tmp = upath+'/'+lup.path
        else:
            url_tmp = img_url
        print(url_tmp)
        fileid = get_and_store(url_tmp)
        if(fileid):
            new_img = img.replace(url_tmp, fileid)
            new_content = new_content.replace(img,new_img)
        
    new_content = clearLinks(u.netloc,new_content)
    topic_dict = TOPIC_DICT.copy()
    topic_dict['title'] = title
    topic_dict['content'] = new_content
    topic_dict['add'] = int(time.time())
    topic_dict['author'] = "admin"
    topic_dict['nodename'] = config[u.netloc]["cata"]
    node = move.ch_category.getByName(topic_dict['nodename'])
    topic_dict['nodeid'] = str(node["id"])
    
    id = model.Topic.add(topic_dict)
    if(id):
        print(title)
        model.Node.incr_count(topic_dict['nodeid'], 1)

def get_file_name(url):
    import hashlib
    import time
    m = hashlib.md5()
    m.update(url)
    m.update(time.time() + "")
    return m.hexdigest()

def get_and_store(img_url):
    try:
        request = urllib2.Request(img_url)
        request.add_header('User-Agent', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.95 Safari/537.11')
        response = urllib2.urlopen(request)
        content = response.read()
        info = response.info()
    except:
        return None
    if(response.getcode() != 200 or info.subtype not in ["jpg","JPG",'jpeg','JPEG',"png","PNG","gif","GIF"]):
        return False
    name = get_file_name(img_url)
    filename = "/var/www/cmshead/images/" + name
    open(filename,"wb+").write(content).close()
    return "/images/" + name
    
def clearLinks(domain,content):
    a_re = re.compile("(<a.+?href\s*=\s*[\"'].+?" + domain.replace(".","\\.") + ".+?[\"']>(.+?)</a>)")
    tmp = a_re.findall(content)
    if(len(tmp) > 0):
        for n in tmp:
            t_c = content.replace(n[0],n[1])
            content = t_c
    return content

config = {}
config = parseRuleFile("spider.txt")

if __name__=="__main__":
    #s = Spider("http://www.444tt.com/", 3)
    #s.walk()
    #s = Spider("http://www.rtyscn.com/", 2)
    #s.walk()
    #s = Spider("http://www.51mmm.com/", 2)
    #s.walk()
    #s = Spider("http://www.2009renti.com/", 3)
    #s.walk()
    #s = Spider("http://www.answersky.com/", 3,cb)
    #s.walk()
    #s = Spider("http://www.uuyishu.com/", 3,cb)
    #s.walk()
    #s = Spider("http://www.v147.com/", 3,cb)
    #s.walk()
    #s = Spider("http://www.baidu.com/", 4,cb)
    #s.walk()
    #print(s.urlpool.__sizeof__())
    #jq = gevent.JoinableQueue
    #config = {'6' : 'http://www.rtyscn.com/','7' : 'http://www.51mmm.com/'}
    for k,v in config.items():
        s = Spider(v['url'], 2,cb)
        #s = Spider(v, 4)
        #print(v)
        url = [(s.url,s.callback)]
        #url = [(s.url,None)]
        level = 0
        while(level < s.level):
            for u in url:
                jobs = findUrls(u)
            #print(jobs)
                if(not jobs):continue
                for y in jobs:
                    if(not s.urlpool.has_key(y)):
                        url.append((y,s.callback))
                            #url.append((y,None))
                        s.urlpool[y] = True
                                
            level  = level + 1
        

