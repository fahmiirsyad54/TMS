<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div>

			<div class="box-body">
				<div class="row">
					<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
					<div class="col-md-4">
							<div class="form-group">
								<label>Code</label>
								<input type="text" name="vckode" id="vckode" placeholder="Code" class="form-control" required="true" value="<?=$vckode?>" />
							</div>

							<div class="form-group">
								<label>Name</label>
								<input type="text" name="vcnama" id="vcnama" placeholder="Name" class="form-control" required="true" value="<?=$vcnama?>" />
							</div>
					</div>

					<div class="col-md-8">
						<div class="table table-resposive">
							<label>List Menu</label>
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>Group</th>
										<th><input type="checkbox" name="selectall" id="selectall"> Menu</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$maksesmenu = array();
										foreach ($aksesmenu as $datamenu) {
											array_push($maksesmenu,$datamenu->intmenu);
										}

										
										foreach ($listmenu as $opt) { 
											$checked = '';
											$checked = (in_array($opt->intid, $maksesmenu)) ? 'checked' : '' ;
									?>
									<tr>
										<td><?=$opt->vcparent?></td>
										<td><input type="checkbox" class="intmenu" name="intmenu[]" value="<?=$opt->intid?>" <?=$checked?>> <?=$opt->vcnama?></td>
									</tr>
									<?php
										}
									?>
								</tbody>
							</table>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
							<a href="javascript:void(0);" onclick="simpanData('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i>Save</a>

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