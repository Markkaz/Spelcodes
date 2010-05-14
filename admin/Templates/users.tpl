<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="../img/nieuwvakmidden.gif" align=center>


<table cellspacing=0 cellpadding=0 border=0 width=530>
<tr>
    <th align=left>ID: </th>
    <th align=left>Gebruikersnaam: </th>
    <th align=left>Niveau: </th>
    <th></th>
  </tr>
  {~USER~}
  <tr>
    <th align=left>ID: </th>
    <th align=left>Gebruikersnaam: </th>
    <th align=left>Niveau: </th>
    <th></th>
  </tr>
</table>


  </td>
  <td background="../img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksonder.gif"></td>
  <td background="../img/nieuwvakonder.gif" width=*><img src="../img/nieuwvakonder.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table>


[BLOCK user]
<tr>
  <td align=left background="{~BG~}">{~ID~}</td>
  <td align=left background="{~BG~}">{~USERNAME~}</td>
  <td align=left background="{~BG~}">{~PERMIS~}</td>
  <td background="{~BG~}"><a href="userPermis.php?id={~ID~}"><b>Permissies geven</b></a></td>
</tr>
{~USER~}
[END BLOCK user]