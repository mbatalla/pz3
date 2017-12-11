/**
 * 2002-2016 TemplateMonster
 *
 * TM Mega Layout
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-2.0
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the module to newer
 * versions in the future.
 *
 *  @author    TemplateMonster (Alexander Grosul & Alexander Pervakov)
 *  @copyright 2002-2016 TemplateMonster
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */
var isMobile = false;
$(document).ready(function() {
  wishlistBtn();
  mobileMenu();
  $(window).resize(function() {
    mobileMenu();
  });
  featuredProductCarousel();
});
function wishlistBtn() {
  var wishlist_lnk = $('header .wishlist-link');
  var wishlist_div = $('header .wishlist-button');
  if (wishlist_div && wishlist_lnk) {
    wishlist_lnk.appendTo(wishlist_div);
  }
}
function mobileMenu() {
  var menu_mobile;
  var head_login;
  var languages;
  var currencies;
  var search;
  var wishlist;
  var cart;
  var mega_menu_container = $('header .top_menu').parent();
  var top_menu = $('header .top_menu > .menu');
  if ($(document).width() + scrollCompensate() <= 1199 && !isMobile) {
    if (top_menu.length) {
      head_login = $('#header-login');
      languages = $('#languages-block-top');
      currencies = $('#currencies-block-top');
      search = $('#tmsearch');
      wishlist = $('header .wishlist-link');
      cart = $('header .shopping_cart');
      top_menu.append('<li class="mobile-items"></li>');
      menu_mobile = top_menu.find('.mobile-items');
      if (head_login.length) {
        head_login.parent().addClass('header-login');
        head_login.appendTo(menu_mobile);
      }
      if (languages.length) {
        languages.parent().addClass('languages');
        languages.appendTo(menu_mobile);
      }
      if (currencies.length) {
        currencies.parent().addClass('currencies');
        currencies.appendTo(menu_mobile);
      }
      if (search.length) {
        search.parent().addClass('search');
        search.appendTo(mega_menu_container);
      }
      if (wishlist.length) {
        wishlist.parent().addClass('wishlist');
        wishlist.appendTo(mega_menu_container);
      }
      if (cart.length) {
        cart.parent().addClass('cart');
        cart.appendTo(mega_menu_container);
      }
      isMobile = true;
    }
  } else if ($(document).width() + scrollCompensate() > 1199 && isMobile) {
    menu_mobile = top_menu.find('.mobile-items');
    head_login = menu_mobile.find('#header-login');
    languages = menu_mobile.find('#languages-block-top');
    currencies = menu_mobile.find('#currencies-block-top');
    search = mega_menu_container.find('#tmsearch');
    wishlist = mega_menu_container.find('.wishlist-link');
    cart = mega_menu_container.find('.shopping_cart');
    if (head_login.length) {
      head_login.appendTo($('header .header-login'));
    }
    if (languages.length) {
      languages.appendTo($('header .languages'));
    }
    if (currencies.length) {
      currencies.appendTo($('header .currencies'));
    }
    if (search.length) {
      search.appendTo($('header .search'));
    }
    if (wishlist.length) {
      wishlist.appendTo($('header .wishlist'));
    }
    if (cart.length) {
      cart.appendTo($('header .cart'));
    }
    menu_mobile.remove();
    isMobile = false;
  }
}
function featuredProductCarousel() {
  countItemsFeatured();
  var featuredCarousel = $('.featured-product-carousel #homefeatured');
  if (featuredCarousel.length && !!$.prototype.bxSlider) {
    featured_slider = featuredCarousel.bxSlider({
      minSlides: featured_carousel_items,
      maxSlides: featured_carousel_items,
      slideWidth: 500,
      slideMargin: 30,
      pager: false,
      nextText: '',
      prevText: '',
      moveSlides: 1,
      infiniteLoop: true,
      hideControlOnEnd: true,
      responsive: true,
      useCSS: false,
      autoHover: false,
      speed: 500,
      pause: 3000,
      controls: true,
      autoControls: false
    });
  }
  $(window).resize(function() {
    if (featuredCarousel.length) {
      resizeCarouselFeatured()
    }
  });
}
function resizeCarouselFeatured() {
  countItemsFeatured();
  featured_slider.reloadSlider({
    minSlides: featured_carousel_items,
    maxSlides: featured_carousel_items,
    slideWidth: 500,
    slideMargin: 30,
    pager: false,
    nextText: '',
    prevText: '',
    moveSlides: 1,
    infiniteLoop: true,
    hideControlOnEnd: true,
    responsive: true,
    useCSS: false,
    autoHover: false,
    speed: 500,
    pause: 3000,
    controls: true,
    autoControls: false
  });
}
function countItemsFeatured() {
  var featured = $('.featured-product-carousel');
  if (featured.width() < 400) {
    featured_carousel_items = 1;
  }
  if (featured.width() >= 400) {
    featured_carousel_items = 2;
  }
  if (featured.width() >= 640) {
    featured_carousel_items = 3;
  }
  if (featured.width() >= 990) {
    featured_carousel_items = 4;
  }
  if (featured.width() >= 1880) {
    featured_carousel_items = 6;
  }
}

$(window).on("scroll", function(e) {
  if ($(window).scrollTop() > 35 && $(window).scrollTop() < 450) {
    $('.huincha-top').addClass("fixed-top").removeClass("active");
  } else if ($(window).scrollTop() > 451) {
     $('.huincha-top.fixed-top').addClass("active");
  } else if($(window).scrollTop() <= 35) {
    $('.huincha-top.fixed-top').removeClass("fixed-top");
  }
});
