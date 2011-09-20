/* 

	List Expander 
	written by Alen Grakalic, provided by Css Globe (cssglobe.com)
	
*/

this.listexpander = function(){
	
	// edit 
	
	var expandTo = 1; // level up to which you want your lists to be initially expanded. 1 is minimum
	var expandText = "Expand All"; // text for expand all button
	var collapseText = "Collapse All"; // text for collapse all button		
	var listClass = "listexpander" // class name that you want to assign to list(s). If you wish to change it make sure to update the css file as well  
	
	// end edit (do not edit below this line)
	
	this.start = function(){
		var ul = document.getElementsByTagName("ul");
		for (var i=0;i<ul.length;i++){
			if(ul[i].className == listClass){
				create(ul[i]);
				buttons(ul[i])
			};
		};
	};

	this.create = function(list) {	
		var items = list.getElementsByTagName("li");
		for(var i=0;i<items.length;i++){
			listItem(items[i]);
		};
	};	

	this.listItem = function(li){
		if(li.getElementsByTagName("ul").length > 0){
			var ul = li.getElementsByTagName("ul")[0];
			ul.style.display = (depth(ul) <= expandTo) ? "block" : "none";
			li.className = (depth(ul) <= expandTo) ? "expanded" : "collapsed";
			li.over = true;	
			ul.onmouseover = function(){li.over = false;} 
			ul.onmouseout = function(){li.over = true;} 
			li.onclick = function(){
				if(this.over){
					ul.style.display = (ul.style.display == "none") ? "block" : "none";
					this.className = (ul.style.display == "none") ? "collapsed" : "expanded";				
				};
			};
		};		
	};	
	
	this.buttons = function(list){
		var parent = list.parentNode;
		var p = document.createElement("p");
		p.className = listClass;
		var a = document.createElement("a");
		a.innerHTML = expandText;
		a.onclick = function(){expand(list)};
		p.appendChild(a);
		var a = document.createElement("a");
		a.innerHTML = collapseText;
		a.onclick = function(){collapse(list)};
		p.appendChild(a);
		parent.insertBefore(p,list);
	};
	
	this.expand = function(list){
		li = list.getElementsByTagName("li");
		for(var i=0;i<li.length;i++){
			if(li[i].getElementsByTagName("ul").length > 0){
				var ul = li[i].getElementsByTagName("ul")[0];
				ul.style.display = "block";
				li[i].className = "expanded";
			};
		};
	};
	
	this.collapse = function(list){
		li = list.getElementsByTagName("li");
		for(var i=0;i<li.length;i++){
			if(li[i].getElementsByTagName("ul").length > 0){
				var ul = li[i].getElementsByTagName("ul")[0];
				ul.style.display = "none";
				li[i].className = "collapsed";
			};
		};
	};
	
	this.depth = function(obj){
		var level = 1;
		while(obj.parentNode.className != listClass){
			if (obj.tagName == "UL") level++;
			obj = obj.parentNode;
		};
		return level;
	};	
	
	start();
	
};

window.onload = listexpander;
