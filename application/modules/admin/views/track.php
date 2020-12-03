<?php //echo $form->messages(); ?>
<style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
            position: relative;
			overflow: hidden;
			clear: both;
			height: 500px;
      }
    </style>

	<?php //echo "========>>>>> <pre>"; print_r($this->uri->segment(4)); exit;?>
<div class="row">

	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-body">
				<form class="form-inline" action="" method="post">
					
					<div class="form-group textright" style="margin-left: 20px">
						<label for="pwd">Vehicle Number:</label>

						<?php if(count($all_vehicles) > 0) { ?>
							<select name="bus" id="bus" class="form-control" >
							<option value="" disabled selected>Select Vehicle</option>
							<?php foreach($all_vehicles as $vehicle) {
							?>
							<option value="<?php echo $vehicle->row_id; ?>" <?php echo ($this->uri->segment(4) == $vehicle->row_id) ? 'selected' : ''; ?>><?php echo $vehicle->vehicle_number; ?></option>				
						<?php } ?>
						</select>

						
						<?php } else { ?>
							
						<input type="text" class="form-control" id="bus" name="bus" placeholder="">
						<?php } ?>
						
					</div>
					<!-- <div class="form-group textright" style="margin-left: 20px">
						<label for="pwd">(OR)</label>
					</div>  -->

					<!-- <div class="form-group textright" style="margin-left: 20px">
						<label for="pwd">Route COde :</label>
						<input type="text" class="form-control" id="route" name="route" placeholder="Route Code">
					</div> -->
					

					<div class="form-group textright" style="margin-left: 20px;">						
						<span class="btn btn-info" onclick="getLocation();">Get Location</span>
					</div>

					<span id="location_time_block" style="display:none; float:right;"><label>Location updated at : </label><span id="location_time"></span></span>
				</form> 
			</div>
		</div>
	</div>
	
</div>

<div id="map"></div>
    <script>
		var bus, route, lat, lng ;
		var map;
	  //CHeck for location update
		
		$(document).ready(function() {
			<?php if($this->uri->segment(4)) { ?>
				getLocation();
			<?php } ?>
		});
		function getLocation() {
			$('#location_time_block').hide();
			bus = $("#bus").val();
			route = $("#route").val();
			lat = "";
			lng = "";
			$.ajax({
				url : '<?php echo base_url() . "admin/track/get_location";?>',
				type : 'POST',
				data : {'bus' : bus, 'route' : route, 'lat' : lat, 'long' : lng},
				datatype : 'json',
				success : function (response) {
					var responseObj = jQuery.parseJSON(response);

					if(responseObj.status == 1) {
						$('#location_time_block').show();
						$('#location_time').html(responseObj.location_time);

						console.log("Lat : " + responseObj.latitude + " |||| Long : " + responseObj.longitude);
						initMap(parseFloat(responseObj.latitude), parseFloat(responseObj.longitude));
					} else {
						alert(responseObj.message);
					}
				},
				error: function() {
					alert('something went wrong please try again later!');
				}
			});
		}
      // This example adds a marker to indicate the position of Bondi Beach in Sydney,
      // Australia.
      function initMap(lat = -33.890, lng = 151.274) {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 15,
          //center: {lat: -33, lng: 151}
		  center: {lat: lat, lng: lng}
        });

        var image = 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png';
        var beachMarker = new google.maps.Marker({
          position: {lat: lat, lng: lng},
          map: map,
          icon: image
        });
      }
    </script>
	
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqClHWBhzM49WsmmJTjUgoV_IxJPNPGiU"> </script>