<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<?=$title?>
			</div>

			<div class="box-body">
				<div class="row">
					<div class="col-md-8">
						<div class="table-responsive">
							<table class="table table-bordered tabel-hover tabel-striped">
								<tr>
									<td>Code</td>
									<td><?=$dataMain[0]->vckode?></td>
								</tr>

								<tr>
									<td>Name</td>
									<td><?=$dataMain[0]->vcnama	?></td>
								</tr>
								<tr>
									<td>Username</td>
									<td><?=$dataMain[0]->vcusername	?></td>
								</tr>
							</table>
						</div>
					</div>
				</div>

				<div class="row">
					<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
						<div class="col-md-8">
							<div class="panel-heading" align="Left">
								Change password
							</div>
							<div class="table-responsive">
								<table class="table table-bordered tabel-hover tabel-striped">
									<tr>
										<td>Enter your existing password</td>
										<td>
											<input type="password" name="vcpasswordold" placeholder="Existing password" class="form-control" id="vcpasswordold" required/>
										</td>
									</tr>
									<tr>
										<td>Enter your new password</td>
										<td>
											<input type="password" name="vcpassword" placeholder="New password" class="form-control" id="vcpassword" required/>
										</td>
									</tr>
									<tr>
										<td>Retype your new password</td>
										<td>
											<input type="password" name="vcpasswordretype" placeholder="Retype new password" class="form-control" id="vcpasswordretype" required/>
										</td>
									</tr>
									<tr>
										<td></td>
										<td>
										<div class="form-group">
											<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
											<a href="javascript:void(0);" onclick="simpanData('<?=$action?>')" class="btn btn-success"><i class="fa fa-retweet"></i>  Change</a>
											<!-- <a href="<?=base_url($controller . '/lihat')?>" class="btn btn-danger"><i class="fa fa-close"></i> Cancel</a> -->
										</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function simpanData(action) {
		var vcpasswordold    = $('#vcpasswordold').val();
		var vcpassword       = $('#vcpassword').val();
		var vcpasswordretype = $('#vcpasswordretype').val();

		
		 if (action == 'Ubah') {
			var base_url = '<?=base_url($controller)?>';
			var formrequired = {};
			var formdata = {'vcpassword' : vcpasswordold};
			if (vcpasswordold == '' || vcpassword == '' || vcpasswordretype == '') {
				swal({
						type: 'error',
						title: 'There is something wrong',
						text: 'Sorry, Your form is Null'
					});
			} else {

				$.ajax({
					url: base_url + '/validasi_password/' + vcpasswordold,
					method: "GET"
					})
				.done(function( data ) {
					var jsonData = JSON.parse(data);

					console.log(jsonData[0].intpasswordcek);
					if (jsonData[0].intpasswordcek == 0) {
						swal({
							type: 'error',
							title: 'There is something wrong',
							text: 'Sorry, your password is not same'
						});
					} else {
						if (vcpassword == vcpasswordretype) {
						$('#postdata').submit()
						swal({
								type: 'success',
								title: 'Congrats',
								text: 'the password was successfully replaced'
							});
						} else {
							swal({
								type: 'error',
								title: 'There is something wrong',
								text: 'Sorry, Retype is not same with new password'
							});
						}
					}
				})
				.fail(function( jqXHR, statusText ) {
					alert( "Request failed: " + jqXHR.status );
				});
			}
		}
	}
</script>