<div class="row"> 
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div>

			<div class="box-body">
				<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Code</label>
								<input type="text" name="vckode" placeholder="Model Name" class="form-control" id="vckode" required value="<?=$vckode?>" />
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Name</label>
								<input type="text" name="vcnama" placeholder="Model Name" class="form-control" id="vcnama" required value="<?=$vcnama?>" />
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="box-body">
								<div class="after-add-more">
									<?php
										if (count($dataModels) == 0) {
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

											<div class="col-md-2">
												<div class="form-group">
													<button class="btn btn-success margin-top-25" onclick="addmore()" type="button"><i class="glyphicon glyphicon-plus"></i></button>
												</div>
											</div>
										</div>
									<?php
										} else {

										$loop = 0;
										foreach ($dataModels as $models) {
											$hideadd = ($loop == 0) ? '' : 'hidden' ;
											$hideremove = ($loop == 0) ? 'hidden' : '' ;
									?>
										<input type="hidden" name="intmodelproses[]" value="<?=$models['intid']?>">
										<div class="control-group input-group">
											<div class="row">
												<div class="col-md-10">	
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

												<div class="col-md-2 margin-top-25">
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

<script type="text/javascript">
	$(function () {
	    //Initialize Select2 Elements
	    $('.select2').select2()
	});
	
	function simpanData(action) {
		var vcnama       = $('#vcnama').val();

		if (action == 'Add') {
			var base_url = '<?=base_url($controller)?>';
			var formrequired = {'vcnama' : vcnama};
			var formdata = {'vcnama' : vcnama};

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

	function addmore(){
		var html = $(".copy-fields").html();
	  		// $(".after-add-more").append(html);
	  		var base_url = '<?=base_url($controller)?>';
	  		$.ajax({
				url: base_url + '/form_detail_models',
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

	function addmore2(){
		var html = $(".copy-fields").html();
	  		// $(".after-add-more").append(html);
	  		var base_url = '<?=base_url($controller)?>';
	  		$.ajax({
				url: base_url + '/form_detail_models2',
				method: "GET"
			})
			.done(function( data ) {
				$(".after-add-more2").append(data);
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		}
		$(document).ready(function() {
			//here first get the contents of the div with name class copy-fields and add it to after "after-add-more" div class.
		  	$(".add-more").click(function(){ 
		    	var html = $(".copy-fields").html();
		      	$(".after-add-more2").append(html);
		  	});
			//here it will remove the current value of the remove button which has been pressed
		  	$("body").on("click",".remove",function(){ 
		      	$(this).parents(".control-group").remove();
		  	});
		});

	$('.intkomponen').change(function(){
		var row         = $(this).closest(".row");
		var intkomponen = row.find('.intkomponen').val();
		
		var base_url = '<?=base_url("models")?>';
		$.ajax({
			url: base_url + '/getintkomponen/' + intkomponen,
			method: "GET"
		})
		.done(function( data ) {
			var result = JSON.parse(data);
			
			row.find('.intkomponenct').val(result[0].intid);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});

	});

	$('.intkomponen2').change(function(){
		var row         = $(this).closest(".row");
		var intkomponen2 = row.find('.intkomponen2').val();
		
		var base_url = '<?=base_url("models")?>';
		$.ajax({
			url: base_url + '/getintkomponen2/' + intkomponen2,
			method: "GET"
		})
		.done(function( data ) {
			var result = JSON.parse(data);
			
			row.find('.intkomponenct2').val(result[0].intid);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});

	});

</script>