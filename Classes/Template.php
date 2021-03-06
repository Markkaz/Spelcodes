<?php
/***********************************************************
/ Class: Template class
/ Type: php4
/**********************************************************/
class Template
{
  var $m_sTemplate = '';

  var $m_aNamespaces;
  var $m_aBlocks;

  /************************************
  / Function: Constructor
  / Type: php4
  /***********************************/
  function Template($sMainFile)
  {
    if(!file_exists($sMainFile))
    {
      die('Het bestand ' . $sMainFile . ' bestaat niet');
    }
    $this -> m_sTemplate = @file_get_contents($sMainFile);

    $this -> m_aNamespaces = array();
    $this -> m_aBlocks = array();
  }

  /*******************************************************
  / Function: Namespaces vervangen door een value
  / Type: php4
  /******************************************************/
  function setPlace($sName, $sValue)
  {
    $this -> m_aNamespaces[$sName] = $sValue;
  }

  /*******************************************************
  / Function: Namespaces vervangen door value uit file
  / Type: php4
  /******************************************************/
  function setFile($sName, $sFile)
  {
    if(!file_exists($sFile))
    {
      die('Het bestand ' . $sFile . ' bestaat niet');
    }
    $this -> m_aNamespaces[$sName] = @file_get_contents($sFile);
  }

  /*******************************************************
  / Function: Namespaces vervangen door een block
  / Type: php4
  /******************************************************/
  function setBlock($sName, $sBlockName)
  {
    if(array_key_exists($sBlockName, $this -> m_aBlocks))
    {
      $this -> m_aNamespaces[$sName] = $this -> m_aBlocks[$sBlockName];
    }
    else
    {
      $iPosBegin = strpos($this -> m_sTemplate, '[BLOCK ' . $sBlockName . ']');
      $iPosEnd = strpos($this -> m_sTemplate, '[END BLOCK ' . $sBlockName . ']');
      $iNumChars = $iPosEnd - $iPosBegin;

      $sBlock = substr($this -> m_sTemplate, $iPosBegin, $iNumChars);
      $sBlock = str_replace('[BLOCK ' . $sBlockName . ']', '', $sBlock);

      $this -> m_aBlocks[$sBlockName] = $sBlock;
      $this -> m_aNamespaces[$sName] = $sBlock;
    }
  }

  /*********************************************
  / Function: Parsed de template
  / Type: php4
  /********************************************/
  function parse()
  {
    foreach($this -> m_aNamespaces as $sName => $sValue)
    {
      $this -> m_sTemplate = str_replace('{~' . $sName . '~}', $sValue, $this -> m_sTemplate);
    }

    $this -> m_aNamespaces = array();
  }

  /*************************************
  / Function: Weergeeft de template
  / Type: php4
  /************************************/
  function show()
  {
    $this -> parse();

		$this -> m_sTemplate = preg_replace("'\[BLOCK (.*?)\](.*?)\[END BLOCK (.*?)]'s", "", $this -> m_sTemplate);
		$this -> m_sTemplate = preg_replace("'{~(.*?)~}'", "", $this -> m_sTemplate);

		print $this -> m_sTemplate;
  }
}