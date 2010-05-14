<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="../img/nieuwvakmidden.gif" align=center>
  <a href="linkToevoeg.php"><B>Link toevoegen</B></a><p>
<table cellspacing=0 cellpadding=0 border=0 width=530>
  <tr>
    <th align=left>ID: </th>
    <th align=left>Link: </th>
    <th align=left>Uit: </th>
    <th align=left>In: <th>
    <th></th>
    <th></th>
  </tr>
  {~LINK~}
  <tr>
    <th align=left>ID: </th>
    <th align=left>Link: </th>
    <th align=left>Uit: </th>
    <th align=left>in: </th>
    <th width=20></th>
    <th width=20></th>
  </tr>
</table><P>
  <a href="linkToevoeg.php"><B>Link toevoegen</B></a><p>
  </td>
  <td background="../img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksonder.gif"></td>
  <td background="../img/nieuwvakonder.gif" width=*><img src="../img/nieuwvakonder.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table>
[BLOCK link]
<tr>
  <td background="{~BG~}">{~ID~}</td>
  <td background="{~BG~}"><a href="{~URL~}" target="blanc">{~LINKNAAM~}</a></td>
  <td background="{~BG~}">{~OUT~}</td>
  <td background="{~BG~}">{~IN~}</td>
  <td background="{~BG~}"><a href="linkBewerk.php?id={~ID~}"><img src="../img/veranderen.gif" border=0></a></td>
  <td background="{~BG~}"><a href="linkVerwijder.php?id={~ID~}"><img src="../img/verwijderen.gif" border=0></a></td>
</tr>
{~LINK~}
[END BLOCK link]