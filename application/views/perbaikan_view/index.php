<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-md-2 margin-bottom-10">
						<a href="<?=base_url($controller . '/add')?>" class="btn btn-primary"><i class="fa fa-plus"></i>Add New Data</a>
					</div>

					<div class="col-md-10">
						<div class="row">
							<form method="GET" action="<?=base_url($controller . '/view')?>">
								<div class="col-md-2">
									<div class="form-group">
										<input type="text" name="from" placeholder="From" class="form-control datepicker" id="from" value="<?=$from_input?>" />
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<input type="text" name="to" placeholder="To" class="form-control datepicker" id="to" value="<?=$to_input?>" />
									</div>
								</div>
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

								<div class="col-md-2">
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

								<div class="col-md-1">
									<button class="btn btn-default btn-block" type="sbumit"><i class="fa fa-search"></i></button>
								</div>

								<div class="col-md-1">
									<a href="javascript:void();" onclick="exportexcel()" class="btn btn-success btn-block"><i class="fa fa-file-excel-o"></i></a>
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
								<th>Date</th>
								<th>Code</th>
								<th>Building</th>
								<th>Model</th>
                                <th>process</th>
								<th>Update By</th>
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
										if ($data->intstatus == 0) {
											$colorstatus = 'success';
											$tooltiptext = 'Activate';
										} elseif ($data->intstatus == 1) {
											$colorstatus = 'danger';
											$tooltiptext = 'Deactivate';
										} elseif ($data->intstatus == 1) {
											$colorstatus = 'danger';
											$tooltiptext = 'Deactivate';
										}
							?>
								<tr>
									<td><?=++$no?></td>
									<td><?=$data->dtperbaikan?></td>
									<td><?=$data->vckode?></td>
									<td><?=$data->vcgedung?></td>
									<td><?=$data->vcmodel?></td>
									<td><?=$data->vcproses?></td>
									<td><?=$data->vcuser?></td>
									<td><span class="label label-<?=$data->vcstatuswarna?>"><?=$data->vcstatus?></span></td>
									<td>
										<!-- <a href="javascript:void(0);" onclick="detailData(<?=$data->intid?>)" class="btn btn-xs btn-info"><i class="fa fa-info"></i> Detail</a>

										<a href="<?=base_url($controller . '/edit/' . $data->intid)?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i> Edit</a> -->

										<a href="javascript:void(0);" onclick="ubahKondisi(<?=$data->intid?>)" class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="bottom">
											<i class="fa fa-gear"></i> Change Condition
										</a>
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
					echo pagination4($halaman, $link, $jmlpage, $from, $to, $intgedung, $intmodel, $intproses);
				?>
			</div>

		</div>
	</div>
</div>

<!-- Modal -->
<div id="modalUbah" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm">
		<!-- Modal content-->
		<div class="modal-content" id="dataubah">
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
	    $('.datepicker').datepicker({
		autoclose: true
		})

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

	function ubahKondisi(intid) {
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/ubahkondisi/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			$('#dataubah').html(data);
			$('#modalUbah').modal('show');
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	}

	function ubahStatus(intid, intkondisi){
		swal({
			title: 'Warning !',
			text: "Condition will change",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Good',
			cancelButtonText: 'Destroy'
		}).then((result) => {
		  if (result.value) {
		    var base_url = '<?=base_url($controller)?>';
			$.ajax({
				url: base_url + '/aksi/ubahkondisi/' + intid + '/' + intstatus,
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

	function exportexcel(){
		var base_url  = '<?=base_url($controller)?>';
		var from      = $('#from').val();
		var to        = $('#to').val();
		var intgedung = $('#intgedung').val();
		var intmodel  = $('#intmodel').val();
		var intproses = $('#intproses').val();
		window.open(base_url + '/exportexcel?from=' + from + '&to=' + to + '&intgedung=' + intgedung + '&intmodel=' + intmodel + '&intproses=' + intproses);
	}
</script>