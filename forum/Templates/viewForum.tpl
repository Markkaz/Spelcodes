<script>
function smileypopup() {open("../Templates/smileysinvoegen.html","popup","toolbar=no,directories=no,scrollbars=yes,width=200,height=400")}
</script>

<table cellspacing=0 cellpadding=0 border=0 width=550>
  <tr>
    <td width=41 height=11><img src="../img/koplinksboven.gif"></td>
    <td bgcolor="#990000" width=* rowspan=3 height=11 align=center valign=center id="kopje"><font face=arial size=6 color="#FF0000"><B>Topics</B></font></td>
    <td width=41><img src="../img/koprechtsboven.gif"></td>
  </tr>
  <tr>
    <td bgcolor="#990000" id="kopjelinks" height=20>&nbsp;</td>
    <td bgcolor="#990000" id="kopjerechts">&nbsp;</td>
  </tr>
  <tr>
    <td height=11><img src="../img/koplinksonder.gif"></td>
    <td><img src="../img/koprechtsonder.gif"></td>
  </tr>
</table><P>

<a href="index.php"><< Terug naar forum</a>

<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="../img/nieuwvakmidden.gif" align=center>
  
<table cellspacing=1 cellpadding=1 border=0 width=530>
<tr>
  <th align="left"><I>Titel: </I></th>
  <th align="left"><I>Geplaatst door/op: </I></th>
  <th align="left"><I>Posts: </I></th>
  <th align="left"><I>Laatste post: </I></th>
</tr>
{~TOPIC~}
</table>

  </td>
  <td background="../img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksonder.gif"></td>
  <td background="../img/nieuwvakonder.gif" width=*><img src="../img/nieuwvakonder.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table>

<P>

<TABLE cellspacing=0 cellpadding=0 border=0 width=300>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>
 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="../img/nieuwvakmidden.gif" align=center>
 <table>
  <tr><form action="topicToevoeg.php?forumid={~FORUMID~}" method="post" name="formulier">
    <td>
      
        <b>Plaats Topic: </b><br>
        <table>
          <tr>
            <td width=20>Titel: </td>
            <td><input type="text" name="titel" size=30></td>
          </tr>
          <tr>
            <td align=left>Bericht: </td>
            <td align=right><a href="javascript:smileypopup()"><I>Smileys Invoegen</I></A></td>
          <tr>
            <td colspan=2><textarea name="reactie" cols=40 rows=8></textarea></td>
          </tr>
          <tr>
            <td colspan=2 align=center>
              <input type="submit" value="Plaats Topic">
            </td>
          </tr>
        </table>
      
    </td></form>
  </tr>
</table>
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
	<td background="../img/patroon.gif"><a href='viewTopic.php?topicid={~TOPICID~}&p=0'><B>{~TITEL~}</B></a><br>(Pagina's: {~NUMMERS~})</td>
	<td background="../img/patroon.gif"><b>{~POSTER~}</b><I><BR>{~MOMENT~}</I></td>
	<td background="../img/patroon.gif" width=40 align=center>{~AANTAL_POSTS~}</td>
	<td background="../img/patroon.gif"><b>{~LAST_POST~}</b><I><BR>{~LAST_POST_TIME~}</I></td>
	<td background="">{~EDIT~}</td>
 </tr>
{~TOPIC~}
[END BLOCK topic]

[BLOCK edit]
	<a href='topicBewerk.php?topicid={~TOPICIDADMIN~}'><img src="../img/veranderen.gif" border=0></a>
	<a href='topicVerwijder.php?topicid={~TOPICIDADMIN~}'><img src="../img/verwijderen.gif" border=0></a>
	{~MOVE~}
[END BLOCK edit]

[BLOCK move]
  <a href="topicMove.php?topicid={~TOPICIDADMIN~}&forumid={~FORUMID~}"><img src="../img/pijl.gif" border=0></a>
[END BLOCK move]