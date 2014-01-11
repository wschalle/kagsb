$(function() {
  doConnect();
});

var socket;
var connected = false;
var status = 'disconnected';

var buddyList = [];
var onlineBuddies = [];

function doConnect() {

  socket = io.connect(NotifierEndpoint);
  /*socket.on('chat', function (data) {
    $('#chatlog').append("\n" + data);
    console.log(data);
  });*/
  socket.on('error', function (reason) {
    console.error('Unable to connect to Chat server.', reason);
  });

  socket.on('connect', function() {
    connected = true;
  });

  socket.on('buddyList', function(list) {
    buddyList = list;
    updateBuddyListDisplay();
    socket.emit('getOnlinePlayers');
  }); 
  
  socket.on('onlineBuddies', function(list) {
    onlineBuddies = list;
    updateBuddyListDisplay();
  });
  socket.on('status', function(newStatus) {
    status = newStatus;
    if(status === "loggedin")
      socket.emit('getBuddies');
  });
}

function updateBuddyListDisplay()
{
  var online = onlineBuddies;
  var offline = [];
  for(var x in buddyList) {
    offline.push(buddyList[x].toLowerCase())
  }
  onhtml = "";
  for(var x in online){ 
    onhtml = onhtml + "<li>" + online[x] + ' <a href="joinBuddy.php?kagname=' + online[x] + '" target="_blank" title="Join their server!"><i class="icon-white icon-play"></i></a></li>';
    offline.splice($.inArray(online[x],offline), 1); 
  }
  offhtml = "";
  for(var x in offline){
    offhtml = offhtml + "<li>" + offline[x] + "</li>";
  }
  
  $('#navbar-online-buddy-count').html('' + online.length);
  $('#online-buddy-count').html('' + online.length);
  $('#offline-buddy-count').html('' + offline.length);
  $('#online-buddies').html(onhtml);
  $('#offline-buddies').html(offhtml);
  
}

function sendChat() {
  if(!connected)
    return;
  socket.emit('message', $('#chat-input').val());
  $('#chat-input').val('');
}


function getBuddies() {
  if(!connected)
    return;
  socket.emit('getBuddies');
}

$(function() {
  $('#nickname').keydown(function(event) {
    if(event.which == 13) {
      event.preventDefault();
      connect();
    }
  });
  $('#chat-input').keydown(function(event) {
    if(event.which == 13) {
      event.preventDefault();
      sendChat();
    }
  });

});