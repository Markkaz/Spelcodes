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

/* Verbinding met database maken */
connectDB();

/* Inloggen */
include('Includes/login.php');

if(!(($cUser -> checkSession()) || ($cUser -> checkCookie())))
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['username']))
{
  $sQuery = "SELECT userid FROM users WHERE username='" . add($_POST['username']) . "' AND password=PASSWORD('" . add($_POST['wachtwoord']) . "');";
  if($cResult = mysql_query($sQuery))
  {
    if(mysql_num_rows($cResult) <= 0)
    {
      header('Location: profiel.php?error=Je wachtwoord en/of gebruikersnaam klopt niet');
      exit;
    }
    $aData = mysql_fetch_assoc($cResult);
    if($aData['userid'] != $cUser -> m_iUserid)
    {
      header('Location: profiel.php?error=Je wachtwoord en/of gebruikersnaam klopt niet');
      exit;
    }
    
    /* Alle controles zijn uitgevoerd, nu kijken of de persoon wachtwoord wil veranderen */
    if(!empty($_POST['wachtwoord_nieuw1']))
    {
      if($_POST['wachtwoord_nieuw1'] != $_POST['wachtwoord_nieuw2'])
      {
        header('Location: profiel.php?error=De twee nieuwe wachtwoorden komen niet overeen');
        exit;
      }
      /* Het nieuwe wachtwoord opslaan */
      $sQuery = "UPDATE users SET password=PASSWORD('" . add($_POST['wachtwoord_nieuw1']) . "') WHERE userid='" . add($cUser -> m_iUserid) . "';";
      if(!mysql_query($sQuery))
      {
        header('Location: profiel.php?error=Er is een probleem met de database');
        exit;
      }
    }
      
    /* Controleren of de persoon zijn email wil veranderen */
    if(!empty($_POST['email']))
    {
      $sQuery = "SELECT userid FROM users WHERE email='" . add($_POST['email']) . "';";
      if(!$cResult = mysql_query($sQuery))
      {
        header('Location: profiel.php?error=Er is een probleem met de database');
        exit;
      }
      if(mysql_num_rows($cResult) > 0)
      {
        header('Location: profiel.php?error=Het email adres is al in gebruik');
        exit;
      }
      
      $sQuery = "UPDATE users SET email='" . add($_POST['email']) . "', activate=0 WHERE userid='" . add($cUser -> m_iUserid) . "';";
      if(!mysql_query($sQuery))
      {
        header('Location: profiel.php?error=Er is een probleem met de database');
        exit;
      }
      else
      {
        $sQuery = "SELECT username FROM users WHERE userid='" . add($cUser -> m_iUserid) . "';";
        if($cResult = mysql_query($sQuery))
        {
          $aData = mysql_fetch_assoc($cResult);
          
          $sSubject = 'Verandering van email bij Spelcodes';
          $sBericht = "Beste, " . strip($aData['username']) . "\nJij of iemand anders heeft het email adres bij zijn account veranderd naar " . add($_POST['email']) . ".\nJe account is door deze verandering tijdelijk gedeactiveerd. Klik op de volgende link om het weer te activeren:\nhttp://www.spelcodes.nl/reg.php?id=" . base64_encode($cUser -> m_iUserid) . "\n\nMet vriendelijke groeten,\nHet webmaster team van Spelcodes";
          mail(trim($_POST['email']), $sSubject, $sBericht);
        }
        else
        {
          header('Location: profiel.php?error=Er is iets fout gegaan bij het verzenden van een herregistratie mail');
        }
      }
    }
    
    /* Terug naar profiel pagina sturen */
    header('Location: profiel.php?error=Je profiel is met succes gewijzigd');
    exit;
  }
  else
  {
    header('Location: profiel.php?error=Er is een probleem met de database');
    exit;
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Profiel bewerken');
  $cTPL -> setFile('CONTENT', 'Templates/profiel.tpl');
  $cTPL -> parse();
  
  if(isset($_GET['error']))
  {
    $cTPL -> setPlace('ERROR', $_GET['error']);
  }
  
  $sQuery = "SELECT username FROM users WHERE userid='" . add($cUser -> m_iUserid) . "';";
  if($cResult = mysql_query($sQuery))
  {
    $aData = mysql_fetch_assoc($cResult);
    $cTPL -> setPlace('USERNAME', $aData['username']);
  }
}

$cTPL -> show();
?>