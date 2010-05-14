<script>
function smileypopup() {open("../Templates/smileysinvoegen.html","popup","toolbar=no,directories=no,scrollbars=yes,width=200,height=400")}
</script>

<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td><form action="postBewerk.php?postid={~POSTID~}" method="post" name="formulier">
  <td background="../img/nieuwvakmidden.gif" align=center>
  <table>
    <tr>
      <td>Titel: </td>
      <td><input type="text" name="titel" value="{~TITEL~}"></td>
    </tr>
    <tr>
      <td>Bericht: </td>
      <td align=right><a href="javascript:smileypopup()"><I>Smileys Invoegen</I></A></td>
    </tr>
    <tr>
      <td colspan=2><textarea name="reactie" rows=5 cols=50>{~BERICHT~}</textarea></td>
    </tr>
    <tr>
      <td colspan=2>
        <input type="submit" value="Bewerken">
        <input type="button" value="Terug" onClick="location.replace('viewTopic.php?p=0&topicid={~TOPICID~}')">
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