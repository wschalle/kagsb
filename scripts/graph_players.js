google.load('visualization', '1', {'packages':['annotatedtimeline']});
google.setOnLoadCallback(initChart);

var resizeTimeout;
$(window).resize(function(){
  clearTimeout(resizeTimeout);
  resizeTimeout = setTimeout(function(){renderChart();}, 100);
});

var data;
var chart;
var flashEnabled = false;
function initChart() {
  flashEnabled = swfobject.hasFlashPlayerVersion("9.0.0");
  data = new google.visualization.DataTable();
  data.addColumn('date', 'Date');
  data.addColumn('number', 'New Players');
  data.addColumn('number', 'Players Online');
  data.addColumn('number', 'Total Players');
  $.ajax({
    url: 'mainGraphData.php',
    data: null,
    success: loadChartDataCallback,
    dataType: 'json'
  });  
  
  if(flashEnabled)
    chart = new google.visualization.AnnotatedTimeLine(document.getElementById('chart_div'));
  else
    chart = new Dygraph.GVizChart(document.getElementById("chart_div"));
}

function loadChartDataCallback(jsonData) {
  for(var x in jsonData) {
    data.addRows([[new Date(jsonData[x].date),
      Number(jsonData[x].newPlayers), 
      Number(jsonData[x].onlinePlayers), Number(jsonData[x].newPlayersCum),
      ]]);
  }
  
  renderChart();
}

function renderChart() {
  var width = $('#chart_div').parent().width();
  $('#chart_div').width(width);
  chart.draw(data, {
    displayAnnotations: true, 
    legendPosition: 'newRow',
    scaleColumns: [0,1,2],
    scaleType: 'allmaximized'
    });
}