<div class="col-12">
	<div class="card">
		<div class="card-header">
			<i class="fas fa-cogs"></i> {{ _("Profile Information") }}
		</div>
		<div class="card-body">
			<form method="post" enctype="multipart/form-data">
				
				<div class="row">
					{% for item in form.getElements() %}
					{% if item.getAttribute('placeholder') is not null  %}
					<div class="form-group col-lg-6">
						<label for="fullname">{{ _(item.getAttribute('placeholder')) }} :</label>
						{{ item.render() }}
					</div>
					{% endif %}
					{% endfor %}
				</div>
				<div class="form-group text-center">
					<button class="btn btn-info">
						<i class="fas fa-save"></i> {{ _("Save") }}
					</button>
				</div>
			</form>
		</div>		
	</div>
</div>