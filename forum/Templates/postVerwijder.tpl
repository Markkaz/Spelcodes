<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td><form action="postVerwijder.php?postid={~POSTID~}" method="post">
  <td background="../img/nieuwvakmidden.gif" align=center>
  Weet je zeker dat je het bericht met postID <b>{~POSTID~}</b> wil verwijderen?<p>
  
  <input type="hidden" name="delete" value=1>
  <input type="submit" value="JA">
  <input type="button" value="NEE" onClick="location.replace('viewTopic.php?p=0&topicid={~TOPICID~}')">
  </td></form>
  <td background="../img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksonder.gif"></td>
  <td background="../img/nieuwvakonder.gif" width=*><img src="../img/nieuwvakonder.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table>