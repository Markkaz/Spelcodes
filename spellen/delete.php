<?php
$iAantal = 0;
if($hDir = opendir('.'))
{
  print 'Directory geopend<br />';
  print 'De directory bevat de volgende bestanden:<br />';
  print '<ul>';
  while(($sDir = readdir($hDir)) !== false)
  {
    if(strpos($sDir, '.') === false)
    {
      print '<li>Open: ' . $sDir;
      if($hSub = opendir($sDir))
      {
        print '<ul>';
        while(($sFile = readdir($hSub)) !== false)
        {
          if(strpos($sFile, '.php') !== false || strpos($sFile, '.htaccess'))
          {
            print '<li>' . $sFile . '</li>';
            $iAantal++;
          }
        }
        print '</ul>';
        
        closedir($hSub);
      }
      print '</li>';
    }
  }
  print '</ul>';
  closedir($hDir);
}

print 'Aantal foute bestanden: ' . $iAantal;
?>