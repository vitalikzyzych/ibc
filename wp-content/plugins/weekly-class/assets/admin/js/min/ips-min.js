!function($){"use strict";function t(){$(".image-upload-button").click(function(t){var e=$(this).parent(),i=$(this);t.preventDefault();var l=wp.media({title:i.data("upload-title"),button:{text:i.data("upload-button")},multiple:!1}).on("select",function(){var t=l.state().get("selection"),i=t.first().toJSON();$("input[type=text]",e).val(i.url),$("input[type=hidden]",e).val(i.id).trigger("change"),e.hasClass("upload_file")||($("img",e).length>0?$(".image-preview",e).attr("src",i.url):($('<img src="'+i.url+'" class="image-preview">').insertBefore($(":last-child",e)),$(".image-clear-button",e).attr("style","display:inline-block")))}).open()})}function e(){$(".video-upload-button").click(function(t){var e=$(this).parent(),i=$(this);t.preventDefault();var l=wp.media({title:i.data("upload-title"),button:{text:i.data("upload-button")},multiple:!1}).on("select",function(){var t=l.state().get("selection"),i=t.first().toJSON();$("input[type=text]",e).val(i.url),e.hasClass("upload_file")||$(".video-clear-button",e).attr("style","display:inline-block")}).open()})}$(document).ready(function(){$(".image-clear-button").click(function(t){$(this).siblings("input[type=text]").val(null),$(this).siblings("input[type=hidden]").val(null).trigger("change"),$(this).siblings(".image-preview").remove(),t.preventDefault()}),$(".video-clear-button").click(function(t){$(this).siblings("input[type=text]").val(null),t.preventDefault()});var i=new HashTabber;i.run(),$(".color-picker").wpColorPicker();var l=100;$("#class-settings-wrapper > ul > li[role*=tab]").each(function(){l+=$(this).outerHeight()}),t(),e()})}(jQuery);