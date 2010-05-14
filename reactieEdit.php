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

/* Verbinding met mysql maken */
connectDB();

/* Permissies controleren */
if((isset($_GET['id'])) && (isset($_GET['spelid'])))
{
  if(($cUser -> checkSession()) || ($cUser -> checkCookie()))
  {
    $sQuery = "SELECT userid, topicid, bericht FROM berichten WHERE berichtid='" . $_GET['id'] . "';";
    if($cResult = mysql_query($sQuery))
    {
      $aData = mysql_fetch_assoc($cResult);
      if(($cUser -> m_iPermis & 2) || ($cUser -> m_iUserid == $aData['userid']))
      {
        /* Controleren of het formulier is verzonden */
        if(isset($_POST['bericht']))
        {
          $sQuery = "UPDATE berichten SET bericht='" . add($_POST['bericht']) . "' WHERE berichtid='" . add($_GET['id']) . "';";
          if(mysql_query($sQuery))
          {
            header('Location: gameview.php?id=' . $_GET['spelid'] . '&topicid=' . $aData['topicid']);
          }
          else
          {
            $cTPL -> setPlace('TITEL', 'Fout met de database');
            $cTPL -> setPlace('CONTENT', 'Er is iets fout gegaan met de mysql database');
          }
        }
        else
        {
          $cTPL -> setPlace('TITEL', 'Bericht bewerken');
          $cTPL -> setBlock('LOGIN', 'logout');
          if($cUser -> m_iPermis & 2)
          {
            $cTPL -> parse();
            $cTPL -> setBlock('ADMIN', 'admin');
          }
          $cTPL -> setFile('CONTENT', 'Templates/reactieEdit.tpl');
          $cTPL -> parse();
          
          $cTPL -> setPlace('BERICHT', $aData['bericht']);
          $cTPL -> setPlace('SPELID', $_GET['spelid']);
          $cTPL -> setPlace('TOPICID', $aData['topicid']);
          $cTPL -> setPlace('ID', $_GET['id']);
          
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
      $cTPL -> setPlace('TITEL', 'Fout met de database');
      $cTPL -> setPlace('CONTENT', 'Er is iets fout gegaan met de mysql database');
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