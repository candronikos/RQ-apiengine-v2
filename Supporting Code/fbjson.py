import os,json,re,time,ast
from pprint import pprint
from py2neo import neo4j, authenticate, Graph, Node, Relationship
from threading import Thread
#from collections import Ordered, Dict

#http://stackoverflow.com/questions/22112439/valueerror-extra-data-while-loading-json
nonspace = re.compile(r'\S')
def iterparse(j):
    decoder = json.JSONDecoder()
    pos = 0
    while True:
        matched = nonspace.search(j, pos)
        if not matched:
            break
        pos = matched.start()
        decoded, pos = decoder.raw_decode(j, pos)
        yield decoded


def checkkey(key, ddict):
  if (key in ddict):
    return True
  else:
    return False


def some_action(post):
  
  obj =  json.loads(post) 

  if checkkey('name', obj):
    titlepost = pprint(json.dumps(obj['name'])) 
    title = Node('Post', title=titlepost)
    graph.create(title)

    if checkkey('message', obj):
      bodypost = json.dumps(obj['message'])
      title.properties['body'] = bodypost
      title.push()
    if checkkey('updated_time', obj):
      title.properties['updated'] = json.dumps(obj['updated_time'])
      title.push()
    if checkkey('id', obj):
      title.properties['remoteid'] = json.dumps(obj['id'])
      title.push()
    if checkkey('link', obj):
      title.properties['link'] = json.dumps(obj['link'])
      title.push()
              
    if title:
      if checkkey('shares', obj):
        sharespost = json.dumps(obj['shares']['count'])
        title.properties['shares'] = sharespost
        title.push()

    if title:
      if checkkey('likes', obj):
        x = json.dumps(obj['likes']['data'])
        y = json.loads(x)
        for index, item in enumerate(y):
          #print index, item
          #pprint(json.dumps(item['name']))
          fbid = Node('fbid', fbid=item['id'])
          fbid.properties['name'] = item['name']
          userlikespost = Relationship(fbid, 'Supports', title)
          graph.create(userlikespost)
    print "-----------"
 

'''
  for decoded in iterparse(post):
    if ('name' in decoded):
      if checkkey('name', decoded):
        pprint((decoded['name']).encode('utf-8'))
#            mkPostNodes((decoded['name']).encode('utf-8'))
      if checkkey('message', decoded):
        pprint((decoded['message']).encode('utf-8'))
      #if checkkey('shares', decoded):
        #obj = json.loads(decoded['shares'])
      #  print (decoded['shares'])
      if checkkey('likes', decoded):
        pprint((decoded['likes']))
      if checkkey('updated_time', decoded):
        pprint((decoded['updated_time']).encode('utf-8'))
      if checkkey('link', decoded):
        pprint((decoded['link']).encode('utf-8'))
      if checkkey('description', decoded): 
        pprint((decoded['description']).encode('utf-8'))
      if checkkey('data', decoded):
        pprint((decoded['data']).encode('utf-8'))

  print "-----------"
'''

def mkPostNodes(name):
  graph.create({"Title": name})
  
def mkDescrNodes(graph, descr):
  ref_node = graph.get_reference_node()
  msg,rel = graph.create({"description": descr}, (ref_node, "HAS DESCR",0))

def mkMessagesNodes(graph, message):
  ref_node = graph.get_reference_node()
  msg,rel = graph.create({"Post": message}, (ref_node, "POST", 0))
  
def mkLikesNodes(liked):
  ref_node = graphdb.get_reference_node()
  msg,rel = graphdb.create({"FBUser": message}, (ref_node, "Liked By", 0))


def main():  

  global graph
  # Connect to graph and add constraints.
  url = os.environ.get('NEO4J_URL',"http://172.17.8.101:7474/db/data/")
  authenticate("172.17.8.101:7474", "neo4j", "password")
  graph = Graph(url)
   
  with open('dumprq') as file:
    [some_action(post=post) for post in file]  
    file.close()
 
  
if __name__ == '__main__':
  main()
