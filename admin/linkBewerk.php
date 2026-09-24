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
    if(isset($_POST['link']))
    {
        $sQuery = "UPDATE links SET link='" . add($_POST['link']) . "', url='" . add($_POST['url']) . "'
             WHERE linkid='" . add($_GET['id']) . "';";
        if(mysql_query($sQuery))
        {
            header('Location: links.php');
        }
        else
        {
            $cTPL -> setPlace('TITEL', 'Fout met database');
            $cTPL -> setPlace('CONTENT', 'Door een fout met de database is je request niet verwerkt.');
        }
    }
    else
    {
        $cTPL -> setPlace('TITEL', 'Admin - Link bewerken');
        $cTPL -> setFile('CONTENT', __DIR__ . '/Templates/linkBewerk.tpl');
        $cTPL -> parse();

        /* Data ophalen en verwerken */
        $sQuery = "SELECT link, url FROM links WHERE linkid='" . add($_GET['id']) . "';";
        if($cResult = mysql_query($sQuery))
        {
            $aData = mysql_fetch_assoc($cResult);
            $cTPL -> setPlace('LINK', $aData['link']);
            $cTPL -> setPlace('URL', $aData['url']);
            $cTPL -> setPlace('ID', $_GET['id']);
        }
    }

    $cTPL -> show();
} catch(ExitException $e) {}