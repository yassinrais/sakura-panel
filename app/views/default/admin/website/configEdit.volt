<div class="col-12">
	<div class="card">
		<div class="card-header">
			<i class="fas fa-cogs"></i> Edit Config ID#{{ row.id }} 
		</div>
		<div class="card-body">
			<form method="post">
				
				<div class="form-group">
					<label for="key">Key Name :</label>
					{{ form.render('key' , ["placeholder":"Config KeyName"]) }}
				</div>
				
				<div class="form-group">
					<label for="value">Value :</label>
					{{ form.render('val' , ["placeholder":"Config Value"]) }}
				</div>

				<hr class="mb-2">
				
				<div class="form-group">
					<button class="btn btn-info">
						<i class="fas fa-save"></i> Save
					</button>
				</div>
			</form>
		</div>		
	</div>
</div>