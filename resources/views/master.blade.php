
   <x-header/>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

    <x-mobile/>
    <x-sidebar/>

    <x-main/>
    
            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
    </div>

   <x-scripts/>

   <script>
    window.setTimeout(function() {
      $(".alert").fadeTo(500, 0).slideUp(500, function(){
          $(this).remove(); 
      });
  }, 600);
   </script>
