<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="../img/nieuwvakmidden.gif" align=center>

<I><B><font size=5>{~SPEL~}</font><br><font size=4>({~CONSOLE~})</font></B></I><P>
<a href="topicsSpellenToevoeg.php?spelid={~SPELID~}"><B>Nieuw topic toevoegen</a> | <a href="topicsSpellenOud.php?spelid={~SPELID~}">Bestaand topic toevoegen</a></B><P>

<table cellspacing=0 cellpadding=0 border=0 width=530>
<tr>
    <th align=left>ID: </th>
    <th align=left>Titel: </th>
    <th align=left>Auteur: </th>
    <th align=left>Datum: </th>
    <th align=left>Tijd: </th>
    <th align=left width=20></th>
    <th align=left width=20></th>
    <th align=left width=20></th>
    <th align=left width=20></th>
  </tr>
  {~TOPIC~}
<tr>
    <th align=left>ID: </th>
    <th align=left>Titel: </th>
    <th align=left>Auteur: </th>
    <th align=left>Datum: </th>
    <th align=left>Tijd: </th>
    <th align=left width=20></th>
    <th align=left width=20></th>
    <th align=left width=20></th>
    <th align=left width=20></th>
  </tr>
</table>
<P>
<a href="topicsSpellenToevoeg.php?spelid={~SPELID~}"><B>Nieuw topic toevoegen</a> | <a href="topicsSpellenOud.php?spelid={~SPELID~}">Bestaand topic toevoegen</a></B>

  </td>
  <td background="../img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksonder.gif"></td>
  <td background="../img/nieuwvakonder.gif" width=*><img src="../img/nieuwvakonder.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table>


[BLOCK topic]
<tr>
  <td background="{~BG~}">{~TOPICID~}</td>
  <td background="{~BG~}"><a href="../gameview.php?id={~SPELID~}&topicid={~TOPICID~}" target="_blanc">{~TITEL~}</a></td>
  <td background="{~BG~}">{~AUTEUR~}</td>
  <td background="{~BG~}">{~DATUM~}</td>
  <td background="{~BG~}">{~TIJD~}</td>
  <td background="{~BG~}"><a href="topicsSpellenUnlink.php?topicid={~TOPICID~}&spelid={~SPELID~}"><B>Ontkoppel</B></a></td>
  <td background="{~BG~}"><a href="topicsSpellenBewerk.php?topicid={~TOPICID~}&spelid={~SPELID~}"><img src="../img/veranderen.gif" border=0></a></td>
  <td background="{~BG~}"><a href="topicsSpellenVerwijder.php?topicid={~TOPICID~}&spelid={~SPELID~}"><img src="../img/verwijderen.gif" border=0></a></td>
  <td background="{~BG~}"><a href="../gameview.php?id={~SPELID~}&topicid={~TOPICID~}" target="_blanc"><img src="../img/pijl.gif" border=0></a></td>
</tr>
</tr>
{~TOPIC~}
[END BLOCK topic]