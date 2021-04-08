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
								<label>Parent</label>
								<select name="intparent" class="form-control">
									<option value="0">-- Select Parent --</option>
									<?php
										foreach ($menuheader as $opt) {
											$selected = ($intparent == $opt->intid) ? 'selected' : '';
									?>
									<option <?=$selected?> value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>

							<div class="form-group">
								<label>Controller</label>
								<input type="text" name="vccontroller" placeholder="Controller" class="form-control" id="vccontroller" value="<?=$vccontroller?>" />
							</div>

							<div class="form-group">
								<?php
									$valueaktif = ($intis_header == 1) ? 'checked' : '' ;
									$valuetidakaktif = ($intis_header == 0) ? 'checked' : '' ;
								?>
								<label>Header</label><br>
								<label><input type="radio" name="intis_header" value="1" <?=$valueaktif?>> Active</label> &nbsp;&nbsp;&nbsp;
								<label><input type="radio" name="intis_header" value="0" <?=$valuetidakaktif?>>Not Active</label>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Table</label>
								<input type="text" name="vctabel" placeholder="Tabel" class="form-control" value="<?=$vctabel?>" />
							</div>

							<div class="form-group">
								<label>Icon</label>
								<input type="text" name="vcicon" placeholder="Icon" class="form-control" value="<?=$vcicon?>" />
							</div>

							<div class="form-group">
								<label>Link</label>
								<input type="text" name="vclink" placeholder="Link" class="form-control" value="<?=$vclink?>" />
							</div>

							<div class="form-group">
								<label>Sorter</label>
								<input type="number" name="intsorter" placeholder="Sorter" class="form-control" value="<?=$intsorter?>" />
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
								<a href="javascript:void(0);" onclick="simpanData('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
								<a href="<?=base_url($controller . '/view')?>" class="btn btn-danger"><i class="fa fa-close"></i>Cancel</a>
							</div>
						</div>
					</form>
				</div>
			</div>

		</div>
	</div>
</div>

<script type="text/javascript">
	function simpanData(action) {
		var vckode       = $('#vckode').val();
		var vcnama       = $('#vcnama').val();

		if (action == 'Add') {
			var base_url = '<?=base_url($controller)?>';
			var formrequired = {'vckode' : vckode, 'vcnama' : vcnama};
			var formdata = {'vckode' : vckode, 'vcnama' : vcnama};

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
</script>