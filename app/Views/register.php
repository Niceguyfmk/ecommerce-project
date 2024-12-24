<div class="container">
    <div class="title-container text-center mt-5">
            <div class="row justify-content">
                <div class="row">
                    <h2>ADD USER</h2>
            </div>
        </div>
    </div>
        <form action = "<?= site_url(relativePath: '/auth/addAdmin') ?>" method="post" id="form-submit">

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                <p id="passwordError" class="text-danger"></p>
            </div>
            <div class="mb-3">
                <input type="hidden" id="role" name="role" value="">
                <div class="select btn-group">
                    <select id="role_id" name="role_id" class="form-select" required>
                        <option value="" disabled selected>Select a role</option>
                        <option value="1">Admin</option>
                        <option value="2">Manager</option>
                    </select>
                    </div>
            </div>
            <?php if (isset($adminData['role_id']) && $adminData['role_id'] === "1"): ?>
            <button type="submit" id="submit" class="btn btn-primary">Submit</button>
            <?php else: ?>
            <button type="submit" id="submit" class="btn btn-primary" disabled>Submit</button>
            <?php endif; ?>
        </form>
</div>

