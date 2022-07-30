/* ==========================================================================
   Widget Toggle
   ========================================================================== */
$(function() {
    $('#widget a.toggle').click(function(e){
        e.preventDefault();
        $(this).prev("div.widget_container").slideToggle(200);
    });
});


/* ==========================================================================
   Scroll To Navigation
   ========================================================================== */

$(function() {
    $('nav ul li a').bind('click',function(event){
        var $anchor = $(this);
        
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 2000,'easeInOutExpo');
        event.preventDefault();
    });
    
    $('navabout ul li a').bind('click',function(event){
        var $anchor = $(this);
        
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 2000,'easeInOutExpo');
        event.preventDefault();
    });
});

$('.ribbon').click(function (e) {
    //e.stopPropagation();
    $('body,html').animate({ scrollTop: 0 });
    //return false;
});

(function($) {

  /**
   * Copyright 2012, Digital Fusion
   * Licensed under the MIT license.
   * http://teamdf.com/jquery-plugins/license/
   *
   * @author Sam Sehnert
   * @desc A small plugin that checks whether elements are within
   *     the user visible viewport of a web browser.
   *     only accounts for vertical position, not horizontal.
   */

  $.fn.visible = function(partial) {
    
      var $t            = $(this),
          $w            = $(window),
          viewTop       = $w.scrollTop(),
          viewBottom    = viewTop + $w.height(),
          _top          = $t.offset().top,
          _bottom       = _top + $t.height(),
          compareTop    = partial === true ? _bottom : _top,
          compareBottom = partial === true ? _top : _bottom;
    
    return ((compareBottom <= viewBottom) && (compareTop >= viewTop));

  };
    
})(jQuery);

$(window).scroll(function(event) {
  
  $("section").each(function(i, el) {
    var el = $(el);
    if (el.visible(true)) {
      el.addClass("animated"); 
    } 
  });
  
});


/* ==========================================================================
   Social Places / Brands
   ========================================================================== */

if($('.social-wrapper .social').length == true) {
    var brandsWrapper = $('.social-wrapper .social'),
        brandsHeight = parseInt(brandsWrapper.css('height').replace('px', '')),
        brandsWidth = brandsWrapper.width(),
        brandNode = brandsWrapper.find('div'),
        showMore = $('<a>', {
                        'class': 'show-more',
                        'href': '#',
                        'html': '<span>Show more</span><i class="icon-angle-down"></i>'
                    }),
        showLess = $('<a>', {
                        'class': 'show-less',
                        'href': '#',
                        'html': '<span>Show less</span><i class="icon-angle-up"></i>'
                    }),
        trueBrandsWidth = 0,
        brandNodeMarginTop,
        brandNodeMarginLeft,
        trueBrandsHeight;

    // Waiting for images loaded
    // and calculate true width brands wrapper
    $(window).load(function(){
        brandNode.each(function(i){

            // Margin top css value
            brandNodeMarginTop = parseInt($(this).css('marginTop').replace('px', '') * 2);
            // Margin left css value
            brandNodeMarginLeft = parseInt($(this).css('marginLeft').replace('px', '') * 2);

            // Calculate true width
            trueBrandsWidth += ($(this).width() + brandNodeMarginLeft) + (brandNode.length * 5);

            // Calculate true height
            trueBrandsHeight = Math.ceil(trueBrandsWidth / brandsWidth) * ($(this).height() + brandNodeMarginTop);

            // If brands wrapper width is outer container, add show more button
            if((trueBrandsWidth) >= brandsWidth) {
                brandsWrapper.after(showMore).after(showLess);
                showLess.hide();
            }
        });
    });

    // Show more toggle button click
    showMore.click(function(){

        // Expand wrapper
        brandsWrapper.css({height: trueBrandsHeight + 20});
        showMore.hide();
        showLess.show();

        return false;
    });

    // Show less button
    showLess.click(function(){

        // Collapse wrapper
        brandsWrapper.css({height: brandsHeight});
        showLess.hide();
        showMore.show();

        return false;
    });
}


/* ==========================================================================
   Scroll To Navigation
   ========================================================================== */

// Cache selectors
var lastId,
    topMenu = $("#top-menu"),
    topMenuHeight = topMenu.outerHeight()+35,
    // All list items
    menuItems = topMenu.find("a"),
    // Anchors corresponding to menu items
    scrollItems = menuItems.map(function(){
      var item = $($(this).attr("href"));
      if (item.length) { return item; }
    });

// Bind click handler to menu items
// so we can get a fancy scroll animation
menuItems.click(function(e){
  var href = $(this).attr("href"),
      offsetTop = href === "#" ? 0 : $(href).offset().top-topMenuHeight+1;
  $('html, body').stop().animate({ 
      scrollTop: offsetTop
  }, 600);
  e.preventDefault();
});

