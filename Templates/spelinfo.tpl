<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="img/nieuwvaklinksboven.gif"></td>
  <td background="img/nieuwvakboven.gif" width=* height=10><img src="img/nieuwvakboven.gif"></td>
  <td width=12><img src="img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="img/nieuwvakmidden.gif" align=center>

<h2><font face=arial>{~NAAMSPEL~}</font></h2>
  
<table cellspacing=0 cellpadding=0 border=0 width=530>
  <tr>
    <td id="goedoveral" align=center width=180 bgcolor=#FF0000 height=120>  
      <table cellspacing=0 cellpadding=0 border=0 width=127 height=97>
        <tr>
          <td width=120 height=90>
             <img src="spellen/{~DISPLAY~}/display.jpg" border=0 width=120 height=90></td>
          <td width=7 height=90><img src="img/schaduwrechts.gif" border=0></td>
        </tr>
        <tr>
          <td colspan=2 width=127 height=7><img src="img/schaduwonder.gif" border=0></td>
        </tr>
      </table>
    </td>
    <td id="goednietlinks" bgcolor=#FF0000>
      <table cellspacing=0 cellpadding=0 border=0>
        <tr>
          <td width=110><b><font size=2 face=arial>Console: </font></b></td>
          <td><font size=2 face=arial>{~CONSOLE~}</font></td>
        </tr>
        <tr>
          <td><b><font size=2 face=arial>Ontwikkelaar: </font></b></td>
          <td><a href="{~DEVELOPERURL~}" target="_blanc"><font size=2 face=arial>{~DEVELOPER~}</font></a></td>
        </tr>
        <tr>
          <td><b><font size=2 face=arial>Uitgever: </font></b></td>
          <td><a href="{~PUBLISHERURL~}" target="_blanc"><font size=2 face=arial>{~PUBLISHER~}</font></a></td>
        </tr>
        <tr>
          <td><b><font size=2 face=arial>Waardering: </b></td>
          <td>
            <table cellspacing=0 cellpadding=0 border=0>
              <tr>
                <td width=23 align=center><img src="img/{~STER1~}"></td>
                <td width=23 align=center><img src="img/{~STER2~}"></td>
                <td width=23 align=center><img src="img/{~STER3~}"></td>
                <td width=23 align=center><img src="img/{~STER4~}"></td>
                <td width=23 align=center><img src="img/{~STER5~}"></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
            {~STEMFORMULIER~}
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan=2 align=center>
      <table border=0 width=400 id="tabeloveral">
       {~TOPIC~}
      </table>    
    </td>
  </tr>
</table>

  </td>
  <td background="img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="img/nieuwvaklinksonder.gif"></td>
  <td background="img/nieuwvakonder.gif" width=*><img src="img/nieuwvakonder.gif"></td>
  <td width=12><img src="img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table><P>
{~CONTENT~}

[BLOCK topic]
<tr>
  <td background="{~KLEUR~}"><a href="gameview.php?id={~ID~}&topicid={~TOPICID~}"><font size=3 face=arial><I><B>>> {~TITEL~}</B></I></FONT></a></td>
</tr>
{~TOPIC~}
[END BLOCK topic]

[BLOCK stemformulier]
<form action="stemmen.php?spelid={~SPELID~}" method="post">
    <td width=110>&nbsp;</td>
    <td>
      <table cellspacing=0 cellpadding=0 border=0>
        <tr>
          <td width=23 align=center><input type="radio" name="stem" value=1 onClick="this.form.submit()" id="radio"></td>
          <td width=23 align=center><input type="radio" name="stem" value=2 onClick="this.form.submit()" id="radio"></td>
          <td width=23 align=center><input type="radio" name="stem" value=3 onClick="this.form.submit()" id="radio"></td>
          <td width=23 align=center><input type="radio" name="stem" value=4 onClick="this.form.submit()" id="radio"></td>
          <td width=23 align=center><input type="radio" name="stem" value=5 onClick="this.form.submit()" id="radio"></td>
        </tr>
      </table>
    </td>
</form>
[END BLOCK stemformulier]