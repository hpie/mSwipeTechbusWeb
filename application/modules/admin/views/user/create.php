<?php echo $form->messages(); ?>

<div class="row">

	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">User Info</h3>
			</div>
			<div class="box-body">
				<?php echo $form->open(); ?>

					<?php if ( !empty($operators) ): ?>
					<div class="form-group">
						<label for="operators">Operators</label>
						<select name="operator_id" class="form-control">
						<option value="" disabled selected>Please select operator</option>
						<?php foreach ($operators as $operator): ?>
							<option value="<?php echo $operator->row_id; ?>"><?php echo $operator->operator_name; ?></option>
						<?php endforeach; ?>
						</select>
					</div>
					<?php endif; ?>


				<!--	<div class="form-group"><label for="first_name">First Name</label><input type="text" name="first_name" value="" id="first_name" class="form-control">
</div> -->


					<?php echo $form->bs3_text('First Name', 'first_name'); ?>
					<?php echo $form->bs3_text('Last Name', 'last_name'); ?>
					<?php echo $form->bs3_text('Username', 'username'); ?>
					<?php echo $form->bs3_text('Email', 'email'); ?>

					<?php echo $form->bs3_password('Password', 'password'); ?>
					<?php echo $form->bs3_password('Retype Password', 'retype_password'); ?>

					<?php if ( !empty($groups) ): ?>
					<div class="form-group">
						<label for="groups">Groups</label>
						<div>
						<?php foreach ($groups as $group): ?>
							<label class="checkbox-inline">
								<input type="checkbox" name="groups[]" value="<?php echo $group->id; ?>"> <?php echo $group->name; ?>
							</label>
						<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php echo $form->bs3_submit(); ?>
					
				<?php echo $form->close(); ?>
			</div>
		</div>
	</div>
	
</div>