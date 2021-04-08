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
									<td>Application Name</td>
									<td><?=$namaaplikasi?></td>
								</tr>
								<tr>
									<td>Application Version</td>
									<td><?=$versiapp?></td>
								</tr>
								<tr>
									<td>Description</td>
									<td><?=$deskripsi?></td>
								</tr>
								<tr>
									<td>Company</td>
									<td><?=$namaperusahaan?></td>
								</tr>
								<tr>
									<td>Departement</td>
									<td><?=$departement?></td>
								</tr>
								<tr>
									<td>Team</td>
									<td><?=$team?></td>
								</tr>
							</table>
						</div>
					</div>
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