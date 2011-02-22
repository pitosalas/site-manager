<?php

class Registry
{
	var $db;
	
    function Registry()
    {
    	$this->db = &ADONewConnection(DB_TYPE, 'pear');
    	$this->db->debug = DB_DEBUG;
    	$this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }
    
    /**
     * Returns the array of structures:
     * class -> class fields
     * instances -> array of instance fields
     */
    function select_classes_and_instances($with_type_and_expdate = true, 
    	$with_login_info = true, $with_licensing_info = true)
    {
    	$classes = array();

		// Fetch classes    	
    	$rs_classes = &$this->db->Execute('SELECT * FROM classes ORDER BY title');
    	while (!$rs_classes->EOF)
    	{
    		$data = array();
    		$data['class'] = $rs_classes->fields;
    		$data['instances'] = array();
    		
    		$classes[$rs_classes->fields['id']] = $data;
    		$rs_classes->MoveNext();
    	}
    	$rs_classes->Close();

		// Fetch instances    	
    	$rs_instances = &$this->db->Execute('SELECT * FROM instances ORDER BY title');
    	while (!$rs_instances->EOF)
    	{
    		$class_id = $rs_instances->fields['class_id'];
    		$data = &$classes[$class_id];
    		$inst = &$data['instances'];
    		$flds = $rs_instances->fields;
			if ($with_type_and_expdate) $this->_select_type_and_expdate($flds);
			if ($with_login_info) $this->_get_last_login($flds);
			if ($with_licensing_info) $this->_get_licensing_info($flds);
    		
    		$inst []= $flds;
    		$rs_instances->MoveNext();
    	}
    	$rs_instances->Close();
    	
    	return $classes;
    }
    
    /** Creates new class record. */
    function class_new($d)
    {
    	$titl = $this->db->qstr($d['title']);
    	$name = $this->db->qstr($d['name']);
    	$desc = $this->db->qstr($d['description']);
    	
    	if ($this->db->Execute("INSERT INTO classes (title, name, description) VALUES ($titl, $name, $desc)") == false)
    	{
    		$this->_error('Error Inserting: ');
    	}
    }
    
    /** Deletes a class with all instances. */
    function class_delete($id)
    {
		$i_db_names = $this->_find_instance_db_names_by_cid($id);
		foreach ($i_db_names as $i_db_name) $this->_drop_instance_db($i_db_name);
    
    	if ($this->db->Execute("DELETE FROM classes WHERE id=$id") === false)
    	{
    		$this->_error('Error Deleting: ');
    	}
    }
    
    /** Returns the list of instance IDs corresponding to the class ID given. */
    function _find_instance_db_names_by_cid($cid)
    {
    	$names = array();
    	
    	$res = &$this->db->Execute("SELECT db_name FROM instances where class_id=$cid");
    	if ($res != false && !$res->EOF)
    	{
    		$names []= $res->fields['db_name'];
    		$res->MoveNext();
    	}

    	return $names;
    }

	/** Returns class name by its ID. */
	function get_class_name($cid)
	{
		$name = null;
		
		$rs = &$this->db->Execute("SELECT name FROM classes WHERE id=$cid");
		if ($rs != false && !$rs->EOF)
		{
			$name = $rs->fields['name'];
		}
		
		return $name;
	}

	/** Returns class fields by class ID. */
	function get_class($cid)
	{
		$c = null;
		
		$rs = &$this->db->Execute("SELECT * FROM classes WHERE id=$cid");
		if ($rs != false && !$rs->EOF)
		{
			$c = $rs->fields;
		}
		
		return $c;
	}
	
	/** Updates class fields. */
	function update_class($d)
	{
		$tit = $this->db->qstr($d['title']);
		$nam = $this->db->qstr($d['name']);
		$des = $this->db->qstr($d['description']);
		$id = $d['id'];

		if ($this->db->Execute("UPDATE classes SET title=$tit, name=$nam, description=$des WHERE id=$id") === false)
		{
			return "Failed to udpate the class: " . $this->db->ErrorMsg();
		}
		
		return false;
	}
	
