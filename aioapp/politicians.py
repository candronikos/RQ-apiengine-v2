#Working script using gremlinclient
#https://github.com/davebshow/gremlinclient
import asyncio
from aiohttp import web
from gremlinclient import AioGremlinClient

import json

def register(*args, **kwargs):
    return Politicians(*args, **kwargs)

class Politicians():
    def __init__(self, app, url='ws://172.17.0.2:8182'):
        self.client = AioGremlinClient(url=url)

        app.router.add_route('GET', '/politicians/{item:\d+}', self.handler)

        from tornado.platform.asyncio import AsyncIOMainLoop
        AsyncIOMainLoop().install() # Use the asyncio event loop

    async def handler(self, request):
        d = request.match_info
        resp = await self.client.submit("item * item",
                bindings={'item':int(d['item'])})

        body = None
        while True:
            msg = await resp.read()
            if msg is None:
                break
            body = msg.data[0]

        return web.Response(body=bytes(json.dumps({"square": int(body)}), 'utf-8'))
