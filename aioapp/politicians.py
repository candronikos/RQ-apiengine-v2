#Working script using gremlinclient
#https://github.com/davebshow/gremlinclient
import asyncio
from gremlinclient import AioGremlinClient

class Politicians():
    def __init__(self, url='ws://172.17.0.2:8182'):
        self.client = AioGremlinClient(url=url)

    async def handler(self, request=None):
        resp = await self.client.submit("1 + 5")
        while True:
            msg = await resp.read()
            if msg is None:
                break
            #print(msg)
            print("Result: %d" % msg.data[0])

if __name__ == '__main__':
    from tornado.platform.asyncio import AsyncIOMainLoop

    AsyncIOMainLoop().install() # Use the asyncio event loop
    loop = asyncio.get_event_loop()

    app = Politicians()

    loop.run_until_complete(app.handler())
    app.client.close()
