<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
<HEAD>
  <TITLE>{$title}</TITLE>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <META http-equiv="Content-Language" content="en-us"/>
  <link rel="stylesheet" href="styles/global.css"/>
  <link rel="stylesheet" href="styles/{$page}.css"/>
</HEAD>

<BODY bgcolor="#ffffff">

<table id="page">
  <tr> {* Header *}
    <td id="header" align="center" valign="top">&nbsp;</td>
  </tr>
  
  <tr> {* Main Part*}
    <td id="main_part">
      {include file="$page.tpl"}
    </td>
  </tr>
  
  <tr> {* Footer *}
    <td id="footer" valign="top">&nbsp;</td>
  </tr>
</table>
</BODY>
</HTML>
