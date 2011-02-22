<?php

require_once 'functions/function.copyr.php';
require_once 'functions/function.chmodr.php';
require_once 'functions/function.rmdirr.php';
require_once 'Registry.class.php';

class Controller
{
	var $view;
	
	function Controller($view)
	{
		$this->view = $view;
	}
	
	function process_request()
	{
		$action = isset($_GET['action']) ? $_GET['action'] : false;

		if (!$action || !method_exists($this, 'req_' . $action)) $action = 'home';
		call_user_func(array(&$this, 'req_' . $action));
	}
	
	// ------------------------------------------------------------------------
	// General Requests
	// ------------------------------------------------------------------------
		
	function req_home($error = null, $reg = false)
	{
		if (!$reg) $reg = new Registry;
		$classes = $reg->select_classes_and_instances();
		$this->view->home($classes, $error);
	}

	// ------------------------------------------------------------------------
	// Classes
	// ------------------------------------------------------------------------
		
	function req_class_form()
	{
		$cmd = $_POST['cmd'];
		
		switch ($cmd)
		{
			case 'new':
				$error = $this->src_create_class($_POST['name']);
				$reg = false;
				if (!$error)
				{
					$reg = new Registry;
					$reg->class_new($_POST);
				}
				$this->req_home($error, $reg);
				break;			

			case 'edit':
				$reg = new Registry;
				$old_cl = $reg->get_class($_POST['id']);
				
				$error = $reg->update_class($_POST);
				if (!$error)
				{
					$old_name = $old_cl['name'];
					$new_name = $_POST['name'];
					if ($old_name != $new_name)
					{
						// Move the class
						$src = CLASSES_DIR . "/$old_name";
						$dst = CLASSES_DIR . "/$new_name";
						shell_exec("mv $src $dst");
						
						$this->apache_update_vhosts($reg);
						$this->apache_restart();
					}
				}
				$this->req_home($error, $reg);
				break;
				
			default:
				$this->req_home();
		}
	}

	function req_class_new()
	{
		$this->view->class_form('new');
	}
	
	function req_class_edit()
	{
		$reg = new Registry;
		$cls = $reg->get_class($_GET['id']);
		$this->view->class_form('edit', $cls);
	}
	
	function req_class_delete()
	{
		$id = $_GET['id'];
		$reg = new Registry;
		$this->src_delete_class($reg->get_class_name($id));
		$reg->class_delete($id);
		$this->apache_update_vhosts($reg);
		$this->apache_restart();

		$this->req_home();
	}
	
	// ------------------------------------------------------------------------
	// Instances
	// ------------------------------------------------------------------------

	function req_instance_new()
	{
		$this->view->instance_form('new');
	}
	
	function req_instance_edit()
	{
		$reg = new Registry;
		$i = $reg->get_instance($_GET['id']);
		$this->view->instance_form('edit', $i);
	}
	
	function req_instance_delete()
	{
		$id = $_GET['id'];
		$reg = new Registry;

		$error = false;
		$info = $reg->get_instance_deleteinfo($id);
		if ($info)
		{
			$this->src_delete_instance($info['class_name'], $info['name']);
			$reg->instance_delete($id);
			$this->apache_update_vhosts($reg);
			$this->apache_restart();
		} else $error = "Instance delete-info is invalid.";
		
		$this->req_home($error);
	}

	function req_instance_form()
	{
		$cmd = $_POST['cmd'];
		
		switch ($cmd)
		{
			case 'new':
				$reg = new Registry;

				$class_name = $reg->get_class_name($_POST['cid']);
				$error = $this->src_create_instance($class_name, $_POST['name'], $_POST['db_name']);
				if (!$error)
				{
					if ($reg->instance_new($_POST))
					{
						$this->db_initialize_instance($reg, $_POST['db_name'], $_POST);
						$this->apache_update_vhosts($reg);
						$this->apache_restart();
					} else $this->src_delete_instance($class_name, $_POST['name']);
				}
				$this->req_home($error, $reg);
				break;			
			
			case 'edit':
				$reg = new Registry;

				$old_ins = $reg->get_instance($_POST['id']);
				$error = $reg->update_instance($_POST);
				if (!$error && $old_ins['name'] != $_POST['name'])
				{
					$cl_name = $reg->get_class_name($_POST['cid']);
					$old_name = $old_ins['name'] . CLASSES_SITES_SUFFIX;
					$new_name = $_POST['name'] . CLASSES_SITES_SUFFIX;
					$src = CLASSES_DIR . "/$cl_name/sites/$old_name";
					$dst = CLASSES_DIR . "/$cl_name/sites/$new_name";
					
					shell_exec("mv $src $dst");
					$this->apache_update_vhosts($reg);
					$this->apache_restart();
				}
				$this->req_home($error, $reg);
				break;

			default:
				$this->req_home();			
		}
	}
	
