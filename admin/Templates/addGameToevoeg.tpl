<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="../img/nieuwvakmidden.gif" align=center>
  
  <h1><I>{~CONSOLE~}</I></h2>
  
<table cellspacing=0 cellpadding=0 border=0 width=530>
  <tr>
    <th align=left>Spelid: </th>
    <th align=left>Naam: </th>
    <th align=left>&nbsp;</th>
  </tr>
  {~SPEL~}
  <tr>
    <th align=left>Spelid: </th>
    <th align=left>Naam: </th>
    <th align=left>&nbsp; </th>
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

[BLOCK spel]
<tr>
  <td background="{~BG~}">{~SPELID~}</td>
  <td background="{~BG~}">{~NAAM~}</td>
  <td background="{~BG~}"><a href="addGameAdd.php?id={~ID~}&spelid={~SPELID~}"><B>Toevoegen</B></a></td>
</tr>
{~SPEL~}
[END BLOCK spel]