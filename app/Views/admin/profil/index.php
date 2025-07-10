<?= $this->extend('template/index') ?>
<?= $this->section('content') ?>
<main id="main-content">
    <div id="profil">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-4">Profil Pengguna</h2>
            <a class="btn btn-danger" id="tambahKategori" href="<?= base_url('auth/logout'); ?>">
                <i class="bi bi-box-arrow-right me-2"></i></i>Logout
            </a>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body profile-header">
                        <?php if (@$login['foto']): ?>
                            <img src="<?= base_url('profil/profil_foto'); ?>" alt="Profile Picture" class="profile-pic profile-img">
                        <?php else: ?>
                            <img src="https://placehold.co/120x120/0d6efd/ffffff?text=<?= substr(strtoupper($login['nama_lengkap']), 0, 1); ?>" alt="Profile Picture" class="profile-pic profile-img">
                        <?php endif ?>
                        <h4 class="mb-0 mt-2"><?= strtoupper(@$user['nama_lengkap']); ?></h4>
                        <p class="text-muted"><?= $user['username']; ?> | <?= @$user['email']; ?></p>
                        <form id="uploadForm" enctype="multipart/form-data">
                            <input type="file" id="real-file-input" name="profil_foto" accept="image/*" hidden="hidden" />
                        </form>
                        <button class="btn btn-sm btn-outline-primary mt-2" id="custom-button">
                            <i class="bi bi-camera-fill me-1"></i> Ganti Foto
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Data Diri</h5>
                        <form id="profileForm">
                            <div class="mb-3">
                                <label for="profile-username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= @$user['username']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="profile-name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= @$user['nama_lengkap']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="profile-email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= @$user['email']; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Ubah Password</h5>
                        <form id="changePasswordForm">
                            <div class="mb-3">
                                <label for="old-password" class="form-label">Password Lama</label>
                                <input type="password" class="form-control" id="old-password" placeholder="********">
                            </div>
                            <div class="mb-3">
                                <label for="new-password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="new-password" placeholder="********">
                            </div>
                            <div class="mb-3">
                                <label for="confirm-password" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="confirm-password" placeholder="********">
                            </div>
                            <button type="submit" class="btn btn-warning">Ubah Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    $(document).ready(function() {
        $('#custom-button').on('click', function() {
            $('#real-file-input').click();
        });

        $('#real-file-input').on('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('.profile-img').attr('src', e.target.result);
                }

                reader.readAsDataURL(this.files[0]);

                $.ajax({
                    url: '<?= base_url('profil/upload_foto') ?>',
                    type: 'POST',
                    data: new FormData($('#uploadForm')[0]),
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        response = JSON.parse(response);
                        toas(response.status, response.message);
                    },
                    error: function(xhr, status, error) {
                        toas('error', 'Gagal mengunggah foto profil.');
                    }
                });
            }
        });

        $('#changePasswordForm').on('submit', function(e) {
            e.preventDefault();
            const oldPassword = $('#old-password').val();
            const newPassword = $('#new-password').val();
            const confirmPassword = $('#confirm-password').val();

            if (newPassword !== confirmPassword) {
                toas('error', 'Konfirmasi password tidak cocok.');
                return;
            }

            $.ajax({
                url: '<?= base_url('profil/change_password') ?>',
                type: 'POST',
                data: {
                    old_password: oldPassword,
                    new_password: newPassword,
                    confirm_password: confirmPassword
                },
                dataType: 'json',
                success: function(response) {
                    toas(response.status, response.message);
                    if (response.status === 'success') {
                        $('#changePasswordForm')[0].reset();
                    }
                },
                error: function(xhr, status, error) {
                    toas('error', 'Gagal mengubah password.');
                }
            });
        });

        $('#profileForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '<?= base_url('profil/update_profil') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    toas(response.status, response.message);
                    if (response.status === 'success') {
                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(xhr, status, error) {
                    toas('error', 'Gagal memperbarui profil.');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>