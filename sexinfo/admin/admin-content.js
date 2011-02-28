var SexInfo;
SexInfo = SexInfo || {};
SexInfo.Content = (function() {
  var editAreaLoaded = false;
  var currType;
  
  var keepChanges = function() {
    $('alter-places-input').value = 1;
  };
  
  var ignoreChanges = function() {
    $('alter-places-input').value = 0;
  };
  
  var showEditor = function() {
    $('existing-placements').hide();
    $('edited-placements').show();
  };
  
  var hideEditor = function() {
    $('edited-placements').hide();
    $('existing-placements').show();
  };
  
  var watchForChanges = function(select) {
    $(select).observe('change', function(event) {
      changedCat(this.id);
    });
  }
  
  var changedCat = function(id) {
    var values, placeID, place, catID, cat, tag, nextTag, nextName;
    keepChanges();
    values = id.split('-');
    
    // determine the place and category positions from the id
    placeID = parseInt(values[1]);
    catID = parseInt(values[2]);
    
    // calculate more interesting values
    place = $('place-' + placeID);
    cat = $F(id);
    tag = placeID + '-' + catID;
    nextTag = placeID + '-' + (catID + 1);
    nextName = 'cat[' + placeID + ']' + '[' + (catID + 1) + ']';
    
    // remove any bridges and cats following this one in the same place
    place.childElements().each(function(child) {
      if(child.hasClassName('bridge')) {
        if(child.id.split('-')[2] >= catID) Element.remove(child);
      }
      else if(child.hasClassName('cat-select') || child.hasClassName('loading')) {
        if(child.id.split('-')[2] > catID) Element.remove(child);
      }
    });
    
    // now add a bridge and another select, if needed
    // (i'm calling the little ' > ' thing between categories a 'bridge')
    if(cat > 0) {
      place.insert('<span class="bridge" id="bridge-' + tag + '"> &gt; </span>');
      place.insert('<span class="loading" id="loading-' + nextTag + '">Loading...</span>');
      
      new Ajax.Request('_category-options.php', {
        method: 'get',
        parameters: {
          id: cat,
          type: currType
        },
        
        onSuccess: function(transport) {
          var html;
          html = '<select class="cat-select" name="' + nextName + '" id="cat-' + nextTag + '">';
          html += transport.responseText;
          html += '</select>';
          
          Element.replace('loading-' + nextTag, html);
          
          watchForChanges('cat-' + nextTag);
        },
        
        onFailure: function(transport) {
          $('loading-' + nextTag).addClassName('error').update('Server-side error! Retry and investigate.');
        }
      });
    }
  };
  
  var changedType = function(type) {
    if(confirm("This will lose any changes you've made to categories. Are you sure?")) {
      keepChanges();
      currType = type;
      deleteAllPlacements();
    }
    else {
      // revert it
      $('type-select').value = currType;
    }
  };
  
  var deleteAllPlacements = function() {
    $$('.place').each(function(place) {
      Element.remove(place);
    });
  }
  
  return {
    editPlacements : function() {
      showEditor();
      
      if(!editAreaLoaded) this.resetEditArea();
      keepChanges();
    } ,
    
    resetEditArea : function() {
      ignoreChanges();
      currType = $F('known-type-input');
      $('edited-placements').update('Loading...<br /><a href="javascript:SexInfo.Content.cancelEdits();">[cancel]</a>');
      
      new Ajax.Request('_placement-editor.php', {
        method: 'get',
        parameters: {
          id: $F('id-input'),
          type: currType
        },
        
        onSuccess: function(transport) {
          $('edited-placements').update(transport.responseText);
          
          $('type-select').observe('change', function(event) {
            changedType($F(this));
          });
          
          $$('.cat-select').each(function(select) {
            watchForChanges(select);
          });
          
          showEditor();
          editAreaLoaded = true;
          keepChanges();
        },
        
        onFailure: function(transport) {
          $('edited-placements').update();
          editAreaLoaded = false;
          ignoreChanges();
          hideEditor();
          alert('Server-side error! Retry and investigate.');
        }
      });
    } ,
    
    confirmResetEditArea : function() {
      if(confirm("This will lose any changes you've made to categories. Are you sure?")) {
        this.resetEditArea();
      }
    } ,
    
    cancelEdits : function() {
      ignoreChanges();
      hideEditor();
    } ,
    
    addPlacement : function() {
      var placeID = 0, row, tag, name;
      keepChanges();
      
      // find the lowest place number that's not in use
      while($('place-' + placeID)) { ++placeID; }
      
      tag = placeID + '-0';
      name = 'cat[' + placeID + '][0]';
      
      row = '<div class="place" id="place-' + placeID + '">';
      row += '<a href="javascript:SexInfo.Content.deletePlacement(' + placeID + ');">[-]</a> ';
      row += '<span class="loading" id="loading-' + tag + '">Loading...</span>';
      row += '</div>';
      
      $('places').insert(row);
      
      new Ajax.Request('_category-options.php', {
        method: 'get',
        parameters: {
          id: 0,
          type: currType
        },
        
        onSuccess: function(transport) {
          var html;
          html = '<select class="cat-select" name="' + name + '" id="cat-' + tag + '">';
          html += transport.responseText;
          html += '</select>';
          
          Element.replace('loading-' + tag, html);
          
          watchForChanges('cat-' + tag);
        },
        
        onFailure: function(transport) {
          $('loading-' + tag).addClassName('error').update('Server-side error! Retry and investigate.');
        }
      });
    } ,
    
    deletePlacement : function(placeID) {
      keepChanges();
      if(confirm("Are you sure you want to remove this line?")) {
        Element.remove('place-' + placeID);
      }
    }
  };
})();