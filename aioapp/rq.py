import asyncio
from aiohttp import web
from politicians import register

app = web.Application()
politicians = register(app)

loop = asyncio.get_event_loop()
handler = app.make_handler()
f = loop.create_server(handler, '0.0.0.0', 8080)
srv = loop.run_until_complete(f)

#Print serving address and port
sn = srv.sockets[0].getsockname()
print('Serving on:')
print('  http://%s:%s\n' % (sn[0], sn[1]))

#Display Routes
print("Routing table:")
for route in app.router.routes():
    print("  " + str(route))

try:
    loop.run_forever()
except KeyboardInterrupt:
    pass
finally:
    loop.run_until_complete(handler.finish_connections(1.0))
    srv.close()
    loop.run_until_complete(srv.wait_closed())
    loop.run_until_complete(app.finish())

loop.close()
