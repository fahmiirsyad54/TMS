<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<?=$title?>
			</div>

			<div class="box-body">
				<div class="row">
					<div class="col-md-2 margin-bottom-10">
						<a href="<?=base_url($controller . '/add')?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Data</a>
					</div>

					<div class="col-md-10">
						<div class="row">
							<form method="GET" action="<?=base_url($controller . '/view')?>">
								<input type="hidden" name="intgedung" placeholder="intgedung" id="intgedung" value="<?=$intgedung?>" />
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

								<div class="col-md-3">
									<select name="intcell" class="form-control select2" id="intcell">
										<option value="0">-- All Cell --</option>
										<?php
											foreach ($listcell as $opt) {
												$selected = ($opt->intid == $intcell) ? 'selected' : '';
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>

								<div class="col-md-3">
									<select name="intkaryawan" class="form-control select2" id="intkaryawan">
										<option value="0">-- All Employee --</option>
										<?php
											foreach ($listkaryawan as $opt) {
												$selected = ($opt->intid == $intkaryawan) ? 'selected' : '';
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
								<th>Cell</th>
                                <th>Employee</th>
								<th>Admin</th>
                                <th>Return</th>
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
									<td colspan="10" align="center">Data Not Found</td>
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
										}
							?>
								<tr>
									<td><?=++$no?></td>
                                    <td><?=$data->dtkembali?></td>
									<td><?=$data->vckode?></td>
									<td><?=$data->vcgedung?></td>
									<td><?=$data->vccell?></td>
									<td><?=$data->vckaryawan?></td>
									<td><?=$data->vcuser?></td>
									<td><?=$data->total?></td>
									<td><span class="label label-<?=$data->vcstatuswarna?>"><?=$data->vcstatus?></span></td>
									<td>
										<a href="javascript:void(0);" onclick="detailData(<?=$data->intid?>)" class="btn btn-xs btn-info"><i class="fa fa-info"></i> Detail</a>
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
					echo pagination3($halaman, $link, $jmlpage, $from, $to, $intgedung, $intcell, $intkaryawan);
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

<div id="modalAdd" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<!-- Modal content-->
		<div class="modal-content" id="dataadd">
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
	})

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

	function exportexcel(){
		var base_url    = '<?=base_url($controller)?>';
		var from        = $('#from').val();
		var to          = $('#to').val();
		var intgedung   = $('#intgedung').val();
		var intcell     = $('#intcell').val();
		var intkaryawan = $('#intkaryawan').val();
		window.open(base_url + '/exportexcel?from=' + from + '&to=' + to + '&intgedung=' + intgedung + '&intcell=' + intcell + '&intkaryawan=' + intkaryawan);
	}
</script>