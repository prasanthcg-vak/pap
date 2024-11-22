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


@yield('script')

<script>
    new DataTable('#datatable');
</script>
<script>
    var uploadAsset = document.getElementById('uploadAsset');
    if (uploadAsset) {

        document.getElementById('uploadAsset').addEventListener('click', function() {
            // Toggle the 'img-upload-con' visibility
            document.querySelector('.img-upload-con').classList.toggle('d-none');
        });
    }

    // Handle modal cancel button
    $('#cancel, #model-close').on('click', function() {
        location.reload();
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

<!-- Your custom scripts -->
<script src="{{ asset('assets/js/main.js') }}"></script>

@yield('script')

</body>

</html>
