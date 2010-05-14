<?php
error_reporting(E_ALL);
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

/* Verbinding met de database maken */
connectDB();

/* Controleren of de user is ingelogd */
if(($cUser -> checkSession()) || ($cUser -> checkCookie()))
{
  $cTPL -> setBlock('LOGIN', 'logout');
  $cTPL -> setBlock('FORUMLINKS', 'forumingelogd');
  
  if(2045 & $cUser -> m_iPermis)
  {
    $cTPL -> setBlock('ADMIN', 'admin');
  }
  
  if($cUser -> m_iPermis & 4)
  {
    $cTPL -> setBlock('FORUMADMIN', 'forumadmin');
    $cTPL -> setBlock('FORUMADMINBOVEN', 'forumadminboven');
  }
}
else
{
  $cTPL -> setBlock('LOGIN', 'login');
  $cTPL -> setBlock('FORUMLINKS', 'forumnormaal');
  $cTPL -> parse();
  
  $cTPL -> setPlace('THISPAGE', $_SERVER['PHP_SELF']);
}
?>