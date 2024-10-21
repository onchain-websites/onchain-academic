jQuery(document).ready(function ($) {
  jQuery(window).scroll(function () {
    var scroll = jQuery(window).scrollTop();

    if (scroll >= 100) {
      jQuery(".header").addClass("active");
    } else {
      jQuery(".header").removeClass("active");
    }
  });

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


  // save video play history to localStorage xxxxxxxxxxxxxxxzzzzzzzzzxxxxxxxxxxx

  function updateVisitedUrls(url) {
    // Get the existing array from localStorage or initialize it if it doesn't exist
    let visitedUrls = JSON.parse(localStorage.getItem('visitedUrls')) || [];

    // Check if the URL is already in the array
    if (!visitedUrls.includes(url)) {
      // If the URL is new, add it to the beginning of the array
      visitedUrls.unshift(url);

      // Ensure the array doesn't exceed 10 items
      if (visitedUrls.length > 10) {
        visitedUrls.pop(); // Remove the last item if the array exceeds 10
      }

      // Update localStorage with the new array
      localStorage.setItem('visitedUrls', JSON.stringify(visitedUrls));
    }
  }

  // Example usage
  const currentUrl = window.location.href; // Get the current URL

  // Only track URLs that contain '/play?video='
  if (currentUrl.includes('/play')) {
    updateVisitedUrls(currentUrl); // Update the visited URLs in local storage
  }


  //  modal start xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
  // Open modal when the button is clicked
  $('[data-target]').click(function () {
    const targetModal = $(this).data('target'); // Get the target modal from data-target attribute
    $(targetModal).addClass('fade show'); // Show the targeted modal
  });

  // Close modal when the 'x' is clicked
  $('.close-modal').click(function () {
    $(this).closest('.modal').removeClass('fade show'); // Close the closest modal
  });


  // Close modal if clicked outside the modal content
  $(window).click(function (event) {
    if ($(event.target).is('.modal')) {
      $(event.target).removeClass('fade show'); // Close the modal if the overlay is clicked
    }
  });




  // notes page js start xxxxxxxxxxxxxxxxxxxx

  function loadNotes(postId, currentModule) {
    $.ajax({
      url: base_ajax.ajax_url,  // Use the localized ajax_url
      type: 'POST',
      data: {
        action: 'load_user_notes',
        course_id: postId,
        current_module: currentModule  // WordPress action for the AJAX handler
      },
      success: function (response) {
        if (response.success) {
          var notes = response.data;

          console.log(notes);

          // Clear any previous notes display
          $('#notes-container').empty();

          // Loop through the notes and display them
          $.each(notes, function (index, note) {
            var noteHtml = '<hr>';
            noteHtml = '<a href="' + note.url + '" class="note-item">';
            noteHtml += '<span class="d-block fs-20 font-gilroy-bold">' + note.video_title + '</span>';
            noteHtml += '<p>' + note.content + '</p>';
            noteHtml += '</a>';

            // Append note to the container
            $('#notes-container').append(noteHtml);
          });
        } else {
          console.log('No notes found.');
          $('#notes-container').html('<p>No notes found for the user.</p>');
        }
      },
      error: function (error) {
        console.error('Error loading notes:', error);
      }
    });

  }

  function loadModules(courseId) {
    $.ajax({
      url: base_ajax.ajax_url, // WordPress AJAX URL
      type: 'POST',
      data: {
        action: 'load_modules', // The action we defined in PHP
        course_id: courseId // Send the course ID
      },
      success: function (response) {
        if (response.success) {
          var moduleSelect = $('#moduleSelector'); // For displaying unique modules in select
          moduleSelect.empty(); // Clear any existing module options

          // Add a default option to the select

          // Loop through unique modules and add them as options in the select
          $.each(response.data.modules, function (index, module) {
            var optionHtml = '<option value="' + module + '">Module: ' + module + '</option>';
            moduleSelect.append(optionHtml);
          });
        } else {
          console.error('Error loading modules:', response.data);
        }
      },
      error: function (error) {
        console.error('Error loading modules:', error);
      }
    });
  }

  let customCourseId = null;
  $('[data-target]').click(function () {
    let courseId = $(this).data('courseid');
    // loadNotes(courseId)
    loadModules(courseId);
    customCourseId = courseId

    $('#modalCourseTitle').text($(this).data('course-title'))

    setTimeout(() => {
      loadNotes(courseId, parseInt($('#moduleSelector').val()));
    }, 1800);

  });
  $('#moduleSelector').on('change', function () {
    console.log(customCourseId);

    $('#notes-container').empty();
    loadNotes(customCourseId, parseInt($('#moduleSelector').val()))

  });
});