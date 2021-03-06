<?php
class Navigatie
{
  var $m_iMax_posts;
  var $m_iPages;
  
  var $m_iTopic_id;
  
  /***************************************************
  / Function: Contructor
  / Type: php4
  /**************************************************/
  function Navigatie($iTopic_id, $iMax_posts = 20)
  {
    $this -> m_iMax_posts = $iMax_posts;
    $this -> m_iTopic_id = $iTopic_id;
    
    $sQuery = "SELECT count(post_id) AS posts FROM forum_posts WHERE topic_id='" . add($iTopic_id) . "';";
    if($cResult = mysql_query($sQuery))
    {
      $this -> m_iPages = mysql_result($cResult, 0) / $this -> m_iMax_posts;
    }
    else
    {
      $this -> m_iPages = 1;
    }
  }
  
  /**************************************************
  / Function: Een navigatie menu maken
  / Type: php4
  /*************************************************/
  function makeNavigation($iThisPage = -1)
  {
    $sOutput = '';
    for($i = 0; $i < $this -> m_iPages; $i++)
    {
      if($i == $iThisPage)
      {
        $sOutput .= '<b>' . ($i + 1) . '</b>';
      }
      else
      {
        $sOutput .= '<a href="viewTopic.php?topicid=' . $this -> m_iTopic_id . '&p=' . $i . '">' . ($i + 1) . '</a>';
      }
      
      /* - jes tussen de cijfers zetten */
      if($i < $this -> m_iPages - 1)
      {
        $sOutput .= ' - ';
      }
    }
    return $sOutput;
  }
}
?>