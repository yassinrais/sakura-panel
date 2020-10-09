<div class="col-12">
<!-- DataTales Example -->
  <form method="post">
    <div class="card shadow mt-0 mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Create New File</h6>
      </div>
      <div class="card-body">
        <div class="form-group">
            <label>File Name</label>
            {{ form.render('name') }}
        </div>
        <div class="form-group">
            <label>File Type</label>
            {{ form.render('type') }}
        </div>
      </div>
      <div class="card-footer">
        <button class="btn btn-info" name="action" value="create" ><i class="fa fa-save"></i> Create</button>
      </div>
    </div>
  </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function(event) {
  
  var editor = CodeMirror.fromTextArea(document.getElementById('editorjs'), {
    "lineNumbers": true
  });

});
</script>