<h1>{$title}</h1>

<form method="post" action="index.php?action=instance_form">
	<input type="hidden" name="cmd" value="{$cmd}" />
	{if isset($cid)}<input type="hidden" name="cid" value="{$cid}" />{/if}
	{if isset($ins)}<input type="hidden" name="id" value="{$ins.id}" />{/if}
	<table>
		<tr>
			<td colspan="2">
				<label>Title:</label><br />
				<input name="title" type="text" class="field" {if isset($ins)}value="{$ins.title}"{/if}/>
			</td>
		</tr>
		<tr valign="top">
			<td>
				<label>Name:</label><br />
				<input name="name" type="text" class="field_half" {if isset($ins)}value="{$ins.name}"{/if}/> <br />
				<span class="tip">xyz ({$suffix})</span>
			</td>
			<td>
				<label>DB Name:</label><br />
				<input name="db_name" type="text" {if isset($ins)}readonly="yes" {/if}class="field_half" {if isset($ins)}value="{$ins.db_name}"{/if}/> <br />
				<span class="tip">Format: fl_xxx</span>
			</td>
		</tr>
		<tr valign="top">
			<td>
				<label>Type:</label><br />
				<select name="type" class="field_half">
					{html_options options=$type_options selected=$type_selected}
				</select>
			</td>
			<td>
				<label>Exp Date:</label><br />
				<input name="expdate" type="text" class="field_half" {if isset($ins) and $ins.expiration_date neq -1}value="{$ins.expiration_date}"{/if}/><br />
				<span class="tip">Format: yyyy-mm-dd</span>
			</td>
		</tr>
		<tr>
			<td>
				<label>Sponsor:</label><br />
				<input name="sponsor" type="text" class="field_half" {if isset($ins)}value="{$ins.sponsor_name}"{/if}/>
			</td>
			<td>
				<label>Sponsor E-mail:</label><br />
				<input name="sponsor_email" type="text" class="field_half" {if isset($ins)}value="{$ins.sponsor_email}"{/if}/>
			</td>
		</tr>
	</table>

	<input type="submit" value="Save" />
</form>