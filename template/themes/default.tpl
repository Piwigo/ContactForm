{$MENUBAR}

<div id="content" class="content{if isset($MENUBAR)} contentWithMenu{/if}">

<div class="titrePage">
  {if !empty($PLUGIN_INDEX_ACTIONS)}
	<ul class="categoryActions">
    {$PLUGIN_INDEX_ACTIONS}
	</ul>
  {/if}

  <h2>{$TITLE}</h2>
</div>