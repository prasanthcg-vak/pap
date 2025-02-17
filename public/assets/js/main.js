var addMoreBtn = document.getElementById('add-more-btn');
if (addMoreBtn) {
  document.getElementById('add-more-btn').addEventListener('click', function () {
    const container = document.getElementById('multiple-image');

    // Create a new image upload section
    const newSection = document.createElement('div');
    newSection.classList.add('upload--col');

    newSection.innerHTML = `
            <div class="drop-zone">
                <div class="drop-zone__prompt">
                    <div class="drop-zone_color-txt">
                        <span><img src="assets/images/Image.png" alt=""></span> <br />
                        <span style="font-size:14px;"><img src="assets/images/fi_upload-cloud.svg" alt=""> Upload Asset</span>
                        <span style="font-size:10px;">(JEPG, PNG, JPG, MP4, PDF, JFIF).</span>
                    </div>
                </div>
                <input type="file" name="additional_images[]" class="drop-zone__input" onchange="handleFileChange(this)">
            </div>
            <button type="button" class="btn btn-danger btn-sm mt-2 remove-btn">X</button>
            <div class="thumbnail-upload" style="display: none;">
                <label for="thumbnail">Upload Thumbnail (for Video/PDF):</label>
                <div class="drop-zone">
                    <div class="drop-zone__prompt">
                        <div class="drop-zone_color-txt">
                            <span><img src="assets/images/Image.png" alt=""></span><br />
                            <span style="font-size:14px;"><img src="assets/images/fi_upload-cloud.svg" alt="">
                                Upload Asset</span>
                            <span style="font-size:10px;">(JPEG, PNG, JPG).</span>
                        </div>
                    </div>
                    <input type="file" name="thumbnail[]" class="drop-zone__input" onchange="handleThumbnailFileChange(this)">
                </div>
            </div>
        `;

    // Append the new section to the container
    container.appendChild(newSection);

    // Add delete functionality to the delete button
    const deleteButton = newSection.querySelector('.remove-btn');
    deleteButton.addEventListener('click', function () {
      newSection.remove();
    });

    // Initialize the drop-zone functionality for the new section
    initializeDropZone(newSection.querySelector('.drop-zone'));

    // Initialize drop zone functionality for the thumbnail upload
    const thumbnailDropZone = newSection.querySelector('.thumbnail-upload .drop-zone');
    if (thumbnailDropZone) {
      initializeDropZone(thumbnailDropZone);
    }
  });
}

// Initialize drop-zone functionality for all elements
function initializeDropZone(dropZoneElement) {
  const fileInput = dropZoneElement.querySelector('.drop-zone__input');

  // Make the drop zone clickable
  dropZoneElement.addEventListener('click', (event) => {
    if (event.target !== fileInput) {
      fileInput.click();
    }
  });

  fileInput.addEventListener('change', (e) => {
    if (fileInput.files.length) {
      const file = fileInput.files[0];
      updateThumbnail(dropZoneElement, file);

      // Show the custom thumbnail upload for videos and PDFs
      if (file.type === "video/mp4" || file.type === "application/pdf") {
        const thumbnailUpload = dropZoneElement.closest('.upload--col').querySelector('.thumbnail-upload');
        thumbnailUpload.style.display = 'block';
        // alert();
      }



    }
  });

  dropZoneElement.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZoneElement.classList.add('drop-zone--over');
  });

  ['dragleave', 'dragend'].forEach((type) => {
    dropZoneElement.addEventListener(type, () => {
      dropZoneElement.classList.remove('drop-zone--over');
    });
  });

  dropZoneElement.addEventListener('drop', (e) => {
    e.preventDefault();

    if (e.dataTransfer.files.length) {
      fileInput.files = e.dataTransfer.files;
      const file = e.dataTransfer.files[0];
      updateThumbnail(dropZoneElement, file);

      // Show the custom thumbnail upload for videos and PDFs
      if (file.type === "video/mp4" || file.type === "application/pdf") {
        const thumbnailUpload = dropZoneElement.closest('.upload--col').querySelector('.thumbnail-upload');
        thumbnailUpload.style.display = 'block';
      }
    }

    dropZoneElement.classList.remove('drop-zone--over');
  });
}

