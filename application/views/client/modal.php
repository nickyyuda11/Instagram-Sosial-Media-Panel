<!-- Modal Riwayat Transaksi Flowers -->
<div class="modal fade examplemodal riwayatfollowers" id="riwayatfollowers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Riwayat Followers</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-xl-12 col-md-12 mb-4">
					<div class="card mb-4">
						<div class="table-responsive p-3">
							<table class="table align-items-center table-flush table-hover" id="dataTableHover">
								<thead class="thead-light">
									<tr>
										<th>No</th>
										<th>Link</th>
										<th>Jumlah</th>
										<th>Tanggal</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>No</th>
										<th>Link</th>
										<th>Jumlah</th>
										<th>Tanggal</th>
									</tr>
								</tfoot>
								<tbody class="show_transaksi">
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Riwayat Transaksi Flowers -->
<!-- Modal Add Flowers -->
<div class="modal fade examplemodal" id="addfl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tambah Followers</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<div class="sukses_fl"></div>
					<label for="exampleInputEmail1">Username (tanpa @ contoh: instagram)</label>
					<input type="text" name="link" class="form-control link" id="link" placeholder="instagram" value="<?= $profile['username'] ?>" disabled required>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary submitfl" id="submitfl">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Add Flowers -->
<!-- Modal Riwayat Transaksi Likes -->
<div class="modal fade examplemodal panellikes" id="panellikes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Riwayat Likes</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-xl-12 col-md-12 mb-4">
					<div class="card mb-4">
						<div class="table-responsive p-3">
							<table class="table align-items-center table-flush table-hover" id="dataTableHover1">
								<thead class="thead-light">
									<tr>
										<th>No</th>
										<th>Link</th>
										<th>Jumlah</th>
										<th>Tanggal</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>No</th>
										<th>Link</th>
										<th>Jumlah</th>
										<th>Tanggal</th>
									</tr>
								</tfoot>
								<tbody class="show_transaksi_likes">
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Riwayat Transaksi Likes -->
<!-- Modal Add Likes -->
<div class="modal fade examplemodal" id="paneladdlikes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tambah Likes</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="form-group">
					<div class="sukses_likes"></div>
					<label for="exampleInputEmail1">Link foto/video (contoh: https://instagram.com/p/xxxxxxxx/)</label>
					<!-- <input type="text" name="link" class="form-control linklikes" id="linklikes" placeholder="instagram"> -->
					<?php if ($user['TYPE_ACC'] == 1) { ?>
						<select name="linklikes" class="form-control linklikes" id="linklikes" required>
							<option>Select...</option>
							<?php
							$cpost = $profile['post'];
							if ($cpost > 12) {
								$cpost = 12;
							}
							for ($i = 0; $i < $cpost; $i++) {
							?>
								<option value="https://instagram.com/p/<?= $profile['tespost']->edges[$i]->node->shortcode ?>">https://instagram.com/p/<?= $profile['tespost']->edges[$i]->node->shortcode ?></option>
							<?php
							} ?>
						</select>
					<?php } elseif ($user['TYPE_ACC'] == 2 || 3 || 4) {
						echo '<input type="text" name="linklikes" class="form-control linklikes" id="linklikes" placeholder="https://instagram.com/p/xxxxxxxx/" required>';
					} ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary addlikes" id="addlikes">Submit</button>
				</div>

			</div>
		</div>
	</div>
</div>
<!-- End Modal Add Likes -->
<!-- Modal Riwayat Transaksi Likes -->
<div class="modal fade examplemodal riwayatcommentlikes" id="riwayatcommentlikes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Riwayat Likes Comment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-xl-12 col-md-12 mb-4">
					<div class="card mb-4">
						<div class="table-responsive p-3">
							<table class="table align-items-center table-flush table-hover" id="dataTableHover1">
								<thead class="thead-light">
									<tr>
										<th>No</th>
										<th>Link</th>
										<th>Jumlah</th>
										<th>Tanggal</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>No</th>
										<th>Link</th>
										<th>Jumlah</th>
										<th>Tanggal</th>
									</tr>
								</tfoot>
								<tbody class="show_transaksi_commentlikes">
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Riwayat Transaksi Likes -->
<!-- Modal Add Likes Comments -->
<div class="modal fade panelcommentlikes" id="panelcommentlikes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tambah Likes Comment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="form-group">
					<div class="sukses_comment_likes"></div>
					<label for="exampleInputEmail1">Link foto/video (contoh: https://instagram.com/p/xxxxxxxx/)</label>
					<input type="text" name="link" class="form-control linkcommentlikes" id="linkcommentlikes" placeholder="https://instagram.com/p/xxxxxxxx/" required>
					<button type="submit" class="btn btn-secondary text-center" id="cekcomment">Cek Id</button>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary submitcommentlikes" id="submitcommentlikes">Submit</button>
				</div>

			</div>
		</div>
	</div>
</div>
<!-- End Modal Add Likes Comments -->
<!-- Modal Riwayat Transaksi Comments -->
<div class="modal fade examplemodal riwayatcomment" id="riwayatcomment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Riwayat Comments</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-xl-12 col-md-12 mb-4">
					<div class="card mb-4">
						<div class="table-responsive p-3">
							<table class="table align-items-center table-flush table-hover" id="dataTableHover1">
								<thead class="thead-light">
									<tr>
										<th>No</th>
										<th>Link</th>
										<th>Jumlah</th>
										<th>Tanggal</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>No</th>
										<th>Link</th>
										<th>Jumlah</th>
										<th>Tanggal</th>
									</tr>
								</tfoot>
								<tbody class="show_transaksi_comment">
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Riwayat Transaksi Comments -->
<!-- Modal Add Comments -->
<div class="modal fade panelcomment" id="panelcomment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tambah Comment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="form-group">
					<div class="sukses_comment"></div>
					<label for="exampleInputEmail1">Link foto/video (contoh: https://instagram.com/p/xxxxxxxx/)</label></br>
					<?php if ($user['TYPE_ACC'] == 1) { ?>
						<select name="linkcomment" class="form-control linkcomment" id="linkcomment" required>
							<option>Select...</option>
							<?php
							$cpost = $profile['post'];
							if ($cpost > 12) {
								$cpost = 12;
							}
							for ($i = 0; $i < $cpost; $i++) {
							?>
								<option value="https://instagram.com/p/<?= $profile['tespost']->edges[$i]->node->shortcode ?>">https://instagram.com/p/<?= $profile['tespost']->edges[$i]->node->shortcode ?></option>
							<?php
							} ?>
						</select>
					<?php } elseif ($user['TYPE_ACC'] == 2 || 3 || 4) {
						echo '<input type="text" name="linkcomment" class="form-control linkcomment" id="linkcomment" placeholder="https://instagram.com/p/xxxxxxxx/" required>';
					} ?>

					<?php if ($user['TYPE_ACC'] == 1) { ?>
						<label for="exampleInputEmail1">Pilih kata yang akan dikirim</label>
						<select id="text" name="text[]" class="form-control text" multiple="multiple" required>
							<option value="Nice Post">Nice Post</option>
							<option value="Keren kak">Keren kak</option>
							<option value="Wow">Wow</option>
							<option value="Imut banget">Imut banget</option>
							<option value="Mantap gan">Mantap gan</option>
							<option value="Keren fotonya kak">Keren fotonya kak</option>
							<option value="Wah boleh nih">Wah boleh nih</option>
							<option value="Good job">Good job</option>
							<option value="Boleh nih">Boleh nih</option>
							<option value="Wahh keren">Wahh keren</option>
						</select>
					<?php } else { ?>
						<label for="exampleInputEmail1">Ketik kata yang akan dikirim perbaris</label>
						<textarea name="text[]" id="text" class="form-control text"></textarea>
					<?php } ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary submitcomment" id="submitcomment">Submit</button>
				</div>

			</div>
		</div>
	</div>
</div>
<!-- End Modal Add Comments -->