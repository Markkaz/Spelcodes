<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Controleren of er een id is */
if(!isset($_GET['id']))
{
  die('Geen id...');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 1)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['delete']))
{
  $sQuery = "SELECT topicid FROM spellenhulp WHERE spelid='" . add($_GET['id']) . "';";
  if($cResult = mysql_query($sQuery))
  {
    if(mysql_num_rows($cResult) > 0)
    {
      $cTPL -> setPlace('TITEL', 'Admin - Spel niet verwijderd');
      $cTPL -> setPlace('CONTENT', 'Het spel kan niet worden verwijderd, omdat er nog topics naar dit spel verwijzen');
    }
    else
    {
      $sQuery = "DELETE FROM spellen WHERE spelid='" . add($_GET['id']) . "';";
      if(mysql_query($sQuery))
      {
        header('Location: spellen.php');
      }
      else
      {
        $cTPL -> setPlace('TITEL', 'Fout in database');
        $cTPL -> setPlace('CONTENT', 'Er is iets fout gegaan met de database');
      }
    }
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout met database');
    $cTPL -> setPlace('CONTENT', 'Er zit een fout in de database. Daardoor kan deze pagina niet worden uitgevoerd');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Admin - Spel verwijderen');
  $cTPL -> setFile('CONTENT', 'Templates/spelVerwijder.tpl');
  $cTPL -> parse();
  
  $cTPL -> setPlace('ID', $_GET['id']);
}

$cTPL -> show();
?>