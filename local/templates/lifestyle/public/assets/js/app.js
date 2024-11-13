'use strict';

$(function() {
  $("nav ul").mCustomScrollbar();

  $('.lessons__slick').slick({
    slidesToShow: 4,
    infinite: false,
    responsive: [{
      breakpoint: 1600,
      settings: {
        slidesToShow: 4
      }
    }, {
      breakpoint: 1300,
      settings: {
        slidesToShow: 2
      }
    }, {
      breakpoint: 575,
      settings: {
        slidesToShow: 1
      }
    }]
  });

  $('nav').on('click', '.collapse', function() {
    $('body').toggleClass('min');
    return false;
  });

  $('.js-quiz').on('click', function() {
    $('.quiz__modal--overlay').toggleClass('active');
    $('body').toggleClass('active');
    return false;
  });

  $('.quiz__modal').on('click', '.close', function() {
    $('.quiz__modal--overlay').removeClass('active');
    $('body').removeClass('active');
  });



    $(document).on('click', function(e){
        if (!$('.quiz__modal').is(e.target)){
            $('.quiz__modal--overlay').removeClass('active');
        }
    })

  $(document).on('change', '.quiz__form', function() {
    const hasCheckedAnswer = $(this).find('input[type="radio"]:checked, input[type="checkbox"]:checked').length > 0;
    const nextButton = $(this).find('.btn:not(.button-back)');

    if (hasCheckedAnswer) {
      nextButton.removeAttr('disabled');
    } else {
      nextButton.attr('disabled', 'disabled');
    }
  });

  $('.quiz__form').on('input', '[type="text"]', function() {
    var inputValue = $(this).val();
    if (inputValue.length >= 10) {
      $(this).closest('.quiz__form').find('.btn:not(.button-back)').removeAttr('disabled');
    } else {
      $(this).closest('.quiz__form').find('.btn:not(.button-back)').attr('disabled', 'disabled');
    }
  });

  $('.quiz__modal--overlay').on('click', '.btn', function() {
    $(this).closest('.quiz__modal--wrapper').removeClass('active').next('.quiz__modal--wrapper').addClass('active');
    if ($(this).closest('.quiz__modal--wrapper').next('.quiz__modal--wrapper').length == 0) {
      $('.quiz__modal--overlay').removeClass('active');
      $('body').removeClass('active');
      $($('.quiz__modal--wrapper')[0]).addClass('active');
    }
    return false;
  });

  var b = $(".sphere--big");
  var s = $(".sphere--small");
  var m = $(".move");
  $(".login").on("mousemove", function(t) {
    var e = -($(window).innerWidth() / 2 - t.pageX) / 40,
      n = ($(window).innerHeight() / 2 - t.pageY) / 30;
    b.attr("style", "transform: translate(" + e * -4 + "px, " + n * 2 + "px);");
    s.attr("style", "transform: translate(" + e * 3 + "px, " + n * -5 + "px);");
    m.attr("style", "transform: translate(" + e * 2 + "px, " + n * 1.5 + "px);");
  });

  var wave = $(".wave");
  var img = $(".start");
  $(".start__quiz").on("mousemove", function(t) {
    var e = -($(window).innerWidth() / 2 - t.pageX) / 70,
      n = ($(window).innerHeight() / 2 - t.pageY) / 60;
    wave.attr("style", "transform: translate(" + e + "px, " + n * 0.5 + "px);");
    img.attr("style", "transform: translate(" + e * 2 + "px, " + n * -1.5 + "px);");
  });

  $('.js-type__toggle').on('click', function() {
    $(this).prev('input').attr('type') == "text" ? $(this).prev('input').attr('type', 'password') : $(this).prev('input').attr('type', 'text');
  });

  $('[data-modal]').on('click', function() {
    var _tg = $(this).data('target');
    $(_tg).toggleClass('active');
    $('body').toggleClass('active');
    return false;
  });

  $('.js-toogle-nav').on('click', function() {
    $('body').toggleClass('show-menu');
    return false;
  });
    $('.quiz__modal--overlay').on('click', function() {
        $('body').removeClass('active');
    });


  $('.js-next').on('click', function() {
    $(this).closest('.start__quiz--page').removeClass('active show').next('.start__quiz--page').addClass('active show');
    return false;
  });

  $('.js-prev').on('click', function() {
    $(this).closest('.start__quiz--page').removeClass('active show').prev('.start__quiz--page').addClass('active show');
    return false;
  });

  $('.js-close').on('click', function() {
    $(this).closest('.start__quiz').removeClass('show');
    return false;
  });

  $(window).on('load', function() {
    $('.start__quiz').addClass('show');
    setTimeout(function() {
      $('.start__quiz--page.active').addClass('show');
    }, 10);
  });



});
//# sourceMappingURL=app.js.map