<?php

use Webdevils\Spelcodes\ExitException;

try {
    /* Header importeren */
    include('Includes/header.php');

    /* Controleren of er een mailid is */
    if(!isset($_GET['mailid']))
    {
        echo('Geen mailid...');
        throw new ExitException();
    }

    /* Permissie controleren */
    if(!($cUser -> m_iPermis & 1024))
    {
        echo('Geen permissie...');
        throw new ExitException();
    }

    /* Controleren of het formulier is verzonden */
    if(isset($_POST['delete']))
    {
        $sQuery = "DELETE FROM mail WHERE mailid='" . add($_GET['mailid']) . "';";
        if(mysql_query($sQuery))
        {
            header('Location: mail.php');
        }
        else
        {
            $cTPL -> setPlace('TITEL', 'Fout met database');
            $cTPL -> setPlace('CONTENT', 'Door een fout in de database, is de email niet verwijderd.');
        }
    }
    else
    {
        $cTPL -> setPlace('TITEL', 'Admin - Mail verwijderen');
        $cTPL -> setFile('CONTENT', __DIR__ . '/Templates/mailVerwijder.tpl');
        $cTPL -> parse();

        $cTPL -> setPlace('MAILID', $_GET['mailid']);
    }

    $cTPL -> show();
} catch(ExitException $e) {}