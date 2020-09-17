<div class="col-12">
<!-- DataTales Example -->
  <form method="post">
    <div class="card shadow mt-0 mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Edit File "{{ file_name | e }}.{{ file_extension | e }}"</h6>
      </div>
      <div class="card-body">
          <textarea id="editorjs"  name="content">{{ file_content | e }}</textarea>
      </div>
      <div class="card-footer">
        <button class="btn btn-info" name="action" value="save" ><i class="fa fa-save"></i> Save</button>
        <button class="btn btn-warning" name="action" value="restore"><i class="fa fa-save"></i> Restore Last Version</button>
      </div>
    </div>
  </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function(event) {
  
  var editor = CodeMirror.fromTextArea(document.getElementById('editorjs'), {
    "lineNumbers": true,
    "mode": "{{ file_type }}"
  });

});
</script>