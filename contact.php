<?php
error_reporting(E_ALL);
session_start();

/* Classes importeren */
include('Classes/User.php');
include('Classes/Template.php');

/* Includes importeren */
include('Includes/slashes.php');
include('Includes/connect.php');

/* Verbinding met database maken */
connectDB();

/*Classes initialiseren */
$cUser = new User();
$cTPL = new Template('Templates/main.tpl');

/* Pagina goed weergeven */
include('Includes/login.php');

/* Controleren of het formulier is verzonden */
if(isset($_POST['titel']))
{
  $sQuery = "INSERT INTO mail (mailid, titel, bericht, email, gelezen)
             VALUES ('', '" . add($_POST['titel']) . "', '" . add($_POST['bericht']) . "',
             '" . add($_POST['email']) . "', 0);";
  if(mysql_query($sQuery))
  {
    $cTPL -> setPlace('TITEL', 'Email verzonden');
    $cTPL -> setPlace('CONTENT', 'Je email is met succes verzonden. Wij proberen zo snel mogelijk reactie te geven');
  }
  else
  {
    $cTPL -> setPlace('TITEL', 'Fout met database');
    $cTPL -> setPlace('CONTENT', 'Door een fout met de database is je email niet verzonden.');
  }
}
else
{
  $cTPL -> setPlace('TITEL', 'Contact');
  $cTPL -> setFile('CONTENT', 'Templates/contact.tpl');
}

$cTPL -> show();
?>