<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Notes Data</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<label>Data</label>
			<label class="pull-right">Status : <span class="label label-<?=$this->datanotes[0]->vcstatuswarna?>"><?=$this->datanotes[0]->vcstatus?></span></label>
		</div>

		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tr>
						<td><label>Date</label></td>
						<td><?=date('d M Y H:i:s', strtotime($this->datanotes[0]->dttanggal))?></td>
					</tr>

					<tr>
						<td><label>Building</label></td>
						<td><?=$this->datanotes[0]->vcgedung?></td>
					</tr>

					<tr>
						<td><label>Machine</label></td>
						<td><?=$this->datanotes[0]->vcmesin?></td>
					</tr>

					<tr>
						<td><label>Operator</label></td>
						<td><?=$this->datanotes[0]->vcoperator?></td>
					</tr>

					<tr>
						<td><label>Notes</label></td>
						<td><?=$this->datanotes[0]->vcpesan?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<hr style="margin-top: 0px; margin-bottom: 10px;">

	<!-- <div class="row">
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
							<td><?=$history->user?></td>
							<td><?=$history->aksi?></td>
						</tr>
						<?php
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div> -->
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
</div>