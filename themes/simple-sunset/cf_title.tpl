{php}
	global $conf;
	$this->assign('LEVEL_SEPARATOR', $conf[ 'level_separator' ]);
{/php}
<div class="titrePage">
  <span id="menuswitcher" title="Afficher/cacher le menu">Menu</span> »
  <ul class="categoryActions"></ul>
  <h2><a href="{$U_HOME}" title="{'Return to home page'|@translate}">{'Home'|@translate}</a>{$LEVEL_SEPARATOR}{$CF.TITLE|@translate}</h2>
</div>