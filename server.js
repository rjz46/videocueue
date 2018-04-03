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

var queue_index = 0;

io.on('connection', function (socket) {
  
  console.log('a user connected');


  socket.on('add', function (data) {
    console.log(data);

    data.queue_index = queue_index;
    io.emit('plus', data);

    queue_index++;

    //queue.push(data);
    //console.log(queue);
  });

  socket.on('remove', function (data) {
    console.log(data);

    io.emit('minus', data );
  });

  socket.on('jump', function (data) {
    console.log(data);
    io.emit('jump', { data });
  });

});