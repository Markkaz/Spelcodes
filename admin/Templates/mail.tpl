<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="../img/nieuwvakmidden.gif" align=center>
  <table width=530>
  <tr>
    <th width=20>ID: </th>
    <th>Titel: </th>
    <th width=10></th>
  </tr>
  {~MAIL~}
  <tr>
    <th>ID: </th>
    <th>Titel: </th>
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

[BLOCK mail]
<tr>
  <td background="{~BG~}">{~MAILID~}</td>
  <td background="{~BG~}"><a href="showMail.php?mailid={~MAILID~}">{~TITEL~}</a></td>
  <td background="{~BG~}"><a href="mailVerwijder.php?mailid={~MAILID~}"><img src="../img/verwijderen.gif" border=0></a></td>
</tr>
{~MAIL~}
[END BLOCK mail]

[BLOCK nieuw]
<tr>
  <td background="{~BG~}"><b>{~MAILID~}</b></td>
  <td background="{~BG~}"><b><a href="showMail.php?mailid={~MAILID~}">{~TITEL~}</a></b></td>
  <td background="{~BG~}"><a href="mailVerwijder.php?mailid={~MAILID~}"><img src="../img/verwijderen.gif" border=0></a></td>
</tr>
{~MAIL~}
[END BLOCK nieuw]

[BLOCK empty]
{~EMPTYNIEUW~}
{~EMPTYMAIL~}
[END BLOCK empty]