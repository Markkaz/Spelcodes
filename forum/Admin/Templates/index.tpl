<table cellspacing=0 cellpadding=0 border=0 width=550>
  <tr>
    <td width=41 height=11><img src="../../img/koplinksboven.gif"></td>
    <td bgcolor="#990000" width=* rowspan=3 height=11 align=center valign=center id="kopje"><font face=arial size=6 color="#FF0000"><B>Forum-Admin</B></font></td>
    <td width=41><img src="../../img/koprechtsboven.gif"></td>
  </tr>
  <tr>
    <td bgcolor="#990000" id="kopjelinks" height=20>&nbsp;</td>
    <td bgcolor="#990000" id="kopjerechts">&nbsp;</td>
  </tr>
  <tr>
    <td height=11><img src="../../img/koplinksonder.gif"></td>
    <td><img src="../../img/koprechtsonder.gif"></td>
  </tr>
</table><P>

{~CAT~}
<table>
  <tr>
    <td>
<form action="catToevoeg.php" method="post">
<TABLE cellspacing=0 cellpadding=0 border=0 width=250>
 <tr>
  <td width=12 height=10><img src="../../img/nieuwvaklinksboven.gif"></td>
  <td background="../../img/nieuwvakboven.gif" width=* height=10><img src="../../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../../img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="../../img/nieuwvakmidden.gif" align=center>
  
        <b>Nieuwe Categorie: </b><br>
          <table>
            <tr>
              <td>Titel: </td>
              <td><input type="text" name="titel"></td>
            </tr>
            <tr>
              <td>Order: </td>
              <td><input type="text" name="order"></td>
            </tr>
            <tr>
              <td colspan=2 align=center>
                <input type="submit" value="Toevoegen">
              </td>
            </tr>
          </table>
  </td>
  <td background="../../img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="../../img/nieuwvaklinksonder.gif"></td>
  <td background="../../img/nieuwvakonder.gif" width=*><img src="../../img/nieuwvakonder.gif"></td>
  <td width=12><img src="../../img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table><P>
</form>
    </td>
    
    
    <td>
<TABLE cellspacing=0 cellpadding=0 border=0 width=250>
 <tr>
  <td width=12 height=10><img src="../../img/nieuwvaklinksboven.gif"></td>
  <td background="../../img/nieuwvakboven.gif" width=* height=10><img src="../../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../../img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="../../img/nieuwvakmidden.gif" align=center>
<form action="forumToevoeg.php" method="post">
  <table>
    <tr>
      <th colspan=2 align=center>Nieuw Forum</td>
    </tr>
    <tr>
      <td>Titel: </td>
      <td><input type="text" name="titel"></td>
    </tr>
    <tr>
      <td>Beschrijving: </td>
      <td><textarea name="beschrijving"></textarea></td>
    </tr>
    <tr>
      <td>Categorie: </td>
      <td><select name="catid">{~OPTION~}</select>
    </tr>
    <tr>
      <td colspan=2 align=center>
        <input type="submit" value="Toevoegen">
      </td>
    </tr>
  </table>
  </td>
  <td background="../../img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="../../img/nieuwvaklinksonder.gif"></td>
  <td background="../../img/nieuwvakonder.gif" width=*><img src="../../img/nieuwvakonder.gif"></td>
  <td width=12><img src="../../img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table><P>
</form>
    </td>
  </tr>
</table>

[BLOCK categorie]
<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="../../img/nieuwvaklinksboven.gif"></td>
  <td background="../../img/nieuwvakboven.gif" width=* height=10><img src="../../img/nieuwvakboven.gif"></td>
  <td width=12><img src="../../img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="../../img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="../../img/nieuwvakmidden.gif" align=center> 
			<table cellspacing=1 cellpadding=1 border=0 width=530>
			  <tr>
			    <td><B><font size=4>{~NAAM~}</font></B></td>
			    <td align=right>
			    	<a href="catVerwijder.php?catid={~CATID~}"><img src="../../img/verwijderen.gif" border=0></a>
						<a href="catBewerk.php?catid={~CATID~}"><img src="../../img/veranderen.gif" border=0></a>
					</td>
			  <tr>
			    <th align="left" colspan=2><I>Naam: </I></th>
			  </tr>
{~FORUM~}
			</table>
  </td>
  <td background="../../img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="../../img/nieuwvaklinksonder.gif"></td>
  <td background="../../img/nieuwvakonder.gif" width=*><img src="../../img/nieuwvakonder.gif"></td>
  <td width=12><img src="../../img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table><P>
{~CAT~}
[END BLOCK categorie]



[BLOCK forum]
              <tr>
						    <td background="../../img/patroon.gif" width=*><a href="../viewForum.php?forumid={~FORUMID~}"><B><U>{~TITEL~}</U></B></a><br>{~BESCHRIJVING~}</td>
						    <td background="../../img/patroon.gif" width=40 align=center>
						    	<a href="forumVerwijder.php?forumid={~FORUMID~}"><img src="../../img/verwijderen.gif" border=0></a>
									<a href="forumBewerk.php?forumid={~FORUMID~}"><img src="../../img/veranderen.gif" border=0></a>
							  </td>
						  </tr>
{~FORUM~}
[END BLOCK forum]

[BLOCK option]
<option value={~OPTIONCATID~}>{~CATTITEL~}
{~OPTION~}
[END BLOCK option]

[BLOCK empty]
{~EMPTY~}
[END BLOCK empty]