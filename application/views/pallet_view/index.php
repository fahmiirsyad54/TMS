<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-md-2 margin-bottom-10">
						<a href="javascript:void();" onclick="add()" class="btn btn-primary"><i class="fa fa-plus"> Add New Data</i></a>
						<!-- <a href="<?=base_url($controller . '/add')?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Data</a> -->
					</div>

					<div class="col-md-10">
						<div class="row">
							<form method="GET" action="<?=base_url($controller . '/view')?>">
								<div class="col-md-2">
									<select name="intgedung" class="form-control select2" id="intgedung">
										<option value="0">-- All Building --</option>
										<?php
											foreach ($listgedung as $opt) {
												$selected = ($opt->intid == $intgedung) ? 'selected' : '';
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>

								<div class="col-md-2">
									<select name="intmodel" class="form-control select2" id="intmodel">
										<option value="0">-- All Models --</option>
										<?php
											foreach ($listmodel as $opt) {
												$selected = ($opt->intid == $intmodel) ? 'selected' : '';
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>

								<div class="col-md-4">
									<select name="intproses" class="form-control select2" id="intproses">
										<option value="0">-- All Process --</option>
										<?php
											foreach ($listproses as $opt) {
												$selected = ($opt->intid == $intproses) ? 'selected' : '';
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>

								<div class="col-md-3">
									<input type="text" class="form-control" name="key" placeholder="Enter data for search">
								</div>

								<div class="col-md-1">
									<button class="btn btn-default" type="sbumit"><i class="fa fa-search"></i></button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Code</th>
								<th>Building</th>
								<th>Model</th>
                                <th>process</th>
                                <th>Size</th>
								<th>Location</th>
								<th>Status</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
								$jmldata = count($dataP);
								if ($jmldata === 0) {
							?>
								<tr>
									<td colspan="8" align="center">Data Not Found</td>
								</tr>
							<?php
								} else {
									$no = $firstnum;
									foreach ($dataP as $data) {
							?>
								<tr>
									<td><?=++$no?></td>
									<td><?=$data->vckode?></td>
									<td><?=$data->vcgedung?></td>
									<td><?=$data->vcmodel?></td>
									<td><?=$data->vcproses?></td>
									<td><?=$data->vcsize?></td>
									<td><?=$data->vclokasi?></td>
									<td><span class="label label-<?=$data->vcwarna?>"><?=$data->vcstatus?></span></td>
									<td>
										<a href="javascript:void(0);" onclick="detailData(<?=$data->intid?>)" class="btn btn-xs btn-info"><i class="fa fa-info"></i> Detail</a>

										<a href="<?=base_url($controller . '/edit/' . $data->intid)?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i> Edit</a>
									</td>
								</tr>
							<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>

				<?php
					$link = base_url($controller . '/view');
					echo pagination2($halaman, $link, $jmlpage, $intgedung, $intmodel, $intproses, $keyword);
				?>
			</div>

		</div>
	</div>
</div>

<!-- Modal -->
<div id="modalDetail" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content" id="datadetail">
		</div>
	</div>
</div>

<div id="modalExcel" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12" style="text-align:center">
						<a href="<?=base_url($controller . '/add')?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add by Manual</a>
					</div>
					<br> <br>
					<div class="col-sm-12" style="text-align:center">
						<a href="<?=base_url($controller . '/addbarcode')?>" class="btn btn-warning"><i class="fa fa-barcode"></i> Add by Barcode</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
	    //Initialize Select2 Elements
	    $('.select2').select2()
	});

	function detailData(intid) {
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/detail/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			$('#datadetail').html(data);
			$('#modalDetail').modal('show');
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	}

	function ubahStatus(intid, intstatus){
		swal({
			title: 'Warning !',
			text: "Status will change",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Change',
			cancelButtonText: 'Cancel'
		}).then((result) => {
		  if (result.value) {
		    var base_url = '<?=base_url($controller)?>';
			$.ajax({
				url: base_url + '/aksi/ubahstatus/' + intid + '/' + intstatus,
				method: "GET"
			})
			.done(function( data ) {
				window.location.replace(base_url + "/view");
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		  }
		})
	}

    $('#intmodel').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/get_proses_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- All Process --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intproses + '">' +jsonData[i].vckode+ ' - ' +jsonData[i].vcnama+ '</option>';
			}
			$('#intproses').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	function add(){
		$('#modalExcel').modal('show');
	}
</script>