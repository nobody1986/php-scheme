# -*- coding: utf-8 -*-
import time

from peewee import *
import hashlib
import time
import re


def removetags(value, tags):
    "Removes a space separated list of [X]HTML tags from the output"
    tags = [re.escape(tag) for tag in tags.split()]
    tags_re = '(%s)' % '|'.join(tags)
    starttag_re = re.compile('<%s(>|(\s+[^>]*>))' % tags_re)
    endtag_re = re.compile('</%s>' % tags_re)
    singletag_re = re.compile('<%s*/>' % tags_re)
    value = starttag_re.sub('', value)
    value = endtag_re.sub('', value)
    value = singletag_re.sub('', value)
    return value

#db = SqliteDatabase('data.db')
db = MySQLDatabase('12pir2',user='root',passwd='iamsnow')

# create a base model class that our application's models will extend
class BaseModel(Model):
    class Meta:
        database = db

    
    
class ch_article(BaseModel):
    '''
CREATE TABLE `ch_article` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tid` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `img` varchar(50) NOT NULL,
  `content` longtext NOT NULL,
  `add_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  `adder_id` int(10) NOT NULL,
  `sort` smallint(5) NOT NULL,
  `apv` smallint(5) NOT NULL,
  `rewrite` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `template` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
'''
    id = PrimaryKeyField()
    tid = IntegerField()
    title = CharField(max_length=100) # <-- VARCHAR
    keywords = CharField(max_length=100) # <-- VARCHAR
    description = CharField(max_length=200) # <-- VARCHAR
    content = TextField()
    img = CharField(max_length=50) # <-- VARCHAR
    rewrite = CharField(max_length=50) # <-- VARCHAR
    update_time = IntegerField() # <-- VARCHAR
    add_time = IntegerField()
    adder_id = IntegerField()
    sort = IntegerField()
    apv = IntegerField()
    status = IntegerField()
    template = CharField(max_length=50) # <-- VARCHAR


    def newArticle(self,title,content,code,created,tid):
        self.tid = tid
        self.title = title.encode("utf-8")
        self.keywords = ''
        self.status = 1
        self.add_time = created
        self.content = content
        self.update_time = created
        self.description = removetags(self.content,"a br div p")
        self.description = self.description[0:255]
        self.rewrite = code
        self.adder_id=1
        self.sort=0
        self.apv = 0
        self.img=''
        self.template=''
        return self.save()    

    @staticmethod  
    def getByTitle(self,title):
        return ch_article.select(fn.Count(ch_article.id).alias('count')).where(ch_article.title == title)


'''
CREATE TABLE `ch_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `sort` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `keywords` varchar(150) NOT NULL,
  `description` varchar(200) NOT NULL,
  `module` varchar(30) NOT NULL,
  `rewrite` varchar(100) NOT NULL,
  `template` varchar(50) NOT NULL DEFAULT '',
  `newstemplate` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

'''
class ch_category(BaseModel):
    '''

'''
    id = PrimaryKeyField()
    pid = IntegerField()
    title = CharField(max_length=100) # <-- VARCHAR
    sort = IntegerField()
    status = IntegerField()
    keywords = CharField(max_length=150) # <-- VARCHAR
    description = CharField(max_length=200) # <-- VARCHAR
    module = CharField(max_length=30) # <-- VARCHAR
    rewrite = CharField(max_length=100) # <-- VARCHAR
    template = CharField(max_length=50) # <-- VARCHAR
    newstemplate = CharField(max_length=50) # <-- VARCHAR

    

    def newCategory(self,title):
        self.pid = 0
        self.title = title.encode("utf-8")
        self.keywords = ''
        self.status = 1
        self.description = ''
        self.sort=0
        self.template=''
        self.module='Article'
        self.rewrite = ''
        self.newstemplate = ''
        return self.save() 
    @staticmethod  
    def getByName(self,name):
        return ch_category.select().where(ch_category.title == name)
