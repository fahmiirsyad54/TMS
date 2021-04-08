<div class="row">
	<div class="col-md-12">
		<div class="box"> 
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div> 

			<div class="box-body">
				<div class="row">
					<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
						<div class="col-md-6">
							<div class="form-group">
								<label>NIK</label>
								<input type="text" name="vckode" placeholder="NIK" class="form-control" id="vckode" required value="<?=$vckode?>" />
							</div>

							<div class="form-group">
								<label>Name</label>
								<input type="text" name="vcnama" placeholder="Name" class="form-control" id="vcnama" required value="<?=$vcnama?>" />
							</div>

							<div class="form-group">
								<label>Position</label>
								<select name="intjabatan" class="form-control" id="intjabatan">
									<option data-nama="" value="0">-- Select Position --</option>
									<?php
										foreach ($listjabatan as $opt) {
											$selected = ($intjabatan == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>

							<div class="form-group">
								<label>Building</label>
								<select name="intgedung" class="form-control" id="intgedung">
									<option data-nama="" value="0">-- Select Building --</option>
									<?php
										foreach ($listgedung as $opt) {
											$selected = ($intgedung == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>

						<div class="col-md-6">
							
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
								<a href="javascript:void(0);" onclick="simpanData('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
								<a href="<?=base_url($controller . '/view')?>" class="btn btn-danger"><i class="fa fa-close"></i>Close</a>
							</div>
						</div>
					</form>
				</div>
			</div>

		</div>
	</div>
</div>

<div id="alertModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Same Name</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped table-hover">
        	<thead>
        		<tr>
        			<th>NIK</th>
        			<th>Name</th>
        			<th>Position</th>
        			<th></th>
        		</tr>
        	</thead>
        	<tbody id="dtkaryawan">
        		
        	</tbody>
        </table><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button class="btn btn-success" onclick="createnew()">Create New</button>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">
	function simpanData(action) {
		var vckode     = $('#vckode').val();
		var vcnama     = $('#vcnama').val();
		var intjabatan = $('#intjabatan').val();

		if (action == 'Add') {
			var base_url = '<?=base_url($controller)?>';
			var formrequired = {'vcnama' : vcnama};
			var formdata = {};

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
							$.ajax({
								url: base_url + '/ceknamakaryawan',
								method: "POST",
								data : {'vcnama':vcnama,'intjabatan':intjabatan}
							})
							.done(function( data ) {
								var jsonData = JSON.parse(data);
								if (jsonData.length > 0) {
									$('#alertModal').modal('show');
									var _html = '';
									for (var i = 0; i < jsonData.length; i++) {
										_html += '<tr>';
										_html += '<td>'+jsonData[i].vckode+'</td>';
										_html += '<td>'+jsonData[i].vcnama+'</td>';
										_html += '<td></td>';
										_html += '<td><button class="btn btn-primary  btn-xs" type="button" onclick="replace('+jsonData[i].intid+')">Replace</button></td>';
										_html += '</tr>';
									}
									$('#dtkaryawan').html(_html);
								} else {
									$('#postdata').submit();
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
				}
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		} else if (action == 'Edit') {
			$('#postdata').submit();
		}
	}

	$('#inthakakses').change(function(){
		var intid = $(this).val();
		var vcnama = $(this).children('option:selected').data('nama');

		$('#vchakakses').val(vcnama);
	});

	function replace(intid) {
		var base_url   = '<?=base_url($controller)?>';
		var vckode     = $('#vckode').val();
		var vcnama     = $('#vcnama').val();
		var intjabatan = $('#intjabatan').val();
		var intgedung  = $('#intgedung').val();

		$.ajax({
			url: base_url + '/aksi/Edit/'+intid,
			method: "POST",
			data : {'vckode':vckode,'vcnama':vcnama,'intjabatan':intjabatan,'intgedung':intgedung}
		})
		.done(function( data ) {
			window.location.replace(base_url);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	}

	function createnew() {
		$('#postdata').submit();
	}

</script>