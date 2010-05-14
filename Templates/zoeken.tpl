
<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
 <tr>
  <td width=12 height=10><img src="img/nieuwvaklinksboven.gif"></td>
  <td background="img/nieuwvakboven.gif" width=* height=10><img src="img/nieuwvakboven.gif"></td>
  <td width=12><img src="img/nieuwvakrechtsboven.gif"></td>  
 </tr>

 <tr>
  <td background="img/nieuwvaklinks.gif">&nbsp;</td>
  <td background="img/nieuwvakmidden.gif" align=center>
 <h2>Je hebt gezocht op "{~ZOEKEN~}"</h2>
{~RESULTATEN~}

  </td>
  <td background="img/nieuwvakrechts.gif">&nbsp;</td>
 </tr>

 <tr>
  <td width=12 height=10><img src="img/nieuwvaklinksonder.gif"></td>
  <td background="img/nieuwvakonder.gif" width=*><img src="img/nieuwvakonder.gif"></td>
  <td width=12><img src="img/nieuwvakrechtsonder.gif"></td>  
 </tr>
</table>

[BLOCK resultaten]
<table cellspacing=0 cellpadding=0 border=0 width=530>
  <tr>
    <th align="left">Naam: </th>
    <th align="left">Console: </th>
    <th></th>
  </tr>
    {~SPEL~}
  <tr>
    <th align="left">Naam: </th>
    <th align="left">Console: </th>
    <th></th>
  </tr>
</table>
[END BLOCK resultaten]

[BLOCK spel]
<tr>
  <td background="{~BG~}"><a href="gameview.php?id={~SPELID~}">{~NAAM~}</a></td>
  <td background="{~BG~}">{~CONSOLE~}</a></td>
  <td background="{~BG~}">{~STER1~} {~STER2~} {~STER3~} {~STER4~} {~STER5~}</td>
</tr>
{~SPEL~}
[END BLOCK spel]

[BLOCK niks]
<!-- SiteSearch Google -->
<form method="get" action="http://www.google.nl/custom" target="google_window">
<table border="0">
<tr><td nowrap="nowrap" valign="top" align="left" height="32">

</td>
<td nowrap="nowrap">
<input type="hidden" name="domains" value="www.spelcodes.nl"></input>
<input type="text" name="q" size="31" maxlength="255" value=""></input>
<input type="submit" name="sa" value="Google Zoeken"></input>
</td></tr>
<tr>
<td>&nbsp;</td>
<td nowrap="nowrap">
<table>
<tr>
<td>
<input type="radio" name="sitesearch" value=""></input>
<font size="-1">Web</font>
</td>
<td>
<input type="radio" name="sitesearch" value="www.spelcodes.nl" checked="checked"></input>
<font size="-1">www.spelcodes.nl</font>
</td>
</tr>
</table>
<input type="hidden" name="client" value="pub-0341694722117339"></input>
<input type="hidden" name="forid" value="1"></input>
<input type="hidden" name="ie" value="ISO-8859-1"></input>
<input type="hidden" name="oe" value="ISO-8859-1"></input>
<input type="hidden" name="cof" value="GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1;"></input>
<input type="hidden" name="hl" value="nl"></input>
</td></tr></table>
</form>
<!-- SiteSearch Google -->
Helaas, je zoekwoord kwam niet in de naam van één van de spellen in onze database voor.<br />
Het is mogelijk dat het spel wel in de database voor komt, maar anders is gespeld. Probeer het zoekwoord anders te spellen of nog eens via google binnen onze website te zoeken.


[END BLOCK niks]

[BLOCK helester]
<img src="img/helester.gif" border=0>
[END BLOCK helester]

[BLOCK halvester]
<img src="img/halvester.gif" border=0>
[END BLOCK halvester]

[BLOCK legester]
<img src="img/legester.gif" border=0>
[END BLOCK legester]