$(document).ready(function() {
  countItemsCross();
  if ($('#crossselling_list_car').length && !!$.prototype.bxSlider) {
    featured_slider = $('#crossselling_list_car').bxSlider({
      minSlides: cross_carousel_items,
      maxSlides: cross_carousel_items,
      slideWidth: 500,
      slideMargin: 30,
      pager: false,
      nextText: '',
      prevText: '',
      moveSlides: 1,
      infiniteLoop: false,
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
});
if (!isMobile) {
  $(window).resize(function() {
    if ($('#crossselling_list_car').length) {
      resizeCarouselCross()
    }
  });
} else {
  $(window).on("orientationchange", function() {
    var orientation_time;
    clearTimeout(orientation_time);
    orientation_time = setTimeout(function() {
      if ($('#crossselling_list_car').length) {
        resizeCarouselCross()
      }
    }, 500);
  });
}
function resizeCarouselCross() {
  countItemsCross();
  featured_slider.reloadSlider({
    minSlides: cross_carousel_items,
    maxSlides: cross_carousel_items,
    slideWidth: 500,
    slideMargin: 30,
    pager: false,
    nextText: '',
    prevText: '',
    moveSlides: 1,
    infiniteLoop: false,
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
function countItemsCross() {
  var crossselling = $('#crossselling');
  if (crossselling.width() < 405) {
    cross_carousel_items = 1;
  }
  if (crossselling.width() > 405) {
    cross_carousel_items = 2;
  }
  if (crossselling.width() >= 750) {
    cross_carousel_items = 3;
  }
  if (crossselling.width() >= 1180) {
    cross_carousel_items = 4;
  }
  if (crossselling.parent().parent('.crossselling-type-2').length) {
    return;
  }
  if (crossselling.width() >= 1300) {
    cross_carousel_items = 5;
  }
  if (crossselling.width() >= 1500) {
    cross_carousel_items = 6;
  }
  if (crossselling.width() >= 2300) {
    cross_carousel_items = 7;
  }
}