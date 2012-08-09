<div class="titrePage">
  <span id="menuswitcher" title="{'Show/hide menu'|@translate}">{'Menu'|@translate}</span> »
  <h2>{$TITLE}</h2>
  
  {if !empty($PLUGIN_INDEX_ACTIONS)}
  <ul class="categoryActions">
    {$PLUGIN_INDEX_ACTIONS}
  </ul>
  {/if}
</div>

<div id="content">
{$MENUBAR}