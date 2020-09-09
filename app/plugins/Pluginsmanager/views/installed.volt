<div class="col-12">
<!-- DataTales Example -->
  <div class="card shadow mt-0 mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold">Plugins Manager</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="discordbots-dataTable" width="100%" cellspacing="0">
          <thead>
            <th style="min-wdith:20%">Plugin</th>
            <th style="max-width:40%">Description</th>
            <th>Author</th>
            <th>Version</th>
            <th>Status</th>
            <th>Actions</th>
          </thead>
          <tfoot>
          </tfoot>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  window.addEventListener("DOMContentLoaded", (event) => {
    $('#discordbots-dataTable').DataTable({
        serverSide: true,
        ajax: {
            url: '{{ url(page.get('base_route') ~ '/ajax') }}',
            method: 'POST'
        },
        columns: [
            {data: "c_plugin"},
            {data: "description"},
            {data: "author"},
            {data: "version"},
            {data: "c_status"},
            {data: "c_actions"}
        ]
    });
  });
</script>