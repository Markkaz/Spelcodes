<?php
/****************************************
/ Class: User class
/ Type: php4
/***************************************/
class User
{
  var $m_iUserid;
  var $m_iPermis;
  
  var $m_sIP;
  var $m_iExpiration;
  
  function User()
  {
    $this -> m_sIP = $_SERVER['REMOTE_ADDR'];
    $this -> m_iExpiration = time() + 3600 * 24 * 7;
  }
  
  /*********************************************************
  / Function: De username van een user ophalen
  / Type: php4
  /********************************************************/
  function getUsername($iUserid)
  {
    $sQuery = "SELECT username FROM users WHERE userid='" . $iUserid . "';";
    if($cResult = mysql_query($sQuery))
    {
      if(mysql_num_rows($cResult) > 0)
      {
        $aData = mysql_fetch_assoc($cResult);
        return $aData['username'];
      }
    }
    return '';
  }
  
  /*********************************************************
  / Function: Het plaatsen van een session met login data
  / Type: php4
  /********************************************************/
  function setSession()
  {
    $aUserdata = array();
    $aUserdata['userid'] = $this -> m_iUserid;
    $aUserdata['permis'] = $this -> m_iPermis;
    $aUserdata['ip'] = $this -> m_sIP;
    
    $_SESSION['USERDATA'] = serialize($aUserdata);
  }
  
  /************************************************************
  / Function: Het plaatsen van een cookie met login data
  / Type: php4
  /***********************************************************/
  function setCookie()
  {
    $sQuery = "UPDATE users 
               SET ip = '" . $this -> m_sIP . "'
               WHERE userid = '" . $this -> m_iUserid . "';";
    if(!$cResult = mysql_query($sQuery))
    {
      return false;
    }
    
    $aUserdata = array();
    $aUserdata['userid'] = $this -> m_iUserid;
    $aUserdata['ip'] = $this -> m_sIP;
    
    setcookie('USERDATA', serialize($aUserdata), $this -> m_iExpiration);
  }
  
  /*******************************************
  / Function: Inloggen
  / Type: php4
  /******************************************/
  function login($sUsername, $sPassword, $bCookie = false)
  {
    $sQuery = "SELECT userid, permis 
               FROM users 
               WHERE username = '" . $sUsername . "' 
               AND password = PASSWORD('" . $sPassword . "')
               AND activate=1;";
    if(!$cResult = mysql_query($sQuery))
    {
      return false;
    }
    if(mysql_num_rows($cResult) <= 0)
    {
      return false;
    }
    $aUserdata = mysql_fetch_assoc($cResult);
    $this -> m_iUserid = $aUserdata['userid'];
    $this -> m_iPermis = $aUserdata['permis'];
    
    if($bCookie)
    {
      $this -> setCookie();
    }
    $this -> setSession();
    return true;
  }
  
  /**********************************************
  / Function: Controleren of er een session is
  / Type: php4
  /*********************************************/
  function checkSession()
  {
    if(!isset($_SESSION['USERDATA']))
    {
      return false;
    }
    $aUserdata = unserialize($_SESSION['USERDATA']);
    
    if(!$aUserdata['ip'] == $this -> m_sIP)
    {
      $_SESSION['USERDATA'] = '';
      return false;
    }
    
    $this -> m_iUserid = $aUserdata['userid'];
    $this -> m_iPermis = $aUserdata['permis'];
    return true;
  }
  
  /******************************************************
  / Function: Controleren of er een cookie is
  / Type: php4
  /*****************************************************/
  function checkCookie()
  {
    if(!isset($_COOKIE['USERDATA']))
    {
      return false;
    }
    $aUserdata = unserialize(strip($_COOKIE['USERDATA']));
    if($aUserdata['ip'] != $this -> m_sIP)
    {
      setcookie('USERDATA', '', 0);
    }
    $sQuery = "SELECT ip, permis FROM users WHERE userid = '" . $aUserdata['userid'] . "';";
    if(!$cResult = mysql_query($sQuery))
    {
      setcookie('USERDATA', '', 0);
      return false;
    }
    $aData = mysql_fetch_assoc($cResult);
    if($aData['ip'] != $aUserdata['ip'])
    {
      setcookie('USERDATA', '', 0);
      return false;
    }
    $this -> m_iUserid = $aUserdata['userid'];
    $this -> m_iPermis = $aData['permis'];
    
    $this -> setSession();
    return true;
  }
  
  /***********************************************
  / Function: Post bij user optellen
  / Type: php4
  /**********************************************/
  function addPost()
  {
    $sQuery = "UPDATE users SET posts=posts+1 WHERE userid='" . $this -> m_iUserid . "';";
    if(mysql_query($sQuery))
    {
      return true;
    }
    return false;
  }
  
  /***********************************************
  / Function: Uitloggen
  / Type: php4
  /**********************************************/
  function logout()
  {
    $_SESSION['USERDATA'] = '';
    setcookie('USERDATA', '', 0);
  }
}
?>