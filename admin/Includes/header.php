<?php
session_start();

/* Classes importeren */
include('../Classes/User.php');
include('../Classes/Template.php');

/* Includes importeren */
include('../Includes/connect.php');
include('../Includes/slashes.php');

/* Classes initialiseren */
$cUser = new User();
$cTPL = new Template('Templates/main.tpl');

/* Verbinding met database maken */
connectDB();

if((!$cUser -> checkSession()) && (!$cUser -> checkCookie()))
{
  header('Location: ../loginForm.php');
}

/* Users beheren */
if($cUser -> m_iPermis & 8)
{
  $cTPL -> setBlock('USERLINK', 'userlink');
}

/* Consoles beheren */
if(($cUser -> m_iPermis & 16) || ($cUser -> m_iPermis & 256))
{
  $cTPL -> setBlock('CONSOLELINK', 'consolelink');
}

/* Spellen beheren */
if(($cUser -> m_iPermis & 1) || ($cUser -> m_iPermis & 128))
{
  $cTPL -> setBlock('SPELLINK', 'spellink');
}

/* Nieuws beheren */
if($cUser -> m_iPermis & 32)
{
  $cTPL -> setBlock('NIEUWSLINK', 'nieuwslink');
}

/* Links beheren */
if($cUser -> m_iPermis & 64)
{
  $cTPL -> setBlock('LINKLINK', 'linklink');
}

/* Backup maken */
if($cUser -> m_iPermis & 512)
{
  $cTPL -> setBlock('BACKUPLINK', 'backuplink');
}

/* Mail beheren */
if($cUser -> m_iPermis & 1024)
{
  $cTPL -> setBlock('MAILLINK', 'maillink');
}
?>