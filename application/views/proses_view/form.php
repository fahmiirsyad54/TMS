<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?=$action . ' ' . $title?>
				</div>

				<div class="panel-body">
					<div class="row">
						<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
						<div class="col-md-6">
								<div class="form-group">
									<label>Code</label>
									<input type="text" name="vckode" placeholder="Code" class="form-control" id="vckode" required value="<?=$vckode?>" readonly/>
								</div>

								<div class="form-group">
									<label>Nama</label>
									<input type="text" name="vcnama" id="vcnama" placeholder="Nama" class="form-control" required="true" value="<?=$vcnama?>" />
								</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
								<a href="javascript:void(0);" onclick="simpanData('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i> Save</a>

								<a href="<?=base_url($controller . '/view')?>" class="btn btn-danger"><i class="fa fa-close"></i> Cancel</a>
							</div>
						</div>

						</form>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#selectall').change(function() {
			var selected = $('#selectall').is(':checked');
			if (selected) {
				$('.intmenu').attr('checked',true);
			} else {
				$('.intmenu').removeAttr('checked');
			}
		});
	});

	function simpanData(action) {
		var vckode = $('#vckode').val();
		var vcnama = $('#vcnama').val();

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
						title: 'Terjadi Kesalahan',
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
								title: 'Terjadi Kesalahan',
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