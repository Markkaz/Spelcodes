<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../../img/nieuwvaklinksboven.gif"></td>
  <td background="../../img/nieuwvakboven.gif" width=* height=10><img src="../../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../../img/nieuwvaklinks.gif">&nbsp;</td><form action="forumBewerk.php?forumid={~FORUMID~}" method="post">
  <td background="../../img/nieuwvakmidden.gif" align=center>
  <table>
    <tr>
      <td>Titel: </td>
      <td><input type="text" name="titel" value="{~TITEL~}"></td>
    </tr>
    <tr>
      <td>Beschrijving: </td>
      <td><textarea name="beschrijving">{~BESCHRIJVING~}</textarea></td>
    </tr>
    <tr>
      <td>Categorie: </td>
      <td><select name="catid">{~OPTION~}</select>
    </tr>
    <tr>
      <td colspan=2>
        <input type="submit" value="Bewerk">
        <input type="button" value="Terug" onClick="location.replace('index.php')">
      </td>
    </tr>
  </table>
  </td></form>
  <td background="../../img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="../../img/nieuwvaklinksonder.gif"></td>
  <td background="../../img/nieuwvakonder.gif" width=*><img src="../../img/nieuwvakonder.gif"></td>
  <td width=12><img src="../../img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table><P>

[BLOCK option]
<option value="{~CATID~}"{~SELECTED~}>{~CATEGORIE~}
{~OPTION~}
[END BLOCK option]