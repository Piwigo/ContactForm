<div class="titrePage">
    <h2>{$CF.TITLE}<br>{'cf_tab_persoform'|@translate}</h2>
</div>

<form method="post" >
<fieldset>
  <legend>{'Text before the contact form'|@translate}</legend>
  <textarea rows="4" cols="80" class="description" name="persoform_top" id="persoform_top">{$PERSOFORMTOP}</textarea>
</fieldset>

<fieldset>
  <legend>{'Text after the contact form'|@translate}</legend>
  <textarea rows="4" cols="80" class="description" name="persoform_bottom" id="persoform_bottom">{$PERSOFORMBOTTOM}</textarea>
</fieldset>

<p>
  <input class="submit" type="submit" name="submitpersoform" value="{'Submit'|@translate}">
</p>
</form>
