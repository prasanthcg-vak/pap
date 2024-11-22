    <!-- Bootstrap 5.2 JS cdn link-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"
        integrity="sha512-FHZVRMUW9FsXobt+ONiix6Z0tIkxvQfxtCSirkKc5Sb4TKHmqq1dZa8DphF0XqKb3ldLu/wgMa8mT6uXiLlRlw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

    {{-- <script type="text/javascript"  src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script> --}}
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>


    <!-- Select2 JS -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}


    <script>
        new DataTable('#datatable');

        var uploadAsset = document.getElementById('uploadAsset');

        if (uploadAsset) {
            document.getElementById('uploadAsset').addEventListener('click', function() {
                // Toggle the 'img-upload-con' visibility
                document.querySelector('.img-upload-con').classList.toggle('d-none');
            });
        }

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
        document.addEventListener("DOMContentLoaded", function() {
            const navItems = document.querySelectorAll(".nav-item");
            const currentPath = window.location.pathname;

            // Loop through each nav item and check if it matches the current path
            navItems.forEach(function(navItem) {
                const link = navItem.querySelector("a");
                const linkHref = link ? link.getAttribute("href") : "";

                // Compare the href with the current path
                if (currentPath === linkHref || currentPath.startsWith(linkHref)) {
                    navItem.classList.add("active"); // Add active class to matching item
                } else {
                    navItem.classList.remove("active"); // Remove active class from non-matching items
                }
            });
        });
    </script>

    @yield('script')
    </body>

    </html>
