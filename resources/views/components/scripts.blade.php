 <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Dropzone JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"
        integrity="sha512-SnHkO9cP47yt0J6fH9o7hF3V7jLzRRYxChh9z7nTS+7A17R37Cy6x8G4fXajNwT6SRWKmFFDKRP8+bdj9dErYw=="
        crossorigin="anonymous"></script>

    <!-- J Queery CDN  -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- DataTables JS  -->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // let table = new DataTable('#myTable');

        document.addEventListener('DOMContentLoaded', function() {
            @if (session()->has('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if (session()->has('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        });
        Dropzone.autoDiscover = false;

        let attachmentDropzone = new Dropzone("#attachmentDropzone", {
            url: "{{ route('finance.invoices.store') }}", 
            paramName: "attachment", 
            maxFiles: 1, 
            acceptedFiles: ".pdf,.jpg,.jpeg,.png",
            addRemoveLinks: true,
            autoProcessQueue: false, 
        });

        
        document.getElementById("invoiceForm").addEventListener("submit", function(e) {
            e.preventDefault();

            if (attachmentDropzone.getQueuedFiles().length > 0) {
                attachmentDropzone.processQueue(); 
            } else {
                this.submit(); /
            }
        });

        
        attachmentDropzone.on("success", function(file, response) {
            document.getElementById("invoiceForm").submit(); 
        });
    </script>

    
</body>
</html>