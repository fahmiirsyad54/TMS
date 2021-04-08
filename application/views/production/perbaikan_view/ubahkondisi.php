<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Change Condition</h4>
</div>

<div class="modal-body">
	<div class="row">
		<div class="col-sm-12">
			<input type="hidden" name="intid" id="intid"  value="<?=$intid?>" readonly/>
    		<input type="hidden" name="intpallet" id="intpallet"  value="<?=$intpallet?>" readonly/>
			<?php
				if ($intkondisi == 1) {
					$baik        = 'disabled';
					$rusakparah  = 'disabled';
				} else if ($intkondisi == 2) {
					$baik        = '';
					$rusakparah  = '';
				}  else if ($intkondisi == 3) {
					$baik        = 'disabled';
					$rusakparah  = 'disabled';
				}
			?>

			<label><input type="radio" name="intkondisi" id="intkondisi" value="1" <?=$baik?> > Good</label> <br>
			<label><input onclick="kondisi()" type="radio" name="intkondisi" value="0" <?=$rusakparah?> >Destroy</label>
		</div>
		<div class="col-sm-12" id="rak">
			<div class="form-group" >
				<label>Building</label>
				<select name="introom" class="form-control select2" id="introom">
					<option data-nama="" value="0">-- Select Rak Room --</option>
					<?php
						foreach ($listroom as $opt) {
					?>
					<option  value="<?=$opt->introom?>"><?=$opt->vcnama?></option>
					<?php
						}
					?>
				</select>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
	    $('.select2').select2();
		$('#rak').addClass('hidden');
	});

    function kondisi() {
		var base_url  = '<?=base_url($controller)?>';
		var intid     = $('#intid').val();
		var intpallet = $('#intpallet').val();
			$.ajax({
				url: base_url + '/kondisiubah/' + intpallet + '/' + intid,
				method: "GET"
			})
			.done(function( data ) {
				window.location.replace(base_url + "/view");
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
	}

	$('#intkondisi').change(function(){
		$('#rak').removeClass('hidden');
	});

	$('#introom').change(function(){
		var intperbaikan = $('#intid').val();
		var introom      = $(this).val();
		var intpallet    = $('#intpallet').val();
		var base_url     = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/simpanroom/' + intperbaikan + '/' + intpallet + '/' + introom,
			method: "GET"
		})
		.done(function( data ) {
			window.location.replace(base_url + "/view");
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});
</script>