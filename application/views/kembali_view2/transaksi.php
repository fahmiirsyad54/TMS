<div class="row">
	<div class="box">
		<div class="box-header with-border">
			<div class="col-md-12">
				<span style="padding-right: 100px; " name="vcpinjam" id="vckodepinjam">Code : <?=$vckodekembali?></span>
				<span style="padding-right: 100px; " id="vcgedung">Building : <?=$vcgedung?></span>
				<span style="padding-right: 100px; " id="vccell">Cell : <?=$vccell?></span>
				<span style="padding-right: 100px; " id="vckaryawan">Borrower : <?=$vckaryawan?></span>
			</div> <hr>

			<div class="box-header">
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
			</div>

			<div class="col-md-6">
				<div class="box box-default">
					<div class="box-body">
						<label> Data Pallet Borrow </label>
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th>Code Pallet</th>
										<th>Model</th>
										<th>Process</th>
										<th>Size</th>
									</tr>
								</thead>
								<tbody id="datapinjamlist">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="box box-primary">
				<div class="box-body">
					<label> Data Pallet Return </label>
					<div class="table-responsive">
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<th>Code Pallet</th>
									<th>Model</th>
									<th>Process</th>
									<th>Size</th>
									<th>Room</th>
								</tr>
							</thead>
							<tbody id="datakembalilist">
							</tbody>
						</table>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<a href="<?=base_url( $controller . '/view')?>" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
							<a href="<?=base_url($controller . '/edit/' . $intid)?>" class="btn btn-danger"><i class="fa fa-wrench"></i> Add Damage</a>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	// Set Default Page
	$(function () {
		$('.select2').select2();
		$("#vckode").focus();
		var intkembali = $('#intkembali').val();
		var base_url   = '<?=base_url("kembali")?>';
		$.ajax({
			url: base_url + '/getdatadefault_ajax/'  + intkembali,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var datakembali = jsonData.datakembali;
			var html = '';
			for (var i = 0; i < datakembali.length; i++) {
				html += '<tr>';
				html += '<td>' + datakembali[i].vckode + '</td>';
				html += '<td>' + datakembali[i].vcmodel + '</td>';
				html += '<td>' + datakembali[i].vcproses + '</td>';
				html += '<td>' + datakembali[i].vcsize + '</td>';
				html += '<td>' + datakembali[i].vcroom + '</td>';
				html += '</tr>';
			}
			if (datakembali.length == 0) {
				html = '<tr><td colspan="5" align="center">Data Not Found</td></tr>';
			}
			$('#datakembalilist').html(html);

			var datapinjam = jsonData.datapinjam;
			var _html = '';
			for (var j = 0; j < datapinjam.length; j++) {
				_html += '<tr>';
				_html += '<td>' + datapinjam[j].vckode + '</td>';
				_html += '<td>' + datapinjam[j].vcmodel + '</td>';
				_html += '<td>' + datapinjam[j].vcproses + '</td>';
				_html += '<td>' + datapinjam[j].vcsize + '</td>';
				_html += '</tr>';
			}
			if (datapinjam.length == 0) {
				_html = '<tr><td colspan="5" align="center">Data Not Found</td></tr>';
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
	
	$('#vckode').change(function(){
		var vckode     = $('#vckode').val();
		var intkembali = $('#intkembali').val();
		var introom    = $('#introom').val();
		var base_url   = '<?=base_url($controller)?>';
		if (introom == 0) {
				swal({
						type: 'error',
						title: 'Sorry, the room has not been selected !'
					});
		} else {
			$.ajax({
			url: base_url + '/simpan_kembali_scan/' + vckode + '/' + intkembali  + '/' + introom,
			method: "GET"
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
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
					var datakembali = jsonData.datakembali;
					var html = '';
					for (var i = 0; i < datakembali.length; i++) {
						html += '<tr>';
						html += '<td>' + datakembali[i].vckode + '</td>';
						html += '<td>' + datakembali[i].vcmodel + '</td>';
						html += '<td>' + datakembali[i].vcproses + '</td>';
						html += '<td>' + datakembali[i].vcsize + '</td>';
						html += '<td>' + datakembali[i].vcroom + '</td>';
						html += '</tr>';
					}
					if (datakembali.length == 0) {
					_html = '<tr><td colspan="6" align="center">Data Not Found</td></tr>';
					}
					$('#datakembalilist').html(html);
					
					var datapinjam = jsonData.datapinjam;
					var _html = '';
					for (var i = 0; i < datapinjam.length; i++) {
						_html += '<tr>';
						_html += '<td>' + datapinjam[i].vckode + '</td>';
						_html += '<td>' + datapinjam[i].vcmodel + '</td>';
						_html += '<td>' + datapinjam[i].vcproses + '</td>';
						_html += '<td>' + datapinjam[i].vcsize + '</td>';
						_html += '</tr>';
					}
					if (datapinjam.length == 0) {
					_html = '<tr><td colspan="6" align="center">Data Not Found</td></tr>';
					}
					$('#datapinjamlist').html(_html);

					$('#vckode').val('');
					$("#vckode").focus();
				}
			})
			.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
			});
		}
		
		
	});
	
</script>