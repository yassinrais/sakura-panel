<div class="col-12">
    <!-- DataTales Example -->
          <div class="card shadow mt-0 mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold">{% if row is defined and row.id is defined %}{{ _("Edit") }} {{ _("Role") }} {{ row.id }}{% else %}{{ _("Create New") }} {{ _("Role") }}{% endif %}</h6>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <label>{{ _("Title") }}</label>
                        {{ form.render('title') }} 
                    </div>

                    <div class="form-group">
                        <label>{{ _("Name") }}</label>
                        {{ form.render('name') }} 
                    </div>
                    
                    <div class="form-group pt-4">
                        <label>{{ _("Status") }}</label>
                        {{ form.render('status') }} 
                    </div>
                    
                    <div class="form-group mt-3">
                        <label>{{ _("Inherit from") }}</label>
                        {{ form.render('inherit') }} 
                    </div>
    
                    <div class="form-group"><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ _("Save") }}</button></div>
                </form>
            </div>
        </div>
    </div>