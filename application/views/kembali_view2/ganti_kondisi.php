<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Change Pallet Condition</h4>
</div>   
<div class="modal-body">
    <div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<?php
				if ($intkondisi == 1) {
					$baik        = 'disabled';
					$rusakringan = '';
					$rusakparah  = '';
				} else if ($intkondisi == 2) {
					$baik        = 'disabled';
					$rusakringan = 'disabled';
					$rusakparah  = 'disabled';
				}  else if ($intkondisi == 3) {
					$baik        = 'disabled';
					$rusakringan = 'disabled';
					$rusakparah  = 'disabled';
				}
				?>

				<input type="hidden" name="intid" id="intid" value="<?=$intid?>">
				<input type="hidden" name="intkembali" id="intkembali" value="<?=$intkembali?>">
				<button onclick="kondisi(1)" class="btn btn-success btn-block" <?=$baik?>>Good</button>
				<button onclick="kondisi(2)" class="btn btn-warning btn-block" <?=$rusakringan?>>Broken</button>
				<button onclick="kondisi(3)" class="btn btn-danger btn-block" <?=$rusakparah?>>Destroy</button>
			</div>
		</div>
    </div>
</div>

<script type="text/javascript">
	function kondisi(intkondisi){
		var base_url   = '<?=base_url($controller)?>';
		var intid      = $('#intid').val();
		var intkembali = $('#intkembali').val();
			$.ajax({
				url: base_url + '/ubahkondisi_ajax/' + intkondisi + '/'  + intid + '/' + intkembali,
				method: "GET"
			})
			.done(function( data ) {
				window.location.replace(base_url + "/edit/" + intkembali);
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
	}
</script>