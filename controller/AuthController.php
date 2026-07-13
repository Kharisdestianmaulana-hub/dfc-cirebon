<?php
require_once __DIR__ . '/BaseController.php'; require_once __DIR__ . '/../models/Auth.php'; require_once __DIR__ . '/../models/ActivityLog.php';
class AuthController extends BaseController {
 public function login(): void { $this->session(); if(!empty($_SESSION['isLoggedIn'])) $this->redirect('admin-stok.php'); $error=null; if(isset($_POST['login'])) { $user=(new Auth())->login($_POST['username'] ?? '',$_POST['password'] ?? ''); if($user){ $_SESSION['isLoggedIn']=true;$_SESSION['username']=$user['username'];$_SESSION['kode_outlet']=$user['username'];$_SESSION['nama_outlet']=$user['nama_outlet'];(new ActivityLog())->add($user['username'],'Login Masuk',"Admin {$user['username']} berhasil login ke sistem.");$this->redirect('admin-stok.php'); } $error='Username atau Password salah!'; } $this->view('login',compact('error')); }
 public function logout(): void { $this->session(); session_destroy(); $this->redirect('admin-login.php'); }
}
