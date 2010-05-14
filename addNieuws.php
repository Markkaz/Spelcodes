<?php
error_reporting(E_ALL);

session_start();

/* Classes importeren */
include('Classes/User.php');

/* Includes importeren */
include('Includes/connect.php');
include('Includes/slashes.php');

/* Classes initialiseren */
$cUser = new User();

/* Verbinding met de database maken */
connectDB();

/* Permissies controleren */
if(isset($_GET['id']))
{
  if(isset($_POST['reactie']))
  {
    if(($cUser -> checkSession()) || ($cUser -> checkCookie()))
    {
      $sQuery = "INSERT INTO nieuwsreacties (reactieid, nieuwsid, userid, bericht, datum, tijd)
                 VALUES ('', '" . add($_GET['id']) . "', '" . $cUser -> m_iUserid . "', 
                 '" . add($_POST['reactie']) . "', NOW(), NOW());";
      if(mysql_query($sQuery))
      {
        $cUser -> addPost();
        header('Location: shownieuws.php?id=' . $_GET['id']);
      }
      else
      {
        print 'Er is iets fout gegaan met de database en/of query';
        print '<br>' . $sQuery . '<br>' . mysql_error();
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
}
else
{
  header('HTTP/1.0 404 Page not Found');
}
?>