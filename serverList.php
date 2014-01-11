<?php
use KAGClient\Client;
include 'sortFunctions.php';
require_once 'bootstrap.php';

/** Process sort parameters **/
$sortFunctions = array('playersDesc', 'playersAsc');
$sortFunction = 'playersDesc';
if(isset($_POST['sort']) && array_search($_POST['sort'], $sortFunctions, true) !== false)
  $sortFunction = $_POST['sort'];

/** Process options **/
$getServerListOptions = array('current' => true);
$validOptions = array('gold', 'password');
if(isset($_POST['options']) && is_array($_POST['options']))
{
  foreach($_POST['options'] as $key => $option)
  {
    if(array_search($key, $validOptions, true) !== false){
      $getServerListOptions[$key] = KAGClient\ParseUtils::parseVariable ($option);
      if($getServerListOptions[$key] === false)
        unset($getServerListOptions[$key]);
    }
  }
}

/** Init KAGClient **/
$cli = new Client();

/** Fetch Servers **/
try {
$servers = $cli->getServerList($getServerListOptions);
} catch (KAGClient\ClientException $e) {
  $errorMessage = $e->getMessage();
  include 'error.php';
  exit;  
}

$servers = $servers['serverList'];

/** Sort server list **/
usort($servers, $sortFunction);
?>

<?php foreach ($servers as $server): ?>
        <?php $serverIp = (isset($server['serverIPv4Address'])) ? $server['serverIPv4Address'] : $server['serverIPv6Address']; ?>
        <div class="row server-row">
          <div class="span5">
            <div class="server-name">
              <h2>
                <a href="#" 
                   onclick="connect('<?php echo $serverIp . "','" . $server['serverPort'] ?>')"><?php echo $server['serverName'] ?></a> 
                
              </h2>


            </div>
            <div class="server-desc">
              <p><?php echo $server['description'] ?></p>
            </div>
            <div class="server-stats">
              <table>
                <tr>
                  <td>Current/Max Players:</td>
                  <td><strong><?php echo $server['currentPlayers'] . ' / ' . $server['maxPlayers'] ?></strong></td>
                </tr>
                <tr>
                  <td>Game Mode:</td>
                  <td><strong><?php echo $server['gameMode'] ?></strong></td>
                </tr>
                <tr>
                  <td>Map Size:</td>
                  <td><strong><?php echo $server['mapW'] . 'x' . $server['mapH'] ?></strong></td>
                </tr>
                <tr>
                  <td>Gold Only:</td>
                  <td><strong><?php echo (($server['gold'] == 0) ? 'No' : '<span style="color: #AE841A">Yes</span>') ?></strong></td>
                </tr>
                <tr>
                  <td>Password Protected:</td>
                  <td><strong><?php echo (($server['password'] == 0) ? 'No' : '<span style="color: #b00">Yes</span>') ?></strong></td>
                </tr>
                <tr>
                  <td>Build:</td>
                  <td><strong><?php echo $server['build'] ?></strong></td>
                </tr>

              </table>
            </div>
            <div class="server-buttons">
              <a class="btn" 
                   href="#" 
                   onclick="getServerInfo(this, '<?php echo $serverIp . "','" . $server['serverPort'] ?>')">
                  <i class="icon-search"></i> Info
                </a>
              <br />
              <a href="<?php echo gameLink($serverIp, $server['serverPort']) ?>" 
                 class="btn btn-success">
                <i class="icon-play icon-white"></i> Play
              </a> 
            </div>
          </div>
          <div class="span7">
            <div class="server-minimap">
              <img src="https://api.kag2d.com/server/ip/<?php echo $server['serverIPv4Address'] . '/port/' . $server['serverPort'] ?>/minimap" /> 
            </div>
          </div>
        </div>
      <?php endforeach; ?>