    /** Creates new instance record. */
    function instance_new($d)
    {
    	$c = &$this->db;
    	
    	$cid = $d['cid'];
    	$title = $c->qstr($d['title']);
    	$name = $c->qstr($d['name']);
    	$db_name = $c->qstr($d['db_name']);
    	$sponsor = $c->qstr($d['sponsor']);
    	$sponsor_email = $c->qstr($d['sponsor_email']);
    	
    	// Create instance database
    	if ($this->db->Execute("CREATE DATABASE " . $d['db_name']) === false)
    	{
    		$this->_error('Error Creating Instance Database: ');
    		return false;
    	}
    	
    	if ($this->db->Execute("INSERT INTO instances (class_id, title, name, db_name, sponsor_name, sponsor_email) VALUES " .
    			"($cid, $title, $name, $db_name, $sponsor, $sponsor_email)") == false)
    	{
    		$this->_error('Error Inserting: ');
    		return false;
    	}
    	
    	return true;
    }

	/** Returns instance fields. */
	function get_instance($id)
	{
		$i = null;
		
		$rs = &$this->db->Execute("SELECT * FROM instances WHERE id=$id");
		if ($rs != false && !$rs->EOF)
		{
			$i = $rs->fields;
			$this->_select_type_and_expdate($i);
		}
		
		return $i;
	}
	
	/** Selects type and expdate for instance. */
	function _select_type_and_expdate(&$i)
	{
		$i['expiration_date'] = -1;
		$i['type'] = 0;

		$db = $i['db_name'];
		$rs_in = &$this->db->Execute("SELECT name, value FROM $db.ApplicationProperty WHERE name in ('app_type', 'app_expiration_date')");
		while (!$rs_in->EOF)
		{
			$k = $rs_in->fields['name'];
			$v = $rs_in->fields['value'];

			$rs_in->MoveNext();
			if ($v <= 0) continue;

			if ($k == 'app_expiration_date')
			{
				$i['expiration_date'] = Registry::ts2date((int)$v);
			} else
			{
				$i['type'] = (int)$v;
			}
		}
		
		$rs_in->Close();
	}
	
	/** Selects the date of last login into this instance. */
	function _get_last_login(&$i)
	{
		$last_login = null;
		
		$db = $i['db_name'];
		$rs_in = &$this->db->Execute("SELECT MAX(last_login) last_login FROM $db.Person");
		if (!$rs_in->EOF)
		{
			$ts = (int)$rs_in->fields['last_login'];
			$last_login = $ts <= 0 ? 'Never' : Registry::ts2date($ts); 
		}
		$rs_in->Close();
		
		$i['last_login'] = $last_login;
		
		return $last_login;
	}
	
	function _get_licensing_info(&$i)
	{
		$i['admin_license_accepted'] = 'Never';

		$db = $i['db_name'];
		$rs = &$this->db->Execute("SELECT passwd, license_accepted FROM $db.Person WHERE id=1");
		if ($rs && !$rs->EOF)
		{
			$i['admin_password'] = $rs->fields['passwd'];
			
			$la = $this->ts2date($rs->fields['license_accepted']);
			if ($la == null)
			{
				$la = 'Never';				
			} else {
				$iid = $i['id'];
				$la = "<a href=\"index.php?action=show_license&iid=$iid\">$la</a>";
			}
			
			$i['admin_license_accepted'] = $la;
		}		
	}
	
	/** Returns the text of the license which was accepted by the admin of an instance. */
	function get_instance_license_text($iid)
	{
		$txt = null;

		$rs = &$this->db->Execute("SELECT db_name FROM instances WHERE id=$iid");
		if ($rs && !$rs->EOF)
		{
			$db = $rs->fields['db_name'];
			$rs = &$this->db->Execute("SELECT license_text FROM $db.Person WHERE id=1");
			if ($rs && !$rs->EOF)
			{
				$txt = $rs->fields['license_text'];
			} else
			{
				$this->_error("Failed to get the license text for instance $iid at $db");
			}
		} else
		{
			$this->_error("Failed to get the database name for instance $iid");
		}		
		
		return $txt;
	}
	
