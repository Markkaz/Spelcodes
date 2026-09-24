<?php

use Webdevils\Spelcodes\ExitException;

try {
    /* Header importeren */
    include('Includes/header.php');

    /* Permissie controleren */
    if(!($cUser -> m_iPermis & 1024))
    {
        echo('Geen permissie...');
        throw new ExitException();
    }

    $cTPL -> setPlace('TITEL', 'Admin - Mail beheren');
    $cTPL -> setFile('CONTENT', __DIR__ . '/Templates/mail.tpl');
    $cTPL -> parse();

    /* Block nieuw en mail bewaren */
    $cTPL -> setBlock('EMPTYNIEUW', 'nieuw');
    $cTPL -> setBlock('EMPTYMAIL', 'mail');

    /* Data ophalen */
    $sQuery = "SELECT mailid, titel, gelezen FROM mail ORDER BY gelezen, mailid DESC;";
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

            if($aData['gelezen'] == 0)
            {
                $cTPL -> setBlock('MAIL', 'nieuw');
                $cTPL -> parse();
            }
            else
            {
                $cTPL -> setBlock('MAIL', 'mail');
                $cTPL -> parse();
            }

            $cTPL -> setPlace('MAILID', $aData['mailid']);
            $cTPL -> setPlace('TITEL', $aData['titel']);
            $cTPL -> setPlace('BG', $sBG);
            $cTPL -> parse();
        }
    }

    $cTPL -> show();
} catch (ExitException $e) {}