<?php /* Smarty version 2.6.13, created on 2007-01-09 04:46:44
         compiled from main.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
<HEAD>
  <TITLE><?php echo $this->_tpl_vars['title']; ?>
</TITLE>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <META http-equiv="Content-Language" content="en-us"/>
  <link rel="stylesheet" href="styles/global.css"/>
  <link rel="stylesheet" href="styles/<?php echo $this->_tpl_vars['page']; ?>
.css"/>
</HEAD>

<BODY bgcolor="#ffffff">

<table id="page">
  <tr>     <td id="header" align="center" valign="top">&nbsp;</td>
  </tr>
  
  <tr>     <td id="main_part">
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['page']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </td>
  </tr>
  
  <tr>     <td id="footer" valign="top">&nbsp;</td>
  </tr>
</table>
</BODY>
</HTML>