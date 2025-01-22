<!-- Bootstrap 5.2 JS and other dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.0/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"
    integrity="sha512-FHZVRMUW9FsXobt+ONiix6Z0tIkxvQfxtCSirkKc5Sb4TKHmqq1dZa8DphF0XqKb3ldLu/wgMa8mT6uXiLlRlw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.3.1/"
        }
    }
</script>


<!-- Your custom scripts -->
<script src="{{ asset('assets/js/main.js') }}"></script>
<script type="module" src="{{ asset('assets/js/ckeditor5.js') }}"></script>

<script>
    new DataTable('#datatable');

    var uploadAsset = document.getElementById('uploadAsset');
    if (uploadAsset) {

        document.getElementById('uploadAsset').addEventListener('click', function() {
            // Toggle the 'img-upload-con' visibility
            document.querySelector('.img-upload-con').classList.toggle('d-none');
        });
    }

    // Handle modal cancel button
    $(document).ready(function() {


        $('#campaign-cancel , #campaign-close').click(function() {

            if ($('#Model-Form').length > 0) {
                $('#Model-Form')[0].reset();
            }
            $('.modal.fade').modal('hide'); // Close the modal
            // alert();
            location.reload(); // Reload the page            
        });
        $('#cancel, #model-close').click(function() {
            // Reset the form
            if ($('#Model-Form').length > 0) {
                $('#Model-Form')[0].reset();
            }
            $('.modal.fade').modal('hide'); // Close the modal

            // Reset the file input in the first drop-zone
            const firstDropZoneInput = $('.drop-zone__input').first();
            firstDropZoneInput.val(''); // Clear the file input field

            // Clear any thumbnails in the first drop-zone
            const firstDropZone = firstDropZoneInput.closest('.drop-zone');
            firstDropZone.find('.drop-zone__thumb').remove();
            if (!firstDropZone.find('.drop-zone__prompt').length) {
                firstDropZone.prepend(`<div class="drop-zone__prompt">
          <div class="drop-zone_color-txt">
              <span><img src="assets/images/Image.png" alt=""></span><br />
              <span style="font-size:14px;"><img src="assets/images/fi_upload-cloud.svg" alt="">
                  Upload Asset</span>
              <span style="font-size:10px;">(JEPG, PNG, JPG, MP4, PDF, JFIF).</span>
          </div>
      </div>
    `);
            }

            // Remove all dynamically added `upload--col` divs except the first one
            $('.upload--col').not(':first').remove();

            // Hide thumbnail upload for videos/PDFs
            $('.thumbnail-upload').hide();
        });



    });
    $('#createButton').on('click', function() {
        const form = document.getElementById('campaignForm');
        form.reset(); // Clear form fields

        form.action = '/campaigns'; // Set the action for creating
        document.getElementById('campaignMethod').value = 'POST'; // Change method to POST

        // Clear dropdowns
        const clientInput = document.getElementById('client');
        if (clientInput) {
            clientInput.value = '';
        }
        const clientGroup = document.getElementById('clientGroup');
        if (clientGroup) {
            clientGroup.value = '';
        }

        // Hide active/inactive headers
        // document.getElementById('active_header_block').style.display = 'none';
        // document.getElementById('inactive_header_block').style.display = 'none';
        document.getElementById('select-status').style.display = 'none';


        // Hide existing image
        document.getElementById('existingImageDiv').style.display = 'none';

        // Open the modal
        $('#createcampaign').modal('show');
    });



    // Initialize Bootstrap modals with static backdrop
    $('.modal.fade').each(function() {
        const modalInstance = new bootstrap.Modal(this, {
            backdrop: 'static',
            keyboard: false
        });

        $(this).on('show.bs.modal', function() {
            modalInstance._config.backdrop = 'static';
            modalInstance._config.keyboard = false;
        });
    });

    // Highlight active navigation item based on current path
    const currentPath = window.location.pathname;
    $('.nav-item a').each(function() {
        const linkHref = $(this).attr('href');
        if (currentPath === linkHref || currentPath.startsWith(linkHref)) {
            $(this).closest('.nav-item').addClass('active');
        } else {
            $(this).closest('.nav-item').removeClass('active');
        }
    });
</script>

@yield('script')

</body>

</html>
