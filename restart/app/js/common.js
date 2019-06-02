function dropdown_menu_load(e) {
	var category = e.target.getAttribute("data-category");
	$.ajax({
			url: "index.php?route=product/category/getDeviceCategory",
			type: "post",
			beforeSend: function() {
				$(".s-category__links").css("opacity", "0");
				$(".header__menu a").removeClass("menu_active");
				$(e.target).addClass("menu_active");
				$(".s-category__links a").remove();
			},
			data: {
					category_id: category
			},
			dataType: 'json',
			success: function(json) {
				$(".s-category__links").empty();
				for(var item in json['devices']) {
					$(".s-category__links").append("<a href='" + json['devices'][item]['href'] + "'>" + json['devices'][item]['name'] + "</a>");
				}
				$(".s-category__links").css("opacity", "1");
			}
	});
	return false;
}

$(function() {
	$(document).ready(function() {
		var cache = $(".s-category__links").html();
		var stock_category = $(".header__menu a.menu_active");
		$(".s-category__wrap").mouseleave(function() {
			$(".s-category__links").css("opacity", "0");
			$(".s-category__links").html(cache);
			$(".header__menu a").removeClass("menu_active");
			$(stock_category).addClass("menu_active");
      if ($(stock_category).length > 0) {
        $(".s-category__links").css("opacity", "1");
      }
		});
    $(".header__menu li a").mouseover(function(e) {
      dropdown_menu_load(e);
    });

		$("[data-paroller-factor]").paroller();

		$('select').each(function(){
		  var $this = $(this), numberOfOptions = $(this).children('option').length;

		  $this.addClass('select-hidden');
		  $this.wrap('<div class="select ' + $this.attr("data-select") + ' ' + $this.attr("name") +'"></div>');
		  $this.after('<div class="select-styled"></div>');

		  var $styledSelect = $this.next('div.select-styled');
		  $styledSelect.text($this.children('option').eq(0).text());

		  var $list = $('<ul />', {
		      'class': 'select-options' + ' ' + $this.attr('name')
		  }).insertAfter($styledSelect);

		  for (var i = 0; i < numberOfOptions; i++) {
		    var image = '',
						choise = true;
		    if (typeof $this.children('option').eq(i).attr('data-image') != 'undefined' && $this.children('option').eq(i).attr('data-image') != '') {
		      image = '<span class="pic" style="background-image: url(' + $this.children('option').eq(i).attr('data-image') + ');"></span>';
		    }
				if (typeof $this.children('option').eq(i).attr('disabled') != 'undefined' && typeof $this.children('option').eq(i).attr('disabled') != '') {
					choise = false;
				}
		    $list.append("<li rel=" + $this.children('option').eq(i).val() + " choise=" + choise + ">" + image + $this.children('option').eq(i).text() + "</li>");
		  }

		  var $listItems = $list.children('li');

		  $styledSelect.click(function(e) {
		      e.stopPropagation();
		      $('div.select-styled.active').not(this).each(function(){
		          $(this).removeClass('active').next('ul.select-options').hide();
		      });
		      $(this).toggleClass('active').next('ul.select-options').toggle();
		  });

		  $(document).click(function() {
		      $styledSelect.removeClass('active');
		      $list.hide();
		  });
		});
	    $(".hamburger").click(function() {
		  $(".hamburger").toggleClass("is-active");
		});
	});
	// input focus
	$('.input-text').focus(function() {
	  $(this).closest('.input-group-a').addClass('focus');
	});

	// input defocus
	$('.input-text').focusout(function() {
	  $('.input-group-a').removeClass('focus');
	});

	// input keypress focus
	$('.input-text').keyup(function() {
	  if( $(this).val().length > 0 ) {
	    $(this).closest('.input-group-a').addClass('filled');
	  } else {
	    $(this).closest('.input-group-a').removeClass('filled');
	  }
	});

	$('input[name=\'search\']').parent().find('button').on('click', function(e) {
		var self = e.currentTarget;
		var url = $('base').attr('href') + 'index.php?route=product/search';
		var value = $(self).parent().find('input[name=\'search\']').val();

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		location = url;
	});

	$('input[name=\'search\']').on('keydown', function(e) {
		if (e.keyCode == 13) {
			var self = e.currentTarget;
			var url = $('base').attr('href') + 'index.php?route=product/search';
			var value = $(self).parent().find('input[name=\'search\']').val();

			if (value) {
				url += '&search=' + encodeURIComponent(value);
			}

			location = url;
		}
	});
});

$('.s-process__carousel').owlCarousel({
	loop: true,
	nav: true,
	navText: " ",
	smartSpeed: 700,
	items: 1
});

$(".s-process .owl-prev").addClass("nav-button");
$(".s-process .owl-prev").addClass("nav-button4");
$(".s-process .owl-prev").html('<div class="hoverBtn"></div><div class="hoverBtn-bottom"></div>');

$(".s-process .owl-next").addClass("nav-button");
$(".s-process .owl-next").addClass("nav-button5");
$(".s-process .owl-next").html('<div class="hoverBtn"></div><div class="hoverBtn-bottom"></div>');

$(document).on("click", ".select-options li[choise='true']", function(e) {
  var self = $(e.target);
  var style = '';
  $(".select-options li").removeClass("active");
  self.addClass("active");
  self.parent(".select-options").siblings(".select-styled").text(self.text()).removeClass('active');
  self.parent(".select-options").siblings("select").val(self.attr('rel'));
  self.parent(".select-options").siblings("select").find("option[value='" + self.attr('rel') + "']").attr("selected");
  $(".select-options").hide();
  e.stopPropagation();
  });

$(document).on("click", ".select-options li[choise='false']", function(e) {
  e.stopPropagation();
});
