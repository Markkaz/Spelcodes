<html>
  <head>
    <title>Spelcodes - Cheats in tabel zetten</title>
  </head>
  <body background="../img/achtergrond.gif">
  <TABLE cellspacing=0 cellpadding=0 border=0 width=330>
    <tr>
      <td width=12 height=10><img src="../img/nieuwvaklinksboven.gif"></td>
      <td background="../img/nieuwvakboven.gif" width=* height=10><img src="../img/nieuwvakboven.gif"></td>
      <td width=12><img src="../img/nieuwvakrechtsboven.gif"></td>  
    </tr>

    <tr>
     <td background="../img/nieuwvaklinks.gif">&nbsp;</td>
     <td background="../img/nieuwvakmidden.gif" align=center>
       <form action="cheats.php" method="get">
          <input type="text" name="aantal">
         <input type="submit" value="OK">
        </form><p>
    
    {~FORM~}
    </td>
    <td background="../img/nieuwvakrechts.gif">&nbsp;</td>
  </tr>

     <tr>
      <td width=12 height=10><img src="../img/nieuwvaklinksonder.gif"></td>
      <td background="../img/nieuwvakonder.gif" width=*><img src="../img/nieuwvakonder.gif"></td>
      <td width=12><img src="../img/nieuwvakrechtsonder.gif"></td>  
    </tr>
   </table>
  </body>
</html>

[BLOCK form]
<form action="cheats.php?aantal={~AANTAL~}" method="post">
  {~REGEL~}
  <input type="submit" value="Parse">
</form>
[END BLOCK form]

[BLOCK regel]
<input type="text" name="cheat{~TELLER~}" size=15>
<input type="text" name="uitwerking{~TELLER~}" size=15><br>
{~REGEL~}
[END BLOCK regel]

[BLOCK uitwerking]
<textarea rows=10 cols=30>{~UITWERKING~}</textarea>
[END BLOCK uitwerking]