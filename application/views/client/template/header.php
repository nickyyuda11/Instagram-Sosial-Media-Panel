<!--
   Author: W3layouts
   Author URL: http://w3layouts.com
-->
<!doctype html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title><?= $title ?></title>

	<!-- Template CSS -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/lp/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/lp/css/style-liberty-demo.css">
	<link href="<?= base_url() ?>assets/lp/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700&amp;display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Muli:400,600,700&amp;display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
</head>

<body id="home">
	<section class=" w3l-header-4 header-sticky">
		<header class="absolute-top">
			<div class="container">
				<nav class="navbar navbar-expand-lg navbar-light">
					<h1><a class="navbar-brand" href="<?= base_url(); ?>"><span class="fa fa-line-chart" aria-hidden="true"></span>
							Semanggipanel
						</a></h1>
					<button class="navbar-toggler bg-gradient" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>

					<div class="collapse navbar-collapse" id="navbarNav">
						<ul class="navbar-nav">
							<li class="nav-item">
								<a class="nav-link" href="<?= base_url() ?>">Home <span class="sr-only">(current)</span></a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="<?= base_url() ?>about">About</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="<?= base_url() ?>services">Services</a>
							</li>

							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Blog
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
									<a class="nav-link " href="<?= base_url() ?>blog" class="drop-text">Blog</a>
									<a class="nav-link " href="<?= base_url() ?>blog-single" class="drop-text">Blog Single</a>
								</div>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="<?= base_url() ?>pricing">Pricing</a>
							</li>

						</ul>
						<?php if (!isset($_SESSION['username'])) {
							echo '<ul class="navbar-nav search-righ">
                            <li class="nav-item">
                                <a class="btn btn-secondary" data-toggle="modal" data-target="#masuk"> <i class="fa fa-sign-in"></i> Masuk</a>
                            </li>
                        </ul>';
						} else {
							echo '<ul class="navbar-nav search-righ">
                            <li class="nav-item">
                                <a class="btn btn-secondary" href="' . base_url('client/logout') . '"><i class="fa fa-sign-out"></i> Keluar</a>
                            </li>
                        </ul>';
						} ?>

					</div>
			</div>

			</nav>
			</div>
		</header>
	</section>

	<script src="<?= base_url() ?>assets/lp/js/jquery-3.3.1.min.js"></script> <!-- Common jquery plugin -->
	<!--bootstrap working-->
	<script src="<?= base_url() ?>assets/lp/js/bootstrap.min.js"></script>
	<!-- //bootstrap working-->
	<!-- disable body scroll which navbar is in active -->
	<script>
		$(function() {
			$('.navbar-toggler').click(function() {
				$('body').toggleClass('noscroll');
			})
		});
	</script>
	<!-- disable body scroll which navbar is in active -->
