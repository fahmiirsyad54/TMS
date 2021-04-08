<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div>

			<div class="box-body">
				<div class="row">
					<form method="POST" id="postdata" action="<?=base_url($controller . '/simpan_kembali/')?>">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="vckode" placeholder="Code" id="vckode" class="form-control" value="<?=$vckode?>" readonly/>
                                
                            </div>

                            <div class="form-group">
                                <label>Building</label>
                                <select name="intgedung" class="form-control select2 intgedung" id="intgedung">
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
                                <label>Cell</label>
                                <select name="intcell" class="form-control select2" id="intcell">
                                    <option data-nama="" value="0">-- Select Cell --</option>
                                    <?php
                                        foreach ($listproses as $opt) {
                                            $selected = ($intcell == $opt->intid) ? 'selected' : '' ;
                                    ?>
                                    <option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
                                    
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Employee</label>
                                <select name="intkaryawan" class="form-control select2" id="intkaryawan">
                                    <option data-nama="" value="0">-- Select Employee --</option>
                                    <?php
                                        foreach ($listmodel as $opt) {
                                            $selected = ($intkaryawan == $opt->intid) ? 'selected' : '' ;
                                    ?>
                                    <option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

						<div class="col-md-12">
							<div class="form-group">
								<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
								<a href="javascript:void(0);" onclick="simpanData()" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
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
	function simpanData(){
		$('#postdata').submit();
	}

	$('#intgedung').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/get_cell_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Cell --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intid + '">' +jsonData[i].vckode+ ' - ' +jsonData[i].vcnama+ '</option>';
			}
			$('#intcell').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

    $('#intgedung').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/get_karyawan_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Employee --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intid + '">' +jsonData[i].vckode+ ' - ' +jsonData[i].vcnama+ '</option>';
			}
			$('#intkaryawan').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});
</script>