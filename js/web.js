// JavaScript Document
function SetSameHeight(obj1,obj2,obj3) 
{ 
     var h1 = $(obj1).outerHeight(); 
     var h2 = $(obj2).outerHeight(); 
	 var h3 = $(obj3).outerHeight(); 
     var mh = Math.max( h1, h2, h3);
     $(obj1).css({minHeight:mh}); 
     $(obj2).css({minHeight:mh}); 
	  $(obj3).css({minHeight:mh}); 
}
$(function(){
        $('#searchBtn').on('click',function(e){
            e.preventDefault();
            var keyword = $('#keyword').val();
            if($.trim(keyword)==''){
                alert('请输入需要搜索的内容');  
            }
            else{
                $('#searchFrom').submit();
            }
        });
	SetSameHeight('.vListPad'); 
	
	$('.navLi').hover(function () {
		if (!$(this).find(".sonNav").is(":visible")) {
			$(this).find(".sonNav").slideDown(250);
			$(this).addClass('cut');
		}
	}, function () {
		if (!$(this).find(".sonNav").is(":hidden")) {
			$(this).find(".sonNav").slideUp(250);
			$(this).removeClass('cut');
		}
	});
	
	if ($(window).width() < 767) {
			 $('.navLi').unbind("hover");
			 $('.navLi').click(function(){
				$(this).find(".sonNav").slideToggle(300);
				$(this).siblings('li').find(".sonNav").slideUp(300);
			});
		};
	
	$(window).resize(function() {
		if ($(window).width() > 767) {
				$('.navLi').hover(function () {
			if (!$(this).find(".sonNav").is(":visible")) {
				$(this).find(".sonNav").slideDown(250);
				$(this).addClass('cut');
			}
		}, function () {
			if (!$(this).find(".sonNav").is(":hidden")) {
				$(this).find(".sonNav").slideUp(250);
				$(this).removeClass('cut');
			}
		});
			
		};
		
		if ($(window).width() < 767) {
			$('.navLi').unbind("hover");
			 $('.navLi').click(function(){
					$(this).find(".sonNav").slideToggle(300);
					$(this).siblings('li').find(".sonNav").slideUp(300);
				});
		};
	});
	
	
	
});
