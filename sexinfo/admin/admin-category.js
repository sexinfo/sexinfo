var SexInfo;
SexInfo = SexInfo || {};
SexInfo.Category = (function() {
  var nowMoving = false;
  var movingID = 0;
  var movingName = '';
  
  var show = function(el) {
    $(el).removeClassName('hidden');
  };
  
  var showTotal = function(el) {
    el = $(el);
    do {
      show(el);
    } while(el = el.up('.hidden'));
  };
  
  var hide = function(el) {
    $(el).addClassName('hidden');
  };
  
  var toggle = function(el) {
    el = $(el);
    if(el.hasClassName('hidden')) {
      show(el);
    } else {
      hide(el);
    }
  };
  
  var moveFormTo = function(el) {
    el = $(el);
    
    var form = $('newform-li').remove();
    el.insert(form);
    show('newform-li');
    $('newform-button').enable();
    $('newform-name').activate();
    
    // retention of observers on the element when it is removed and reinserted
    // seems unpredictable, so we redo this every time
    watchForm();
  };
  
  var submitEvent = function(event) {
    event.stop();
    SexInfo.Category.createNew();
  };
  
  var watchForm = function() {
    $('newform').stopObserving();
    $('newform').observe('submit', submitEvent);
  };
  
  return {
    showSubList : function(catID) {
      show('sublist-' + catID);
      show('sublist-' + catID + '-hide');
      hide('sublist-' + catID + '-show');
    },
    
    hideSubList : function(catID) {
      hide('sublist-' + catID);
      hide('sublist-' + catID + '-hide');
      show('sublist-' + catID + '-show');
      
      $$('.highlight').each(function(el) {
        showTotal(el);
      });
    },
    
    showCatList : function(typeID) {
      show('catlist-' + typeID);
      show('catlist-' + typeID + '-hide');
      hide('catlist-' + typeID + '-show');
    },

    hideCatList : function(typeID) {
      hide('catlist-' + typeID);
      hide('catlist-' + typeID + '-hide');
      show('catlist-' + typeID + '-show');
      
      $$('.highlight').each(function(el) {
        showTotal(el);
      });
    },

    expandAll : function() {
      $$('.collapse').each(function(el) {
        hide(el);
      });
      
      $$('.expand').each(function(el) {
        show(el);
      });
    },

    collapseAll : function() {
      $$('.expand').each(function(el) {
        hide(el);
      });
      
      $$('.collapse').each(function(el) {
        show(el);
      });
      
      $$('.highlight').each(function(el) {
        showTotal(el);
      });
    },

    toggleOpts : function(catID) {
      toggle('opts-' + catID);
    },
    
    showAll : function() {
      $$('.opts').each(function(el) {
        show(el);
      });
    },
    
    hideAll : function() {
      $$('.opts').each(function(el) {
        hide(el);
      });
    },
    
    move : function(catID, typeID) {
      var wasMoving = movingID;
      this.stopMoving();
      
      if(wasMoving === catID) { // 'cancel'
        return;
      }
      
      nowMoving = true;
      movingID = catID;
      movingName = $('name-' + catID).innerHTML;
      
      $$('.move-' + typeID).each(function(link) {
        if(link.identify() !== 'movecat-' + catID && // can't move a category to itself
           !link.descendantOf($('sublist-' + catID))) { // or its children
          show(link);
        }
      });
      
      $('movethis-' + catID).update('[cancel]');
      $('cat-' + catID).addClassName('highlight');
    },

    stopMoving : function() {
      if(nowMoving) {
        $('movethis-' + movingID).update('[move]');
        $('cat-' + movingID).removeClassName('highlight');
        $$('.move').each(function(link) {
          hide(link);
        });
      }
      nowMoving = false;
      movingID = 0;
      movingName = '';
    },
    
    moveToCat : function(catID) {
      if(nowMoving) this.stopMoving();
    },
    
    moveToType : function(typeID) {
      if(nowMoving) this.stopMoving();
    },
    
    remove : function(catID) {
      
    },
    
    newInType : function(typeID) {
      var form;
      this.showCatList(typeID);
      $('newform-type').value = typeID;
      $('newform-parent').value = '';
      
      moveFormTo('catlist-' + typeID);
    },

    newInCat : function(catID) {
      var form;
      this.showSubList(catID);
      $('newform-type').value = '';
      $('newform-parent').value = catID;
      
      moveFormTo('sublist-' + catID);
    },
    
    cancelNew : function() {
      hide('newform-li');
      $('newform-button').disable();
    },
    
    createNew : function() {
      if($F('newform-name').blank()) {
        alert("Please enter a name for the new category.");
        return;
      }
      
      new Ajax.Request('/sexinfo/admin/_new-category.php', {
        asynchronous: true,
        method: 'post',
        parameters: $('newform').serialize(),
        
        onCreate: function() {
          hide('newform-cancel');
          show('newform-waiting');
          $('newform').disable();
        },
        
        onSuccess: function(transport) {
          window.location.href =
            window.location.href.split('?')[0] + 
            "?message=" +
            encodeURIComponent(transport.responseText);
        },
        
        onFailure: function(transport) {
          alert('Error ' + transport.status + ". Response from server:\n" + transport.responseText);
          show('newform-cancel');
          hide('newform-waiting');
          $('newform').enable();
          SexInfo.Category.cancelNew();
        },
      });
    },
    
    addContentTo : function(catID) {
      new Ajax.Request('/sexinfo/admin/_add-content.php', {
        asynchronous: true,
        method: 'post',
        parameters: { 'catID': catID },

        onSuccess: function(transport) {
          var id = parseInt(transport.responseText);
          window.location.href = 'admin-content.php?action=edit&id=' + id;
        },

        onFailure: function(transport) {
          alert('Error ' + transport.status + ". Response from server:\n" + transport.responseText);
        },
      });
    }
  };
})();