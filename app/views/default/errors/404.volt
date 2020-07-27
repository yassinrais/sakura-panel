{{ partial('partial/header') }}
<!-- Page Wrapper -->
<div id="wrapper" class="vh-100">

  <!-- Content Wrapper -->
  <div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
 
      <!-- Begin Page Content -->
      <div class="container-fluid">
        <!-- Page Heading -->
 
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
         {% if page.get('description', null) !== null %}
          <p class="mb-4">{{ page.get('description') }}</p>
         {% endif %}
        </div>

          <div class="text-center" style="margin-top:10%">
            <div class="error mx-auto" data-text="404">404</div>
            <p class="lead text-gray-800 mb-5">Page Not Found</p>
            <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
            <a href="../">&larr; Back to Home</a>
          </div>

      </div>
    </div>
  </div>
  <!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->
{{ partial('partial/footer') }}
