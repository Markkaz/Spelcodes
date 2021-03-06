<?php
error_reporting(E_ALL);

/* Header file importeren */
include('Includes/header.php');

/* Permissie controleren */
if(!$cUser -> m_iPermis & 1)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if(isset($_POST['naam']))
{
  if(mkdir('../spellen/' . $_POST['map'], 0755))
  {
    if(move_uploaded_file($_FILES['plaatje']['tmp_name'], '../spellen/' . $_POST['map'] . '/display.jpg'))
    {
        $sQuery = "INSERT INTO spellen
                     (spelid, consoleid, naam, map, developer, publisher, developerurl, publisherurl, rating, stemmen)
                   VALUES ('', '" . add($_POST['console']) . "', '" . add($_POST['naam']) . "',
                           '" . add($_POST['map']) . "', '" . add($_POST['developer']) . "',
                           '" . add($_POST['publisher']) . "', '" . add($_POST['developersite']) . "',
                           '" . add($_POST['publishersite']) . "', 0, 0);";
        if(mysql_query($sQuery))
        {
          header('Location: spellen.php');
        }
        else
        {
          $cTPL -> setPlace('TITEL', 'Fout in database');
          $cTPL -> setPlace('CONTENT', 'Er is iets fout gegaan in de database');
        }
    }
    else
    {
      $cTPL -> setPlace('TITEL', 'Fout bij uploaden');
      $cTPL -> setPlace('CONTENT', 'Het bestand ' . $_FILES['plaatje']['name'] . ' kon niet worden geupload');
    }
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout bij map cre�ren');
    $cTPL -> setPlace('CONTENT', 'Kon de map ' . $_POST['map'] . ' niet cre�ren.');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Admin - Spellen - Spel toevoegen');
  $cTPL -> setFile('CONTENT', 'Templates/spelToevoeg.tpl');
  $cTPL -> parse();
  
  $sQuery = "SELECT consoleid, naam FROM consoles ORDER BY naam;";
  if($cResult = mysql_query($sQuery))
  {
    while($aData = mysql_fetch_assoc($cResult))
    {
      $cTPL -> setBlock('OPTION', 'option');
      $cTPL -> parse();
      
      $cTPL -> setPlace('CONSOLEID', $aData['consoleid']);
      $cTPL -> setPlace('CONSOLE', $aData['naam']);
      $cTPL -> parse();
    }
  }
}

$cTPL -> show();
?>