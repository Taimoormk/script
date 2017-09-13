$(function(){
$('.seo-keywords').tagsinput({
  confirmKeys: [13, 44]
});
});

$(document).ready(function(){ 
	$(function() {
		$("#sort_links ul").sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable("serialize") + '&action=sort_links'; 
			$.post("ajax.php", order, function(theResponse){}); 															 
		}								  
		});
	});
});
$(document).ready(function(){ 
	$(function() {
		$("#sort_pages ul").sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable("serialize") + '&action=sort_pages'; 
			$.post("ajax.php", order, function(theResponse){
				
			}); 															 
		}								  
		});
	});
});
function changePage(newLoc)
{
   nextPage = newLoc.options[newLoc.selectedIndex].value
		
   if (nextPage != "")
   {
      document.location.href = nextPage
   }
}

$(function(){
$('[data-toggle="tooltip"]').tooltip({html:true});
$('[data-toggle="popover"]').popover({html:true});
});



$(function() {
$(".count_author_quotes").click(function() {
var id = $(this).attr("id");
var dataString = 'id='+ id +'&action=count_author_quotes';
$("a#"+id+" span").removeClass('fa fa-bar-chart');		
$("a#"+id+" span").addClass('fa fa-spinner fa-spin');
$.ajax({
   type: "POST",
   url: "ajax.php",
   data: dataString,
   dataType: "html",
   cache: false,
   success: function(result)
   {
	$("a#"+id+" span").removeClass('fa fa-spinner fa-spin');		
	$("a#"+id+" span").addClass('fa fa-bar-chart');
	$("#author-quotes-"+id).text(result);
  }  
  });  
});
});

$(function() {
$(".count_topic_quotes").click(function() {
var id = $(this).attr("id");
var dataString = 'id='+ id +'&action=count_topic_quotes';
$("a#"+id+" span").removeClass('fa fa-bar-chart');		
$("a#"+id+" span").addClass('fa fa-spinner fa-spin');
$.ajax({
   type: "POST",
   url: "ajax.php",
   data: dataString,
   dataType: "html",
   cache: false,
   success: function(result)
   {
	$("a#"+id+" span").removeClass('fa fa-spinner fa-spin');		
	$("a#"+id+" span").addClass('fa fa-bar-chart');
	$("#topic-quotes-"+id).text(result);
  }  
  });  
});
});

function ConfirmLogOut() {
	if(confirm('Proceed To Logout ?'))
		{
		document.location.href = 'logout.php';
	} else {
		confirm.close();
	}
}
