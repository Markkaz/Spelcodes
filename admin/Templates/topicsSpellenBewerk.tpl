<script>
function cheatpopup() {open("cheats.php","popup","toolbar=no,directories=no,scrollbars=yes,width=370,height=400")}
</script>

<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
  <form action="topicsSpellenBewerk.php?topicid={~TOPICID~}&spelid={~SPELID~}" method="post">
  <td background="../img/nieuwvakmidden.gif" align=center>
  <table border=0>
    <tr>
      <td>Titel topic: </td>
      <td><input type="text" name="titel" value="{~TITEL~}"></td>
    </tr>
    <tr>
      <td colspan=2>Bericht:<br>
      <textarea rows=12 cols=60 name="bericht">{~BERICHT~}</textarea>
    </tr>
    <tr>
      <td colspan=2 align=right>
        <a href="" target=blanc onclick="cheatpopup()"><I>Cheats Invoegen</I></a>
      </td>
    </tr>
    <tr>
      <td colspan=2 align=center>
        <input type="submit" value="Bewerken">
        <input type="button" value="Terug" onClick="location.replace('topicsSpellen.php?spelid={~SPELID~}')">
      </td>
    </tr>
  </table>
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