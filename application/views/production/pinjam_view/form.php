<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<span style="padding-right: 100px; " name="vcpinjam" id="vckodepinjam">Code : <?=$vckodepinjam?></span>
				<span style="padding-right: 100px; " id="vcgedung">Building : <?=$vcgedung?></span>
				<span style="padding-right: 100px; " id="vccell">Cell : <?=$vccell?></span>
				<span style="padding-right: 100px; " id="vckaryawan">Borrower : <?=$vckaryawan?></span>
			</div>
		</div>
	</div>
	<hr>

	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				barcode Scan <input type="text" name="vckode" placeholder="Code" id="vckode" class="form-control" value="<?=$vckode?>"/>
				<input type="hidden" name="intpinjam" id="intpinjam" value="<?=$intid?>">
				<input type="hidden" name="vcpinjam" id="vcpinjam" value="<?=$vckodepinjam?>">
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
									<th>Code Pallet</th>
									<th>Model</th>
									<th>Process</th>
									<th>Size</th>
									<th>Side</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody id="datapinjamlist">
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
		var intpinjam       = $('#intpinjam').val();
		var base_url     = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/getdatadefault_ajax/'  + intpinjam,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var datapinjam = jsonData.datapinjam;
			var _html = '';
			for (var i = 0; i < datapinjam.length; i++) {
				_html += '<tr>';
				_html += '<td>' + datapinjam[i].vckode + '</td>';
				_html += '<td>' + datapinjam[i].vcmodel + '</td>';
				_html += '<td>' + datapinjam[i].vcproses + '</td>';
				_html += '<td>' + datapinjam[i].vcsize + '</td>';
				_html += '<td>' + datapinjam[i].vcside + '</td>';
				_html += '<td> <a class="btn btn-xs btn-danger" class="fa fa-trash" onclick="hapusdata(' + datapinjam[i].intid + ')"> Delete </a>  <td>';
				_html += '</tr>';
			}
		
			if (datapinjam.length == 0) {
				_html = '<tr><td colspan="6" align="center">Data Not Found</td></tr>';
			}
			$('#datapinjamlist').html(_html);
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
		var vckode    = $(this).val();
		var intpinjam = $('#intpinjam').val();
		var base_url  = '<?=base_url($controller)?>';
		if (vckode != '') {
			$.ajax({
			url: base_url + '/simpan_pinjam_scan/' + vckode + '/' + intpinjam,
			method: "GET"
			})
			.done(function(data){
				var jsonData  = JSON.parse(data);
				var intstatus = jsonData.intstatus;
				if (intstatus == 2) {
					swal({
						type: 'error',
						title: 'Sorry, Pallet not found !',
						text: 'Checking your code again.'
					});
					$('#vckode').val('');
					$("#vckode").focus();
				} else {
					var datapinjam = jsonData.datapinjam;
					var _html = '';
					for (var i = 0; i < datapinjam.length; i++) {
						_html += '<tr>';
						_html += '<td>' + datapinjam[i].vckode + '</td>';
						_html += '<td>' + datapinjam[i].vcmodel + '</td>';
						_html += '<td>' + datapinjam[i].vcproses + '</td>';
						_html += '<td>' + datapinjam[i].vcsize + '</td>';
						_html += '<td>' + datapinjam[i].vcside + '</td>';
						_html += '<td> <a class="btn btn-xs btn-danger" class="fa fa-trash" onclick="hapusdata(' + datapinjam[i].intid + ')"> Delete </a>  <td>';
						_html += '</tr>';
					}
					if (datapinjam.length == 0) {
					_html = '<tr><td colspan="6" align="center">Data Not Found</td></tr>';
					}
					$('#datapinjamlist').html(_html);

					$('#vckode').val('');
					//$("#vckode").focus();
				}
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		}
	});

	function hapusdata(intid) {
		var intpinjam = $('#intpinjam').val();
		var base_url  = '<?=base_url($controller)?>';
		
		$.ajax({
		url: base_url + '/hapusdata_ajax/' + intid + '/' + intpinjam,
		method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var datapinjam = jsonData.datapinjam;
			var _html = '';
			for (var i = 0; i < datapinjam.length; i++) {
				_html += '<tr>';
				_html += '<td>' + datapinjam[i].vckode + '</td>';
				_html += '<td>' + datapinjam[i].vcmodel + '</td>';
				_html += '<td>' + datapinjam[i].vcproses + '</td>';
				_html += '<td>' + datapinjam[i].vcsize + '</td>';
				_html += '<td>' + datapinjam[i].vcside + '</td>';
				_html += '<td> <a class="btn btn-xs btn-danger" class="fa fa-trash" onclick="hapusdata(' + datapinjam[i].intid + ')"> Delete </a>  <td>';
				_html += '</tr>';
			}
			if (datapinjam.length == 0) {
			_html = '<tr><td colspan="6" align="center">Data Not Found</td></tr>';
			}
			$('#datapinjamlist').html(_html);
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