<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Pallet Data</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-6">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tr>
						<td><label>Code</label></td>
						<td><?=$dataMain[0]->vckode?></td>
					</tr>

					<tr>
						<td><label>Gedung</label></td>
						<td><?=$dataMain[0]->vcgedung?></td>
					</tr>

					<tr>
						<td><label>Model</label></td>
						<td><?=$dataMain[0]->vcmodel?></td>
					</tr>

					<tr>
						<td><label>Process</label></td>
						<td><?=$dataMain[0]->vcproses?></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="col-md-6">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tr>
						<td><label>Size</label></td>
						<td><?=$dataMain[0]->vcsize?></td>
					</tr>

					<tr>
						<td><label>Side</label></td>
						<td><?=$dataMain[0]->vcside?></td>
					</tr>

					<tr>
						<td><label>Location</label></td>
						<td><?=$dataMain[0]->vclokasi?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<label>Added on : <?=$dataMain[0]->dttanggal?>, by <?=$dataMain[0]->vcuser?></label>
		</div>
	</div>
</div>
<div class="modal-footer">
	<a href="<?=base_url($controller . '/edit/' . $dataMain[0]->intid)?>" class="btn btn-warning"><i class="fa fa-pencil"></i>Edit</a>
	<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i>Close</button>
</div>