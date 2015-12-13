#Working script using gremlinclient
#https://github.com/davebshow/gremlinclient
import asyncio
from gremlinclient import AioGremlinClient

client = AioGremlinClient(url='ws://172.17.0.2:8182/')

@asyncio.coroutine
def handler(request=None):
    resp = yield from client.submit("1 + 5")
    while True:
        msg = yield from resp.read()
        if msg is None:
            break
        #print(msg)
        print("Result: %d" % msg.data[0])

if __name__ == '__main__':
    from tornado.platform.asyncio import AsyncIOMainLoop

    AsyncIOMainLoop().install() # Use the asyncio event loop
    loop = asyncio.get_event_loop()

    loop.run_until_complete(handler())
