<?php

use Webdevils\Spelcodes\ExitException;

error_reporting(E_ALL & ~E_DEPRECATED);

try {
    /* Header file importeren */
    include('Includes/header.php');

    /* Controleren of een id is */
    if(!isset($_GET['id']))
    {
        echo('Geen id...');
        throw new ExitException();
    }

    /* Permissie controleren */
    if(!($cUser -> m_iPermis & 16))
    {
        echo('Geen permissie...');
        throw new ExitException();
    }

    /* Controleren of het formulier is verzonden */
    if(isset($_POST['delete']))
    {
        $sQuery = "DELETE FROM consoles WHERE consoleid='" . add($_GET['id']) . "';";
        if(mysql_query($sQuery))
        {
            header('Location: consoles.php');
        }
        else
        {
            $cTPL -> setPlace('TITEL', 'Fout met database');
            $cTPL -> setPlace('CONTENT', 'Door een fout met de database is je request niet verwerkt.');
        }
    }
    else
    {
        $cTPL -> setPlace('TITEL', 'Admin - Console verwijderen');
        $cTPL -> setFile('CONTENT', __DIR__ . '/Templates/consoleVerwijder.tpl');
        $cTPL -> parse();

        $cTPL -> setPlace('ID', $_GET['id']);
    }

    $cTPL -> show();
} catch(ExitException $e) {}