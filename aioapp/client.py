import requests
import sys

url = 'http://0.0.0.0:8080/politicians'
abbott = {
        'title':'Treasurer',
        'firstName':'Tony',
        'lastName':'Abbott',
        'state': 'NSW',
        'postcode': '2154'}

r = requests.post(url, json=abbott)
print("POST: " + r.text)

params = {'firstName': 'Tony'}
r = requests.get(url, params=params)
print("GET: " + r.text)
