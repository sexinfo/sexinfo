/**
 * @file
 * Javascript functions for the scale question type.
 */

/**
 * Refreshes alternatives when a preset is selected.
 *
 * @param selection
 *  The select item used to select answer collection
 */
function refreshAlternatives(selection) {
  clearAlternatives();
  var colId = selection.options[selection.selectedIndex].value;
  var numberOfOptions = scaleCollections[colId].length;
  for(var i = 0; i<numberOfOptions;i++){
<<<<<<< HEAD
	jQuery('#edit-alternative' + (i)).val(scaleCollections[colId][i]);
=======
	$('#edit-alternative' + (i)).val(scaleCollections[colId][i]);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  }
}

/**
 * Clears all the alternatives on the scale node form
 */
function clearAlternatives() {
  for ( var i = 0; i < scale_max_num_of_alts; i++) {
<<<<<<< HEAD
	jQuery('#edit-alternative' + (i)).val('');
  }
}
=======
	$('#edit-alternative' + (i)).val('');
  }
}
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
