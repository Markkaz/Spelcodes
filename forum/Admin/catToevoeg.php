<?php
/* Header importeren */
include('Includes/header.php');

/* Controleren of het formulier wel verzonden is */
if((!isset($_POST['titel'])) || (!isset($_POST['order'])))
{
  die('Geen permissie...');
}

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

/* Formulier verwerken */
$sQuery = "INSERT INTO forum_categories (cat_id, cat_titel, cat_order)
           VALUES ('', '" . add($_POST['titel']) . "', '" . add($_POST['order']) . "');";
if(mysql_query($sQuery))
{
  header('Location: index.php');
}
else
{
  $cTPL -> setPlace('TITEL', 'Fout met database');
  $cTPL -> setPlace('CONTENT', 'Door een fout met de database, is je categorie niet toegevoegd.');
  $cTPL -> show();
}
?>