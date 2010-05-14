<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td><form action="userPermis.php?id={~ID~}" method="post">
  <td background="../img/nieuwvakmidden.gif" align=center>
  Permissies voor <b>{~USERNAME~}</b>
  <table>
    <tr>
      <td>Spellen beheren: </td>
      <td><input type="checkbox" name="spellen" value=1{~SPELLEN~} id="radio"></td>
    </tr>
    <tr>
      <td>Forum mod: </td>
      <td><input type="checkbox" name="mod" value=2{~MOD~} id="radio"></td>
    </tr>
    <tr>
      <td>Forum admin: </td>
      <td><input type="checkbox" name="admin" value=4{~ADMIN~} id="radio"></td>
    </tr>
    <tr>
      <td>Users beheren: </td>
      <td><input type="checkbox" name="users" value=8{~USERS~} id="radio"></td>
    </tr>
    <tr>
      <td>Consoles beheren: </td>
      <td><input type="checkbox" name="consoles" value=16{~CONSOLES~} id="radio"></td>
    </tr>
    <tr>
      <td>Nieuws beheren: </td>
      <td><input type="checkbox" name="nieuws" value=32{~NIEUWS~} id="radio"></td>
    </tr>
    <tr>
      <td>Links beheren: </td>
      <td><input type="checkbox" name="links" value=64{~LINKS~} id="radio"></td>
    </tr>
    <tr>
      <td>Topics beheren: </td>
      <td><input type="checkbox" name="topics" value=128{~TOPICS~} id="radio"></td>
    </tr>
    <tr>
      <td>Favorieten beheren: </td>
      <td><input type="checkbox" name="favorieten" value=256{~FAVORIETEN~} id="radio"></td>
    </tr>
    <tr>
      <td>Backup maken: </td>
      <td><input type="checkbox" name="backup" value=512{~BACKUP~} id="radio"></td>
    </tr>
    <tr>
      <td>Mails beheren: </td>
      <td><input type="checkbox" name="mails" value=1024{~MAILS~} id="radio"></td>
    </tr>
    <tr>
      <td colspan=2>
        <input type="hidden" name="permissie" value=1>
        <input type="submit" value="Permissie geven">
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