<div class="col-12">
	<div class="card">
		<div class="card-header">
			<i class="fas fa-cogs"></i> {{ _("Profile Information") }}
		</div>
		<div class="card-body">
			<form method="post">
				
				<div class="form-group">
					<label for="fullname">{{ _('Full Name') }} :</label>
					{{ form.render('fullname' , ["placeholder": _("Your Full Name") ]) }}
				</div>
				
				<div class="form-group">
					<label for="email">Email :</label>
					{{ form.render('email' , ["placeholder": _("Your Email Adresse") ]) }}
				</div>
				
				<div class="form-group">
					<label for="npassword">{{ _("New Password") }} :</label>
					{{ form.render('npassword' , ["placeholder": _("New Password")]) }}
				</div>
				
				<div class="form-group">
					<label for="cpassword">{{ _("Confirm Password") }} :</label>
					{{ form.render('cpassword' , ["placeholder": _("Confirm Password")]) }}
				</div>


				<hr class="mb-2">

				<div class="form-group">
					<label for="currentPassword">{{ _("Current Password") }} :</label>
					{{ form.render('currentPassword' , ["placeholder": _("Current Password")]) }}
				</div>
				
				
				<div class="form-group">
					<button class="btn btn-info">
						<i class="fas fa-save"></i> {{ _("Save") }}
					</button>
				</div>
			</form>
		</div>		
	</div>
</div>