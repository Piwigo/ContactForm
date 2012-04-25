<div class="titrePage">
    <h2>{$CF.TITLE}<br>{'cf_tab_persoform'|@translate}</h2>
</div>
<h3>{'Add text to the contact form'|@translate}</h3>
<br>
	<form method="post" >
	 <fieldset id="mainConf">
			<span class="property">
				<label for="persoformtop">{'Add text above'|@translate} *1</label>
			</span>
			<br>
			<textarea rows="4" cols="80" class="description" name="persoform_top" id="persoform_top">{$PERSOFORMTOP}</textarea>
  			<br>
			<br>
			<h3>{'contact_form_title'|@translate}</h3>
			<br>
			<br>
			<span class="property">
				<label for="persoformbottom">{'Add text below'|@translate} *2</label>
			</span>
			<br>
			<textarea rows="4" cols="80" class="description" name="persoform_bottom" id="persoform_bottom">{$PERSOFORMBOTTOM}</textarea>
  <p>
    <input class="submit" type="submit" name="submitpersoform" value="{'Submit'|@translate}">
  </p>
  	</form>
<br>
<br>
<br>
<br>
*1 {'use tag < div id="persoformtop" > to customize your personal text'|@translate}<br>
*2 {'use tag < div id="persoformbottom" > to customize your personal text'|@translate}<br>
{'if plugin ExtendedDescription is actif, you can use tags [lang]'|@translate}<br>
</fieldset>