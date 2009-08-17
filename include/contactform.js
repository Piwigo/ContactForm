function cf_check_content(item, is_mail) {
  if (null == item) {
    return false;
  }
  var value = item.value;
  if (null == value || 0 == value.length || '' == value) {
    return false;
  }
  if (is_mail) {
    return cf_check_mail_content(value);
  }
  return true;
}

function cf_check_mail_content(value) {
  var atom = '[-a-z0-9!#$%&\'*+\\/=?^_`{|}~]';     // before  arobase
  var domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // domain name
  var regex; 
  regex  = '^' + atom + '+' + '(\.' + atom + '+)*';
  regex += '@' + '(' + domain + '{1,63}\.)+' + domain + '{2,63}$';
  var reg = new RegExp(regex, 'i');
  if(reg.test(value)) {
    return true;
  } else {
    return false;
  }
}

function cf_set_visible(element, visible) {
  var new_state, new_display;
  if (visible) {
    new_state = "visible";
    new_display = "block";
  } else {
    new_state = "hidden";
    new_display = "none";
  }
  element.style.visibility = new_state;
  element.style.display = new_display;
}

function hideShowDiv(select_item) {

  for (var i=0 ; i<select_item.length ; i++) {
    var div_item = document.getElementById(select_item[i].value);
    var visible = select_item[i].selected;
    cf_set_visible(div_item, visible);
  }
  return;
  if (lCheckBoxSelected.checked) {
    lShowCB = false;
    lShowDiv = true;
  }
  var i=0;
  for (i=0 ; i<lCategories.length ; i++) {
    if (lCategories[i].id != current_category &&
      lCategories[i].id.match(gCategoryPrefix)) {
      
      setVisible(lCategories[i], lShowCB);
    }
  }
  for (i=0 ; i<lInputs.length ; i++) {
    if (lInputs[i].type == 'checkbox' && lInputs[i].name == gCBName) {
      lCB.push(lInputs[i]);
    }
  }
  for (i=0 ; i<lCB.length ; i++) {
    if (lCB[i].id != lIdSelected) {
      setVisible(document.getElementById(gListPrefix + lCB[i].value), lShowCB);
      lCB[i].checked = false;
    }
  }
  if (!lShowDiv) {
    setVisible(gFormDetailsDiv, lShowDiv);
    setVisible(gProductDetailsDiv, lShowDiv);
    clearDiv();
  } else {
    getParameters(productId);
  }
}
