<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

/* Permissie controleren */
if(!$cUser -> m_iPermis & 8)
{
  die('Geen permissie...');
}

$cTPL -> setPlace('TITEL', 'Admin - Users beheren');
$cTPL -> setFile('CONTENT', 'Templates/users.tpl');
$cTPL -> parse();

/* Userdata ophalen */
$sQuery = "SELECT userid, username, permis FROM users ORDER BY permis DESC, username;";
if($cResult = mysql_query($sQuery))
{
  while($aData = mysql_fetch_assoc($cResult))
  {
    $cTPL -> setBlock('USER', 'user');
    $cTPL -> parse();
    
    $cTPL -> setPlace('ID', $aData['userid']);
    $cTPL -> setPlace('USERNAME', $aData['username']);
    $cTPL -> setPlace('PERMIS', $aData['permis']);
  }
}

$cTPL -> show();
?>