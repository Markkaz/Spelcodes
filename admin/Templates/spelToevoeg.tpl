<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
<form action="spelToevoeg.php" method="post" enctype="multipart/form-data">
  <td background="../img/nieuwvakmidden.gif" align=center>


  <table>
    <tr>
      <td>Naam spel: </td>
      <td><input type="text" name="naam"></td>
    </tr>
    <tr>
      <td>Console: </td>
      <td><select name="console">{~OPTION~}</select></td>
    </tr>
    <tr>
      <td>Map naam: </td>
      <td><input type="text" name="map">
    </tr>
    <tr>
      <td>Ontwikkelaar: </td>
      <td><input type="text" name="developer"></td>
    </tr>
    <tr>
      <td>Ontwikkelaar site: </td>
      <td><input type="text" name="developersite"></td>
    </tr>
    <tr>
      <td>Uitgever: </td>
      <td><input type="text" name="publisher"></td>
    </tr>
    <tr>
      <td>Uitgever site: </td>
      <td><input type="text" name="publishersite"></td>
    </tr>
    <tr>
      <td>Display plaatje: </td>
      <td>
        <input type="file" name="plaatje">
      </td>
    </tr>
    <tr>
      <td colspan=2 align=center>
        <input type="submit" value="Toevoegen">
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
<option value="{~CONSOLEID~}">{~CONSOLE~}
{~OPTION~}
[END BLOCK option]