<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<span style="padding-right: 100px; " name="vckembali" id="vckodekembali">Code : <?=$vckodekembali?></span>
				<span style="padding-right: 100px; " id="vcgedung">Building : <?=$vcgedung?></span>
				<span style="padding-right: 100px; " id="vccell">Cell : <?=$vccell?></span>
				<span style="padding-right: 100px; " id="vckaryawan">Returner : <?=$vckaryawan?></span>
			</div>
		</div>
	</div>
	<hr>

	<div class="col-md-12">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label> barcode Scan</label>
					<input type="text" name="vckode" placeholder="Code" id="vckode" class="form-control" value="<?=$vckode?>"/>
					<input type="hidden" name="intkembali" id="intkembali" value="<?=$intid?>">
					<input type="hidden" name="vckembali" id="vckembali" value="<?=$vckodekembali?>">
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
				<form method="POST" id="batal_kembali" action="<?=base_url($controller . '/pembatalan')?>">
					<div class="table-responsive">
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<th>Code Pallet</th>
									<th>Model</th>
									<th>Process</th>
									<th>Size</th>
									<th>Borrower</th>
									<th>Date Borrow</th>
									<th>Condition</th>
									<th>Room</th>
									<th></th>
								</tr>
							</thead>
							<tbody id="datakembalilist">
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
				The pallet returned is correct?
				</b>
			</div>
			<div class="modal-footer">
				<a href="<?=base_url($controller . '/simpanPallet/' . $intid)?>" class="btn btn-danger"><i class="fa fa-info"></i> Yes, Right !</a>
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
			</div>
		</div>
	</div>
</div>

<div id="modalKondisi" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content" id="datakondisi">
			
		</div>
	</div>
</div>

<script type="text/javascript">
	// Set Default Page
	$(function () {
		$("#vckode").focus();
		$('.select2').select2();
		var intkembali       = $('#intkembali').val();
		var base_url     = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/getdatadefault_ajax/'  + intkembali,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var datakembali = jsonData.datakembali;
			var _html = '';
			for (var i = 0; i < datakembali.length; i++) {
				_html += '<tr>';
				_html += '<td>' + datakembali[i].vckode + '</td>';
				_html += '<td>' + datakembali[i].vcmodel + '</td>';
				_html += '<td>' + datakembali[i].vcproses + '</td>';
				_html += '<td>' + datakembali[i].vcsize + '</td>';
				_html += '<td>' + datakembali[i].vcpeminjam + '</td>';
				_html += '<td>' + datakembali[i].dtpinjam + '</td>';
				_html += '<td> <a class="btn btn-xs btn-'+ datakembali[i].vcwarna +'" onclick="ubahkondisi(' + datakembali[i].intid + ')"> ' + datakembali[i].vckondisi + ' </a>  </td>';
				_html += '<td>' + datakembali[i].vcroom + '</td>';
				_html += '<td> <a class="btn btn-xs btn-danger" onclick="hapusdata(' + datakembali[i].intid + ')"> Delete </a>  </td>';
				_html += '</tr>';
			}
		
			if (datakembali.length == 0) {
				_html = '<tr><td colspan="9" align="center">Data Not Found</td></tr>';
			}
			$('#datakembalilist').html(_html);
		})

		.fail(function( jqXHR, statusText ) {
		alert( "Request failed: " + jqXHR.status );
		});

	});

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
	
	// 3. Scan input
	$('#vckode').change(function(){
		var vckode     = $(this).val();
		var intkembali = $('#intkembali').val();
		var introom    = $('#introom').val();
		var base_url   = '<?=base_url($controller)?>';
		if (introom == 0) {
			swal({
				title: "Sorry, the room has not been selected !",
				type: 'warning',
			}).then(function(isConfirm) {
				if (isConfirm) {
					window.location.replace(base_url + "/transaksi/" + intkembali);
				}
			});
		} else {
			if (vckode != '') {
				$.ajax({
				url: base_url + '/simpan_kembali_scan/' + vckode + '/' + intkembali  + '/' + introom,
				method: "GET"
				})
				.done(function(data){
					var jsonData  = JSON.parse(data);
					var intstatus = jsonData.intstatus;
					if (intstatus == 2) {
						swal({
							type: 'error',
							title: 'Sorry, Pallet has ben returned !',
							text: 'Checking your code again.'
						});
						$('#vckode').val('');
						$("#vckode").focus();
					} else {
						var datakembali = jsonData.datakembali;
						var _html = '';
						for (var i = 0; i < datakembali.length; i++) {
							_html += '<tr>';
							_html += '<td>' + datakembali[i].vckode + '</td>';
							_html += '<td>' + datakembali[i].vcmodel + '</td>';
							_html += '<td>' + datakembali[i].vcproses + '</td>';
							_html += '<td>' + datakembali[i].vcsize + '</td>';
							_html += '<td>' + datakembali[i].vcpeminjam + '</td>';
							_html += '<td>' + datakembali[i].dtpinjam + '</td>';
							_html += '<td> <a class="btn btn-xs btn-'+ datakembali[i].vcwarna +'" onclick="ubahkondisi(' + datakembali[i].intid + ')"> ' + datakembali[i].vckondisi + ' </a>  </td>';
							_html += '<td>' + datakembali[i].vcroom + '</td>';
							_html += '<td> <a class="btn btn-xs btn-danger" class="fa fa-trash" onclick="hapusdata(' + datakembali[i].intid + ')"> Delete </a>  </td>';
							_html += '</tr>';
						}
						if (datakembali.length == 0) {
						_html = '<tr><td colspan="9" align="center">Data Not Found</td></tr>';
						}
						$('#datakembalilist').html(_html);

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

	function hapusdata(intid) {
		var intkembali = $('#intkembali').val();
		var base_url  = '<?=base_url($controller)?>';
		
		$.ajax({
		url: base_url + '/hapusdata_ajax/' + intid + '/' + intkembali,
		method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var datakembali = jsonData.datakembali;
			var _html = '';
			for (var i = 0; i < datakembali.length; i++) {
				_html += '<tr>';
				_html += '<td>' + datakembali[i].vckode + '</td>';
				_html += '<td>' + datakembali[i].vcmodel + '</td>';
				_html += '<td>' + datakembali[i].vcproses + '</td>';
				_html += '<td>' + datakembali[i].vcsize + '</td>';
				_html += '<td>' + datakembali[i].vcpeminjam + '</td>';
				_html += '<td>' + datakembali[i].dtpinjam + '</td>';
				_html += '<td> <a class="btn btn-xs btn-'+ datakembali[i].vcwarna +'" onclick="ubahkondisi(' + datakembali[i].intid + ')"> ' + datakembali[i].vckondisi + ' </a>  </td>';
				_html += '<td>' + datakembali[i].vcroom + '</td>';
				_html += '<td> <a class="btn btn-xs btn-danger" class="fa fa-trash" onclick="hapusdata(' + datakembali[i].intid + ')"> Delete </a>  </td>';
				_html += '</tr>';
			}
			if (datakembali.length == 0) {
			_html = '<tr><td colspan="9" align="center">Data Not Found</td></tr>';
			}
			$('#datakembalilist').html(_html);
			$("#vckode").focus();
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	}

	function ubahkondisi(intid) {
		var intkembali = $('#intkembali').val();
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/ubahkondisi/' + intid + '/' + intkembali,
			method: "GET"
		})
		.done(function( data ) {
			$('#datakondisi').html(data);
			$('#modalKondisi').modal('show');
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