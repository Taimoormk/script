$(function() {
    $('#search_topics').fastLiveFilter('#topics_list');
});
function authors_lookup(inputString) {
				if(inputString.length < 3) {
						$('#searchresults').hide();
						$('#authors').show();
				} else {
						$.post("ajax.php?do=ajax_author_search", {queryString: ""+inputString+""}, function(data){
							if(data.length > 0) {
							$('#authors').hide();
							$('#searchresults').show();
							$('#searchresults').html(data);
						}
						});
				}
			} 
			
$(function() {
$(".generate-random-quote").click(function() {
$(".generate-random-quote i").addClass('fa-spin');
$.get("ajax.php?do=ajax_random_quote", function(data){
$('#random-quote').html(data);
$(".generate-random-quote i").removeClass('fa-spin');
});	
});
});

$(document).ready(function(){
var letter = $("a.selected").attr('scope');
$('.searchresults').hide();
$('.authors').show();
$(".authors").html('<div class="loader"><span class="fa fa-spinner fa-spin"></span></div>');
$.get("ajax.php?do=ajax_author_by_letter", { "letter": letter, "page": 1 }, function(result){
$('.authors').html(result);
});	
});

$(function() {
$(".letter").click(function() {
var letter = $(this).attr('scope');
$(".letters a.selected").removeClass('selected');
$(this).addClass('selected');
$.get("ajax.php?do=ajax_author_by_letter", { "letter": letter, "page": 1 }, function(data){
$('.searchresults').hide();
$('.authors').show();
$('.authors').html(data);
$(".authors div.loader").remove();
});	
});
});

function authors_page_lookup(inputString) {
				if(inputString.length < 3) {
						$('.searchresults').hide();
						$('.authors').show();
				} else {
						$.get("ajax.php?do=ajax_search_author_page", {"q": inputString, "page": 1}, function(data){
							if(data.length > 0) {
							$('.authors').hide();
							$('.searchresults').show();
							$('.searchresults').html(data);
						}
						});
				}
			} 
			