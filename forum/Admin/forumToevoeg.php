<?php
/* Header importeren */
include('Includes/header.php');

/* Controleren of je bent ingelogd */
if((!$cUser -> checkSession()) && (!$cUser -> checkCookie()))
{
  header('Location: ../../loginForm.php');
}

/* Permissie controleren */
if(!$cUser -> m_iPermis & 4)
{
  die('Geen permissie...');
}

/* Controleren of het formulier is verzonden */
if((!isset($_POST['titel'])) || (!isset($_POST['beschrijving'])) || (!isset($_POST['catid'])))
{
  die('Geen permissie...');
}

/* Formulier verwerken */
$sQuery = "INSERT INTO forum_forums (forum_id, cat_id, forum_titel, forum_text, last_post, last_post_time)
           VALUES ('', '" . add($_POST['catid']) . "', '" . add($_POST['titel']) . "',
                   '" . add($_POST['beschrijving']) . "', '', '');";
if(mysql_query($sQuery))
{
  header('Location: index.php');
}
else
{
  $cTPL -> setPlace('TITEL', 'Fout met database');
  $cTPL -> setPlace('CONTENT', 'Door een fout met de database, kon het forum niet toegevoegd worden');
}
?>