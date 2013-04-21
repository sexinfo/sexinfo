
/**
 * Constructor
 */
matchImgEditor = function (parentDiv, config) {
  var imageEditor;

  this.config = config || {};
  this.tabs = {};

  var matchImgEditor = this;
  this.mainElement = jQuery('<div class="matchImgEditor"></div>');

  config.imageURL = config.imageURL;
  if (config.imageURL) {
    this.hotspotEditorImage = jQuery('<img style="border: 1px solid #ccc" src="' + config.imageURL + '" />');
  }
  else {
    // config.imageURL not defined, aborting.
    return
  }

  /* add general elements */
  if (!this.config.insertBeforeElement) {
    parentDiv.prepend(this.mainElement);
  }
  else {
    this.mainElement.insertBefore(this.config.insertBeforeElement);
  }

  /* image elements */
  this.imageEditor = jQuery('<div></div>');

  this.imageThumbnail = jQuery('<img style="border: 1px solid #ccc" src="' + config.imageURL + '" />');
  this.imageThumbnail.css({
    "max-height": "100px",
    "display": "block"
  });

  /* hotspot elements */
  this.hotspotEditor         = jQuery('<div class="xmlJsonEditor_attribute" ></div>');
  this.hotspotEditorCanvas   = jQuery('<div style="overflow:hidden;position:relative;border:#999999 1px solid; margin: 10px; padding:0;background-color:white;"></div>');
  this._showImageInCanvas();
  this.hotspotEditor.append(this.hotspotEditorCanvas);

  if (this.config.mode === 'edit hotspot') {
    this.mainElement.append(this.hotspotEditor);
    this.mainElement.append(this.imageEditor);

    if (config.imageFormElements) {
      config.imageFormElements.splice(1, 0, this.hotspotEditor);
      imageEditor = this.imageEditor;
      jQuery(config.imageFormElements).each(function () {
        imageEditor.append(jQuery(this));
      });
    }
  }

  if (this.config.mode === 'view hotspots') {
    this.mainElement.append(this.hotspotEditor);
    this.mainElement.append(this.imageEditor);

    if (config.imageFormElements) {
      config.imageFormElements.splice(1, 0, this.hotspotEditor);
      imageEditor = this.imageEditor;
      jQuery(config.imageFormElements).each(function () {
        imageEditor.append(jQuery(this));
      });
    }
  }

  /* bring controls to life */
  this.gr                    = new jsGraphics(this.hotspotEditorCanvas[0]);
  this.pen                   = new jsPen(new jsColor('#0000FF'), 1);
  this.points                = new Array();
  this.pointsDivs            = new Array();
  this.pointDivWithFocus     = false;
  this.hotspotXMLTemplate    = '<hotspot identifier="$1" shape="$2" coords="$3" />';
  this.hotspotEditorCanvas.data('matchImgEditor', this);

  this.listeners = {
    "addhotspot": [],
    "ondrawshape": []
  };

  this.hotspotEditorCanvas.mousemove(function (e) {
    matchImgEditor._getMouseXY(e);
  });

  if (this.config.mode !== 'view hotspots') {
    this.hotspotEditorCanvas.click(function (e) {
      matchImgEditor._clickEvent(e);
    });
  }

  this.currentShape = 'poly';
  this.mouseX = 0;
  this.mouseY = 0;
  this.shapes = new Array();
  this._setShapeDrawed(false);
};

matchImgEditor.prototype._selectTab = function (tabElement) {
  var tabname, showTabs = {};

  /* inactivate all tabs */
  jQuery(this.mainElement).children().filter('.xmlJsonEditor_tab').removeClass('xmlJsonEditor_activeTab');
  jQuery(this.mainElement).children().filter('.xmlJsonEditor_tab').addClass('xmlJsonEditor_inactiveTab');

  /* show provided tab */
  tabElement.removeClass('xmlJsonEditor_inactiveTab');
  tabElement.addClass('xmlJsonEditor_activeTab');

  for (tabname in this.tabs) {
    this.tabs[tabname].hide();
    if (tabElement.attr('rel') == tabname) {
      showTabs[tabname] = this.tabs[tabname];
    }
  }
  for (tabname in showTabs) {
    this.tabs[tabname].show();
  }
}

/**
 * Saves current shape in local array
 **/
matchImgEditor.prototype.addShape = function () {
  var newShape = {
    "identifier" : this._getIdentifier(),
    "points"     : this.points,
    "type"       : this.currentShape
  };
  this.shapes[this.shapes.length] = newShape;
  this.notifyListeners('addhotspot', this.getShapeOutputData());
};


