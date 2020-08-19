<div class="col-12">
    <!-- DataTales Example -->
          <div class="card shadow mt-0 mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold">{% if row is defined and row.id is defined %}Edit User {{ row.id }}{% else %}Create New User{% endif %}</h6>
            </div>
            <div class="card-body">
                <form method="post" >
                    <div class="form-group pt-3">
                        <label class="text-primary h5" ><b>Personnel :</b></label>
                        <div class="form-group mt-3"><label>Full Name</label> {{ form.render('fullname') }} </div>
                    </div>
                    <div class="form-group pt-3">
                        <label class="text-primary h5" ><b>Authentification :</b></label>
                        <div class="form-group mt-3"><label>Role</label> {{ form.render('role_name') }} </div>
                        <div class="form-group"><label>Username</label> {{ form.render('username') }} </div>
                        <div class="form-group"><label>Email</label> {{ form.render('email') }} </div>
                        <div class="form-group"><label>Password</label> {{ form.render('password') }} </div>
                    </div>

                    
                    <div class="form-group pt-4"><label>Status</label> {{ form.render('status') }} </div>
    
                    <div class="form-group"><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button></div>
                </form>
            </div>
        </div>
    </div>