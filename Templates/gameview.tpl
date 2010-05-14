<script>
function smileypopup() {open("Templates/smileysinvoegen.html","popup","toolbar=no,directories=no,scrollbars=yes,width=200,height=400")}
</script>

{~CONTENT~}

[BLOCK topicview]
<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="img/nieuwvaklinksboven.gif"></td>
  <td background="img/nieuwvakboven.gif" width=* height=10><img src="img/nieuwvakboven.gif"></td>
  <td width=12><img src="img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="img/nieuwvakmidden.gif" align=center>
  
<table cellspacing=0 cellpadding=0 border=0 width=530>
  <tr>
    <td><font size=5 face=arial><B>{~TITEL~}</B></font></td>
  </tr>
  <tr>
    <td><font size=1 face=arial>Geschreven door <i>{~AUTEUR~}</i> op <i>{~DATUM~}</i></font></td>
  </tr>
  <tr>
    <td><font size=2 face=arial>
      {~TEXT~}
      <hr color=darkred>
      <I>Reacties:</I></font><P align=center>
      {~COMMENTAAR~}
      
      <form action="addpost.php?topicid={~TOPICID~}&id={~ID~}" method="post" name="formulier"><center>
        <b>Geef een reactie:</b><br>
          <table>
            <tr>
              <td align=left>
                <a href="" target=blanc onclick="smileypopup()"><I>Smileys Invoegen</I></A>
              </td>
            </tr>
            <tr>
              <td><textarea name="reactie" rows=5 cols=50></textarea></td>
            </tr>
          </table>
        <input type="submit" value="Reageren">
        <input type="reset" value="Reset"></center>
      </form>
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
</table>
[END BLOCK topicview]

[BLOCK reactie]
<table cellspacing=0 cellpadding=0 border=0 width=480>
  <tr>
    <td><font size=1 face=arial>Geplaatst door <i>{~USERNAME~}</i> op <i>{~DATUMREACTIE~}</i></font></td>
    <td align=right>{~REACTIEEDIT~}</td>
  </tr>
  <tr>
    <td background="img/patroon.gif" id="goedoveral" colspan=2><font size=2 face=arial>{~BERICHT~}</font></td>
  </tr>
</table><P align=center>
{~COMMENTAAR~}
[END BLOCK reactie]

[BLOCK edit]
<table cellspacing=0 cellpadding=0 border=0>
<tr>
  <td>
    <a href="reactieEdit.php?id={~BERICHTID~}&spelid={~SPELID~}"><img src="img/veranderen.gif" border=0></a>
    <a href="reactieDelete.php?id={~BERICHTID~}&spelid={~SPELID~}"><img src="img/verwijderen.gif" border=0></a>
  </td>
</tr>
</table>
[END BLOCK edit]