matchImgEditor.prototype.loadShape = function (data, doNotClearCanvas) {
  doNotClearCanvas = doNotClearCanvas || false;
  var matchImgEditor = this;
  var coords;
  var numberOfCoords;
  var coordsAsArray = [];
  var i;

  if (!doNotClearCanvas) {
    this.clearCanvas();
  }
  else {
    this.points = [];
  }

  if (!jQuery.isArray(data.coords)) {
    coords = data.coords.split(',');
    switch (data.shape) {
      case 'circle':
        var x = parseInt(coords[0]);
        var y = parseInt(coords[1]);
        var x_radius = x + parseInt(coords[2]);
        var y_radius = y;

        /* circle center */
        coordsAsArray.push({
          "x": x,
          "y": y
        });

        /* circle border */
        coordsAsArray.push({
          "x": x_radius,
          "y": y_radius
        });
        break;

      default:
        numberOfCoords = coords.length;
        for (i = 0; i < numberOfCoords; i = i + 2) {
          coordsAsArray.push({
            "x": coords[i],
            "y": coords[i+1]
          });
        }
        break;
    }
  }
  else {
    coordsAsArray = data.coords;
  }

  /* draw the shape */
  this.setCurrentShape(data.shape);
  jQuery(coordsAsArray).each(function () {
    matchImgEditor.drawPoint(this.x, this.y, doNotClearCanvas);
  })

  /* add text */
  if (data.title) {
    var containerDiv = this.gr.drawText(data.title, new jsPoint(coordsAsArray[0].x, coordsAsArray[0].y));
    jQuery(containerDiv).addClass('xmlJsonEditor_hotspot_tag');
  }

  this.drawShape();
}

/**
 * Gets mouse x and y position and sets internal variables
 */
matchImgEditor.prototype._getMouseXY = function (e) {
  //because of event: 'this' is the DOM element
  if (document.all)
  {
    this.mouseX = e.clientX + document.body.parentElement.scrollLeft;
    this.mouseY = e.clientY + document.body.parentElement.scrollTop;
  }
  else {
    this.mouseX = e.pageX;
    this.mouseY = e.pageY;
  }

  if (this.mouseX < 0) {
    this.mouseX = 0;
  }
  if (this.mouseY < 0) {
    this.mouseY = 0;
  }

  this.mouseX = this.mouseX - this.hotspotEditorCanvas.offset().left;
  this.mouseY = this.mouseY - this.hotspotEditorCanvas.offset().top;

  return true;
}


/**
 * Draws a point to the canvas and draws shape in some cases
 * @param integer mouseX
 *   (optional) The x position of the point. Current mouse position is taken when omitted.
 * @param integer mouseY
 *   (optional) The y position of the point. Current mouse position is taken when omitted.
 * @param boolean keepOldContent
 *   Keep old content on the canvas, or clear it out?
 */
matchImgEditor.prototype.drawPoint = function (mouseX, mouseY, keepOldContent) {
  keepOldContent = keepOldContent || false;
  var newDiv;

  /* clear canvas if this would be the third point for circle/rectangle */
  if (!keepOldContent && this._getShapeDrawed() == true) {
    this.clearCanvas();
  }

  /* determine position */
  mouseX = parseInt(mouseX) || parseInt(this.mouseX);
  mouseY = parseInt(mouseY) || parseInt(this.mouseY);

  /* draw a point */
  newDiv = jQuery(this.gr.fillRectangle(new jsColor("green"), new jsPoint(mouseX - 2, mouseY - 2), 5, 5));
  newDiv.data('matchImgEditor', this);

  //give point focus when cursor hovers it
  newDiv.mouseover(function () {
    this.firstChild.style.backgroundColor = 'red';
    jQuery(this).data('matchImgEditor')._setPointDivWithFocus(this)
  });

  //remove point focus when cursor leaves it
  newDiv.mouseout(function () {
    this.firstChild.style.backgroundColor = 'green';
    jQuery(this).data('matchImgEditor')._unsetPointDivWithFocus()
  });

  /* keep track of points and divs */
  this.points[this.points.length]     = new jsPoint(mouseX, mouseY);
  this.pointsDivs[this.points.length] = newDiv[0];

  /* draws shape if second point for circle/rectangle */
  if (this.currentShape=='circle' || this.currentShape=='rect') {
    if (this.points.length==2) {
      this.drawShape();
      return;
    }
  }
}

/**
 * Deletes a point or draws poly when first point clicked twice
 * @param DOMObject div
 *   The div which is clicked
 */
matchImgEditor.prototype.pointClickedTwice = function (div) {
  var divCount;
  var i;

  /* clear canvas and return when there is a shape on the canvas */
  if (this._getShapeDrawed()==true) {
    this.clearCanvas();
    this.drawPoint();
  }

  divCount = this.points.length;

  /* see which point is clicked */
  for(i = 1; i <= divCount; i++) {
    if(div == this.pointsDivs[i]) {
      /* draw shape when polygon and first point clicked */
      if (i == 1 && this.currentShape == "poly") {
        this.drawShape();
        return;
      }
    }
  }
}

/**
 * Sets which point has focus
 * @param DOMObject div
 *   The div with focus
 */
matchImgEditor.prototype._setPointDivWithFocus = function (div) {
  this.pointDivWithFocus = div;
}

/**
 * Gets which point has focus
 * @param DOMIbject div
 *   The div with focus
 */
matchImgEditor.prototype._getPointDivWithFocus = function (div) {
  return this.pointDivWithFocus;
}

/**
 * Removes which point has focus value
 */
matchImgEditor.prototype._unsetPointDivWithFocus = function () {
  this.pointDivWithFocus = false;
}

