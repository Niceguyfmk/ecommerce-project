<div class="container mt-5 py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h2 class="mb-4 text-center py-3"><?= $heading ?></h2>
            <form method="post" action="<?= base_url('/user/update/' . $userData['user_id']) ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= esc($userData['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= esc($userData['email']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter original password (if changing)">
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Enter new password (if changing)">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3"><?= esc($userData['address']) ?></textarea>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('/') ?>" class="btn btn-secondary">Back to Dashboard</a>
                    <button type="submit" id="submitButton" class="btn btn-primary">Update Profile</button>

                </div>
            </form>
        </div>
    </div>
</div>
