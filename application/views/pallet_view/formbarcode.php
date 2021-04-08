<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label> barcode Scan</label>
					<input type="text" name="vckode" placeholder="Code" id="vckode" class="form-control" value="<?=$vckode?>"/>
					<input type="hidden" name="intid" id="intid" value="<?=$intid?>">
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<label>Rack Room</label>
					<select name="introom" class="form-control select2" id="introom">
						<option data-nama="" value="0">-- Select Rack Room --</option>
						<?php
							foreach ($listroom as $opt) {
								$selected = ($introom == $opt->intid) ? 'selected' : '' ;
						?>
						<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
						<?php
							}
						?>
					</select>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header">
				List pallet
			</div>

			<div class="box-body">
				<form method="POST" id="batal_pinjam" action="<?=base_url($controller . '/pembatalan')?>">
					<div class="table-responsive">
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<th>Building</th>
									<th>Code Pallet</th>
									<th>Model</th>
									<th>Process</th>
									<th>Size</th>
									<th>Side</th>
									<th>Room</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody id="datapalletlist">
							</tbody>
						</table>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
							<a href="javascript:void(0);" onclick="selesai()" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
							<a href="javascript:void(0);" onclick="batal()" class="btn btn-danger"><i class="fa fa-close"></i>Cancel</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="modalBatal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticModalLabel">Confirmation Cancel</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<b>
				Are you Sure?
				</b>
			</div>
			<div class="modal-footer">
				<a href="<?=base_url($controller . '/batalSimpan/' . $intid)?>" class="btn btn-danger"><i class="fa fa-info"></i> Yes, Cancel !</a>
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
			</div>
		</div>
	</div>
</div>

