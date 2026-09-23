<?php

use Webdevils\Spelcodes\ExitException;

error_reporting(E_ALL & ~E_DEPRECATED);

try {

    /* Header file importeren */
    include('Includes/header.php');

    /* Controleren of de id's er zijn */
    if((!isset($_GET['id'])) || (!isset($_GET['spelid'])))
    {
        echo('Geen id en/of spelid...');
        throw new ExitException();
    }

    /* Permissie controleren */
    if(!$cUser -> m_iPermis & 256)
    {
        echo('Geen permissie...');
        throw new ExitException();
    }

    $sQuery = "INSERT INTO spellenview (consoleid, spelid)
           VALUES ('" . add($_GET['id']) . "', '" . add($_GET['spelid']) . "');";
    if(mysql_query($sQuery))
    {
        header('Location: addGame.php?id=' . $_GET['id']);
    }
    else
    {
        $cTPL -> setPlace('TITEL', 'Fout met database');
        $cTPL -> setPlace('CONTENT', 'Door een fout met de database is je request niet verwerkt.');
    }

    $cTPL -> show();
} catch(ExitException $e) {}