    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Search -->
                <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                    <li class="nav-item dropdown no-arrow d-sm-none">
                        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-search fa-fw"></i>
                        </a>
                        <!-- Dropdown - Messages -->
                        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                            <form class="form-inline mr-auto w-100 navbar-search">
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button">
                                            <i class="fas fa-search fa-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $user['USERNAME'] ?></span>
                            <img class="img-profile rounded-circle" src="<?= base_url() ?>assets/admin/img/undraw_profile.svg">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Profile
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                Settings
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                Activity Log
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <h1 class="h3 mb-4 text-gray-800">All Users</h1>

            </div>
            <!-- /.container-fluid -->
            <div class="container-fluid">
                <!-- Content Row -->
                <div class="row">
                    <div class="col-lg-12 mb-4">
                        <div class="card">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Post</h6>
                            </div>
                            <div class="table-responsive p-3">
                                <table class="table align-items-center table-flush" id="dataTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Title</th>
                                            <th>Date</th>
                                            <th>Type Account</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Title</th>
                                            <th>Date</th>
                                            <th>Type Account</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody><?php $no = 1; ?>
                                        <?php foreach ($all_user as $user) : ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $user['USERNAME'] ?></td>
                                                <td><?= $user['CREATED'] ?></td>
                                                <td><?php if ($user['TYPE_ACC'] == 1) {
                                                        echo '<span class="badge badge-danger">Basic</span>';
                                                    } elseif ($user['TYPE_ACC'] == 2) {
                                                        echo '<span class="badge badge-warning">PRO</span>';
                                                    } elseif ($user['TYPE_ACC'] == 3) {
                                                        echo '<span class="badge badge-info">Super PRO</span>';
                                                    } elseif ($user['TYPE_ACC'] == 4) {
                                                        echo '<span class="badge badge-success">GOD PRO</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php if ($user['STATUS'] == 1) {
                                                        echo '<span class="badge badge-success">Active</span>';
                                                    } elseif ($user['STATUS'] == 0) {
                                                        echo '<span class="badge badge-warning">Challenge</span>';
                                                    } elseif ($user['STATUS'] == 2) {
                                                        echo '<span class="badge badge-danger">Suspended</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td><a href="<?= base_url('admin/editUser/' . $user['USERNAME']); ?>" class="btn btn-sm btn-primary">Detail</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>