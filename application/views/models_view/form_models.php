<div class="control-group">
	    <div class="row">
	    	<div class="col-md-6">
	    		<div class="form-group">
				<label>Process</label>
					<select name="intproses[]" class="form-control intproses select2" id="intproses">
						<option value="0">-- Select Process --</option>
						<?php
							foreach ($listproses as $opt) {
						?>
						<option data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
						<?php
							}
						?>
					</select>
				</div>
	    	</div>
			
			<div class="col-md-2 margin-top-25">
				<div class="form-group">
					<a href="javascript:void(0)" class="btn btn-danger remove"><i class="fa fa-remove"></i></a>
					<!-- <button class="btn btn-danger remove margin-top-25" type="button"><i class="glyphicon glyphicon-remove"></i></button> -->
				</div>
			</div>
		</div>
</div>

<script type="text/javascript">
	$(function () {
	    //Initialize Select2 Elements
	    $('.select2').select2()
	});

	$('.intkomponen').change(function(){
		var row         = $(this).closest(".row");
		var intkomponen = row.find('.intkomponen').val();
		
		var base_url = '<?=base_url("models")?>';
		$.ajax({
			url: base_url + '/getintkomponen/' + intkomponen,
			method: "GET"
		})
		.done(function( data ) {
			var result = JSON.parse(data);
			
			row.find('.intkomponenct').val(result[0].intid);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});

		});

</script>