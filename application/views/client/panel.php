<!-- Container Fluid-->
<div class="container pt-4" id="container-wrapper">
	<div class="d-sm-flex align-items-center justify-content-between">
		<h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url('client/index'); ?>">Home</a></li>
			<li class="breadcrumb-item active">Dashboard</li>
		</ol>
	</div>
	<div class="row" id="panel">
		<div class="card-body col-lg-12" id="timer">
		</div>
		<!-- Followers -->
		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card h-100">
				<div class="card-body">
					<div class="row align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-uppercase mb-1">Followers</div>
							<span class="text-info"><i class="fa fa-thumbs-o-up"></i> Up to 7 Followers</span>
							<div class="h5 mb-0 font-weight-bold text-gray-800" id="countfl"></div>
							<div class="mt-2 mb-0 text-muted text-xs">
								<span><i class="fas fa-plus"></i> Followers ditambahkan</span>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-users fa-2x text-info"></i>
						</div>
					</div>
					<hr>
					<div class="text-xs font-weight-bold text-uppercase mb-1 text-center">
						<div class="card border-0">
							<button class="btn btn-info addfl" data-toggle="modal" data-target="#addfl" id="btn-fl"> <i class="fas fa-arrow-up"></i> Tambah Followers</button>
						</div>
						<i class="fas fa-history"></i><a class="text-secondary riwayatfollowers" data-toggle="modal" data-target="#riwayatfollowers"> Lihat riwayat Followers</a>
					</div>
				</div>
			</div>
		</div>

		<!-- Likes -->
		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card h-100">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-uppercase mb-1">Likes</div>
							<?php if ($user['TYPE_ACC'] == 1) { ?>
								<span class="text-info"><i class="fa fa-thumbs-o-up"></i> Up to 50 Likes</span>
							<?php } elseif ($user['TYPE_ACC'] == 2) { ?>
								<span class="text-info"><i class="fa fa-thumbs-o-up"></i> Up to 100 Likes</span>
							<?php } ?>
							<div class="h5 mb-0 font-weight-bold text-gray-800" id="countlikes"></div>
							<div class="mt-2 mb-0 text-muted text-xs">
								<span><i class="fas fa-plus"></i> Likes ditambahkan</span>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-heart fa-2x text-info"></i>
						</div>
					</div>
					<hr>
					<div class="text-xs font-weight-bold text-uppercase mb-1 text-center">
						<div class="card border-0">
							<button class="btn btn-info" data-toggle="modal" data-target="#paneladdlikes" id="btn-likes"> <i class="fas fa-arrow-up"></i> Tambah Likes</button>
						</div>
						<i class="fas fa-history"></i><a class="text-secondary panellikes" data-toggle="modal" data-target="#panellikes"> Lihat riwayat Likes</a>
					</div>
				</div>
			</div>
		</div>
		<!-- Pending Requests Card Example -->
		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card h-100">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-uppercase mb-1">Comments</div>
							<?php if ($user['TYPE_ACC'] == 1) { ?>
								<span class="text-info"><i class="fa fa-thumbs-o-up"></i> Up to 3 Comments</span>
							<?php } elseif ($user['TYPE_ACC'] == 2) { ?>
								<span class="text-info"><i class="fa fa-thumbs-o-up"></i> Up to 7 Comments</span>
							<?php } ?>
							<div class="h5 mb-0 font-weight-bold text-gray-800" id="countcomments"></div>
							<div class="mt-2 mb-0 text-muted text-xs">
								<span><i class="fas fa-plus"></i> Comments ditambahkan</span>
							</div>
						</div>
						<div class="col-auto">
							<i class="fa fa-comments fa-2x text-info"></i>
						</div>
					</div>
					<hr>
					<div class="text-xs font-weight-bold text-uppercase mb-1 text-center">
						<div class="card border-0">
							<button class="btn btn-info" data-toggle="modal" data-target="#panelcomment" id="btn-comment"> <i class="fas fa-arrow-up"></i> Tambah Comments</button>
						</div>
						<i class="fas fa-history"></i><a class="text-secondary riwayatcomment" data-toggle="modal" data-target="#riwayatcomment"> Lihat riwayat Comments</a>
					</div>
				</div>
			</div>
		</div>
		<!-- New User Card Example -->
		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card h-100">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-uppercase mb-1">Likes Comments</div>
							<?php if ($user['TYPE_ACC'] == 1) { ?>
								<span class="text-info"><i class="fa fa-thumbs-o-up"></i> Up to 50 Likes</span>
							<?php } elseif ($user['TYPE_ACC'] == 2) { ?>
								<span class="text-info"><i class="fa fa-thumbs-o-up"></i> Up to 100 Likes</span>
							<?php } ?>
							<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="countlikescm"></div>
							<div class="mt-2 mb-0 text-muted text-xs">
								<span><i class="fas fa-plus"></i> Likes Comments ditambahkan</span>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-heart fa-2x text-info"></i>
						</div>
					</div>
					<hr>
					<div class="text-xs font-weight-bold text-uppercase mb-1 text-center">
						<div class="card border-0">
							<button class="btn btn-info" data-toggle="modal" data-target="#panelcommentlikes" id="btn-likescomment"> <i class="fas fa-arrow-up"></i> Tambah Likes Comments</button>
						</div>
						<i class="fas fa-history"></i><a class="text-secondary" data-toggle="modal" data-target="#riwayatcommentlikes"> Lihat riwayat Likes Comments</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card h-100">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-uppercase mb-1">Views Story</div>
							<span class="text-danger"><i class="fa fa-refresh"></i> Masih dalam pengembangan</span>
							<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="countvs"></div>
							<div class="mt-2 mb-0 text-muted text-xs">
								<span><i class="fas fa-plus"></i> Views Story ditambahkan</span>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-line-chart fa-2x text-info"></i>
						</div>
					</div>
					<hr>
					<div class="text-xs font-weight-bold text-uppercase mb-1 text-center">
						<div class="card border-0">
							<button class="btn btn-info" data-toggle="modal" data-target="#panelstory" id="btn-story" disabled> <i class="fas fa-arrow-up"></i> Tambah Views Story</button>
						</div>
						<i class="fas fa-history"></i><a class="text-secondary" disabled> Lihat riwayat Views Story</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card h-100">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-uppercase mb-1">Views Video</div>
							<span class="text-danger"><i class="fa fa-refresh"></i> Masih dalam pengembangan</span>
							<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="countvv"></div>
							<div class="mt-2 mb-0 text-muted text-xs">
								<span><i class="fas fa-plus"></i> Views Video ditambahkan</span>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-video-camera fa-2x text-info"></i>
						</div>
					</div>
					<hr>
					<div class="text-xs font-weight-bold text-uppercase mb-1 text-center">
						<div class="card border-0">
							<button class="btn btn-info" data-toggle="modal" data-target="#panelvideo" id="btn-video" disabled> <i class="fas fa-arrow-up"></i> Tambah Views Video</button>
						</div>
						<i class="fas fa-history"></i><a class="text-secondary" disabled> Lihat riwayat Views Video</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-8 col-md-6 mb-4">
			<div class="card mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Riwayat Transaksi</h6>
				</div>
				<div class="table-responsive p-3">
					<table class="table align-items-center table-flush table-hover" id="laporanfollower">
						<thead class="thead-light">
							<tr>
								<th>No</th>
								<th>Link</th>
								<th>Jumlah</th>
								<th>Tanggal</th>
								<th>Tipe</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>No</th>
								<th>Link</th>
								<th>Jumlah</th>
								<th>Tanggal</th>
								<th>Tipe</th>
							</tr>
						</tfoot>
						<tbody>
							<?php $no = 1;
							foreach ($trx as $trx) {
								echo '
							<tr>
								<td>' . $no++ . '</td>								
								<td>' . $trx['LINK'] . '</td>
								<td>' . $trx['JUMLAH'] . '</td>
								<td>' . $trx['DATE'] . '</td>
								';
								if ($trx['TYPE_FT'] == '1')
									echo '<td>Followers</td>';
								if ($trx['TYPE_FT'] == '2')
									echo '<td>Likes</td>';
								if ($trx['TYPE_FT'] == '3')
									echo '<td>Comment</td>';
								if ($trx['TYPE_FT'] == '4')
									echo '<td>Comment Likes</td>';
								echo '</tr>';
							} ?>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-xl-4 col-md-6 mb-4">
			<div id="accordion">
				<div class="card">
					<div class="card-header">
						<div class="card-link text-center" data-toggle="collapse" href="#collapseOne">
							<img src="<?= $profile['foto'] ?>" class="img-fluid rounded-circle mx-auto d-block">
							<h4><?= $profile['fullname'] ?></h4>
							<p><?= $profile['bio'] ?></p>
						</div>
					</div>
					<div id="collapseOne" class="collapse show" data-parent="#accordion">
						<div class="card-body">
							<p><i class="fas fa-user-tie"></i> <span class="font-weight-bold"><?= $profile['fullname'] ?></span></p>
							<p><i class="fas fa-at"></i> <span class="font-weight-bold" id="username"><?= $profile['username'] ?></span></p>
							<p><i class="fas fa-circle"></i> <span class="font-weight-bold"><?= $profile['followers'] ?> Followers</span></p>
							<p><i class="fas fa-circle"></i> <span class="font-weight-bold"><?= $profile['following'] ?> Following</span></p>
							<p><i class="fas fa-folder"></i> <span class="font-weight-bold"><?= $profile['post'] ?> Posts</span></p>
							<p id="id_user" hidden><?= $user['ID_USER'] ?></p>
						</div>
					</div>
					<div class="card-footer text-center">
						<a class="text-info" href="https://instagram.com/<?= $profile['username'] ?>"> <i class="fab fa-instagram fa-2x"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--Row-->

</div>
<!---Container Fluid-->
</div>