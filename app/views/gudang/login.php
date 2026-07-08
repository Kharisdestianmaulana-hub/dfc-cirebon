<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            
            <div class="card shadow-lg border-0 rounded-4 mt-5">
                <div class="card-header bg-white text-center border-0 pt-4 pb-0">
                    <h3 class="fw-bold" style="color: #e60023;">
                        <i class="fas fa-boxes me-2"></i>Login Gudang
                    </h3>
                    <p class="text-muted small">Manajemen Stok DFC Pusat</p>
                </div>
                
                <div class="card-body p-4">
                    
                    <?php if(isset($data['error'])) : ?>
                        <div class="alert alert-danger text-center small py-2 mb-4" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> <?= $data['error']; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= BASEURL; ?>/gudang/login" method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold small">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                                <input type="text" name="username" class="form-control bg-light border-start-0" placeholder="Masukkan username" required autofocus>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold small">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="Masukkan password" required>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="login" class="btn btn-dfc btn-lg fw-bold rounded-3">
                                MASUK AMAN <i class="fas fa-sign-in-alt ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4 text-muted small">
                &copy; <?= date('Y'); ?> DFC Cirebon - Sistem Manajemen
            </div>

        </div>
    </div>
</div>