// Initialize existing drop zones on page load
document.querySelectorAll('.drop-zone').forEach(initializeDropZone);


function updateThumbnail(dropZoneElement, file) {
  let thumbnailElement = dropZoneElement.querySelector(".drop-zone__thumb");

  // First time - remove the prompt
  if (dropZoneElement.querySelector(".drop-zone__prompt")) {
    dropZoneElement.querySelector(".drop-zone__prompt").remove();
  }

  // First time - there is no thumbnail element, so lets create it
  if (!thumbnailElement) {
    thumbnailElement = document.createElement("div");
    thumbnailElement.classList.add("drop-zone__thumb");
    dropZoneElement.appendChild(thumbnailElement);
  }

  thumbnailElement.dataset.label = file.name;

  // Show thumbnail for image files
  if (file.type.startsWith("image/")) {
    const reader = new FileReader();

    reader.readAsDataURL(file);
    reader.onload = () => {
      thumbnailElement.style.backgroundImage = `url('${reader.result}')`;
    };
  } else {
    // thumbnailElement.style.backgroundImage = null;
    if (file.type === "video/mp4") {
      thumbnailElement.style.backgroundImage = `url('assets/images/video.png')`;
    }
    if (file.type === "application/pdf") {
      thumbnailElement.style.backgroundImage = `url('assets/images/document.png')`;
    }
  }
}

let totalItems = $('.owl-carousel .item').length;

// Determine the number of items to display based on the total number of items
let itemsToShow;
if (totalItems === 1) {
  itemsToShow = 1; // Show 1 card if there is only 1 image
} else if (totalItems === 2) {
  itemsToShow = 2; // Show 2 cards if there are 2 images
} else {
  itemsToShow = 3; // Show 3 cards for 3 or more images
}


$('.owl-carousel').owlCarousel({
  loop: true,
  margin: 10,
  dots: false,
  navText: [
    '<img src="/assets/images/previous.svg" alt="Previous" />', // Custom previous arrow
    '<img src="/assets/images/next.svg" alt="Next" />'      // Custom next arrow
  ],
  nav: true,
  autoplay: true,
  autoplayTimeout: 3000,
  autoplayHoverPause: true,
  responsive: {
    0: {
      items: 1
    },
    600: {
      items: totalItems === 1 ? 1 : 2 // Show 2 cards at 600px unless there's only 1 item
    },
    1000: {
      items: itemsToShow
    }
  }
})


$(document).ready(function () {
  const $datePicker = $('#datepicker');
  if ($datePicker.length) {
    const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
    // Set the min attribute to today's date
    $datePicker.attr('min', today);
  }
});

// bottom-navigation navlink active
const navLinkEls = document.querySelectorAll('.nav-link');
navLinkEls.forEach(navLinkEL => {
  navLinkEL.addEventListener('click', () => {
    document.querySelector('.active')?.classList.remove('active');
    navLinkEL.classList.add('active');
  });
});

$(document).ready(function () {
  // On click of any .card-item
  $('.card-item').on('click', function () {
    // Remove the 'active' class from all card items
    $('.card-item').removeClass('active').css('background-color', '');

    // Add the 'active' class to the clicked card item
    $(this).addClass('active');

    // Change the background color based on the card's class
    if ($(this).hasClass('purple')) {
      $(this).css('background-color', '#F4E8FF'); // Purple background
    } else if ($(this).hasClass('green')) {
      $(this).css('background-color', '#CFFBEB'); // Green background
    } else if ($(this).hasClass('orange')) {
      $(this).css('background-color', '#fef4e4'); // Orange background
    }
  });
});

