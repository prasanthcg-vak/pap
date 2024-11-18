    <!-- Bootstrap 5.2 JS cdn link-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

    {{-- <script type="text/javascript"  src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script> --}}
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>

    @yield('script')

    <script>
        new DataTable('#datatable');
    </script>
    <script>
        document.getElementById('uploadAsset').addEventListener('click', function() {
            // Toggle the 'img-upload-con' visibility
            document.querySelector('.img-upload-con').classList.toggle('d-none');
        });

        const toastEl1 = document.getElementById('cancel');

        toastEl1.addEventListener('click', function() {
            location.reload();

            // alert('Cancel button clicked!');
        });

        const toastEl2 = document.getElementById('model-close');

        toastEl2.addEventListener('click', function() {
            location.reload();

            // alert('Cancel button clicked!');
        });
        document.addEventListener('DOMContentLoaded', function() {
    // Select all elements with the modal class `.modal.fade`
    document.querySelectorAll('.modal.fade').forEach((modalElement) => {
        // Initialize each modal with options
        const modalInstance = new bootstrap.Modal(modalElement, {
            backdrop: 'static',
            keyboard: false
        });

        // Optional: attach event listeners for opening modals with the specified options
        modalElement.addEventListener('show.bs.modal', function() {
            modalInstance._config.backdrop = 'static';
            modalInstance._config.keyboard = false;
        });
    });
});

                    // Show the modal with the specified options
                    // myModal.show();
    </script>


    </body>

    </html>
