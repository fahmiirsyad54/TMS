<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div>

			<div class="box-body">
				<div class="row">
					<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
						<div class="col-md-3">
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
						</div>

						<div class="col-md-3">
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
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="box-body">
									<div class="row">
										<div class="col-md-12">
											<div class="after-add-more">
												<?php
													if (count($dataPermintaan) == 0) {
												?>
													<div class="row control-group">
														<div class="col-md-6">	
															<div class="form-group">
																<label>Process</label>
																<select name="intproses[]" class="form-control intproses select2" id="intproses">
																	<option value="0">-- Select Process --</option>
																	<?php
																		foreach ($listproses as $opt) {
																	?>
																	<option data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
																	<?php
																		}
																	?>
																</select>
															</div>
														</div>

														<div class="col-md-3">	
															<div class="form-group">
																<label>Size</label>
																<select name="intsize" class="form-control select2" id="intsize">
																	<option data-nama="" value="0">-- All Size --</option>
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
														</div>

														<div class="col-md-2">
															<div class="form-group">
																<label>Quantity</label>
																<input type="number" name="intqty" placeholder="Sorter" class="form-control" value="<?=$intqty?>" />
															</div>
														</div>
														
														<div class="col-md-1">
															<div class="form-group">
																<button class="btn btn-success margin-top-25" onclick="addmore()" type="button"><i class="glyphicon glyphicon-plus"></i></button>
															</div>
														</div>
													</div>
														<?php
															} else {

															$loop = 0;
															foreach ($dataPermintaan as $models) {
																$hideadd = ($loop == 0) ? '' : 'hidden' ;
																$hideremove = ($loop == 0) ? 'hidden' : '' ;
														?>
															<input type="hidden" name="intmodelproses[]" value="<?=$models['intid']?>">
															<div class="control-group input-group">
																<div class="row">
																	<div class="col-md-6">	
																		<div class="form-group">
																			<label>Process</label>
																			<select name="intproses[]" class="form-control intproses select2">
																				<option value="0">-- Select Process --</option>
																				<?php
																					foreach ($models['listproses'] as $opt) {
																						$selected = ($opt->intid == $models['intproses']) ? 'selected' : '' ;
																				?>
																				<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
																				<?php
																					}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-3">
																		<div class="form-group">
																			<label>Size</label>
																			<select name="intsize[]" class="form-control intsize select2">
																				<option value="0">-- Select Process --</option>
																				<?php
																					foreach ($models['listsize'] as $opt) {
																						$selected = ($opt->intid == $models['intsize']) ? 'selected' : '' ;
																				?>
																				<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
																				<?php
																					}
																				?>
																			</select>
																		</div>
																	</div>

																	<div class="col-md-2">
																		<div class="form-group">
																			<label>Quantity</label>
																			<input type="number" name="intqty" placeholder="Sorter" class="form-control" value="<?=$intqty?>" />
																		</div>
																	</div>

																	<div class="col-md-1 margin-top-25">
																		<div class="form-group">
																			<a href="javascript:void(0)" class="btn btn-success <?=$hideadd?>" onclick="addmore()"><i class="fa fa-plus"></i></a>
																			<a href="javascript:void(0)" class="btn btn-danger <?=$hideremove?> remove"><i class="fa fa-remove"></i></a>
																		</div>
																	</div>
																</div>
															</div>
															<?php
													$loop++;
													}
												}
												?>
											</div>
										</div>
									</div>
								</div>
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

	function addmore(){
		var html = $(".copy-fields").html();
	  		// $(".after-add-more").append(html);
	  		var base_url = '<?=base_url($controller)?>';
	  		$.ajax({
				url: base_url + '/form_detail_permintaan',
				method: "GET"
			})
			.done(function( data ) {
				$(".after-add-more").append(data);
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
	}
		$(document).ready(function() {
			//here first get the contents of the div with name class copy-fields and add it to after "after-add-more" div class.
		  	$(".add-more").click(function(){ 
		    	var html = $(".copy-fields").html();
		      	$(".after-add-more").append(html);
		  	});
			//here it will remove the current value of the remove button which has been pressed
		  	$("body").on("click",".remove",function(){ 
		      	$(this).parents(".control-group").remove();
		  	});
		});
</script>