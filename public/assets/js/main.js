
document.querySelectorAll(".drop-zone__input").forEach((inputElement) => {
    const dropZoneElement = inputElement.closest(".drop-zone");
  
    dropZoneElement.addEventListener("click", (e) => {
      inputElement.click();
    });
  
    inputElement.addEventListener("change", (e) => {
      if (inputElement.files.length) {
        updateThumbnail(dropZoneElement, inputElement.files[0]);
      }
    });
  
    dropZoneElement.addEventListener("dragover", (e) => {
      e.preventDefault();
      dropZoneElement.classList.add("drop-zone--over");
    });
  
    ["dragleave", "dragend"].forEach((type) => {
      dropZoneElement.addEventListener(type, (e) => {
        dropZoneElement.classList.remove("drop-zone--over");
      });
    });
  
    dropZoneElement.addEventListener("drop", (e) => {
      e.preventDefault();
  
      if (e.dataTransfer.files.length) {
        inputElement.files = e.dataTransfer.files;
        updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
      }
  
      dropZoneElement.classList.remove("drop-zone--over");
    });
  });

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
    loop:true,
    margin:10,
    dots:false,
    navText: [
        '<img src="assets/images/previous.svg" alt="Previous" />', // Custom previous arrow
        '<img src="assets/images/next.svg" alt="Next" />'      // Custom next arrow
    ],
    nav:true,
    autoplay: true,              
    autoplayTimeout: 3000,       
    autoplayHoverPause: true,   
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:3
        }
    }
})


$(document).ready(function(){
  $(".layout-btn").click(function() {
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