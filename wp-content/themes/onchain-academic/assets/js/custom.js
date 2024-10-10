jQuery(document).ready(function ($) {
  $('.single-slider').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    autoplay: true
  });

  $('.multiple-slider').slick({
    infinite: true,
    slidesToShow: 4,
    slidesToScroll: 1,
    arrows: true,
    autoplay: true,
    responsive: [
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 3,
        }
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 2
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1
        }
      }
    ]
  });
  $('.three-slider').slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    arrows: true,
    autoplay: true,
    responsive: [
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 2
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1
        }
      }
    ]
  });


  $('#passwordToggler').on('click', function () {
    let passInput = $('#user_pass');
    if (passInput.attr('type') == 'password') {
      passInput.attr('type', 'text');
    } else {
      passInput.attr('type', 'password');

    }

  });


  $(document).on('click', function (event) {
    // Check if the click is outside of the profile-wrapper or profile-dropdown
    if (!$(event.target).closest('.profile-wrapper, .profile-dropdown').length) {
      $('.profile-dropdown').removeClass('d-block');
    }
  });
  $('.profile-wrapper').on('click', function (event) {
    // Prevent the document click handler from immediately firing
    event.stopPropagation();
    $('.profile-dropdown').toggleClass('d-block');
  });
});