<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('Asia/Jakarta');
    }
    private function _this_login()
    {
        if (!isset($_SESSION['username'])) {
            $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">You must been login!</div>');
            redirect(base_url('admin/login'));
        }
    }
    public function index()
    {
        $this->_this_login();
        if (!isset($_SESSION['username'])) {
            redirect('admin/login');
        } else {
            $data['title'] = 'Admin Dashboard - Semanggipanel';
            $data['user'] = $this->admin_model->viewUser();
            $data['active'] = $this->admin_model->getActiveUser();
            $data['nactive'] = $this->admin_model->getNotActiveUser();
            $data['p_user'] = $this->admin_model->getPremiumUser();
            $data['np_user'] = $this->admin_model->getNotPremiumUser();
            $data['fl'] = $this->admin_model->jumlahTransaksi('1');
            $data['likes'] = $this->admin_model->jumlahTransaksi('2');
            $data['cmt'] = $this->admin_model->jumlahTransaksi('3');
            $data['cmtlikes'] = $this->admin_model->jumlahTransaksi('4');
            $this->load->view('admin/template/header', $data);
            $this->load->view('admin/template/sidebar', $data);
            $this->load->view('admin/body', $data);
            $this->load->view('admin/template/footer');
        }
    }

    public function users()
    {
        $this->_this_login();
        if (!isset($_SESSION['username'])) {
            redirect('admin/login');
        } else {
            $data['title'] = 'Admin Dashboard - Semanggipanel';
            $data['user'] = $this->admin_model->viewUser();
            $data['all_user'] = $this->admin_model->getAllUser();
            $this->load->view('admin/template/header', $data);
            $this->load->view('admin/template/sidebar', $data);
            $this->load->view('admin/panel', $data);
            $this->load->view('admin/template/footer');
        }
    }

    public function editUser($id)
    {
        $this->_this_login();
        if (!isset($_SESSION['username'])) {
            redirect('admin/login');
        } else {
            $data['title'] = 'Admin Dashboard - Semanggipanel';
            $data['user'] = $this->admin_model->viewUser();
            $data['all_user'] = $this->admin_model->getUser($id);
            $this->load->view('admin/template/header', $data);
            $this->load->view('admin/template/sidebar', $data);
            $this->load->view('admin/update_users', $data);
            $this->load->view('admin/template/footer');
        }
    }

    public function updateUser()
    {
        $this->_this_login();
        if (isset($_SESSION['username'])) {
            $data = array(
                'username' => htmlspecialchars($this->input->post('username'), true),
                'type_acc' => htmlspecialchars($this->input->post('type_acc'), true),
            );

            $this->db->where('username', $this->input->post('username'));
            $this->db->update('user', $data);
            redirect('admin/users');
        } else {
            redirect('admin/login');
        }
    }

    public function trx()
    {
        $this->_this_login();
        if (isset($_SESSION['username'])) {
            redirect('admin/login');
        } else {
            $data['title'] = 'Admin Dashboard - Semanggipanel';
            $data['user'] = $this->admin_model->viewUser();
            $data['trx'] = $this->admin_model->getAllTrx();
            $this->load->view('admin/template/header', $data);
            $this->load->view('admin/template/sidebar', $data);
            $this->load->view('admin/trx', $data);
            $this->load->view('admin/template/footer');
        }
    }

    public function login()
    {
        $this->load->view('admin/login');
    }

    public function logout()
    {
        $this->session->unset_userdata('username');
        redirect('admin/login');
    }

    public function cek_login()
    {
        $username = str_replace("'", "", $this->input->post('username'));
        $password = $this->input->post('password');
        $user = $this->db->get_where('admin', ['username' => $username])->row_array();
        if (password_verify($password, $user['PASSWORD'])) {
            $data = [
                'username' => $user['USERNAME'],
                'id_admin' => $user['ID_ADMIN']
            ];
            $this->session->set_userdata($data);
            redirect('admin/index');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong password!</div>');
            redirect('admin/index');
        }
    }
}
