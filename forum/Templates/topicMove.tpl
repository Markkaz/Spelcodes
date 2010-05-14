<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td><form action="topicMove.php?topicid={~TOPICID~}&forumid={~FORUMID~}" method="post">
  <td background="../img/nieuwvakmidden.gif" align=center>
  <table>
    <tr>
      <td>Forum: </td>
      <td>
        <select name="forumid">
          {~OPTION~}
        </select>
      </td>
    </tr>
    <tr>
      <td colspan=2>
        <input type="submit" value="Verplaats">
        <input type="button" value="Terug" onClick="location.replace('viewForum.php?forumid={~FORUMID~}')">
      </td>
    </tr>
  </table>
  </td></form>
  <td background="../img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksonder.gif"></td>
  <td background="../img/nieuwvakonder.gif" width=*><img src="../img/nieuwvakonder.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table>

[BLOCK option]
<option value="{~OPTIONFORUMID~}"{~SELECTED~}>{~FORUMNAAM~}
{~OPTION~}
[END BLOCK option]