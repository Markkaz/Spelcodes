<script>
function smileypopup() {open("../Templates/smileysinvoegen.html","popup","toolbar=no,directories=no,scrollbars=yes,width=200,height=400")}
</script>

<table cellspacing=0 cellpadding=0 border=0 width=550>
  <tr>
    <td width=41 height=11><img src="../img/koplinksboven.gif"></td>
    <td bgcolor="#990000" width=* rowspan=3 height=11 align=center valign=center id="kopje"><font face=arial size=6 color="#FF0000"><B>{~TOPIC_TITEL~}</B></font></td>
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
  <a href="viewForum.php?forumid={~FORUMID~}"><< Terug naar lijst met topics</a><P>
  
<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="../img/nieuwvakmidden.gif" align=center>

  
<I>{~PREVIOUS~} <&nbsp;&nbsp;&nbsp;&nbsp; 
{~NUMMERS~} &nbsp;&nbsp;&nbsp;&nbsp;> 
{~NEXT~}
</I>

 {~REACTIES~}
<I>{~PREVIOUS~} <&nbsp;&nbsp;&nbsp;&nbsp; 
{~NUMMERS~} &nbsp;&nbsp;&nbsp;&nbsp;> 
{~NEXT~}
</I>



  </td>
  <td background="../img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksonder.gif"></td>
  <td background="../img/nieuwvakonder.gif" width=*><img src="../img/nieuwvakonder.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table><P>

<TABLE cellspacing=0 cellpadding=0 border=0 width=300>
 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
  <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="../img/nieuwvakmidden.gif" align=center>
  <table border=0>
  <tr><form action="postToevoeg.php?topicid={~TOPICID~}" method="post" name=formulier>
    <td align=center>
      
        <table border=0>
          <tr>
            <td colspan=3 align=center><b>Geef Reactie: </b></td>
          </tr>
          <tr>
            <td width=50>Titel:</td>
            <td align=center><input type="text" name="titel" value="{~TITEL_REACTIE~}" size=30></td>
          </tr>
          <tr>
            <td>Bericht:</td>
            <td align=right><a href="javascript:smileypopup()"><I>Smileys Invoegen</I></A></td>
          </tr>
          <tr>
            <td colspan=2>
              <textarea name="reactie" rows=8 cols=40></textarea>
            </td>
          </tr>
          <tr>
            <td colspan=2 align=center>
              <input type="submit" value="Plaats">
              <input type="reset" value="Reset">
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

[BLOCK reacties]
<P><table border=0 width=530 cellspacing=0 cellpadding=0>
  <tr>
    <td><B>{~TITEL~}</B></td>
    <td align=right>Geplaatst door <i>{~USERNAME~}</i> op <i>{~MOMENT~}</i></td>
    <td align=right width=30>{~EDIT~}</td>
  </tr>
  <tr>
    <td background="../img/patroon.gif" colspan=3>{~BERICHT~}</td>
  </tr>
</table>
{~REACTIES~}
[END BLOCK reacties]

[BLOCK edit]
	<a href="postBewerk.php?postid={~POSTID~}"><img src="../img/veranderen.gif" border=0></a>
	<a href="postVerwijder.php?postid={~POSTID~}"><img src="../img/verwijderen.gif" border=0></a>
[END BLOCK edit]