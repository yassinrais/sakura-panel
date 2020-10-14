{{ partial('partial/header') }}
<!-- Page Wrapper -->
<div id="wrapper">

  <!-- Sidebar -->
  {{ partial('partial/panel/sidebar') }}
  <!-- End of Sidebar -->

  
  <!-- Content Wrapper -->
  <div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
      <!-- Topbar -->
      {{ partial('partial/panel/topbar') }}
      <!-- End of Topbar -->


      <!-- Begin Page Content -->
      <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
          <h1 class="h3 mb-0 text-gray-800">{{ _(page.get('title')) }}</h1>
          <a href="javascript:window.history.go(-1)" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-arrow-left fa-sm text-white-50"></i> {{ _("Go back") }}</a>
        </div>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
         {% if page.get('description', null) !== null %}
          <p class="mb-4">{{ _(page.get('description')) }}</p>
         {% endif %}
        </div>

        <div class="row">
          <div class="col-12">{{ flash.output() }}
          {{ flashSession.output() }}</div>  
          {{ content() }}
        </div>
      </div>
    </div>
    <!-- End of Main Content -->
    <!-- Footer -->
    <footer class="sticky-footer bg-white">
      <div class="container my-auto">
        <div class="copyright text-center my-auto">
          <span>{{ _("Copyright") }} &copy; {{ site.get('app-name') }} 2020-{{ date('Y') }}
          </span>
        </div>
      </div>
    </footer>
    <!-- End of Footer -->
  </div>
  <!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->
{{ partial('partial/footer') }}
