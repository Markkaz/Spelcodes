<?php
/* Function BBCode */
function bbcode($sText)
{
  /* Vervang enters */
  $sText = str_replace("\n", '<br>', $sText);
  
  /* De [i] [b] [u] codes */
  $sText = preg_replace("#\[b\](.*?)\[/b\]#si", "<b>\\1</b>", $sText); 
  $sText = preg_replace("#\[i\](.*?)\[/i\]#si", "<i>\\1</i>", $sText); 
  $sText = preg_replace("#\[u\](.*?)\[/u\]#si", "<u>\\1</u>", $sText);
  
  /* De [quote] code */
  $sText = preg_replace("#\[quote\](.*?)\[/quote\]#si", "<div class=\"quote\">\\1</div>", $sText);
  
  /* [url] code */
  $sText = preg_replace("#(^|[ \n\r\t])(((ftp://)|(http://)|(https://))([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]+))#i", "\\1<a href='\\2' target='_blank'>\\2</a>", $sText);
  
  return $sText;
}
?>