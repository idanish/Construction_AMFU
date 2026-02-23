 <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

<script src="{{ asset('assets/js/main.js') }}"></script>

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

<script>
    $(document).ready(function() {
        // 1. DataTable Initialization
        if ($('.datatable').length > 0) {
            $('.datatable').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 10
            });
        }

        // 2. SweetAlert Notifications
        @if (session()->has('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}'
            });
        @endif

        @if (session()->has('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}'
            });
        @endif

        // 3. Dropzone Logic (With Safety Check)
        if (document.getElementById("attachmentDropzone")) {
            Dropzone.autoDiscover = false;

            let attachmentDropzone = new Dropzone("#attachmentDropzone", {
                url: "{{ route('finance.invoices.store') }}", 
                paramName: "attachment[]", 
                maxFiles: 10, 
                acceptedFiles: ".pdf,.jpg,.jpeg,.png,.doc,.docx",
                addRemoveLinks: true,
                uploadMultiple: true,
                parallelUploads: 5,
                autoProcessQueue: false, 
            });

            // Invoice Form Submit Logic
            const invoiceForm = document.getElementById("invoiceForm");
            if (invoiceForm) {
                invoiceForm.addEventListener("submit", function(e) {
                    e.preventDefault();
                    if (attachmentDropzone.getQueuedFiles().length > 0) {
                        attachmentDropzone.processQueue(); 
                    } else {
                        this.submit(); 
                    }
                });
            }

            attachmentDropzone.on("success", function(file, response) {
                if(invoiceForm) invoiceForm.submit(); 
            });
        }
    });
</script>

    
</body>
</html>