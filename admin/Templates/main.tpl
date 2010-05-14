<html>
 <head>
  <title>Spelcodes - {~TITEL~}</title>
 </head>

  <style type="text/css">
    td {
      font-size: 12;
      font-family: arial;
    }

    th {
      font-size: 13;
      font-family: arial;
    }
    
    input {
      font-size: 13;
      font-family: arial;
      background-color: darkred;
      border: 1px solid black;
    }
    
    textarea {
      font-size: 13;
      font-family: arial;
      background-color: darkred;
      border: 1px solid black;
      scrollbar-face-color: #990000;
      scrollbar-highlight-color: black; 
      scrollbar-shadow-color: red; 
      scrollbar-3dlight-color: red; 
      scrollbar-arrow-color: red; 
      scrollbar-darkshadow-color: #990000; 
    }
    
    input#radio {
      background-color: #FF0000;
      border: 0px;
    }
    
   
    INPUT.login {
      color: #999999;
    	width: 90px;
    	height: 14px;
    	background-color: #333333;
    	border: 1px solid #990000;
    	font: 10px;
    }
    
    .submit {
    	font-family: times;
    	height: 16px;
	    font-size: 10px;
    	background-color: #858A8B;
    	border: 1px solid #990000;
	    font: 10px;
    }

    .achtergrond {
      background-repeat: repeat-x;
    }
    div.quote
      {
       border: thin solid darkred;
       font-style: italic;
    }
A:link		{font: arial; text-decoration: none; color: #000000;}
A:visited	{font: arial; text-decoration: none; color: #000000;}
A:active	{font: arial; text-decoration: none; color: #000000;}
A:hover		{font: arial; color: #000000; text-decoration: underline;}
    
    td#randboven {
      border: 1px solid #333333;
      border-left-width: 0px;
      border-right-width: 0px;
      border-bottom-width: 0px;
    }

    td#randlinks {
      border: 1px solid #333333;
      border-top-width: 0px;
      border-right-width: 0px;
      border-bottom-width: 0px;
    }

    td#randrechts {
      border: 1px solid #333333;
      border-top-width: 0px;
      border-left-width: 0px;
      border-bottom-width: 0px;
    }

    td#randonder {
      border: 1px solid #333333;
      border-top-width: 0px;
      border-right-width: 0px;
      border-left-width: 0px;
    }

    td#randlinksrechts {
      border: 1px solid #333333;
      border-top-width: 0px;
      border-bottom-width: 0px;
    }

    td#goedoveral {
      border: 1px solid #990000;
    }
    
    table#tabeloveral {
      border: 1px solid #990000;
    }

    td#goednietrechts {
      border: 1px solid #990000;
      border-right-width: 0px;
    }

    td#goednietlinks {
      border: 1px solid #990000;
      border-left-width: 0px;
    }

    td#goedlinksrechts {
      border: 1px solid #990000;
      border-top-width: 0px;
      border-bottom-width: 0px;
    }
  </style>

<body background="../img/achtergrond.gif" bgcolor=black topmargin="2" leftmargin="0" onLoad="defaultStatus=('Welkom bij Spelcodes!'); return true">

 <center>
  <table width=772 cellspacing=0 cellpadding=0 border=0>

   <tr>
    <td width=15 height=17><img src="../img/randlinksboven.gif"></td>
    <td width=742 id="randboven" align=right><a href="../logout.php"><font size=2 color=#999999>Uitloggen</font></a></td>
    <td width=15><img src="../img/randrechtsboven.gif"></td>
   </tr>

   <tr>
    <td colspan=3 id="randlinksrechts"><center><img src="../img/koplinks"><img src="../img/kopmidden.gif"><img src="../img/koprechts"></center></td>
   </tr>

  </table>

  <table width=770 cellspacing=0 cellpadding=0 border=0>

    <tr>
     <td width=182 height=8 id="randlinks"><img src="../img/menubovenhelft1.gif"></td>
     <td width=588 colspan=3 height=8 id="randrechts"><img src="../img/middenboven.gif" height=8></td>
    </tr>

    <tr>
     <td width=182 background="../img/menutussen.gif" valign="top">


  <TABLE width=182 cellspacing=0 cellpadding=0 border=0>
   <tr>
    <td colspan=3 height=40 id="randlinks"><img src="../img/menubovenhelft2.gif"></td>
   </tr>

   <tr>
    <td height=296 width=12 id="randlinks"><img src="../img/menulinks.gif"></td>
    <td width=158 background="../img/menumidden.gif" align=right valign=top>
      <a href="../index.php"><img src="../img/linkhome.gif" border=0></a><p>
      
      <a href="index.php"><img src="../img/admin.gif" border=0></a><p>
      
      {~USERLINK~}
      {~CONSOLELINK~}
      {~SPELLINK~}
      {~NIEUWSLINK~}
      {~LINKLINK~}
      {~BACKUPLINK~}
      {~MAILLINK~}<p>
      
      <a href="../logout.php"><img src="../img/uitloggen.gif" border=0></a>
      
    <td width=12><img src="../img/menurechts.gif" border=0></td>
   </tr>

   <tr>
    <td colspan=3 height=33 id="randlinks"><img src="../img/menuonder.gif"></td>
   </tr>
  </TABLE>


     <td width=10 background="../img/middenlinks2.gif" valign="top"><img src="../img/middenlinks.gif"></td>
     <td width=568 height=* background="../img/middenmidden.gif" valign="top" bgcolor=#CC3300 class="achtergrond" align=center>

{~CONTENT~}

     </td>
     <td width=10 background="../img/middenrechts2.gif" valign="top" id="randrechts"><img src="../img/middenrechts.gif"></td>
    </tr>

    <tr>
     <td id="randlinks"><img src="../img/menuonderaan.gif" width=182 height=8></td>
     <td colspan=3 id="randrechts"><img src="../img/middenonder.gif"></td>
     </td>
    </tr>

  </table>

  <table width=772 cellspacing=0 cellpadding=0 border=0>

   <tr>
    <td width=15 height=15><img src="../img/randlinksonder.gif"></td>
    <td width=744 id="randonder" align=center><font size=2 color=black face=arial>(C) Copyright Spelcodes 2005-2006</font></td>
    <td width=14><img src="../img/randrechtsonder.gif"></td>
   </tr>

  </table>
</body></html>
<noscript><noscript><plaintext><plaintext>

[BLOCK userlink]
<a href="users.php"><img src="../img/linkadmin1.gif" border=0></a><br>
[END BLOCK userlink]

[BLOCK consolelink]
<a href="consoles.php"><img src="../img/linkadmin3.gif" border=0></a><br>
[END BLOCK consolelink]

[BLOCK spellink]
<a href="spellen.php"><img src="../img/linkadmin2.gif" border=0></a>
[END BLOCK spellink]

[BLOCK nieuwslink]
<a href="nieuws.php"><img src="../img/linkadmin5.gif" border=0></a>
[END BLOCK nieuwslink]

[BLOCK linklink]
<a href="links.php"><img src="../img/linkadmin6.gif" border=0></a>
[END BLOCK linklink]

[BLOCK backuplink]
<a href="backup.php"><img src="../img/linkadmin4.gif" border=0></a>
[END BLOCK backuplink]

[BLOCK maillink]
<a href="mail.php"><img src="../img/linkadmin8.gif" border=0></a>
[END BLOCK maillink]