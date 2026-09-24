<?php

use Webdevils\Spelcodes\ExitException;

error_reporting(E_ALL & ~E_DEPRECATED);

try {
    /* Header importeren */
    include('Includes/header.php');

    /* Permissie controleren */
    if(!($cUser -> m_iPermis & 64))
    {
        echo('Geen permissie...');
        throw new ExitException();
    }

    $cTPL -> setPlace('TITEL', 'Admin - Links beheren');
    $cTPL -> setFile('CONTENT', __DIR__ . '/Templates/links.tpl');
    $cTPL -> parse();

    /* Data ophalen en verwerken */
    $sQuery = "SELECT linkid, link, url, incomming, outcomming FROM links ORDER BY link;";
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
            $cTPL -> setBlock('LINK', 'link');
            $cTPL -> parse();

            $cTPL -> setPlace('ID', $aData['linkid']);
            $cTPL -> setPlace('LINKNAAM', $aData['link']);
            $cTPL -> setPlace('URL', $aData['url']);
            $cTPL -> setPlace('IN', $aData['incomming']);
            $cTPL -> setPlace('OUT', $aData['outcomming']);
            $cTPL -> setPlace('BG', $sBG);
        }
    }

    $cTPL -> show();
} catch(ExitException $e) {}