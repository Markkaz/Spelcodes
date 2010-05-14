<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
  <form action="topicsSpellenUnlink.php?topicid={~TOPICID~}&spelid={~SPELID~}" method="post">
  <td background="../img/nieuwvakmidden.gif" align=center>

  Weet je zeker dat je het topic met topicID <b>{~TOPICID~}</b> wil unlinken?<br>
  <B>
    Waarschuwing: Als dit topic aan geen enkel ander spel meer is verbonden, zal het dus verwijderd worden!
  </b><P>
  
  <input type="hidden" name="unlink" value="1">
  
  <input type="submit" value="JA">
  <input type="button" value="NEE" onClick="location.replace('topicsSpellen.php?spelid={~SPELID~}')">

  </td>
  </form>
  <td background="../img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksonder.gif"></td>
  <td background="../img/nieuwvakonder.gif" width=*><img src="../img/nieuwvakonder.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table>