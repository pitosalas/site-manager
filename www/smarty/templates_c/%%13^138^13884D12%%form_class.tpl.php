<?php /* Smarty version 2.6.13, created on 2007-01-11 14:52:07
         compiled from form_class.tpl */ ?>
<h1><?php echo $this->_tpl_vars['title']; ?>
</h1>

<form method="post" action="index.php?action=class_form">
	<input type="hidden" name="cmd" value="<?php echo $this->_tpl_vars['cmd']; ?>
" />
	<?php if (isset ( $this->_tpl_vars['obj'] )): ?><input type="hidden" name="id" value="<?php echo $this->_tpl_vars['obj']['id']; ?>
" /><?php endif; ?>
	<table>
		<tr>
			<td>
				<label>Title:</label><br />
				<input name="title" type="text" class="field" <?php if (isset ( $this->_tpl_vars['obj'] )): ?>value="<?php echo $this->_tpl_vars['obj']['title']; ?>
"<?php endif; ?>/>
			</td>
		</tr>
		<tr>
			<td>
				<label>Name:</label><br />
				<input name="name" type="text" class="field" <?php if (isset ( $this->_tpl_vars['obj'] )): ?>value="<?php echo $this->_tpl_vars['obj']['name']; ?>
"<?php endif; ?>/>
			</td>
		</tr>
		<tr>
			<td>
				<label>Description:</label><br />
				<textarea name="description" class="field"><?php if (isset ( $this->_tpl_vars['obj'] )):  echo $this->_tpl_vars['obj']['description'];  endif; ?></textarea>
			</td>
		</tr>
	</table>

	<input type="submit" value="Save" />
</form>