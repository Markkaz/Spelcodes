<?php
error_reporting(E_ALL);

session_start();

/* Classes importeren */
include('Classes/User.php');
include('Classes/Template.php');

/* Includes importeren */
include('Includes/connect.php');
include('Includes/slashes.php');

/* Classes initialiseren */
$cUser = new User();
$cTPL = new Template('Templates/main.tpl');

/* Verbinding met de database maken */
connectDB();

if(isset($_GET['spelid']))
{
  if(isset($_POST['stem']))
  {
    $sQuery = "SELECT spelid FROM stemmen 
               WHERE spelid='" . add($_GET['spelid']) . "' AND ip='" . $_SERVER['REMOTE_ADDR'] . "';";
    if($cResult = mysql_query($sQuery))
    {
      if(mysql_num_rows($cResult) > 0)
      {
        header('Location: gameview.php?id=' . $_GET['spelid']);
      }
      else
      {
        $sQuery = "INSERT INTO stemmen (spelid, ip)
                   VALUES('" . add($_GET['spelid']) . "', '" . $_SERVER['REMOTE_ADDR'] . "');";
        mysql_query($sQuery);
        
        $sQuery = "UPDATE spellen SET rating = rating + " . add($_POST['stem']) . ", stemmen=stemmen + 1
                   WHERE spelid='" . add($_GET['spelid']) . "';";
        mysql_query($sQuery);
        
        header('Location: gameview.php?id=' . $_GET['spelid']);
      }
    }
    else
    {
      $cTPL -> setPlace('TITEL', 'Fout in de database');
      $cTPL -> setPlace('CONTENT', 'Er is iets fout in de database gegaan, waardoor je niet kan stemmen.');
      $cTPL -> show();
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