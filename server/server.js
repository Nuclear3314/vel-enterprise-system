const WebSocket = require('ws');
const wss = new WebSocket.Server({ port: 8080 });

wss.on('connection', function connection(ws) {
    console.log('�s�Ȥ�ݳs��');
    
    ws.on('message', function incoming(message) {
        console.log('����: %s', message);
    });
});
