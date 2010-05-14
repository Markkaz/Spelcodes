<?php
error_reporting(E_ALL);

/* Includes importeren */
include('Includes/connect.php');
include('Includes/slashes.php');

/* Verbinding met de database maken */
connectDB();

/* Controleren of er een id is meegegeven */
if(isset($_GET['id']))
{
  $sQuery = "UPDATE links SET outcomming=outcomming+1 WHERE linkid='" . add($_GET['id']) . "';";
  if(mysql_query($sQuery))
  {
    $sQuery = "SELECT url FROM links WHERE linkid='" . add($_GET['id']) . "';";
    if($cResult = mysql_query($sQuery))
    {
      $aData = mysql_fetch_assoc($cResult);
      header('Location: ' . $aData['url']);
    }
    else
    {
      header('HTTP/1.0 404');
    }
  }
  else
  {
    header('HTTP/1.0 404');
  }
}
else
{
  header('HTTP/1.0 404');
}
?>