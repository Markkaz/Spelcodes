<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
    <tr>
        <td width=12 height=10><img src="img/nieuwvaklinksboven.gif"></td>
        <td background="img/nieuwvakboven.gif" width=*><img src="img/nieuwvakboven.gif"></td>
        <td width=12><img src="img/nieuwvakrechtsboven.gif"></td>
    </tr>

    <tr>
        <td background="img/nieuwvaklinks.gif">&nbsp;</td>
        <td background="img/nieuwvakmidden.gif" align=center>

            <table cellspacing=0 cellpadding=0 border=0 width=510>
                <tr>
                    <td align=center width=170 height=8><I><font size=2 face=arial>{~CONSOLE1~}</font></I></td>
                    <td align=center width=170 id="goedlinksrechts"><I><font size=2 face=arial>{~CONSOLE2~}</font></I>
                    </td>
                    <td align=center width=170><I><font size=2 face=arial>{~CONSOLE3~}</font></I></td>
                </tr>

                <tr>
                    <td align=center height=120 bgcolor=#FF0000 id="goednietrechts">
                        <table cellspacing=0 cellpadding=0 border=0 width=127 height=97>
                            <tr>
                                <td width=120 height=90>
                                    <a href="gameview.php?id={~ID1~}"><img src="spellen/{~MAP1~}/display.jpg" border=0
                                                                           width=120 height=90></A></td>
                                <td width=7 height=90><img src="img/schaduwrechts.gif" border=0></td>
                            </tr>
                            <tr>
                                <td colspan=2 width=127 height=7><img src="img/schaduwonder.gif" border=0></td>
                            </tr>
                        </table>
                    </td>
                    <td align=center bgcolor=#FF0000 id="goedoveral">
                        <table cellspacing=0 cellpadding=0 border=0 width=127 height=97>
                            <tr>
                                <td width=120 height=90>
                                    <a href="gameview.php?id={~ID2~}"><img src="spellen/{~MAP2~}/display.jpg" border=0
                                                                           width=120 height=90></A></td>
                                <td width=7 height=90><img src="img/schaduwrechts.gif" border=0></td>
                            </tr>
                            <tr>
                                <td colspan=2 width=127 height=7><img src="img/schaduwonder.gif" border=0></td>
                            </tr>
                        </table>
                    </td>
                    <td align=center bgcolor=#FF0000 id="goednietlinks">
                        <table cellspacing=0 cellpadding=0 border=0 width=127 height=97>
                            <tr>
                                <td width=120 height=90>
                                    <a href="gameview.php?id={~ID3~}"><img src="spellen/{~MAP3~}/display.jpg" border=0
                                                                           width=120 height=90></A></td>
                                <td width=7 height=90><img src="img/schaduwrechts.gif" border=0></td>
                            </tr>
                            <tr>
                                <td colspan=2 width=127 height=7><img src="img/schaduwonder.gif" border=0></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td align=center width=170><a href="gameview.php?id={~ID1~}"><b><font size=2
                                                                                          face=arial>{~NAAM1~}</font></b></a>
                    </td>
                    <td align=center width=170 id="goedlinksrechts"><a href="gameview.php?id={~ID2~}"><b><font size=2
                                                                                                               face=arial>{~NAAM2~}</font></b></a>
                    </td>
                    <td align=center width=170><a href="gameview.php?id={~ID3~}"><b><font size=2
                                                                                          face=arial>{~NAAM3~}</font></b></a>
                    </td>
                </tr>
            </table>


        </td>
        <td background="img/nieuwvakrechts.gif">&nbsp;</td>
    </tr>

    <tr>
        <td width=12 height=10><img src="img/nieuwvaklinksonder.gif"></td>
        <td background="img/nieuwvakonder.gif" width=* height=5><img src="img/nieuwvakonder.gif"></td>
        <td width=12><img src="img/nieuwvakrechtsonder.gif"></td>
    </tr>
</table><P>

<TABLE cellspacing=0 cellpadding=0 border=0 width=550>
    <tr>
        <td width=12 height=10><img src="img/nieuwvaklinksboven.gif"></td>
        <td background="img/nieuwvakboven.gif" width=* height=10><img src="img/nieuwvakboven.gif"></td>
        <td width=12><img src="img/nieuwvakrechtsboven.gif"></td>
    </tr>

    <tr>
        <td background="img/nieuwvaklinks.gif">&nbsp;</td>
        <td background="img/nieuwvakmidden.gif" align=center>


            {~NIEUWS~}<p>

                <a href="archief.php"><font size=2 face=arial>Archief</font></a>


        </td>
        <td background="img/nieuwvakrechts.gif">&nbsp;</td>
    </tr>

    <tr>
        <td width=12 height=10><img src="img/nieuwvaklinksonder.gif"></td>
        <td background="img/nieuwvakonder.gif" width=* height=10><img src="img/nieuwvakonder.gif"></td>
        <td width=12><img src="img/nieuwvakrechtsonder.gif"></td>
    </tr>
</table><p>

[BLOCK nieuws]
    <table cellspacing=0 cellpadding=0 border=0 width=526>
        <tr>
            <td><font size=4 face=arial><B>{~TITEL~}</B></font>
        </tr>
        <tr>
            <td><font size=2 face=arial>Gepost door <i>{~USERNAME~}</i> op <i>{~DATUM~}</i></font></td>
        </tr>
        <tr>
            <td><font size=2 face=arial>{~BERICHT~}</font></td>
        </tr>
        <tr>
            <td>
                <a href="shownieuws.php?id={~NIEUWSID~}">
                    <font size=2 face=arial>
                        <I>Zie reacties ({~REACTIES~})</I>
                    </font>
                </a>
            </td>
        </tr>
    </table><p>

    {~NIEUWS~}
[END BLOCK nieuws]