<?php /* Smarty version 2.6.13, created on 2007-01-16 06:31:50
         compiled from form_instance.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'form_instance.tpl', 30, false),)), $this); ?>
<h1><?php echo $this->_tpl_vars['title']; ?>
</h1>

<form method="post" action="index.php?action=instance_form">
	<input type="hidden" name="cmd" value="<?php echo $this->_tpl_vars['cmd']; ?>
" />
	<?php if (isset ( $this->_tpl_vars['cid'] )): ?><input type="hidden" name="cid" value="<?php echo $this->_tpl_vars['cid']; ?>
" /><?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['ins'] )): ?><input type="hidden" name="id" value="<?php echo $this->_tpl_vars['ins']['id']; ?>
" /><?php endif; ?>
	<table>
		<tr>
			<td colspan="2">
				<label>Title:</label><br />
				<input name="title" type="text" class="field" <?php if (isset ( $this->_tpl_vars['ins'] )): ?>value="<?php echo $this->_tpl_vars['ins']['title']; ?>
"<?php endif; ?>/>
			</td>
		</tr>
		<tr valign="top">
			<td>
				<label>Name:</label><br />
				<input name="name" type="text" class="field_half" <?php if (isset ( $this->_tpl_vars['ins'] )): ?>value="<?php echo $this->_tpl_vars['ins']['name']; ?>
"<?php endif; ?>/> <br />
				<span class="tip">xyz (<?php echo $this->_tpl_vars['suffix']; ?>
)</span>
			</td>
			<td>
				<label>DB Name:</label><br />
				<input name="db_name" type="text" <?php if (isset ( $this->_tpl_vars['ins'] )): ?>readonly="yes" <?php endif; ?>class="field_half" <?php if (isset ( $this->_tpl_vars['ins'] )): ?>value="<?php echo $this->_tpl_vars['ins']['db_name']; ?>
"<?php endif; ?>/> <br />
				<span class="tip">Format: fl_xxx</span>
			</td>
		</tr>
		<tr valign="top">
			<td>
				<label>Type:</label><br />
				<select name="type" class="field_half">
					<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['type_options'],'selected' => $this->_tpl_vars['type_selected']), $this);?>

				</select>
			</td>
			<td>
				<label>Exp Date:</label><br />
				<input name="expdate" type="text" class="field_half" <?php if (isset ( $this->_tpl_vars['ins'] ) && $this->_tpl_vars['ins']['expiration_date'] != -1): ?>value="<?php echo $this->_tpl_vars['ins']['expiration_date']; ?>
"<?php endif; ?>/><br />
				<span class="tip">Format: yyyy-mm-dd</span>
			</td>
		</tr>
		<tr>
			<td>
				<label>Sponsor:</label><br />
				<input name="sponsor" type="text" class="field_half" <?php if (isset ( $this->_tpl_vars['ins'] )): ?>value="<?php echo $this->_tpl_vars['ins']['sponsor_name']; ?>
"<?php endif; ?>/>
			</td>
			<td>
				<label>Sponsor E-mail:</label><br />
				<input name="sponsor_email" type="text" class="field_half" <?php if (isset ( $this->_tpl_vars['ins'] )): ?>value="<?php echo $this->_tpl_vars['ins']['sponsor_email']; ?>
"<?php endif; ?>/>
			</td>
		</tr>
	</table>

	<input type="submit" value="Save" />
</form>