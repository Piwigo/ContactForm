<div class="titrePage">
    <h2>{$CF.TITLE} [{$CF.VERSION}]<br>{'cf_history'|@translate}</h2>
</div>
<h3 style="width: 100%;">{'cf_history_desc'|@translate}</h3>
<table class="table2">
  <tr class="throw">
    <th>{'cf_history_date'|@translate}</th>
    <th>{'cf_history_version'|@translate}</th>
    <th>{'cf_history_log'|@translate}</th>
  </tr>
{foreach from=$CF_HISTORY item=history_item name=history}
  <tr class="{cycle values="row2,row1"}">
    <td>
    {if isset($history_item.DATE.FORMATTED)}
      {$history_item.DATE.FORMATTED}
    {else}
      {$history_item.DATE.RAW}
    {/if}
    </td>
    <td>{$history_item.VERSION}</td>
    <td>
    {foreach from=$history_item.CHANGES item=history_change}
      <div>{$history_change}</div>
    {/foreach}
    </td>
  </tr>
{foreachelse}
  <tr class="row2">
    <td colspan="3" style="text-align: center;">
    {foreach from=$CF_HISTORY_ERROR item=history_error}
      <div>{$history_error}</div>
    {/foreach}
    </td>
  </tr>
{/foreach}
</table>