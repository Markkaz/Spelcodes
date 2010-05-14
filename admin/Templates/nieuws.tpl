<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="../img/nieuwvakmidden.gif" align=center>
  
  <a href="nieuwsToevoeg.php"><B>Nieuws toevoegen</B></a>
<P>
<table cellspacing=0 cellpadding=0 border=0 width=530>
  <tr>
    <th align=left>ID: </th>
    <th align=left>Titel: </th>
    <th align=left>Auteur: </th>
    <th align=left>Datum: </th>
    <th align=left>Tijd: </th>
    <th align=left>&nbsp; </th>
    <th align=left>&nbsp; </th>
  </tr>
  {~NIEUWS~}
  <tr>
    <th align=left>ID: </th>
    <th align=left>Titel: </th>
    <th align=left>Auteur: </th>
    <th align=left>Datum: </th>
    <th align=left>Tijd: </th>
    <th align=left>&nbsp; </th>
    <th align=left>&nbsp; </th>
  </tr>
</table>
<P>
<a href="nieuwsToevoeg.php"><B>Nieuws toevoegen</B></a>
  </td>
  <td background="../img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksonder.gif"></td>
  <td background="../img/nieuwvakonder.gif" width=*><img src="../img/nieuwvakonder.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table>

[BLOCK nieuws]
<tr>
  <td background="{~BG~}">{~ID~}</td>
  <td background="{~BG~}"><a href="../shownieuws.php?id={~ID~}" target="_blanc">{~TITEL~}</a></td>
  <td background="{~BG~}">{~AUTEUR~}</td>
  <td background="{~BG~}">{~DATUM~}</td>
  <td background="{~BG~}">{~TIJD~}</td>
  <td background="{~BG~}"><a href="nieuwsBewerk.php?id={~ID~}"><img src="../img/veranderen.gif" border=0></a></td>
  <td background="{~BG~}"><a href="nieuwsVerwijder.php?id={~ID~}"><img src="../img/verwijderen.gif" border=0></a></td>
</tr>
{~NIEUWS~}
[END BLOCK nieuws]