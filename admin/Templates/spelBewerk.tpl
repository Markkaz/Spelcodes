<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
<form action="spelBewerk.php?id={~ID~}" method="post">
  <td background="../img/nieuwvakmidden.gif" align=center>


  <table>
    <tr>
      <td>Naam spel: </td>
      <td><input type="text" name="naam" value="{~NAAM~}"></td>
    </tr>
    <tr>
      <td>Console: </td>
      <td><select name="console">{~OPTION~}</select></td>
    </tr>
    <tr>
      <td>Ontwikkelaar: </td>
      <td><input type="text" name="developer" value="{~DEVELOPER~}"></td>
    </tr>
    <tr>
      <td>Ontwikkelaar site: </td>
      <td><input type="text" name="developersite" value="{~DEVELOPERSITE~}"></td>
    </tr>
    <tr>
      <td>Uitgever: </td>
      <td><input type="text" name="publisher" value="{~PUBLISHER~}"></td>
    </tr>
    <tr>
      <td>Uitgever site: </td>
      <td><input type="text" name="publishersite" value="{~PUBLISHERSITE~}"></td>
    </tr>
    <tr>
      <td colspan=2>
        <input type="submit" value="Bewerken">
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


[BLOCK option]
<option value="{~CONSOLEID~}"{~SELECT~}>{~CONSOLE~}
{~OPTION~}
[END BLOCK option]