// Bind to scroll
$(window).scroll(function(){
   // Get container scroll position
   var fromTop = $(this).scrollTop()+topMenuHeight;
   
   // Get id of current scroll item
   var cur = scrollItems.map(function(){
     if ($(this).offset().top < fromTop)
       return this;
   });
   // Get the id of the current element
   cur = cur[cur.length-1];
   var id = cur && cur.length ? cur[0].id : "";
   
   if (lastId !== id) {
       lastId = id;
       // Set/remove active class
       menuItems
         .parent().removeClass("active")
         .end().filter("[href=#"+id+"]").parent().addClass("active");
   }                   
});


/* ==========================================================================
   Intialize Quicksand
   ========================================================================== */

//$('.portfolio').filters();

$('.flexslider').flexslider({
    animation: "slide",
    animationLoop: true,
    itemWidth: 200,
    slideshow: true, 
    useCSS: true,
    touch: true, 
    direction: "horizontal", 
    itemMargin: 1,
    minItems: 1,
    maxItems: 1,
    
    controlNav: true,
    directionNav: false,
});


/* ==========================================================================
   Google Map
   ========================================================================== */

function neighborhood_map(id) {
    $('#map-' + id).animate({width: 'toggle'}, '400');
}

var map;
var geocoder; 
geocoder = new google.maps.Geocoder();
var address = '11 Wall Street New York, NY 10005';
var isShowPin = true;
if (address.indexOf("NOPIN") > 0) //do not show pin
{
    address = address.replace("NOPIN", "");
    isShowPin = false;
}

function GoogleMapInit() {
    var latLng = new google.maps.LatLng(41.659, -4.714);
    var infowindow = new google.maps.InfoWindow();
    if (isShowPin) {
        var myOptions = {
            scrollwheel: false,
            zoom: 18,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
    }
    else {
        var myOptions = {
            scrollwheel: false,
            zoom: 18,
            streetViewControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
    }

    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
}

function ShowPins() {
    var image = 'images/pointer.fw.png';
     
    var infowindow = new google.maps.InfoWindow(); 
    var marker, i, center, pos;
     
    geocoder.geocode({ 'address': address }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            if (isShowPin) {
                marker = new google.maps.Marker({
                    map: map,
                    icon: image,
                    position: results[0].geometry.location,
                    animation: google.maps.Animation.DROP,
                    title: results[0].formatted_address
                });
            } 
        }
    }); 
}

//GoogleMapInit();
//ShowPins();  

/* ==========================================================================
   Mobile Navigation
   ========================================================================== */

$("<select />").appendTo("nav");

// Create default option "Go to..."
$("<option />", {
   "selected": "selected",
   "value"   : "",
   "text"    : "Go to..."
}).appendTo("nav select");

// Populate dropdown with menu items
$("nav a").each(function() {
 var el = $(this);
 $("<option />", {
     "value"   : el.attr("href"),
     "text"    : el.text()
 }).appendTo("nav select");
});
$("nav select").change(function() {
  window.location = $(this).find("option:selected").val();
});

(function ($) {
  // hash change handler
  function hashchange () {
	debugger;
    var hash = window.location.hash
      , el = $('ul.tabs [href*="' + hash + '"]')
      , content = $(hash)

    if (el.length && !el.hasClass('active') && content.length) {
      el.closest('.tabs').find('.active').removeClass('active');
      el.addClass('active');
      content.show().addClass('active').siblings().hide().removeClass('active');
    }
  }

  // listen on event and fire right away
  $(window).on('hashchange.skeleton', hashchange);
  hashchange();
  $(hashchange);
})(jQuery);

/* ==========================================================================
   Intialize Bootstrap JS
   ========================================================================== */

!function ($) {

    $(function () {

        var $window = $(window)

        // Disable certain links in docs
        $('section [href^=#]').click(function (e) {
            e.preventDefault()
        })

        // make code pretty
        window.prettyPrint && prettyPrint()

        // add-ons
        $('.add-on :checkbox').on('click', function () {
            var $this = $(this)
        , method = $this.attr('checked') ? 'addClass' : 'removeClass'
            $(this).parents('.add-on')[method]('active')
        })

        // tooltip 
        $('.tooltip').tooltip({
            selector: "a[rel=tooltip]"
        })
        $("[rel=tooltip]").tooltip();

        // alert boxes
        $(".alert").alert()

        // popover 
        $("a[rel=popover]")
            .popover()
            .click(function (e) {
                e.preventDefault()
            })

        // carousel         
        $('#myCarousel').carousel({
            pause: "false"
        });

        // parallax         
        $('#parallax').carousel();
        
        // scrollspy
        $('[data-spy="scroll"]').each(function () {
          var $spy = $(this).scrollspy('refresh');
        });
    })

} (window.jQuery)
