<?php
require_once __DIR__ . '/BaseController.php'; require_once __DIR__ . '/../models/Order.php';
class OrderController extends BaseController { public function index(): void { $this->requireLogin();$kode_outlet=$_SESSION['kode_outlet'];$nama_outlet=$_SESSION['nama_outlet'];$model=new Order();if(isset($_POST['update_status'])){$model->updateStatus($_POST['order_id']??'',$_POST['status_baru']??'');$this->redirect('pesanan-masuk.php');}$orders=$model->activeForOutlet($kode_outlet);$this->view('orders',compact('kode_outlet','nama_outlet','orders')); } }
