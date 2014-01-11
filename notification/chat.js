var app = require('http').createServer(handler)
  , io = require('socket.io').listen(app)
  , fs = require('fs')
  , http = require('http')
  , dnode = require('dnode')
  , $ = require('jquery')

app.listen(81);

var dnodeServer = dnode({
  onlinePlayers: function (players, cb) {
    //console.log(players);
    setOnlinePlayers(players);
    cb();
  },
  startedUpdate: function(cb) {
    console.log("Server Update Daemon - Started update.");
    cb();
  },
  finishedUpdate: function(cb) {
    console.log("Server Update Daemon - Finished update.");
    cb();
  }
  
});

var onlinePlayers = [];

dnodeServer.listen(82);

io.configure(function() {
  io.set('authorization', function(handshakeData, callback) {
    callback(null,true);
  });
});

var datastore = {};

function setOnlinePlayers(list) {
  onlinePlayers = list;
  sendBuddyListUpdates();
}

/**
 * Send buddy list updates to all connected clients
 * @param {array} list
 */
function sendBuddyListUpdates() {
  console.log("sending buddy list updates");
  var connected = io.sockets.sockets;
  for(var x in connected) {
    var nick = getNick(connected[x]);
    var buddies = connected[x].ds.buddylist;
    var mybuddies = [];
    for(var y in onlinePlayers) {
      if($.inArray(onlinePlayers[y], buddies) !== -1)
        mybuddies.push(list[y]);
    }
    console.log("Sending " + mybuddies.length + " buddies to " + nick + ".");
    connected[x].emit('onlineBuddies', mybuddies);
  }
}
function sendBuddyListUpdateTo(socket) {
    

}

function getNick(socket) {
  return ds(socket).nickname;
}

function ds(socket) {
  return datastore[socket.id];
}


function getBuddies(socket) {
  var req = http.request({
    id: socket.id,
    host: 'localhost',
    port: 80,
    path: '/kagsb/notification/getBuddies.php',
    method: 'GET',
    headers: {
      cookie: socket.handshake.headers.cookie
    }
  },
    function(response) {
      var data = "";
      response.on('data', function(chunk) {
        data += chunk;
      });
      response.on('end', function() {
        var indata = JSON.parse(data);
        socket.ds.buddylist = indata.buddylist;
        socket.emit('buddyList', indata.buddylist);
      });
    });
  req.end();
    
}

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

io.sockets.on('connection', function (socket) {
  datastore[socket.id] = {};
  socket.ds = {};
  socket.on('nickname', function(data) {
    ds(this).nickname = data;
    socket.broadcast.emit('chat', getNick(this) + ' connected');
  });
  socket.on('getOnlinePlayers', function() {
    sendBuddyListUpdateTo(this);
  });
  socket.on('message', function (message) { 
    console.log(getNick(this) + message);
    io.sockets.emit('chat', getNick(this) + ": " + message);
    if(message === "sendBuddies") 
      sendBuddyListUpdates(onlinePlayers);
  });
  socket.on('disconnect', function () {
    io.sockets.emit('chat', getNick(this) + ' disconnected');
  });
  socket.on('getBuddies', function() {
    getBuddies(this);
  });
  
});