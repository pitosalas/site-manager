<?php /* Smarty version 2.6.13, created on 2008-01-07 09:52:45
         compiled from apache_vhosts.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'apache_vhosts.tpl', 1, false),)), $this); ?>
<?php echo smarty_function_config_load(array('file' => "apache.conf"), $this);?>

<?php $_from = $this->_tpl_vars['classes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
 $this->assign('cc', $this->_tpl_vars['c']['class']);  if (count ( $this->_tpl_vars['c']['instances'] ) > 0):  $_from = $this->_tpl_vars['c']['instances']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['i'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['i']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['i']):
        $this->_foreach['i']['iteration']++;
?>
<VirtualHost *:8888>
  ServerName   <?php echo $this->_tpl_vars['i']['name']; ?>
.blogbridge.com
  DocumentRoot <?php echo $this->_config[0]['vars']['classes_dir']; ?>
/<?php echo $this->_tpl_vars['cc']['name']; ?>

  ErrorLog     <?php echo $this->_config[0]['vars']['logs_dir']; ?>
/error_log
  CustomLog    <?php echo $this->_config[0]['vars']['logs_dir']; ?>
/access_log common
</VirtualHost>
<?php endforeach; endif; unset($_from);  endif; ?>

<?php endforeach; endif; unset($_from); ?>