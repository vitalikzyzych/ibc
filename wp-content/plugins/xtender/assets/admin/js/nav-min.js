!function($){$(document).ready(function(){function e(i){i.preventDefault();var t=$(this).parent(".field-custom"),a=$(this),n=$(this).siblings(".image-remove");i.preventDefault();var s=wp.media({title:a.data("upload-title"),button:{text:a.data("upload-button")},multiple:!1}).on("select",function(){var i=s.state().get("selection"),a=i.first().toJSON();$("input[type=hidden]",t).val(a.url),t.hasClass("upload_file")||($("img",t).length>0?$(".image-preview",t).attr("src",a.url):($('<img src="'+a.url+'" class="image-preview">').insertBefore(n).on("click",e),$(".image-remove",t).css("display","block"),$(".btn-upload-image",t).css("display","none")))}).open()}$(".btn-upload-image, .image-preview").on("click",e),$(".image-remove").on("click",function(e){e.preventDefault(),$(this).siblings(".image-preview").remove(),$(this).siblings(".btn-upload-image").css("display","block"),$(this).css("display","none")})})}(jQuery);