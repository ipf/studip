jQuery(function(a){a(".smiley-select select").change(function(){a(this).closest("form").submit()});a(".smiley-toggle").on("click",function(c){var b;b=a(this);b.attr({disabled:true});b.addClass("ajax");a.getJSON(b.attr("href"),function(d){a("#layout_container .messagebox").remove();a("#layout_container").prepend(d.message);b.toggleClass("favorite",d.state);b.removeClass("ajax");b.attr({disabled:false})});c.preventDefault()});a('a[href*="admin/smileys/edit"], a[href*="admin/smileys/upload"]').on("click",function(c){var b;b=a(this).attr("href");a('<div class="smiley-modal"/>').load(b,function(){var d;a(this).hide().appendTo("body");d={modal:true,width:a(this).outerWidth()+50,height:a(this).outerHeight()+50,title:a("thead",this).remove().text(),close:function(){a(this).remove()}};a(this).dialog(d)});c.preventDefault()});a(".smiley-modal .button.cancel").on("click",function(b){a(this).closest(".smiley-modal").dialog("close");b.preventDefault()})});