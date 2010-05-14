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

/* Verbinden met mysql */
connectDB();

/* Permissies controleren */
if((isset($_GET['topicid'])) && (isset($_POST['reactie'])) && (isset($_GET['id'])))
{
  if(($cUser -> checkSession()) || ($cUser -> checkCookie()))
  {
    $sQuery = "INSERT INTO berichten (berichtid, topicid, userid, bericht, datum, tijd)
               VALUES ('', '" . add($_GET['topicid']) . "', '" . $cUser -> m_iUserid . "',
               '" . add($_POST['reactie']) . "', NOW(), NOW());";
    if(mysql_query($sQuery))
    {
      $cUser -> addPost();
      header('Location: gameview.php?id=' . $_GET['id'] . '&topicid=' . $_GET['topicid']);
    }
    else
    {
      Print 'Er is iets niet in orde met de database';
    }
  }
  else
  {
    header('Location: loginForm.php');
  }
}
else
{
  header('HTTP/1.0 404 Page not Found');
}
?>