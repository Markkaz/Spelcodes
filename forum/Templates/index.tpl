<table cellspacing=0 cellpadding=0 border=0 width=550>
  <tr>
    <td width=41 height=11><img src="../img/koplinksboven.gif"></td>
    <td bgcolor="#990000" width=* rowspan=3 height=11 align=center valign=center id="kopje"><font face=arial size=6 color="#FF0000"><B>Forum</B></font></td>
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

{~CAT~}

[BLOCK categorie]
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
			    <td><B><font size=4>{~NAAM~}</font></B></td></tr>
			  <tr>
			    <th align="left"><I>Naam: </I></th>
			    <th align="left"><I>Topics: </I></th>
			    <th align="left"><I>Laatste post: </I></th>
			  </tr>
{~FORUM~}
			</table>
  </td>
  <td background="../img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="../img/nieuwvaklinksonder.gif"></td>
  <td background="../img/nieuwvakonder.gif" width=*><img src="../img/nieuwvakonder.gif"></td>
  <td width=12><img src="../img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table><P>
{~CAT~}
[END BLOCK categorie]

[BLOCK forum]
              <tr>
						    <td background="../img/patroon.gif" width=*><a href="viewForum.php?forumid={~FORUMID~}"><B><U>{~TITEL~}</U></B></a><br>{~BESCHRIJVING~}</td>
						    <td background="../img/patroon.gif" width=40 align=center>{~POSTS~}</td>
						    <td background="../img/patroon.gif" width=200><B>{~LAST_POST~}</B><Br><I>{~LAST_POST_TIME~}</I></td>
						  </tr>
{~FORUM~}
[END BLOCK forum]

[BLOCK empty]
{~EMPTY~}
[END BLOCK empty]