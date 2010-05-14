<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="../img/nieuwvakmidden.gif" align=center>

<I><B><font size=5>{~TOEVOEG~}</font><br><font size=4> ({~CONSOLE~})</font></B></I><P>

{~VORIGE~} {~NAVIGATIE~} {~VOLGENDE~}<p>

<table cellspacing=0 cellpadding=0 border=0 width=530>
  <tr>
    <th align=left width=20>ID: </th>
    <th align=left width=270>Titel: </th>
    <th align=left width=80>Auteur: </th>
    <th align=left width=80>Datum: </th>
    <th align=left width=80>Tijd: </th>
    <th align=left width=80>Toevoegen: </th>
  </tr><td colspan=6>
{~SPEL~}
  </td><tr>
    <th align=left>ID: </th>
    <th align=left>Titel: </th>
    <th align=left>Auteur: </th>
    <th align=left>Datum: </th>
    <th align=left>Tijd: </th>
    <th align=left>Toevoegen: </th>
  </tr>
</table><p>

{~VORIGE~} {~NAVIGATIE~} {~VOLGENDE~}<p>

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
<i>{~NAAMSPEL~} ({~CONSOLESPEL~})</i>
<table cellspacing=0 cellpadding=0 border=0 width=530 background="../img/patroon.gif">
  {~TOPIC~}
</table><p>
{~SPEL~}
[END BLOCK spel]

[BLOCK topic]
<tr>
  <td width=20>{~ID~}</td>
  <td width=270><a href="../gameview.php?id={~IDSPEL~}&topicid={~ID~}" target="_blanc">{~TITEL~}</a></td>
  <td width=80>{~AUTEUR~}</td>
  <td width=80>{~DATUM~}</td>
  <td width=80>{~TIJD~}</td>
  <td width=80><a href="topicToevoegen.php?spelid={~SPELID~}&topicid={~ID~}"><B>Toevoegen</B></a></td>
</tr>
{~TOPIC~}
[END BLOCK topic]

[BLOCK leeg]
{~LEEG~}
[END BLOCK leeg]