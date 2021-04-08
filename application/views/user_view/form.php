<?php
	$passwordhide = ($action == 'Edit') ? 'hidden' : '' ;
?>

<div class="row">
	<div class="col-md-12">
		<div class="box"> 
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div> 

			<div class="box-body">
				<div class="row">
					<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
						<div class="col-md-6">
							<div class="form-group">
								<label>Code</label>
								<input type="text" name="vckode" placeholder="Code" class="form-control" id="vckode" required value="<?=$vckode?>" />
							</div>

							<div class="form-group">
								<label>Name</label>
								<input type="text" name="vcnama" placeholder="Name" class="form-control" id="vcnama" required value="<?=$vcnama?>" />
							</div>

							<div class="form-group">
								<label>Username</label>
								<input type="text" name="vcusername" placeholder="Username" class="form-control" id="vcusername" required value="<?=$vcusername?>" />
							</div>

							<div class="form-group <?=$passwordhide?>">
								<label>Password</label>
								<input type="password" name="vcpassword" placeholder="Password" class="form-control" id="vcpassword" required value="<?=$vcpassword?>" />
							</div>

							<div class="form-group">
								<label>Access Rights</label>
								<select name="inthakakses" class="form-control" id="inthakakses">
									<option data-nama="" value="0">-- Select Access Rights --</option>
									<?php
										foreach ($listhakakses as $opt) {
											$selected = ($inthakakses == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
								<input type="hidden" name="vchakakses" placeholder="User Tipe" class="form-control" id="vchakakses" value="<?=$vchakakses?>" />
							</div>

							<div class="form-group" id="gedungform">
								<label>Building</label>
								<select name="intgedung" class="form-control select2" id="intgedung">
									<option data-nama="" value="0">-- Select Building --</option>
									<?php
										foreach ($listgedung as $opt) {
											$selected = ($intgedung == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select> 
							</div>
						</div>

						<div class="col-md-6">
							
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
								<a href="javascript:void(0);" onclick="simpanData('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
								<a href="<?=base_url($controller . '/view')?>" class="btn btn-danger"><i class="fa fa-close"></i>Close</a>
							</div>
						</div>
					</form>
				</div>
			</div>

		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
		var action = '<?=$action?>';
	    $('.select2').select2();
	    if (action == 'Add') {
		    $('#gedungform').addClass('hidden');
	    }
	});

	function simpanData(action) {
		var vckode       = $('#vckode').val();
		var vcnama       = $('#vcnama').val();
		var vccontroller = $('#vccontroller').val();

		if (action == 'Add') {
			var base_url = '<?=base_url($controller)?>';
			var formrequired = {'vckode' : vckode, 'vcnama' : vcnama};
			var formdata = {'vckode' : vckode, 'vcnama' : vcnama, 'vccontroller' : vccontroller};

			$.ajax({
				url: base_url + '/validasiform/required',
				method: "POST",
				data : formrequired
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				if (jsonData.length > 0) {
					var html = '';
					for (var i = 0; i < jsonData.length; i++) {
						html += '' + jsonData[i].error + '<br/>';
					}

					swal({
						type: 'error',
						title: 'There is something wrong',
						html: html
					});
				} else {
					$.ajax({
						url: base_url + '/validasiform/data',
						method: "POST",
						data : formdata
					})
					.done(function( data ) {
						var jsonData = JSON.parse(data);
						if (jsonData.length > 0) {
							var html = '';
							for (var i = 0; i < jsonData.length; i++) {
								html += '' + jsonData[i].error + '<br/>';
							}

							swal({
								type: 'error',
								title: 'There is something wrong',
								html: html
							});
						} else {
							$('#postdata').submit()
						}
					})
					.fail(function( jqXHR, statusText ) {
						alert( "Request failed: " + jqXHR.status );
					});
				}
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		} else if (action == 'Edit') {
			$('#postdata').submit();
		}
	}

	$('#inthakakses').change(function(){
		var intid = $(this).val();
		var vcnama = $(this).children('option:selected').data('nama');

		$('#vchakakses').val(vcnama);

		if (vcnama == 'Production') {
			$('#gedungform').removeClass('hidden');
		} else {
			$('#gedungform').addClass('hidden');
		}
	});

</script>