/**
 * The user clicked the canvas
 */
matchImgEditor.prototype._clickEvent = function () {

  /* see whether mouse is on old point */
  if (this.pointDivWithFocus == false || this.pointDivWithFocus == undefined) {
    //no: create new point
    this.drawPoint();
  }
  else {
    //yes: remove old point or draw poly
    this.pointClickedTwice(this._getPointDivWithFocus());
  }
}

/**
 * Draw a shape based on the shape selected and the points
 */
matchImgEditor.prototype.drawShape = function () {
  var returnData = {};
  this._setShapeDrawed(true);

  switch (this.currentShape) {
    case 'poly':
      this.gr.drawPolygon(this.pen, this.points);
      returnData.coords = this.points;
      break;

    case 'circle':
      this.gr.drawCircle(this.pen, this.points[0], this._getHypotenusa());
      this.currentShape = 'circle';
      returnData.coords = this.points[0];
      returnData.radius = this._getHypotenusa();
      break;

    case 'rect':
      var x = this.points[0].x < this.points[1].x ? this.points[0].x : this.points[1].x;
      var y = this.points[0].y < this.points[1].y ? this.points[0].y : this.points[1].y;

      var width = Math.abs(this.points[0].x - this.points[1].x);
      var height = Math.abs(this.points[0].y - this.points[1].y);

      this.gr.drawRectangle(this.pen, new jsPoint(x, y), width, height);

      returnData.coords = this.points;
      break;
  }
  returnData.shape = this.currentShape;
  this.notifyListeners('onDrawShape', returnData);
}

/**
 * Sets the shape to draw
 * @param string shape
 *   The shape: 'rect', 'poly' or 'circle'
 */
matchImgEditor.prototype.setCurrentShape = function (shape) {
  this.currentShape = shape;
}

/**
 * Calculates the hypothenusa of a triangle (the radius of a circle)
 */
matchImgEditor.prototype._getHypotenusa = function () {
  return Math.round(Math.sqrt(Math.pow(((this.points[0].x - this.points[1].x)), 2) + Math.pow(((this.points[0].y - this.points[1].y)), 2)));
}

/**
 * Add the xml for the current shape to the textarea
 */
matchImgEditor.prototype.getShapeOutputData = function () {
  var returnObj = {};
  returnObj.id     = this._getIdentifier();
  returnObj.shape   = this.currentShape;
  returnObj.coords = '';

  var i;
  switch(this.currentShape) {
    case 'poly':
      for(i = 0;i < this.points.length; i++)
      {
        returnObj.coords += Math.round(this.points[i].x) + "," + Math.round(this.points[i].y);

        if (i < this.points.length-1) {
          returnObj.coords += ",";
        }
      }

      //txt += this._getHotspotTagXML(id, type, coords)+'\n';
      break;

    case 'circle':
      returnObj.coords = Math.round(this.points[0].x) +  ',' + Math.round(this.points[0].y) + ',' + Math.round(this._getHypotenusa());

      //txt += this._getHotspotTagXML(id, type, coords)+'\n';
      break;

    case 'rect':
      for(i = 0;i < this.points.length; i++)
      {
        returnObj.coords += Math.round(this.points[i].x) + "," + Math.round(this.points[i].y);

        if (i < this.points.length - 1) {
          returnObj.coords += ",";
        }
      }

      //txt += this._getHotspotTagXML(id, type, coords)+'\n';
      break;
  }

  return returnObj;
}

/**
 * Bind to event
 */
matchImgEditor.prototype.bind = function (type, listener) {
  type = type.toLowerCase();
  if (this.listeners[type] != undefined) {
    this.listeners[type].push(listener);
  }
  else {
    alert(Drupal.t("unknown listener type: ") + type);
  }
}

/**
 * Notify all listeners of type "type" that an event occurred, with the
 * given data.
 *
 * @param type
 *   The type of event.
 * @param data
 *   The data of the event.
 */
matchImgEditor.prototype.notifyListeners = function (type, data) {
  var i, value, returnValue;
  type = type.toLowerCase();
  if (this.listeners[type]) {
    for (i = 0; i < this.listeners[type].length; i++) {
      value = this.listeners[type][i].call(null, data);
      returnValue = returnValue === false ? false : value;
    }
  }

  return returnValue;
}


/**
 * Creates hotspot XML
 */
matchImgEditor.prototype._getHotspotTagXML = function (id, type, coords) {
  var xml = this.hotspotXMLTemplate;
  xml = xml.replace('$1', id).replace('$2', type).replace('$3', coords);
  return xml;
}

/**
 * Clears all points/shapes and refreshes the image
 */
matchImgEditor.prototype.clearCanvas = function () {
  this.gr.clear();
  this.points=new Array();
  this._showImageInCanvas();
  this._setShapeDrawed(false);
}

matchImgEditor.prototype._setShapeDrawed = function (value) {
  this.shapeDrawed = value;
}

matchImgEditor.prototype._getShapeDrawed = function () {
  return this.shapeDrawed;
}

matchImgEditor.prototype._showImageInCanvas = function () {
  this.hotspotEditorCanvas.html(this.hotspotEditorImage);
}
