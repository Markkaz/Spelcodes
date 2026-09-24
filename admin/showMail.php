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
    if(!$cUser -> m_iPermis & 1024)
    {
        echo('Geen permissie...');
        throw new ExitException();
    }

    $cTPL -> setPlace('TITEL', 'Admin - Bekijk mail');
    $cTPL -> setFile('CONTENT', __DIR__ . '/Templates/showMail.tpl');
    $cTPL -> parse();

    $sQuery = "SELECT titel, bericht, email FROM mail WHERE mailid='" . add($_GET['mailid']) . "';";
    if($cResult = mysql_query($sQuery))
    {
        $aData = mysql_fetch_assoc($cResult);
        $cTPL -> setPlace('TITEL', $aData['titel']);
        $cTPL -> setPlace('EMAIL', $aData['email']);
        $cTPL -> setPlace('BERICHT', nl2br(htmlspecialchars($aData['bericht'])));
    }

    /* Het bericht moet nu gelezen zijn */
    $sQuery = "UPDATE mail SET gelezen=1;";
    mysql_query($sQuery);

    $cTPL -> show();
} catch (ExitException $e) {}