<div id="modalSelesai" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticModalLabel">Confirmation Saved</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<b>
				The borrowed pallet is correct?
				</b>
			</div>
			<div class="modal-footer">
				<a href="<?=base_url($controller . '/simpanPallet/' . $intid)?>" class="btn btn-danger"><i class="fa fa-info"></i> Yes, Right !</a>
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	// Set Default Page
	$(function () {
		$("#vckode").focus();
		$('.select2').select2();
		var intid       = $('#intid').val();
		var base_url     = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/getdatadefault_ajax/'  + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var datapallet = jsonData.datapallet;
			var _html = '';
			for (var i = 0; i < datapallet.length; i++) {
				_html += '<tr>';
				_html += '<td>' + datapallet[i].vcgedung + '</td>';
				_html += '<td>' + datapallet[i].vckode + '</td>';
				_html += '<td>' + datapallet[i].vcmodel + '</td>';
				_html += '<td>' + datapallet[i].vcproses + '</td>';
				_html += '<td>' + datapallet[i].vcsize + '</td>';
				_html += '<td>' + datapallet[i].vcside + '</td>';
				_html += '<td>' + datapallet[i].vcroom + '</td>';
				_html += '<td> <a class="btn btn-xs btn-danger" class="fa fa-trash" onclick="hapusdata(' + datapallet[i].intid + ')"> Delete </a>  <td>';
				_html += '</tr>';
			}
		
			if (datapallet.length == 0) {
				_html = '<tr><td colspan="6" align="center">Data Not Found</td></tr>';
			}
			$('#datapalletlist').html(_html);
		})

		.fail(function( jqXHR, statusText ) {
		alert( "Request failed: " + jqXHR.status );
		});

	});
	
	// 3. Scan input
	$('#vckode').change(function(){
		var vckode   = $(this).val();
		var introom  = $('#introom').val();
		var intid    = $('#intid').val();
		var base_url = '<?=base_url($controller)?>';
		if (introom == 0) {
			swal({
				title: "Sorry, the room has not been selected !",
				type: 'warning',
			}).then(function(isConfirm) {
				if (isConfirm) {
					window.location.replace(base_url + "/add_barcode/" + intid);
				}
			});
		} else {
			if (vckode != '') {
				$.ajax({
				url: base_url + '/simpan_pallet_scan/' + vckode + '/' + introom + '/' + intid,
				method: "GET"
				})
				.done(function(data){
					var jsonData  = JSON.parse(data);
					var intstatus = jsonData.intstatus;
					if (intstatus == 1) {
						swal({
							type: 'error',
							title: 'Sorry, Pallet already available !',
							text: 'Checking your code again.'
						});
						$('#vckode').val('');
						$("#vckode").focus();
					} else if (intstatus == 2) {
						swal({
							type: 'error',
							title: 'Sorry, Building not registered !',
							text: 'Checking your code again.'
						});
						$('#vckode').val('');
						$("#vckode").focus();
					} else if (intstatus == 3) {
						swal({
							type: 'error',
							title: 'Sorry, Model not registered !',
							text: 'Checking your code again.'
						});
						$('#vckode').val('');
						$("#vckode").focus();
					} else if (intstatus == 4) {
						swal({
							type: 'error',
							title: 'Sorry, Process not registered !',
							text: 'Checking your code again.'
						});
						$('#vckode').val('');
						$("#vckode").focus();
					} else if (intstatus == 5) {
						swal({
							type: 'error',
							title: 'Sorry, Size not registered !',
							text: 'Checking your code again.'
						});
						$('#vckode').val('');
						$("#vckode").focus();
					} else if (intstatus == 6) {
						swal({
							type: 'error',
							title: 'Sorry, Side not registered !',
							text: 'Checking your code again.'
						});
						$('#vckode').val('');
						$("#vckode").focus();
					} else if (intstatus == 2) {
						swal({
							type: 'error',
							title: 'Sorry, There are data is not valid !',
							text: 'Checking your code again.'
						});
						$('#vckode').val('');
						$("#vckode").focus();
					} else {
						var datapallet = jsonData.datapallet;
						var _html = '';
						for (var i = 0; i < datapallet.length; i++) {
							_html += '<tr>';
							_html += '<td>' + datapallet[i].vcgedung + '</td>';
							_html += '<td>' + datapallet[i].vckode + '</td>';
							_html += '<td>' + datapallet[i].vcmodel + '</td>';
							_html += '<td>' + datapallet[i].vcproses + '</td>';
							_html += '<td>' + datapallet[i].vcsize + '</td>';
							_html += '<td>' + datapallet[i].vcside + '</td>';
							_html += '<td>' + datapallet[i].vcroom + '</td>';
							_html += '<td> <a class="btn btn-xs btn-danger" class="fa fa-trash" onclick="hapusdata(' + datapallet[i].intid + ')"> Delete </a>  <td>';
							_html += '</tr>';
						}
					
						if (datapallet.length == 0) {
							_html = '<tr><td colspan="6" align="center">Data Not Found</td></tr>';
						}
						$('#datapalletlist').html(_html);

						$('#vckode').val('');
						//$("#vckode").focus();
					}
				})
				.fail(function( jqXHR, statusText ) {
					alert( "Request failed: " + jqXHR.status );
				});
			}
		}
		
	});

	function hapusdata(intpallet) {
		var intid = $('#intid').val();
		var base_url  = '<?=base_url($controller)?>';
		
		$.ajax({
		url: base_url + '/hapusdata_ajax/' + intpallet + '/' + intid,
		method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var datapallet = jsonData.datapallet;
			var _html = '';
			for (var i = 0; i < datapallet.length; i++) {
				_html += '<tr>';
				_html += '<td>' + datapallet[i].vcgedung + '</td>';
				_html += '<td>' + datapallet[i].vckode + '</td>';
				_html += '<td>' + datapallet[i].vcmodel + '</td>';
				_html += '<td>' + datapallet[i].vcproses + '</td>';
				_html += '<td>' + datapallet[i].vcsize + '</td>';
				_html += '<td>' + datapallet[i].vcside + '</td>';
				_html += '<td>' + datapallet[i].vcroom + '</td>';
				_html += '<td> <a class="btn btn-xs btn-danger" class="fa fa-trash" onclick="hapusdata(' + datapallet[i].intid + ')"> Delete </a>  <td>';
				_html += '</tr>';
			}
		
			if (datapallet.length == 0) {
				_html = '<tr><td colspan="6" align="center">Data Not Found</td></tr>';
			}
			$('#datapalletlist').html(_html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	}

	function batal(){
		$('#modalBatal').modal('show');
	}

	function selesai(){
		$('#modalSelesai').modal('show');
	}
</script>