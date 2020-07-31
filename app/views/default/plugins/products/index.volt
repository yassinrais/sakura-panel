<div class="col-12">
<!-- DataTales Example -->
  <div class="card shadow mt-0 mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold">Products Manager</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="discordbots-dataTable" width="100%" cellspacing="0">
          <thead>
            <th>#ID</th>
            <th>Name</th>
            <th>Title</th>
            <th>Note</th>
            <th>Image</th>
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
            {data: "id"},
            {data: "name"},
            {data: "title"},
            {data: "note"},
            {data: "image"},
            {data: "c_status"},
            {data: "c_actions"}
        ]
    });
  });
</script>