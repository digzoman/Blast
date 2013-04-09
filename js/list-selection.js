 /*
	Ali Khaladj
	MultiSelector for Blast.
	Feb 2013
 */
 
 jQuery.listSelection = function(el,o){
			
	var setOptions = function(){
		if ( !o ){
		   o  = {
			width:"100px",
			height:"100px"
		   }
		}
	}
	
	var getContent = function(obj){
		isearch = $(".list_selection_serach").children("input");
		if (obj.hasClass("active" ) )
			switch(obj.attr("rel")){
				case "all":
					el.children("li").removeClass("hidden");
					break;
				case "selected":
					el.children("*:not(.selected)").addClass("hidden");
					break;
			}
		isearch.val(search_text).css("color","#DBD9DA");
	}
	
	
	var wrapElements = function(){	
		el.wrap("<div class='list_selection_items'></div>");
		$('.list_selection_items').wrap("<div id='list_selection_wrapper'></div>");
		el.children("li").css("height",o.height+"px");
		el.children("li").css("width",o.width+"px");		
		
		allowance = ( o.cols < 4 ) ? 1.15 : 1.09;
		iwidth= parseInt(o.width, 10) * ( parseInt(o.cols, 10) *allowance)+"px" ;
		
		wrapper.css("width","100%");		
		el.parent(".list_selection_items").before("<div class='list_selection_buttons' >&nbsp</div>");
		iheight= parseInt(o.height, 10) * ( parseInt(o.rows, 10)+ 0.7)+"px" ;		
		el.parent(".list_selection_items").css("height",iheight);
	}
	
	
	var render = function(){		
		wrapElements();
		$.each(el.children("li"),function(i){			
			el.children("li").children(".ticked").html("<img src='img/_checked.png'>");
		});
		createButtons();
		createSearch();
	}
	

	
	var visibilityCheck =function(){
		switch(curButton()){
			case "selected":
				el.children("*:not(.selected)").addClass("hidden");
			break;
			
		}
	}
	
	var curButton = function(){
		var cur="";
		$.each( $(".buttons").children("li"),function(i,obj ){
			obj=$(obj);			
			if ( obj.hasClass("active" ) )
				cur = obj.attr("rel");			
		});
		return cur;
	}
	
	var addToSelected = function(obj){
		counter = $(".select_count");
		if (obj.hasClass("selected")) {
            counter.text(parseInt(counter.text(), 10) + 1);
		}else{
			counter.text(parseInt(counter.text(), 10) - 1);
		}
		visibilityCheck();
	}

	
	var createButtons = function(){
		buttons="<ul class='buttons'>"+
				"<li rel='all' class='active'>All</li>"+
				"<li rel='selected' >Selected (<span class='select_count'>0</span>)</li>"+
				"</ul>";
		$("html").find(".list_selection_buttons").html(buttons);
	}
	
    
	var createSearch = function(){
		search="<div class='list_selection_serach'>Search : <input type='text' name='search' id='search' value='"+search_text+"'>";
		oprations="<div class='list_selection_op'><a class='list_selection_ubuttons' rel='selectall'>Select All</a><a class='list_selection_ubuttons' rel='unselectall'>Unselect All</a><a class='list_selection_ubuttons' rel='recommanded'>Select Recommanded</a></div></div>";
		$('.list_selection_buttons').before(search+oprations);
	}
	
	
	var  filter = function (selector, query) {
		query=$.trim(query); //trim white space
		query=query.replace(/ /gi, '|'); //add OR for regex
		$(selector).each(function() {
		  ($(this).text().search(new RegExp(query, "i")) < 0) ? $(this).addClass('hidden') : $(this).removeClass('hidden');
		});
	}
	
	//Handel all events here
	var handelEvents = function(){
		$.each(el.children("li"),function(i,obj){
			obj=$(obj);
			obj.click(function(){
				obj.toggleClass("selected");
				obj.children(".ticked").toggleClass("false");
				addToSelected(obj);	
			});
		});
		
		$.each($(".buttons").children("li"),function(i,obj){
			obj=$(obj);
			obj.click(function(){
				$(".buttons").children("*").removeClass("active");
				obj.addClass("active");
				getContent(obj);		
			});
		});		
		
		$.each($(".list_selection_op").children("a"),function(i,obj){
			obj=$(obj);
			obj.click(function(){
				switch( obj.attr("rel")){
					case "selectall":
						$.each(el.children("li"),function(i,obj){
							obj=$(obj);
							obj.addClass("selected");
							obj.children(".ticked").removeClass("false");
							$(".select_count").text(i+1);		
						});
					break;
					case "unselectall":
						$.each(el.children("li"),function(i,obj){
							obj=$(obj);
							obj.removeClass("selected");
							obj.children(".ticked").addClass("false");
							$(".select_count").text(0);		
						});
					break;
				}
			});
			
		});
		
		$.each($(".list_selection_serach").children("input"),function(i,obj){
			obj=$(obj);
			obj.focus(function(){
				if (obj.val() == search_text )
					obj.val("").css("color","gray");
			});

			obj.blur(function(){
				if (obj.val() == '' )
					obj.val(search_text).css("color","#DBD9DA");;

			});

			obj.keyup(function(){
				switch(curButton()){
					case "all":
						filter(el.children("li"),obj.val());
					break;
					case "selected":
						filter(el.children(".selected"),obj.val());
					break;
				}
			});
		});		
	}
	
	//Main start here
	if (typeof(el) != 'object')  el = $(el);
	wrapper = $("#list_selection_wrapper");
	search_text= 'Start typing a keyword';

	
	setOptions();
	render();
	handelEvents();
 }

 
 jQuery.listSelection.getSelection= function(el,o){

	var getSelectedItems = function(){
		var chosen= new Array();
		$.each(el.children(".selected").find(o),function(i,obj){
			obj = $(obj);
			chosen[i] = obj.text();
		});
		return chosen;
	}
	if (typeof(el) != 'object')  el = $(el);
	return getSelectedItems();	
}
