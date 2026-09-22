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

    if(!$cUser -> m_iPermis & 16)
    {
        echo('Geen permissie...');
        throw new ExitException();
    }

    /* Controleren of formulier is verzonden */
    if(isset($_POST['naam']))
    {
        $sQuery = "UPDATE consoles SET naam='" . add($_POST['naam']) . "' WHERE consoleid='" . add($_GET['id']) . "';";
        if(mysql_query($sQuery))
        {
            header('Location: consoles.php');
        }
        else
        {
            $cTPL -> setPlace('TITEL', 'Fout met database');
            $cTPL -> setPlace('CONTENT', 'Door een fout met de database kon je request niet worden uitgevoerd.');
        }
    }
    else
    {
        $cTPL -> setPlace('TITEL', 'Admin - Console bewerken');
        $cTPL -> setFile('CONTENT', __DIR__ . '/Templates/consoleBewerk.tpl');
        $cTPL -> parse();

        /* Data voor formulier ophalen */
        $sQuery = "SELECT naam FROM consoles WHERE consoleid='" . add($_GET['id']) . "';";
        if($cResult = mysql_query($sQuery))
        {
            $aData = mysql_fetch_assoc($cResult);
            $cTPL -> setPlace('ID', $_GET['id']);
            $cTPL -> setPlace('NAAM', $aData['naam']);
        }
    }

    $cTPL -> show();
} catch(ExitException $e) {}