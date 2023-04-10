<div class="container">
    <div class="row justify-content-center align-items-center g-2 min-vh-100">
        <div class="col-sm-12 col-md-6 bg-white rounded p-3">
            <h3 class="text-center">UPDATE INFO</h3>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mt-4">


                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control <?= $errors['username'] ? 'is-invalid' : '' ?>" placeholder="Username.." value="<?= htmlspecialchars($user['username']) ?>">
                        <small class="form-text text-muted text-danger"><?= $errors['username'] ?></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" name="email" class="form-control <?= $errors['email'] ? 'is-invalid' : '' ?>" placeholder="Email address.." value="<?= htmlspecialchars($user['email']) ?>">
                        <small class="form-text text-muted text-danger"><?= $errors['email'] ?></small>
                    </div>

                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>