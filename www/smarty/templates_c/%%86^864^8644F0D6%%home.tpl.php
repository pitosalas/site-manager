<?php /* Smarty version 2.6.13, created on 2007-01-16 08:19:06
         compiled from home.tpl */ ?>
<?php if (isset ( $this->_tpl_vars['error'] )): ?><div id="error"><?php echo $this->_tpl_vars['error']; ?>
</div><?php endif; ?>
<table id="instances">
  <?php $_from = $this->_tpl_vars['classes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
  	<?php $this->assign('cc', $this->_tpl_vars['c']['class']); ?>
	<tr class="clss">
		<td colspan="7">
			<h1><?php echo $this->_tpl_vars['cc']['title']; ?>
 (<?php echo $this->_tpl_vars['cc']['name']; ?>
)</h1>
			<p><?php echo $this->_tpl_vars['cc']['description']; ?>
</p>
			<p><a href="index.php?action=instance_new&cid=<?php echo $this->_tpl_vars['cc']['id']; ?>
">New Instance</a> | <a href="index.php?action=class_edit&id=<?php echo $this->_tpl_vars['cc']['id']; ?>
">Edit</a> <a href="index.php?action=class_delete&id=<?php echo $this->_tpl_vars['cc']['id']; ?>
">Delete</a></p>
		</td>
	</tr>
	<?php if (count ( $this->_tpl_vars['c']['instances'] ) > 0): ?>
		<tr class="instance">
			<th class="che">&nbsp;</th>
			<th class="tit">Title</th>
			<th class="nam">Disk Name<br />DB Name</th>
			<th class="spo">Sponsor<br />Sponsor E-mail</th>
			<th class="typ">Type<br />Exp. Date</th>
			<th class="las">Last Login<br />&nbsp;</th>
			<th class="adm">Admin&nbsp;Password<br />License&nbsp;accepted</th>
		</tr>
	<?php $_from = $this->_tpl_vars['c']['instances']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['it'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['it']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['i']):
        $this->_foreach['it']['iteration']++;
?>
		<?php $this->assign('in', $this->_foreach['it']['iteration']); ?>
    	<?php $this->assign('i2', $this->_tpl_vars['in']%2); ?>
		<tr class="instance<?php if ($this->_tpl_vars['i2'] == 1): ?> altrow<?php endif; ?>">
			<td class="che">&nbsp;</td>
			<td class="tit"><a href="http://<?php echo $this->_tpl_vars['i']['name'];  echo $this->_tpl_vars['suffix']; ?>
/" class="site"><?php echo $this->_tpl_vars['i']['title']; ?>
</a><br /><a href="index.php?action=instance_delete&id=<?php echo $this->_tpl_vars['i']['id']; ?>
">Delete</a> <a href="index.php?action=instance_edit&id=<?php echo $this->_tpl_vars['i']['id']; ?>
">Edit</a></td>
			<td class="nam"><?php echo $this->_tpl_vars['i']['name']; ?>
<br /><?php echo $this->_tpl_vars['i']['db_name']; ?>
</td>
			<td class="spo"><?php echo $this->_tpl_vars['i']['sponsor_name']; ?>
<br /><?php echo $this->_tpl_vars['i']['sponsor_email']; ?>
</td>
			<td class="typ"><?php if ($this->_tpl_vars['i']['type'] == 0): ?>Licensed<?php else: ?>Trial<?php endif; ?><br /><?php if ($this->_tpl_vars['i']['expiration_date'] <= 0): ?>Unlimited<?php else:  echo $this->_tpl_vars['i']['expiration_date'];  endif; ?></td>
			<td class="las"><?php echo $this->_tpl_vars['i']['last_login']; ?>
<br />&nbsp;</td>
			<td class="adm"><?php echo $this->_tpl_vars['i']['admin_password']; ?>
<br /><?php echo $this->_tpl_vars['i']['admin_license_accepted']; ?>
</td>
		</tr>
	<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
        <tr><td colspan="7">&nbsp;</td></tr>
  <?php endforeach; endif; unset($_from); ?>
</table>

<a href="index.php?action=class_new">New Class</a> 