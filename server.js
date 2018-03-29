var app = require('http').createServer(handler)
var io = require('socket.io')(app);
var fs = require('fs');

app.listen(3000);

function handler (req, res) {
  fs.readFile(__dirname + '/index.html',
  function (err, data) {
    if (err) {
      res.writeHead(500);
      return res.end('Error loading index.html');
    }

    res.writeHead(200);
    res.end(data);
  });
}

io.on('connection', function (socket) {
  console.log('a user connected');

  socket.broadcast.emit('news', { hello: 'world' });

  //socket.broadcast.emit('chat message', "hello");

  socket.on('my other event', function (data) {
    console.log(data);
  });

  socket.on('add', function (data) {
    //io.emit('chat message', data);

    console.log(data);
    socket.broadcast.emit('news', { hello: 'world' });
  });

  socket.on('remove', function (data) {
    console.log(data);
  });

});