	/** Finds delete-info for the instance -- an array with 'name' and 'class_name'. */
	function get_instance_deleteinfo($id)
	{
		$rs = $this->db->Execute("SELECT i.name as name, c.name as class_name FROM instances i, classes c WHERE i.id=$id AND c.id=i.class_id");
    	if ($rs === false)
    	{
    		$this->_error('Error Selecting: ');
    	} else
    	{
    		return $rs->EOF ? null : $rs->fields;
    	}
	}

	/** Updates instance. */
	function update_instance($f)
	{
		$id = $f['id'];
		$tit = $this->db->qstr($f['title']);
		$nam = $this->db->qstr($f['name']);
		$sna = $this->db->qstr($f['sponsor_name']);
		$sem = $this->db->qstr($f['sponsor_email']);
		
		if ($this->db->Execute("UPDATE instances SET title=$tit, name=$nam, sponsor_name=$sna, sponsor_email=$sem WHERE id=$id") === false)
		{
			$this->_error('Failed to update instance: ');
		}

		$this->_update_instance_properties($f['db_name'], $f);
		
		return false;
	}

    /** Deletes an instance. */
    function instance_delete($id)
    {
	    $rs = $this->db->Execute("SELECT db_name FROM instances WHERE id=$id");
	    if ($rs === false)
	    {
	    	return;
	    }

		$this->_drop_instance_db($rs->fields['db_name']);
		
    	if ($this->db->Execute("DELETE FROM instances WHERE id=$id") === false)
    	{
    		$this->_error('Error Deleting: ');
    	}
    }

	/** Drops database by the name given. */    
    function _drop_instance_db($db_name)
    {
		if ($this->db->Execute("DROP DATABASE $db_name") === false)
		{
			$this->_error("Database $db_name wasn't dropped.");
		}
    }
    
    function init_instance($db_name, $fields)
    {
    	$c = &$this->db;
    	
		// Create an account for the sponsor
    	$sponsor = $fields['sponsor'];
    	$sponsor_email = $fields['sponsor_email'];
		if (strlen($sponsor) > 0 && strlen($sponsor_email) > 0)
		{
			$fullName = $c->qstr(trim($sponsor));
			$email = $c->qstr(trim($sponsor_email));
		   
			if (!$c->Execute("UPDATE $db_name.Person " .
				"SET fullName=$fullName, email=$email WHERE id=1"))
			{
				$this->_error("Failed to update sponsor account: ");
			}
		}

		$this->_update_instance_properties($db_name, $fields, true);
	}
	
	/** Updates type and expdate properties of the instance. */	
	function _update_instance_properties($db_name, $fields, $with_title = false)
	{
    	$c = &$this->db;

		$q = "DELETE FROM $db_name.ApplicationProperty " .
			"WHERE name in ('app_type', 'app_expiration_date'" .
			($with_title ? ", 'title'" : "") . ")";
			
		if (!$c->Execute($q))
		{
			$this->_error("Failed to clean application properties: ");
		} else
		{
			// Initialize type
			$type = $fields['type'];
			$expdate = trim($fields['expdate']) == '' ? 'null' : Registry::date2ts($fields['expdate']);
			$title = $c->qstr($fields['title']);
			
			if (!$c->Execute("INSERT INTO $db_name.ApplicationProperty (name, value) " .
				"VALUES ('app_type', $type), ('app_expiration_date', $expdate)" .
				($with_title ? ", ('title', $title)" : "")))
			{
				$this->_error("Failed to update application properties: ");
			}
		}
	}
	
	/** Converts date to timestamp. */
	function date2ts($d)
	{
		return strtotime($d);
	}
	
	/** Converts timestamp to date. */
	function ts2date($ts)
	{
		return $ts > 0 ? date('Y-m-d', $ts) : null;
	}
	
	function _error($msg)
	{
		echo $msg . $this->db->ErrorMsg();
	}
}
?>
