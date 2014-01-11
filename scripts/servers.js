var currentlyLoadingButton;
var pouncing = false;
var updateInterval = self.setInterval("update()", 500);
var options = {};
var postOptions = {};
var defaultSort = "playersDesc";
var sort = defaultSort;
$(function () {
  //Load Options
  loadOptions();
  //$('#modalServerInfo').modal();
  $('#modalServerInfo').modal('hide');
  $('#modalServerInfo').on('hide', pounceOff);
  //$('.collapse').collapse("hide");
  //$('#options').collapse('hide');
  loadServerList();
})
      
$('#options').on('show', onShowOptions);
$('#options').on('hide', onHideOptions);
    
function onShowOptions()
{
  $('#options-toggle i').removeClass('icon-plus icon-transparent');
  $('#options-toggle i').addClass('icon-minus');
  $('body').addClass('options-in');
/*if($.browser.msie && $.browser.version < 10)
          $('body').css('padding-top', '100px');
        else
          $('body').animate({
            'padding-top': '100px'
          }, 150);*/
}
      
function onHideOptions()
{
  $('#options-toggle i').removeClass('icon-minus');
  $('#options-toggle i').addClass('icon-plus icon-transparent');
  $('body').removeClass('options-in');
/*if($.browser.msie && $.browser.version < 10)
          $('body').css('padding-top', '60px');
        else
          $('body').animate({
            'padding-top': '60px'
          }, 150);*/
}
      
function update() {
  pounceCheck();
}
    
function pounceCheck() 
{
  if(pouncing) {
    var d = new Date;
    var x = d.getTime() - pouncing.lastCheck;
    if(x >= 10000)
    {
      pouncing.lastCheck = d.getTime();
      $.get('pounce.php?ip=' + pouncing.ip + '&port=' + pouncing.port, null, pounceCallback, 'json');
    }
  }
}
    
function pounceCallback(data) {
  if(!pouncing)
    return;
  if(typeof(data.open_slot) != 'undefined')
  {
    if(data.open_slot == 1) {
      pounceOff();
      connect(pouncing.ip, pouncing.port);
    }
    else {
      getServerInfo($('#mo-refresh-button'), pouncing.ip, pouncing.port);
    }
  }    
      
}
    
function pounceOff()
{
  pouncing = false;
  if($('#mo-pounce-button').hasClass('active'))
  {
    $('#mo-pounce-button').button('toggle');
    $('#mo-pounce-button').button('reset');
  }
}
    
function pounce(button, ip, port)
{
  if(pouncing)
    pounceOff();
  else {
    $(button).button('toggle');
    $(button).button('waiting');
    var d = new Date;
    pouncing = {
      lastCheck: 0,
      ip: ip,
      port: port
    };
    pounceCheck();
  }
}
    
function connect(ip, port) {
  pounceOff();
  window.location = 'kag://' + ip + ':' + port;
}
    
function getServerInfo(button, ip, port)
{
  $(button).button('loading')
  currentlyLoadingButton = button;
  //$('#modalServerInfo').html('<div id="mo-center"><div id="mo-main"><p>This text is perfectly vertically and horizontally centered.</p></div></div>');
  $.get('server.php?ip=' + ip + '&port=' + port, null, serverInfoSuccess, 'html');
}
    
function serverInfoSuccess(data) {
  $(currentlyLoadingButton).button('reset');
  $('#modalServerInfo').html(data);
  $('#modalServerInfo').modal('show');
  $('#mo-refresh-button').tooltip({
    placement:'left',
    delay: {
      show: 500, 
      hide: 100
    }
  });
$('#mo-pounce-button').tooltip({
  placement:'left',
  delay: {
    show: 500, 
    hide: 100
  }
});
if(pouncing) {
  $('#mo-pounce-button').button('toggle');
  $('#mo-pounce-button').button('waiting');
  pounceCheck();
}
}
    
function setOption(checkbox) {
  options[$(checkbox).attr('id')] = {
    value:$(checkbox).prop('checked'),
    name:$(checkbox).attr('name')
  };
      
  localStorage.setItem("options", JSON.stringify(options));
  setPostOptions();
        
  $('#server-list').prepend($('<div class="alert alert-info">Options have changed. <a href="#" onclick="loadServerList();return false;">Refresh</a> to apply the changed options.</div>'));
}
      
function setSort(link) {
  sort = $(link).attr('id');
  setPostOptions();
  loadServerList();
}
      
function setPostOptions() {
  postOptions = {};
  for(var x in options) {
    postOptions[options[x].name] = options[x].value;
  }
  postOptions.sort = sort;
}
      
function loadOptions() {
  options = JSON.parse(localStorage.getItem("options"));
  if(options == null)
    options = {};
        
  for(var x in options)
  {
    if(options[x].value == true)
      $('#' + x).prop('checked', true);
  }
        
  sort = localStorage.getItem("sort");
  if(sort == null)
  {
    localStorage.setItem("sort", defaultSort);
    sort = defaultSort;
  }
  setPostOptions();
        
}
    
function loadServerList() {
  $("#server-list").html("<center><h2>Loading...</h2><p>Please wait.</p></center>");
  $("#server-list").load("serverList.php", postOptions);
}
    
function resetOptions() {
  options = {};
  sort = defaultSort;
  localStorage.setItem("options", JSON.stringify(options));
  localStorage.setItem("sort", defaultSort);
  $('#options input[type="checkbox"]').prop('checked', false);
  setPostOptions();
  loadServerList();
}