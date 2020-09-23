<div class="col-12">
	<div class="card">
		<div class="card-header">
			<i class="fas fa-cogs"></i> {{ locale._("Profile Information") }}
		</div>
		<div class="card-body">
			<form method="post">
				
				<div class="form-group">
					<label for="fullname">{{ locale._('Full Name') }} :</label>
					{{ form.render('fullname' , ["placeholder": locale._("Your Full Name") ]) }}
				</div>
				
				<div class="form-group">
					<label for="email">Email :</label>
					{{ form.render('email' , ["placeholder": locale._("Your Email Adresse") ]) }}
				</div>
				
				<div class="form-group">
					<label for="npassword">{{ locale._("New Password") }} :</label>
					{{ form.render('npassword' , ["placeholder": locale._("New Password")]) }}
				</div>
				
				<div class="form-group">
					<label for="cpassword">{{ locale._("Confirm Password") }} :</label>
					{{ form.render('cpassword' , ["placeholder": locale._("Confirm Password")]) }}
				</div>


				<hr class="mb-2">

				<div class="form-group">
					<label for="currentPassword">{{ locale._("Current Password") }} :</label>
					{{ form.render('currentPassword' , ["placeholder": locale._("Current Password")]) }}
				</div>
				
				
				<div class="form-group">
					<button class="btn btn-info">
						<i class="fas fa-save"></i> {{ locale._("Save") }}
					</button>
				</div>
			</form>
		</div>		
	</div>
</div>