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
    if(!($cUser -> m_iPermis & 256))
    {
        echo('Geen permissie...');
        throw new ExitException();
    }

    $cTPL -> setPlace('TITEL', 'Admin - Consoles - Favoriet toevoegen');
    $cTPL -> setFile('CONTENT', __DIR__ . '/Templates/addGameToevoeg.tpl');
    $cTPL -> parse();

    /* Data voor pagina ophalen en verwijderen */
    $sQuery = "SELECT spelid, naam FROM spellen WHERE consoleid='" . add($_GET['id']) . "' ORDER BY naam;";
    if($cResult = mysql_query($sQuery))
    {
        $sBG = '';
        while($aData = mysql_fetch_assoc($cResult))
        {
            if($sBG == '')
            {
                $sBG = '../img/patroon.gif';
            }
            else
            {
                $sBG = '';
            }
            $cTPL -> setBlock('SPEL', 'spel');
            $cTPL -> parse();

            $cTPL -> setPlace('SPELID', $aData['spelid']);
            $cTPL -> setPlace('NAAM', $aData['naam']);
            $cTPL -> setPlace('BG', $sBG);
            $cTPL -> parse();
        }
    }

    $cTPL -> setPlace('ID', $_GET['id']);

    $cTPL -> show();
} catch (ExitException $e) {}