var app = require('http').createServer(handler)
  , io = require('socket.io').listen(app)
  , fs = require('fs')
  , http = require('http')
  , dnode = require('dnode')
  , $ = require('jquery')
  , iniparser = require('iniparser')

var config = iniparser.parseSync('./config.ini');

app.listen(81);

var dnodeServer = dnode({
  onlinePlayers: function (players, cb) {
    //console.info(players);
    setOnlinePlayers(players);
    cb();
  },
  startedUpdate: function(cb) {
    console.info("Server Update Daemon - Started update.");
    cb();
  },
  finishedUpdate: function(cb) {
    console.info("Server Update Daemon - Finished update.");
    cb();
  }
  
});

var onlinePlayers = [];

dnodeServer.listen(82);

io.configure(function() {
  io.set('authorization', function(handshakeData, callback) {
    callback(null,true);
  });
  io.set('log level', config.logLevel);
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
  console.info("sending buddy list updates");
  var connected = io.sockets.sockets;
  for(var x in connected) {
    sendBuddyListUpdateTo(connected[x]);
  }
}
function sendBuddyListUpdateTo(socket) {
  var nick = getUsername(socket);
  if(nick === undefined)
    return;
  var buddies = socket.ds.buddylist;
  for(var x in buddies) 
    buddies[x] = buddies[x].toLowerCase();
  var mybuddies = [];
  for(var y in onlinePlayers) {
    if($.inArray(onlinePlayers[y].toLowerCase(), buddies) !== -1)
      mybuddies.push(onlinePlayers[y]);
  }
  console.info("Sending " + mybuddies.length + " buddies to " + nick + ".");
  socket.emit('onlineBuddies', mybuddies);
}

function getUsername(socket) {
  return ds(socket).username;
}

function setUsername(socket, name) {
  ds(socket).username = name;
  console.info("Username for socket " + socket.id + " set to '" + name + "'");
}

function ds(socket) {
  return datastore[socket.id];
}


function getBuddies(socket) {
  var req = http.request({
    host: config.host,
    port: 80,
    path: config.siteRoot + '/notification/getBuddies.php',
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

function checkUser(socket) {
  console.info("Checking user for socket " + socket.id + ".");
  var req = http.request({
    host: config.host,
    port: 80,
    path: config.siteRoot + '/notification/checkUser.php',
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
        if(indata.status !== "error") {
          setUsername(socket, indata.username);
          socket.emit('status', 'loggedin');
        }
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
  socket.emit('status', 'connecting');
  checkUser(socket);  
  socket.on('getOnlinePlayers', function() {
    sendBuddyListUpdateTo(this);
  });
  socket.on('message', function (message) { 
    console.info(getUsername(this) + message);
    io.sockets.emit('chat', getUsername(this) + ": " + message);
    if(message === "sendBuddies") 
      sendBuddyListUpdates(onlinePlayers);
  });
  socket.on('disconnect', function () {
    io.sockets.emit('chat', getUsername(this) + ' disconnected');
  });
  socket.on('getBuddies', function() {
    getBuddies(this);
  });
  
});