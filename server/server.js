const WebSocket = require('ws');
const wss = new WebSocket.Server({ port: 8080 });

wss.on('connection', function connection(ws) {
    console.log('新客戶端連接');
    
    ws.on('message', function incoming(message) {
        console.log('收到: %s', message);
    });
});
