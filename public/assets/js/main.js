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
                      <span><img src="assets/images/fi_upload-cloud.svg" alt=""> Upload Image</span>
                  </div>
              </div>
              <input type="file" name="additional_images[]" class="drop-zone__input">

          </div>
          <button type="button" class="btn btn-danger btn-sm mt-2 remove-btn">X</button>

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
      updateThumbnail(dropZoneElement, fileInput.files[0]);
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
      updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
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
    thumbnailElement.style.backgroundImage = null;
  }
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
      items: 2
    },
    1000: {
      items: 3
    }
  }
})


$(document).ready(function () {
  $(".layout-btn").click(function () {
    var targetTable = $(".common-table table");

    // Remove 'active' class from all buttons and add it to the clicked button
    $(".layout-btn").removeClass("active");
    $(this).addClass("active");

    // Check if the clicked button has the 'list' class
    if ($(this).hasClass("list")) {
      $(this)
      targetTable.removeClass("grid-view").addClass("list-view");
    }
    // Otherwise, check if it has the 'grid' class
    else if ($(this).hasClass("grid")) {
      targetTable.removeClass("list-view").addClass("grid-view");
    }
  });

})

document.addEventListener('DOMContentLoaded', function () {
  const datePicker = document.getElementById('datepicker');
  const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
  datePicker.setAttribute('min', today); // Set the min attribute to today's date
});


// document.getElementById('campaign-select').addEventListener('change', function () {
//   const campaignId = this.value;
//   const partnerSelect = document.getElementById('partner-select');

//   // Clear existing options
//   partnerSelect.innerHTML = '<option value="" selected>Select Partner</option>';
//   partnerSelect.disabled = true;

//   if (campaignId) {
//     // Fetch partners based on the selected campaign
//     fetch(`/partner/${campaignId}`)
//       .then(response => response.json())
//       .then(data => {
//         console.log(data); // Log data to inspect the structure
//         if (data.length > 0) {
//           data.forEach(partner => {
//             const option = document.createElement('option');
//             option.value = partner.id; // Assuming 'id' is the primary key
//             option.textContent = partner.partner ? partner.partner.name :
//               'Unnamed Partner'; // Fallback for null partner
//             partnerSelect.appendChild(option);
//           });
//           partnerSelect.disabled = false;
//         }
//       })

//       .catch(error => console.error('Error fetching partners:', error));
//   }
// });

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


// alert();