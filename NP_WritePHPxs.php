<?php

class NP_WritePHPxs extends NucleusPlugin {

    function getName() { return 'WritePHP xs'; }
    function getAuthor()  { return 'Taka'; }
    function getURL() { return 'http://vivian.stripper.jp/'; }
    function getVersion() { return '0.2'; }
    function getDescription() {
        return 'This plugin makes it possible to write the easy PHP code in the skin.It is light version of NP_WritePHP';
    }
    function supportsFeature($what) {
        switch ($what) {
            case 'SqlTablePrefix':
                return 1;
            default:
                return 0;
        }
    }

    function doSkinVar() {

        $params = func_get_args();
        $skinType = $params[0];
        array_shift($params); //remove 'skinType'
        $code = implode(',',$params);
        
        if ($skinType == 'template') { // when called from a template
            if ($itemid) {
                $skinType = 'item';
            } elseif ($archive) {
                $skinType = 'archive';
            } elseif ($archivelist) {
                $skinType = 'archivelist';
            } elseif ($query) {
                $skinType = 'search';
            } elseif ($memberid) {
                $skinType  = 'member';
            } elseif ($imagepopup) {
                $skinType  = 'imagepopup';
            } else {
                $skinType  = 'index';
            }
        }
        
        ob_start();
        $this->parse_code($code,$skinType);
        $content = ob_get_contents();
        ob_end_clean();
        $this->_doParse($skinType,$content);
    }
    
    
  function _doParse($skinType,$content) {
        $actions = SKIN::getAllowedActionsForType($skinType);
        $handler = new ACTIONS($skinType);
        $parser = new PARSER($actions, $handler);
        $handler->setParser($parser);
        
        $content = preg_replace(array('/<:/','/:>/'), array('<%','%>'), $content);
        $parser->parse($content);
  }
  
  function parse_code($code,$skinType) {
      eval($code);
  }
      

}

?> 