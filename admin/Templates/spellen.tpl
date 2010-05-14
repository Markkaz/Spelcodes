<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="../img/nieuwvakmidden.gif" align=center>

{~TOEVOEGBEGIN~}<B>Spel toevoegen</B>{~TOEVOEGEND~}<P>

{~VORIGE~} {~NAVIGATIE~} {~VOLGENDE~}<p>

<table cellspacing=0 cellpadding=0 border=0 width=530>
  <tr>
    <th align=left>ID </th>
    <th align=left>Naam </th>
    <th align=left>Console </th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  {~SPEL~}
  <tr>
    <th align=left>ID </th>
    <th align=left>Naam </th>
    <th align=left>Console </th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
</table><P>
{~VORIGE~} {~NAVIGATIE~} {~VOLGENDE~}<p>

{~TOEVOEGBEGIN~}<B>Spel toevoegen</B>{~TOEVOEGEND~}<p>

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
  <td background="{~BG~}">{~ID~}</td>
  <td background="{~BG~}">{~TOPICSBEGIN~}{~NAAM~}{~TOPICSEND~}</td>
  <td background="{~BG~}">{~CONSOLE~}</td>
  <td background="{~BG~}">{~EDITBEGIN~}<img src="../img/veranderen.gif" border=0>{~EDITEND~}</td>
  <td background="{~BG~}">{~DELETEBEGIN~}<img src="../img/verwijderen.gif" border=0>{~DELETEEND~}</td>
  <td background="{~BG~}"><a href="../gameview.php?id={~ID~}"><img src="../img/pijl.gif" border=0> </a></td>
</tr>
{~SPEL~}
[END BLOCK spel]

[BLOCK edit]
<a href="spelBewerk.php?id={~ID~}">
[END BLOCK edit]

[BLOCK delete]
<a href="spelVerwijder.php?id={~ID~}">
[END BLOCK delete]

[BLOCK toevoeg]
<a href="spelToevoeg.php">
[END BLOCK toevoeg]

[BLOCK topics]
<a href="topicsSpellen.php?spelid={~ID~}">
[END BLOCK topics]

[BLOCK end]
</a>
[END BLOCK end]