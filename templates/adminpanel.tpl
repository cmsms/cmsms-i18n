<h3>{$title_section}</h3>
{$tab_headers}

{$start_translations_tab}
{if $sources|count > 0}
<table cellspacing="0" class="pagetable">
	<thead>
		<tr>
			<th>Source: [{$default_culture}]</th>
			{if $cultures|count > 0}
			{foreach from=$cultures item=culture}
			<th><a href="{$culture.link}" title="Translate entire {$culture.name} culture">{$culture.name}</a></th>
			{/foreach}
			{/if}
			<th><!-- Actions --></th>
		</tr>
	</thead>
	<tbody>
{foreach from=$sources item=source}
	  {assign var="source_name" value=$source.name}
		<tr {*class="{$entry->rowclass}" onmouseover="this.className='{$entry->rowclass}hover';" onmouseout="this.className='{$entry->rowclass}';"*}>
			<td><a href="{$source.link}" title="Edit entire source">{$source_name|truncate:30:"..."|escape:'html'}</a></td>
			{if $cultures|count > 0}
			{foreach from=$cultures item=culture}
			{assign var="cult" value=$culture.name}
			<td>{$translations.$cult.$source_name}</td>
			{/foreach}
			{/if}
			<td><a href="{$source.delete}" onClick="javascript: return confirm('Are you sure?')">Delete</a></td>
		</tr>
{/foreach}
	</tbody>
</table>
{/if}
{$end_tab}

{$start_links_tab}
{if $links_sources|count > 0}
<table cellspacing="0" class="pagetable">
	<thead>
		<tr>
			<th>{$default_culture}</th>
			{if $link_cultures|count > 0}
			{foreach from=$link_cultures item=culture}
			<th>{$culture}</th>
			{/foreach}
			{/if}
		</tr>
	</thead>
	<tbody>
{foreach from=$links_sources item=source}
		<tr {*class="{$entry->rowclass}" onmouseover="this.className='{$entry->rowclass}hover';" onmouseout="this.className='{$entry->rowclass}';"*}>
			<td>{$source|truncate:30:"..."|escape:'html'}</td>
			{if $link_cultures|count > 0}
			{foreach from=$link_cultures item=culture}
			<td>{$links.$culture.$source}</td>
			{/foreach}
			{/if}
		</tr>
{/foreach}
	</tbody>
</table>
{/if}
{$end_tab}

{$start_preferences_tab}

{if isset($form)}
    {$form}
{/if}


{$form_start}
<div class="pageoverflow">
    <p class="pagetext">{$title_harvest}:</p>
    <p class="pageinput"><label>{$harvest} {$harvest_tips}</label></p>
</div>
{*}<div class="pageoverflow">
    <p class="pagetext">{$title_cache}:</p>
    <p class="pageinput"><label>{$cache} {$cache_label}</label></p>
</div>{*}
<div class="pageoverflow">
    <p class="pagetext">{$default_culture_title}:</p>
    <p class="pageinput">{$default_cultures}</p>
</div>

<div>
{$submit}
{$cancel}
</div>
{$form_end}
{$end_tab}

{$tab_footers}

