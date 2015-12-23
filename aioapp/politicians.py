#Working script using gremlinclient
#https://github.com/davebshow/gremlinclient
import asyncio
from aiohttp import web
from gremlinclient import AioGremlinClient

def register(*args, **kwargs):
    return Politicians(*args, **kwargs)

class Politicians():
    def __init__(self, app, url='ws://172.17.0.2:8182'):
        self.client = AioGremlinClient(url=url)

        app.router.add_route('GET', '/politicians', self.get)
        app.router.add_route('POST', '/politicians', self.post)

        from tornado.platform.asyncio import AsyncIOMainLoop
        AsyncIOMainLoop().install() # Use the asyncio event loop

    async def get(self, request):
        d={}

        for e in iter(request.GET):
            d[e] = request.GET[e]

        resp = await self.client.submit("g.V().has('firstName',firstName)",
                bindings=d)

        body = None
        try:
            while True:
                msg = await resp.read()
                if msg is None:
                    break
                body = msg.data[0]
        except Exception as e:
            print("GET Exception:" + str(e))

        return web.Response(body=bytes(str(body), 'utf-8'))

    async def post(self, request):
        payload = await request.json()

        resp = await self.client.submit("graph.addVertex('firstName', firstName)",
                bindings=payload)

        body = None
        try:
            while True:
                msg = await resp.read()
                if msg is None:
                    break
                body = msg.data[0]
        except Exception as e:
            print("POST Exception:" + str(e))

        return web.Response(body=bytes(str(body), 'utf-8'))
