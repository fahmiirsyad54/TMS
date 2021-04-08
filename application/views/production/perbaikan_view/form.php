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
                                <label>Building</label>
                                <select name="intgedung" class="form-control select2" id="intgedung">
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

                            <div class="form-group">
                                <label>Models</label>
                                <select name="intmodel" class="form-control select2" id="intmodel">
                                    <option data-nama="" value="0">-- Select Models --</option>
                                    <?php
                                        foreach ($listmodel as $opt) {
                                            $selected = ($intmodel == $opt->intid) ? 'selected' : '' ;
                                    ?>
                                    <option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Process</label>
                                <select name="intproses" class="form-control select2" id="intproses">
                                    <option data-nama="" value="0">-- Select Process --</option>
                                    <?php
                                        foreach ($listproses as $opt) {
                                            $selected = ($intproses == $opt->intid) ? 'selected' : '' ;
                                    ?>
                                    <option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
                                    
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
						</div>

						<div class="col-md-6">
                            <div class="form-group">
                                <label>Size</label>
                                <select name="intsize" class="form-control select2" id="intsize">
                                    <option data-nama="" value="0">-- Select Size --</option>
                                    <?php
                                        foreach ($listsize as $opt) {
                                            $selected = ($intsize == $opt->intid) ? 'selected' : '' ;
                                    ?>
                                    <option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>

							<div class="form-group">
								<label>Quantity</label>
								<input type="number" name="intqty" placeholder="Sorter" class="form-control" value="<?=$intqty?>" />
							</div>

                            <div class="form-group">
								<label>Code</label>
								<input type="text" name="vckode" placeholder="Code" id="vckode" class="form-control" value="<?=$vckode?>" readonly/>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
								<a href="javascript:void(0);" onclick="simpanData('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
								<a href="<?=base_url($controller . '/view')?>" class="btn btn-danger"><i class="fa fa-close"></i>Cancel</a>
							</div>
						</div>
					</form>
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

    $('#intmodel').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/get_proses_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Process --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intproses + '">' +jsonData[i].vckode+ ' - ' +jsonData[i].vcnama+ '</option>';
			}
			$('#intproses').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

    $('#intsize').change(function(){
		var intid      = $(this).val();
		var _intmodel  = $('#intmodel').val();
		var _intproses = $('#intproses').val();
		var base_url   = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/getkode/' + intid + '/' + _intmodel + '/' + _intproses,
			method: "GET"
		})
		.done(function( data ) {
			$('#vckode').val(data);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});
</script>