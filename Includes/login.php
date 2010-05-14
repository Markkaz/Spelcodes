<?php
if(($cUser -> checkSession()) || ($cUser -> checkCookie()))
{
  $cTPL -> setBlock('LOGIN', 'logout');
  $cTPL -> setBlock('FORUMLINKS', 'forumingelogd');
  if(2045 & $cUser -> m_iPermis)
  {
    $cTPL -> parse();
    $cTPL -> setBlock('ADMIN', 'admin');
    if($cUser -> m_iPermis & 4)
    {
      $cTPL -> setBlock('FORUMADMIN', 'forumadmin');
    }
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