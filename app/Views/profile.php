<h1><?= esc($message) ?></h1>
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="title-container">
                <div class="row justify-content">
                    <div class="row">
                        <h2>User Profile</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">

            <form method="POST" action="<?= site_url('auth/updateAdminPassword/') ?>">
                <input type="hidden" class="form-control" id="userId" name="id" value="<?= isset($data['id']) ? esc($data['id']) : 'N/A' ?>">
                <div class="form-group mb-4">
                    <label for="userEmail">Email</label>
                    <input type="email" class="form-control" id="userEmail" name="email" value="<?= esc($data['email']) ?>" disabled>
                </div>
                <div class="form-group mb-4">
                    <label for="userRole">Role</label>
                    <input type="text" class="form-control" id="userRole" name="role_id" value="<?= esc($data['role_id']) ?>" disabled>
                </div>
                <div class="form-group mb-4">
                    <label for="userName">Name</label>
                    <input type="text" class="form-control" id="userName" name="name" value="<?= esc($data['username']) ?>">
                </div>
                <div class="form-group mb-4">
                    <label for="userPassword">Password</label>
                    <input type="password" class="form-control" id="userPassword" name="password" placeholder="Enter new password" required>
                </div>
                <div class="form-group mb-4">
                    <label for="userConfirmPassword">Confirm Password</label>
                    <input type="password" class="form-control" id="userConfirmPassword" name="confirm_password" placeholder="Confirm new password" required>
                </div>

                <button type="submit" id="submitButton" class="btn btn-danger">Update Profile</button>
                <a type="button" class="btn btn-info" href="<?= site_url('auth/admin') ?>">Dashboard</a>

            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('submitButton').addEventListener('click', function(event) {
        // Prevent the form from submitting immediately
        event.preventDefault();

        var password = document.getElementById('userPassword').value;
        var confirm_password = document.getElementById('userConfirmPassword').value;

        if (password === confirm_password) {
            alert("Passwords should be different. Please try again.");
            return; 
        }

        if (confirm("Are you sure you want to submit the form?")) {
            document.querySelector('form').submit();
        } else {
            console.log("Form submission cancelled.");
        }
    });
</script>

