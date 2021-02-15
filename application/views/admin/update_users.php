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
        <!-- Content Row -->
        <div class="container-fluid" id="container-wrapper">
            <div class="row">
                <div class="col-lg">
                    <!-- Form Basic -->
                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Update User</h6>
                        </div>
                        <div class="card-body">
                            <?php echo form_open('admin/updateUser'); ?>
                            <input type="hidden" name="id" value="<?php echo $all_user['ID_USER']; ?>">
                            <div class="form-group">
                                <label for="Title">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter title" value="<?= $all_user['USERNAME']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="Description">Type Acc</label>
                                <p>Type Acc User:
                                    <?php if ($all_user['TYPE_ACC'] == 1) {
                                        echo '<span class="badge badge-danger">Basic</span>';
                                    } elseif ($all_user['TYPE_ACC'] == 2) {
                                        echo '<span class="badge badge-warning">PRO</span>';
                                    } elseif ($all_user['TYPE_ACC'] == 3) {
                                        echo '<span class="badge badge-info">Super PRO</span>';
                                    } elseif ($all_user['TYPE_ACC'] == 4) {
                                        echo '<span class="badge badge-success">GOD PRO</span>';
                                    }
                                    ?>
                                </p>
                                <select name="type_acc" id="type_acc" class="form-control">
                                    <option value="1">BASIC</option>
                                    <option value="2">PRO</option>
                                    <option value="3">SUPER PRO</option>
                                    <option value="4">GOD PRO</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <p>Status User:
                                    <?php if ($user['STATUS'] == 1) {
                                        echo '<span class="badge badge-success">Active</span>';
                                    } elseif ($user['STATUS'] == 0) {
                                        echo '<span class="badge badge-warning">Challenge</span>';
                                    } elseif ($user['STATUS'] == 2) {
                                        echo '<span class="badge badge-danger">Suspended</span>';
                                    }
                                    ?>
                                </p>
                            </div>
                            <button type="submit" name="save" value="0" class="btn btn-primary">Save</button>
                            <a href="<?= base_url('admin/users'); ?>" class="btn btn-link text-danger">Cancel</a>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>