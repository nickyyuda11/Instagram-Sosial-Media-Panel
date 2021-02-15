<!-- Modal -->
<div class="modal fade" id="masuk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Masuk dengan Akun Instagram Anda</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row justify-content-center">
					<div class="col-lg-10">
						<?php echo form_open('client/login'); ?>
						<div class="text-center">
							<img src="<?= base_url() ?>assets/lp/images/instagram.png" class="h4 text-gray-900 mb-4"></img>
							<?= $this->session->flashdata('message'); ?>
						</div>
						<div class="form-group">
							<input type="text" class="form-control form-control-user" id="username" name="username" placeholder="Username" value="<?= set_value('email'); ?>">
						</div>
						<div class="form-group">
							<input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
						</div>
						<button type="submit" id="submit" class="btn btn-primary btn-user btn-block">
							<i class="fa fa-sign-in"></i> Secure Login
						</button>
						<?php echo form_close(); ?>
						<hr>
						<small class="text-center" style="color:green;"><i class="fa fa-lock"></i> Form login menggunakan API resmi yang terkoneksi langsung dengan Instagram. Data anda 100% aman dan ter-enkripsi 256 bit.</small>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<section class="w3l-footers-20">
	<div class="footers20">
		<div class="container">
			<h2><a class="footer-logo" href="index">
					<span class="fa fa-line-chart mr-2" aria-hidden="true"></span>Semanggipanel</a></h2>
			<div class=" row">
				<!-- <div class="grids-content col-lg-2 col-md-2 col-sm-6">
					<h4>Company</h4>
					<div class="footer-nav">
						<a href="<?= base_url(); ?>index" class="contact-para3">Home</a>
						<a href="<?= base_url(); ?>about" class="contact-para3">About</a>
						<a href="<?= base_url(); ?>services" class="contact-para3">Services</a>
						<a href="<?= base_url(); ?>blog" class="contact-para3">Blog</a>
						<a href="<?= base_url(); ?>contact" class="contact-para3">Contact</a>
					</div>

				</div>
				<div class="grids-content col-lg-3 col-md-3 col-sm-6">
					<h4>Information</h4>
					<a href="mailto:hello@example.com">
						<p class="contact-text-sub contact-para3">hello@example.com</p>
					</a>
					<a href="tel:+7-800-999-800">
						<p class="contact-text-sub contact-para3">+7-800-999-800</p>
					</a>
					<p class="contact-text-sub contact-para3">California, </p>
					<p class="contact-text-sub contact-para3">75 West Rock, Maple Building</p>
					<div class="buttons-teams">
						<a href="#team"><span class="fa fa-facebook" aria-hidden="true"></span></a>
						<a href="#team"><span class="fa fa-twitter" aria-hidden="true"></span></a>
						<a href="#team"><span class="fa fa-google-plus" aria-hidden="true"></span></a>
					</div>
				</div> -->
				<div class="col-lg-12 col-md-12 col-12 copyright-grid ">
					<p class="copy-footer-29">© 2020 hype . All rights reserved | Design by <a href="https://w3layouts.com/" target="_blank"> W3Layouts</a>
					</p>
				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
	</div>
</section>

<!-- move top -->
<button onclick="topFunction()" id="movetop" title="Go to top">
	<span class="fa fa-angle-up"></span>
</button>
<script src="<?= base_url() ?>assets/lp/js/Chart.min.js"></script>
<script src="<?= base_url() ?>assets/lp/js/script.js"></script>
<script src="<?= base_url() ?>assets/lp/js/jquery-3.5.1.js"></script>
<script src="<?= base_url() ?>assets/lp/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets/lp/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>assets/lp/js/app.js"></script>

<!-- /move top -->
</body>

</html>