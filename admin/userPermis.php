<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Controleren op id */
if(!isset($_GET['id']))
{
  die('Geen id...');
}

if(!$cUser -> m_iPermis & 8)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['permissie']))
{
  $iPermis = 0;
  
  if(isset($_POST['spellen']))
  {
    $iPermis += $_POST['spellen'];
  }
  if(isset($_POST['mod']))
  {
    $iPermis += $_POST['mod'];
  }
  if(isset($_POST['admin']))
  {
    $iPermis += $_POST['admin'];
  }
  if(isset($_POST['users']))
  {
    $iPermis += $_POST['users'];
  }
  if(isset($_POST['consoles']))
  {
    $iPermis += $_POST['consoles'];
  }
  if(isset($_POST['nieuws']))
  {
    $iPermis += $_POST['nieuws'];
  }
  if(isset($_POST['links']))
  {
    $iPermis += $_POST['links'];
  }
  if(isset($_POST['topics']))
  {
    $iPermis += $_POST['topics'];
  }
  if(isset($_POST['favorieten']))
  {
    $iPermis += $_POST['favorieten'];
  }
  if(isset($_POST['backup']))
  {
    $iPermis += $_POST['backup'];
  }
  if(isset($_POST['mails']))
  {
    $iPermis += $_POST['mails'];
  }
  $sQuery = "UPDATE users SET permis='" . $iPermis . "' WHERE userid='" . add($_GET['id']) . "';";
  if(mysql_query($sQuery))
  {
    header('Location: users.php');
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout met database');
    $cTPL -> setPlace('CONTENT', 'Doordat er iets fout ging met de database, is je request niet verwerkt.');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Admin - Permissies geven');
  $cTPL -> setFile('CONTENT', 'Templates/userPermis.tpl');
  $cTPL -> parse();
  
  $sQuery ="SELECT username, permis FROM users WHERE userid='" . add($_GET['id']) . "';";
  if($cResult = mysql_query($sQuery))
  {
    $aData = mysql_fetch_assoc($cResult);
    $cTPL -> setPlace('USERNAME', $aData['username']);
    
    if($aData['permis'] & 1)
    {
      $cTPL -> setPlace('SPELLEN', ' checked');
    }
    if($aData['permis'] & 2)
    {
      $cTPL -> setPlace('MOD', ' checked');
    }
    if($aData['permis'] & 4)
    {
      $cTPL -> setPlace('ADMIN', ' checked');
    }
    if($aData['permis'] & 8)
    {
      $cTPL -> setPlace('USERS', ' checked');
    }
    if($aData['permis'] & 16)
    {
      $cTPL -> setPlace('CONSOLES', ' checked');
    }
    if($aData['permis'] & 32)
    {
      $cTPL -> setPlace('NIEUWS', ' checked');
    }
    if($aData['permis'] & 64)
    {
      $cTPL -> setPlace('LINKS', ' checked');
    }
    if($aData['permis'] & 128)
    {
      $cTPL -> setPlace('TOPICS', ' checked');
    }
    if($aData['permis'] & 256)
    {
      $cTPL -> setPlace('FAVORIETEN', ' checked');
    }
    if($aData['permis'] & 512)
    {
      $cTPL -> setPlace('BACKUP', ' checked');
    }
    if($aData['permis'] & 1024)
    {
      $cTPL -> setPlace('MAILS', ' checked');
    }
  }
  $cTPL -> setPlace('ID', $_GET['id']);
}

$cTPL -> show();
?>