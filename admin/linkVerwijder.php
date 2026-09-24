<?php
use Webdevils\Spelcodes\ExitException;

error_reporting(E_ALL & ~E_DEPRECATED);

try {
    /* Header importeren */
    include('Includes/header.php');

    /* Controleren op id */
    if(!isset($_GET['id']))
    {
        echo('Geen id...');
        throw new ExitException();
    }

    /* Permissie controleren */
    if(!($cUser -> m_iPermis & 64))
    {
        echo('Geen permissie...');
        throw new ExitException();
    }

    /* Controleren of het formulier is verzonden */
    if(isset($_POST['delete']))
    {
        $sQuery = "DELETE FROM links WHERE linkid='" . add($_GET['id']) . "';";
        if(mysql_query($sQuery))
        {
            header('Location: links.php');
        }
        else
        {
            $cTPL -> setPlace('TITEL', 'Admin - Fout met database');
            $cTPL -> setPlace('CONTENT', 'Door een fout in de database kon je request niet worden verwerkt.');
        }
    }
    else
    {
        $cTPL -> setPlace('TITEL', 'Admin - Link verwijderen');
        $cTPL -> setFile('CONTENT', __DIR__ . '/Templates/linkVerwijder.tpl');
        $cTPL -> parse();

        $cTPL -> setPlace('ID', $_GET['id']);
    }

    $cTPL -> show();
} catch(ExitException $e) {}