function toggleGroupSection() {
  const roleSelect = $('#role_id'); // Select role dropdown using jQuery
  const groupSection = $('#group-section'); // Select group section using jQuery
  const clientSection = $('#client-section'); // Select client section using jQuery
  const clientDropdown = $('#client_id_inUser'); // Client dropdown
  const groupDropdown = $('#group_id'); // Group dropdown
  const staffCheckboxContainer = $("#staffCheckboxContainer");

  if (roleSelect.length) { // Check if roleSelect exists
    // Reset the client and group dropdowns
    clientDropdown.val('').change(); // Reset client dropdown and trigger change event
    groupDropdown.empty().append('<option value="" disabled selected>Select Client Group</option>').prop('disabled', true); // Reset group dropdown

    // Show/Hide sections based on the selected role
    if (roleSelect.val() == 4 || roleSelect.val() == 6) {
      clientSection.show(); // Show client section
      groupSection.hide(); // Hide group section
    } else if (roleSelect.val() == 5) {
      clientSection.show(); // Show client section
      groupSection.show(); // Show group section
    } else {
      clientSection.hide(); // Hide client section
      groupSection.hide(); // Hide group section
    }
  }
  // var roleSelect = document.getElementById("role_id");

  // Show checkbox only when Role ID 3 is selected
  if (roleSelect.val() == "3") {
    staffCheckboxContainer.show();
  } else {
    staffCheckboxContainer.hide();
  }
}


// Attach the event listener to the role dropdown
$(document).ready(function () {
  const roleSelect = $('#role_id');

  if (roleSelect.length) {
    // Initialize visibility on page load
    toggleGroupSection();
    // Attach change event to role dropdown
    roleSelect.on('change', toggleGroupSection);
  }
  else {
  }
});


$('#assetTypesTable').DataTable({
  responsive: true,
  pageLength: 10,
  columnDefs: [{
    searchable: false,
    orderable: false,
    targets: 0
  }],
  order: [
    [1, 'asc']
  ], // Initial sort
  drawCallback: function (settings) {
    var api = this.api();
    api.column(0, {
      order: 'applied'
    }).nodes().each(function (cell, i) {
      cell.innerHTML = i + 1; // Number rows dynamically
    });
  }
});

$('#categoriesTable').DataTable({
  responsive: true,
  pageLength: 10,
  columnDefs: [{
    searchable: false,
    orderable: false,
    targets: 0
  }],
  order: [
    [1, 'asc']
  ], // Initial sort
  drawCallback: function (settings) {
    var api = this.api();
    api.column(0, {
      order: 'applied'
    }).nodes().each(function (cell, i) {
      cell.innerHTML = i + 1; // Number rows dynamically
    });
  }
});


$(document).ready(function () {
  $('.selectpicker').selectpicker();
});

