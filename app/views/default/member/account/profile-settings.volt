<div class="col-12">
	<div class="card">
		<div class="card-header">
			<i class="fas fa-cogs"></i> Profile Information
		</div>
		<div class="card-body">
			<form method="post">
				
				<div class="form-group">
					<label for="fullname">Full Name :</label>
					{{ form.render('fullname' , ["placeholder":"Your Full Name"]) }}
				</div>
				
				<div class="form-group">
					<label for="email">Email :</label>
					{{ form.render('email' , ["placeholder":"Your Email Adresse"]) }}
				</div>
				
				<div class="form-group">
					<label for="npassword">New Password :</label>
					{{ form.render('npassword' , ["placeholder":"New Password"]) }}
				</div>
				
				<div class="form-group">
					<label for="cpassword">Confirm Password :</label>
					{{ form.render('cpassword' , ["placeholder":"Confirm Password"]) }}
				</div>


				<hr class="mb-2">

				<div class="form-group">
					<label for="currentPassword">Current Password :</label>
					{{ form.render('currentPassword' , ["placeholder":"Current Password"]) }}
				</div>
				
				
				<div class="form-group">
					<button class="btn btn-info">
						<i class="fas fa-save"></i> Save
					</button>
				</div>
			</form>
		</div>		
	</div>
</div>