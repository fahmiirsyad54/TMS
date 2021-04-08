<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Access Rights Data</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-6">
			<label>Data</label>
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tr>
						<td><label>Code</label></td>
						<td><?=$dataMain[0]->vckode?></td>
					</tr>
					<tr>
						<td><label>Name</label></td>
						<td><?=$dataMain[0]->vcnama?></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="col-md-6">
			<label>Access Menu</label>
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>Group</th>
							<th>Menu</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($aksesmenu as $menu) {
						?>
						<tr>
							<td><?=$menu->vcparent?></td>
							<td><?=$menu->vcnama?></td>
						</tr>
						<?php
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<label>History</label>
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>Date</th>
							<th>User</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($dataHistory as $history) {
						?>
						<tr>
							<td><?=date('d-m-Y H:i:s',strtotime($history->dtupdate))?></td>
							<td><?=$history->pengguna?></td>
							<td><?=$history->aksi?></td>
						</tr>
						<?php
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<a href="<?=base_url($controller . '/ubah/' . $dataMain[0]->intid)?>" class="btn btn-warning"><i class="fa fa-pencil"></i>Edit</a>
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>