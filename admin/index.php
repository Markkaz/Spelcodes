<?php
error_reporting(E_ALL);

/* Header importeren */
include('Includes/header.php');

$cTPL -> setPlace('TITEL', 'Admin - Index');
$cTPL -> setFile('CONTENT', 'Templates/index.tpl');
$cTPL -> parse();

/* Users beheren */
if($cUser -> m_iPermis & 8)
{
  $cTPL -> setBlock('USERS', 'users');
}

/* Consoles beheren */
if($cUser -> m_iPermis & 16)
{
  $cTPL -> setBlock('CONSOLES', 'consoles');
}

/* Spellen beheren */
if(($cUser -> m_iPermis & 1) || ($cUser -> m_iPermis & 128))
{
  $cTPL -> setBlock('SPELLEN', 'spellen');
}

/* Nieuws beheren */
if($cUser -> m_iPermis & 32)
{
  $cTPL -> setBlock('NIEUWS', 'nieuws');
}

/* Links beheren */
if($cUser -> m_iPermis & 64)
{
  $cTPL -> setBlock('LINKS', 'links');
}

/* Backup maken */
if($cUser -> m_iPermis & 512)
{
  $cTPL -> setBlock('BACKUP', 'backup');
}

/* Mail beheren */
if($cUser -> m_iPermis & 1024)
{
  $cTPL -> setBlock('MAIL', 'mail');
}

$cTPL -> show();
?>