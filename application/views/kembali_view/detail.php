<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Return Data</h4>
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
						<td><label>Date</label></td>
						<td><?=$dataMain[0]->dtkembali?></td>
					</tr>

					<tr>
						<td><label>Admin</label></td>
						<td><?=$dataMain[0]->vcuser?></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="col-md-6">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tr>
						<td><label>Building</label></td>
						<td><?=$dataMain[0]->vcgedung?></td>
					</tr>

					<tr>
						<td><label>Cell</label></td>
						<td><?=$dataMain[0]->vccell?></td>
					</tr>

					<tr>
						<td><label>Employee</label></td>
						<td><?=$dataMain[0]->vckaryawan?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<label>List Pallet</label>
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
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($datapallet as $pallet) {
						?>
						<tr>
							<td><?=$pallet->vckode?></td>
							<td><?=$pallet->vcmodel?></td>
							<td><?=$pallet->vcproses?></td>
							<td><?=$pallet->vcsize?></td>
							<td><?=$pallet->vcpeminjam?></td>
							<td><?=$pallet->dtpinjam?></td>
							<td><?=$pallet->vckondisi?></td>
							<td><?=$pallet->vcroom?></td>
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
	<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i>Close</button>
</div>