	// ------------------------------------------------------------------------
	// Supplementary
	// ------------------------------------------------------------------------

	/** Shows the license. */
	function req_show_license()
	{
		$iid = $_GET['iid'];
		$reg = new Registry;
		$lic = $reg->get_instance_license_text($iid);
		
		$this->view->show_license($lic);
	}

	// ------------------------------------------------------------------------
	// Toolkit
	// ------------------------------------------------------------------------
	
	function req_test()
	{
		$reg = new Registry;
		$this->apache_update_vhosts($reg);
		$this->apache_restart();
	}
	
	/** Restarts apache gracefully. */
	function apache_restart()
	{
		shell_exec(APACHE_RESTART);
	}
	
	/** Updates virtual hosts configuration. */
	function apache_update_vhosts(&$reg)
	{
		$classes = $reg->select_classes_and_instances(false, false, false);
		$vhosts = $this->view->apache_vhosts($classes);

		$this->_write_to_file(APACHE_VHOSTS_CONFIG, $vhosts);
	}
	
	/** Copies sources from the template to a new class. */
	function src_create_class($class_name)
	{
		$class_dir = CLASSES_DIR . '/' . $class_name;

		if (!is_dir($class_dir))
		{
			if (is_dir(CLASSES_TEMPLATE))
			{
				copyr(CLASSES_TEMPLATE, $class_dir);
//				shell_exec('cp -R ' . CLASSES_TEMPLATE . " $class_dir");
				if (!is_dir($class_dir)) return "Failed to create the directory for class $class_name from the template.";
			} else return 'Classes template ' . CLASSES_TEMPLATE . ' is not present.';			
		} else return "Class $class_name is already present in the feed libraries directory.";
		
		return false;
	}

	/** Removes class directory. */	
	function src_delete_class($class_name)
	{
		$class_dir = CLASSES_DIR . "/$class_name";
		rmdirr($class_dir);
//		shell_exec("rm -R $class_dir");
	}
	
	/** Copies default class instance and configures permissions. */
	function src_create_instance($class_name, $instance_name, $db_name)
	{
		$class_dir = CLASSES_DIR . "/$class_name";
		if (!is_dir($class_dir)) return "Class $class_name doesn't exist.";
		
		$instance_dir = $class_dir . "/sites/$instance_name" . CLASSES_SITES_SUFFIX;
		if (is_dir($instance_dir)) return "Instance $instance_name already exists in class $class_name.";
		
		$default_dir = $class_dir . '/sites/default';
		copyr($default_dir, $instance_dir);
//		shell_exec("cp -R $default_dir $instance_dir");
		if (!is_dir($instance_dir)) return "Failed to create instance $instance_name in class $class_name.";
		
		// Set the permissions
		chmodr($instance_dir, 0766, 0777);
//		shell_exec("chmod -R a+rw $instance_dir");
		
		// Create config with database name
		$cfg = $this->view->instance_config($db_name);
		$this->_write_to_file($instance_dir . '/config.php', $cfg);
		
		return false;
	}

	/** Removes instance directory. */	
	function src_delete_instance($class_name, $instance_name)
	{
		$instance_dir = CLASSES_DIR . "/$class_name/sites/$instance_name" . CLASSES_SITES_SUFFIX;
		rmdirr($instance_dir);
//		shell_exec("rm -R $instance_dir");
	}
	
	/** Writes data to file. */
	function _write_to_file($filename, $content)
	{
		if (is_writable($filename) || !file_exists($filename))
		{
		   if (!$handle = fopen($filename, 'w'))
		   {
		        echo "Cannot open file ($filename)";
		        exit;
		   }
		
		   // Write $somecontent to our opened file.
		   if (fwrite($handle, $content) === FALSE)
		   {
		       echo "Cannot write to file ($filename)";
		       exit;
		   }
		   
		   fclose($handle);
		} else
		{
		   echo "The file $filename is not writable";
		}
	}
	
	/** Run all scripts for the database. */
	function db_initialize_instance($reg, $db_name, $fields)
	{
		$sql_dir = CLASSES_TEMPLATE . "/sql";
		
		$user = DB_USER;
		$password = DB_PASS;
		$p = $password != '' ? "-p$password" : '';

		if ($handle = opendir($sql_dir))
		{
		   $scs = array();
		   while (false !== ($script = readdir($handle)))
		   {
		       if (preg_match("/^[0-9].*\.sql$/", $script)) $scs []= $script;
		   }

		   closedir($handle);

		   sort($scs);
		   foreach ($scs as $script)
	       {
	           if (DB_DEBUG) echo "Executing: $script<br>";
	           shell_exec("mysql $db_name -u$user $p < $sql_dir/$script");
	       }
		   
		   $reg->init_instance($db_name, $fields);
		}		
	}
}

?>
