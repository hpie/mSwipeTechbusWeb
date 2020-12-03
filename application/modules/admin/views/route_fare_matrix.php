<?php echo $form->messages(); ?>

<div class="row">

	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Generate Route Fare Matrix</h3>
			</div>
			<div class="box-body">
				<?php echo $form->open(); ?>

					<?php if ( !empty($operators) ): ?>
					<div class="form-group">
						<label for="operators">Operators</label>
						<select id="operator_id" name="operator_id" class="form-control">
						<option value="" disabled selected>Please select operator</option>
						<?php foreach ($operators as $operator): ?>
							<option value="<?php echo $operator->row_id; ?>"><?php echo $operator->operator_name; ?></option>
						<?php endforeach; ?>
						</select>
					</div>
					<?php endif; ?>

					<div class="form-group" id="routes_div"> </div>

					<div class="form-group" id="vehicle_type_div"> </div>

					<?php echo $form->bs3_submit(); ?>
					
				<?php echo $form->close(); ?>
			</div>
		</div>
	</div>
	
</div>

<script>
$(document).on('change', '#operator_id', function() {
	//alert($(this).val());
	$.ajax({
		url : '<?php echo base_url() . "admin/RouteMatrix/get_routes";?>',
		type : 'POST',
		data : {'operator_id' : $(this).val()},
		datatype : 'html',
		success : function (response) {
			$('#routes_div').html(response);
		},
		error: function() {
			alert('something went wrong please try again later!');
		}
	});

	$.ajax({
		url : '<?php echo base_url() . "admin/RouteMatrix/get_vehicle_types";?>',
		type : 'POST',
		data : {'operator_id' : $(this).val()},
		datatype : 'html',
		success : function (response) {
			$('#vehicle_type_div').html(response);
		},
		error: function() {
			alert('something went wrong please try again later!');
		}
	});
});
</script>