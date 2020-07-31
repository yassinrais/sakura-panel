<div class="col-12">
<!-- DataTales Example -->
  	<div class="card shadow mt-0 mb-4">
	    <div class="card-header py-3">
	      <h6 class="m-0 font-weight-bold">{% if row is defined and row.id is defined %}Edit Bot {{ row.id }}{% else %}Create New Bot{% endif %}</h6>
	    </div>
	    <div class="card-body">
	    	<form method="post" >
	    		<div class="form-group"><label>Name</label> {{ form.render('name') }} </div>
		    	<div class="form-group"><label>Title</label> {{ form.render('title') }} </div>
		    	<div class="form-group"><label>Note</label> {{ form.render('note' , ['onKeyUp':'$(".total_chars").text(this.value.length);']) }} <span class="text-mute float-right"><i class="total_chars">{{ form.get('note').getValue() | length }}</i>/500</span></div>
		    	<div class="form-group"><label>IP</label> {{ form.render('ip') }} </div>
		    	<div class="form-group"><label>HostName</label> {{ form.render('hostname') }} </div>
		    	<div class="form-group"><label>Port</label> {{ form.render('port') }} </div>
		    	<div class="form-group"><label>Status</label> {{ form.render('status') }} </div>


		    	<div class="form-group"><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button></div>
	    	</form>
	    </div>
	</div>
</div>