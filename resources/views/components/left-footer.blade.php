<!-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Data Table
    $(document).ready(function() {
        if ( $.fn.DataTable ) {
            $('.datatable').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 10,
                
            });
        }
    });
</script>
    <script>
        const btnToggle = document.getElementById('btn-toggle');
        const btnCloseSidebar = document.getElementById('btn-close-sidebar');
        const sidebar = document.getElementById('layout-menu');
        const layoutPage = document.getElementById('layout-page');
        const overlay = document.getElementById('layout-overlay');
        const logoImg = document.getElementById('logo-img');

        function toggleSidebar() {
            if (window.innerWidth >= 1200) {
                // Desktop Logic
                sidebar.classList.toggle('collapsed');
                layoutPage.classList.toggle('expanded');
                logoImg.style.width = sidebar.classList.contains('collapsed') ? '50px' : '140px';
            } else {
                // Mobile Logic
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('show');
            }
        }

        btnToggle.addEventListener('click', toggleSidebar);
        btnCloseSidebar.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Reset if window resized
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1200) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('show');
            }
        });

    </script>
</body>
</html>