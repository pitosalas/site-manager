<h1>{$title}</h1>

<form method="post" action="index.php?action=class_form">
	<input type="hidden" name="cmd" value="{$cmd}" />
	{if isset($obj)}<input type="hidden" name="id" value="{$obj.id}" />{/if}
	<table>
		<tr>
			<td>
				<label>Title:</label><br />
				<input name="title" type="text" class="field" {if isset($obj)}value="{$obj.title}"{/if}/>
			</td>
		</tr>
		<tr>
			<td>
				<label>Name:</label><br />
				<input name="name" type="text" class="field" {if isset($obj)}value="{$obj.name}"{/if}/>
			</td>
		</tr>
		<tr>
			<td>
				<label>Description:</label><br />
				<textarea name="description" class="field">{if isset($obj)}{$obj.description}{/if}</textarea>
			</td>
		</tr>
	</table>

	<input type="submit" value="Save" />
</form>