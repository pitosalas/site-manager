<?php

class View extends Smarty
{
	function View()
	{
		$this->template_dir = SMARTY_TEMPLATES;
		$this->compile_dir = SMARTY_TEMPLATES_C;
		$this->config_dir = SMARTY_CONFIG;
		
		$this->compile_check = true;
		$this->debugging = false;

		$this->assign('suffix', CLASSES_SITES_SUFFIX);
	}
	
	/** Displays home page. */
	function home($classes, $error = false)
	{
		$this->assign('classes', $classes);
		if ($error) $this->assign('error', $error);
		$this->_display('home', 'Home');
	}
	
	/** Displays class form. */
	function class_form($type, $cls = null)
	{
		switch ($type)
		{
			case 'new':
				$title = 'New Class';
				$this->assign('cmd', 'new');
				break;
			case 'edit':
				$title = 'Edit Class';
				$this->assign('cmd', 'edit');
				$this->assign('id', $cls['id']);
				$this->assign('obj', $cls);
				break;
		}
		
		$this->_display('form_class', $title);
	}
	
	/** Displays instance form. */
	function instance_form($type, $ins = null)
	{
		$type_selected = 1;

		switch ($type)
		{
			case 'new':
				$title = 'New Instance';
				$this->assign('cid', $_GET['cid']);
				$this->assign('cmd', 'new');
				break;
				
			case 'edit':
				$title = 'Edit Instance';
				$this->assign('ins', $ins);
				$this->assign('cmd', 'edit');
				$this->assign('cid', $ins['class_id']);
				$type_selected = $ins['type'];
				break;
		}
		
		$this->assign('type_options', array(1 => 'Trial', 0 => 'Licensed'));
		$this->assign('type_selected', $type_selected);
		
		$this->_display('form_instance', $title);
	}
	
	/** Creates apache virtual hosts configuration file. */
	function apache_vhosts($classes)
	{
		$this->assign('classes', $classes);
		return $this->fetch('apache_vhosts.tpl');
	}
	
	/** Creates instance configuration file. */
	function instance_config($db_name)
	{
		$this->assign('db_name', $db_name);
		return $this->fetch('instance_config.tpl');
	}

	/** Shows the given license text. */
	function show_license($license_text)
	{
		$this->assign('license_text', $license_text);
		$this->_display('license', 'License Text');		
	}
	
	/** Displays given page. */
	function _display($page, $title = false)
	{
		$this->assign('page', $page);
		$this->assign('title', $title ? $title : 'Site Manager');
		$this->display('main.tpl');
	}
}
?>