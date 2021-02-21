var WebSocketServer = require('websocket').server;
var http = require('http');
var htmlEntity = require('html-entities');
var PORT = 3280;

// list of connected users
var clients = [];

// create http server
var server = http.createServer();

server.listen(PORT, () => console.log('Server listening on PORT: ' + PORT));

// create websocket 
wsServer = new WebSocketServer({
    httpServer: server
})

/**
 * The websocket server
 */
wsServer.on('request', function (request) {
        var connection = request.accept(null, request.origin);

        // Pass each connection instance to each client
        var index = clients.push(connection) - 1;
        console.log('Client', index, 'connected');

        /**
         * Send message to all the clients connected
         */
        connection.on('message', message => {
            var utf8Data = JSON.parse(message.utf8Data);

            if (message.type === 'utf8') {
                // Prepare the json data to be sent to all client that are connected
                var obj = JSON.stringify({
                    eventName: htmlEntity.encode(utf8Data.eventName),
                    eventMessage: htmlEntity.encode(utf8Data.eventMessage)
                })

                // send them to all the client
                for (let i = 0; i < clients.length; i++) {
                    clients[i].sendUTF(obj)
                }
            }
        });

        /**
         * When the client close its connection to the websocket server
         */
        connection.on('close', connection => {
            clients.splice(index, 1);

            console.log('client', index, 'was disconnected');
        });
    })