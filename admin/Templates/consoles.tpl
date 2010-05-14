<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=*><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
<form action="spelVerwijder.php?id={~ID~}" method="post">
  <td background="../img/nieuwvakmidden.gif" align=center>
{~CONSOLESTART~}<B>Console toevoegen</B>{~CONSOLEEND~}<P>
<table cellspacing=0 cellpadding=0 border=0 width=530>
  <tr>
    <th align=left>ID </th>
    <th align=left>Naam </th>
    <th width=20></th>
    <th width=20></th>
    <th width=20></th>
  </tr>
  {~CONSOLE~}
  <tr>
    <th align=left>ID </th>
    <th align=left>Naam </th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
</table><P>
{~CONSOLESTART~}<B>Console toevoegen</B>{~CONSOLEEND~}
  </td>
  <td height=20 background="../img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksonder.gif"></td>
  <td background="../img/nieuwvakonder.gif" width=* height=5><img src="../img/nieuwvakonder.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table>


[BLOCK console]
<tr>
  <td background="{~BG~}">{~ID~}</td>
  <td background="{~BG~}">{~ADDGAMESTART~}{~NAAM~}{~ADDGAMEEND~}</td>
  <td background="{~BG~}">{~EDITSTART~}<img src="../img/veranderen.gif" border=0>{~EDITEND~}</td>
  <td background="{~BG~}">{~DELETESTART~}<img src="../img/verwijderen.gif" border=0>{~DELETEEND~}</td>
  <td background="{~BG~}"><a href="../consoles.php?id={~ID~}"><img src="../img/pijl.gif" border=0></a></td>
</tr>
{~CONSOLE~}
[END BLOCK console]

[BLOCK consolestart]
<a href="consoleToevoeg.php">
[END BLOCK consolestart]

[BLOCK addgamestart]
<a href="addGame.php?id={~ID~}">
[END BLOCK addgamestart]

[BLOCK editstart]
<a href="consoleBewerk.php?id={~ID~}">
[END BLOCK editstart]

[BLOCK deletestart]
<a href="consoleVerwijder.php?id={~ID~}">
[END BLOCK deletestart]

[BLOCK end]
</a>
[END BLOCK end]