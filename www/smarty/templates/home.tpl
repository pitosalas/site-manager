{if isset($error)}<div id="error">{$error}</div>{/if}
<table id="instances">
  {foreach item=c from=$classes}
  	{assign var=cc value=$c.class}
	<tr class="clss">
		<td colspan="7">
			<h1>{$cc.title} ({$cc.name})</h1>
			<p>{$cc.description}</p>
			<p><a href="index.php?action=instance_new&cid={$cc.id}">New Instance</a> | <a href="index.php?action=class_edit&id={$cc.id}">Edit</a> <a href="index.php?action=class_delete&id={$cc.id}">Delete</a></p>
		</td>
	</tr>
	{if count($c.instances) gt 0}
		<tr class="instance">
			<th class="che">&nbsp;</th>
			<th class="tit">Title</th>
			<th class="nam">Disk Name<br />DB Name</th>
			<th class="spo">Sponsor<br />Sponsor E-mail</th>
			<th class="typ">Type<br />Exp. Date</th>
			<th class="las">Last Login<br />&nbsp;</th>
			<th class="adm">Admin&nbsp;Password<br />License&nbsp;accepted</th>
		</tr>
	{foreach name=it item=i from=$c.instances}
		{assign var='in' value=$smarty.foreach.it.iteration}
    	{assign var='i2' value=$in%2}
		<tr class="instance{if $i2 eq 1} altrow{/if}">
			<td class="che">&nbsp;</td>
			<td class="tit"><a href="http://{$i.name}{$suffix}/" class="site">{$i.title}</a><br /><a href="index.php?action=instance_delete&id={$i.id}">Delete</a> <a href="index.php?action=instance_edit&id={$i.id}">Edit</a></td>
			<td class="nam">{$i.name}<br />{$i.db_name}</td>
			<td class="spo">{$i.sponsor_name}<br />{$i.sponsor_email}</td>
			<td class="typ">{if $i.type == 0}Licensed{else}Trial{/if}<br />{if $i.expiration_date lte 0}Unlimited{else}{$i.expiration_date}{/if}</td>
			<td class="las">{$i.last_login}<br />&nbsp;</td>
			<td class="adm">{$i.admin_password}<br />{$i.admin_license_accepted}</td>
		</tr>
	{/foreach}
	{/if}
        <tr><td colspan="7">&nbsp;</td></tr>
  {/foreach}
</table>

<a href="index.php?action=class_new">New Class</a> 
