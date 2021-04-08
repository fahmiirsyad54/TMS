<div class="row">
	<div class="box">
		<div class="box-header">
			<div class="col-md-12">
				<span style="padding-right: 100px; " name="vcpinjam" id="vckodepinjam">Code : <?=$vckodekembali?></span>
				<span style="padding-right: 100px; " id="vcgedung">Building : <?=$vcgedung?></span>
				<span style="padding-right: 100px; " id="vccell">Cell : <?=$vccell?></span>
				<span style="padding-right: 100px; " id="vckaryawan">Borrower : <?=$vckaryawan?></span>
                <input type="hidden" name="intkembali" id="intkembali" value="<?=$intid?>">
			</div> <hr>

			<div class="col-md-12">
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
										<th>Condition</th>
										<th>Recipient</th>
										<th>Location</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
                                    <?php
                                        $jmldata = count($datakembali);
                                        if ($jmldata === 0) {
                                    ?>
                                    <tr>
                                        <td colspan="5" align="center">Data Not Found</td>
                                    </tr>
                                    <?php
                                        } else {
                                            foreach ($datakembali as $data) {
                                    ?>
                                    <tr>
                                        <td><?=$data->vckode?></td>
                                        <td><?=$data->vcmodel?></td>
                                        <td><?=$data->vcproses?></td>
                                        <td><?=$data->vcsize?></td>
                                        <td><?=$data->vckondisi?></td>
										<td><?=$data->vcuser?></td>
										<td><?=$data->vcroom?></td>
                                        <td>
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="<?=base_url($controller . '/view')?>" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
                            </div>
                        </div>
					</div>
				</div>
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
	function ubahKondisi(intid) {
		var intkembali = $('#intkembali').val();
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/ubahKondisi/' + intid + '/' + intkembali,
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
</script>