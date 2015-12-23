import requests
import sys


#url = '0.0.0.0:8080/' + sys.argv[1] + '/5'
url = 'http://0.0.0.0:8080/politicians/5'

req = requests.get(url)

print(req.text)