$(document).ready(function () {
  // When client dropdown changes
  $('#client').on('change', function () {
    let clientId = $(this).val();
    let clientGroupDropdown = $('#clientGroup');
    let partnerDropdown = $('#related_partner');
    $('#modalLoader').show();

    // Reset subsequent dropdowns
    clientGroupDropdown.empty().append('<option value="" >-- Select Client Group --</option>').prop('disabled', true);
    partnerDropdown.empty().append('<option value="">-- Select Partner --</option>').prop('disabled', true);

    if (clientId) {
      $.ajax({
        url: `/get-client-groups/${clientId}`, // Laravel route for client groups
        type: 'GET',
        success: function (data) {
          clientGroupDropdown.prop('disabled', false);
          data.forEach(function (group) {
            clientGroupDropdown.append(`<option value="${group.id}">${group.name}</option>`);
          });
          // $('#clientGroupLoader').hide();
          $('#modalLoader').hide();


        },
        error: function () {
          alert('Failed to fetch client groups. Please try again.');
          // $('#clientGroupLoader').hide();
          $('#modalLoader').hide();


        }
      });
      $.ajax({
        url: `/get-partners/${clientId}`, // Laravel route for partners
        type: 'GET',
        success: function (data) {
          console.log('Response Data:', data);
          partnerDropdown.empty(); // Clear all existing options
          $('.selectpicker').selectpicker('refresh');
          partnerDropdown.prop('disabled', false);
          if (Array.isArray(data) && data.length > 0) {
            data.forEach(function (partner) {
              console.log(partner.partner);

              if (partner.partner != null) {
                partnerDropdown.append(`<option value="${partner.partner.id}">${partner.partner.name}</option>`);
                $('.selectpicker').selectpicker('refresh');
              }
              else {
                $('#modalLoader').hide();
                alert('No partners found for the selected group........');
              }

            });
          } else {
            alert('No partners found for the selected group.');
          }
          $('#modalLoader').hide();

        },
        error: function () {
          alert('Failed to fetch partners. Please try again.');
          $('#modalLoader').hide();

        }
      });
    }
  });
  $('#client_id_inUser').on('change', function () {
    let clientId = $(this).val();
    let role_id = $('#role_id').val();
    if (role_id == 5) {
      let clientGroupDropdown = $('#group_id');
      // $('#modalLoader').show();

      // Reset subsequent dropdowns
      clientGroupDropdown.empty().append('<option value="">-- Select Client Group --</option>').prop('disabled', true);

      if (clientId) {
        $('#modalLoader').show();
        $.ajax({
          url: `/get-client-groups/${clientId}`, // Laravel route for client groups
          type: 'GET',
          success: function (data) {
            clientGroupDropdown.prop('disabled', false);
            data.forEach(function (group) {
              clientGroupDropdown.append(`<option value="${group.id}">${group.name}</option>`);
              // alert(group.name);
            });
            $('.selectpicker').selectpicker('refresh'); // Refresh dropdown UI
            // $('#clientGroupLoader').hide();
            $('#modalLoader').hide();
          },
          error: function () {
            alert('Failed to fetch client groups. Please try again.');
            // $('#clientGroupLoader').hide();
            $('#modalLoader').hide();


          }
        });
      }
    }

  });

  // When client group dropdown changes
  // $('#clientGroup').on('change', function () {
  //   let groupId = $(this).val();
  //   let partnerDropdown = $('#related_partner');
  //   $('#modalLoader').show();

  //   // Reset the partner dropdown
  //   // partnerDropdown.empty().append('<option value="">-- Select Partner --</option>').prop('disabled', true);

  //   if (groupId != "") {
  //     $.ajax({
  //       url: `/get-partners/${groupId}`, // Laravel route for partners
  //       type: 'GET',
  //       success: function (data) {
  //         partnerDropdown.empty(); // Clear all existing options
  //         $('.selectpicker').selectpicker('refresh');
  //         partnerDropdown.prop('disabled', false);
  //         if (Array.isArray(data) && data.length > 0) {
  //           data.forEach(function (partner) {
  //             console.log(partner.user);

  //             if (partner.user != null) {
  //               partnerDropdown.append(`<option value="${partner.user.id}">${partner.user.name}</option>`);
  //               $('.selectpicker').selectpicker('refresh');
  //             }
  //             else {
  //               $('#modalLoader').hide();
  //               alert('No partners found for the selected group.');
  //             }

  //           });
  //         } else {
  //           alert('No partners found for the selected group.');
  //         }
  //         $('#modalLoader').hide();

  //       },
  //       error: function () {
  //         alert('Failed to fetch partners. Please try again.');
  //         $('#modalLoader').hide();

  //       }
  //     });
  //   }
  //   else {
  //     $('#modalLoader').hide();
  //   }
  // });
});
$(document).ready(function () {
  $('.read-more').on('click', function () {
    $(this).hide(); // Hide "Read more"
    $(this).siblings('.truncated-text').hide(); // Hide truncated text
    $(this).siblings('.full-text').show(); // Show full text
  });

  $('.read-less').on('click', function () {
    $(this).parent('.full-text').hide(); // Hide full text
    $(this).parent('.full-text').siblings('.truncated-text').show(); // Show truncated text
    $(this).parent('.full-text').siblings('.read-more').show(); // Show "Read more"
  });


  $(".layout-btn").click(function() {
    var targetTable = $(".acc-view");

    // Remove 'active' class from all buttons and add it to the clicked button
    $(".layout-btn").removeClass("active");
    $(this).addClass("active");
      
    // Remove existing view classes
    targetTable.removeClass("grid-view list-view table-view");

    // Apply the selected view
    if ($(this).hasClass("list")) {
      targetTable.addClass("list-view");
    } 
    else if ($(this).hasClass("grid")) {
      targetTable.addClass("grid-view");
    }
    else if ($(this).hasClass("table")) {
      targetTable.addClass("table-view");
    }
  });
});