<?php
/********************************************************
/ Class: Cache class
/ Type: php4
/*******************************************************/
class Cache_File
{
  var $m_sFilename;
  var $m_sTempFilename;
  var $m_iExpiration;
  var $m_cFP;
  
  /***********************************************
  / Function: Constructor
  / Type: php4
  /**********************************************/
  function Cache_File($sFilename, $iExpiration = false)
  {
    $this -> m_sFilename = $sFilename;
    $this -> m_sTempFilename = $sFilename . '.' . getmypid();
    $this -> m_iExpiration = $iExpiration;
  }
  
  /***************************************************
  / Function: Start cache
  / Type: php4
  /**************************************************/
  function begin()
  {
    if(($this -> m_cFP = fopen($this -> m_sTempFilename, "w")) == false)
    {
      return false;
    }
    ob_start();
  }
  
  /**********************************************
  / Function: Eindig cache
  / Type: php4
  /*********************************************/
  function end()
  {
    $sBufer = ob_get_contents();
    ob_end_flush();
    if(strlen($buffer))
    {
      fwrite($this -> m_cFP, $sBuffer);
      fclose($this -> m_cFP);
      rename($this -> m_sTempFilename, $this -> m_sFilename);
      return true;
    }
    else
    {
      fclose($this -> m_cFP);
      unlink($this -> m_sTempFilename);
      return false;
    }
  }
  
  /***************************************************
  / Function: Cache ophalen
  / Type: php4
  /**************************************************/
  function get()
  {
    if($this -> m_iExpiration)
    {
      $aStat = @stat($this -> m_sFilename);
      if($aStat[9])
      {
        if(time() > $modified + $this -> m_iExpiration)
        {
          unlink($this -> m_sFilename);
          return false;
        }
      }
    }
    return @file_get_contents($this -> m_sFilename);
  }
  
  /***********************************************
  / Function: Cache verwijderen
  / Type: php4
  /**********************************************/
  function remove()
  {
    @unlink($this -> m_sFilename);
  }
}
?>