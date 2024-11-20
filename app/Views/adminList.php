<?php if (!empty($message)): ?>
    <div class="alert alert-success">
        <?= esc($message) ?>
    </div>
<?php endif; ?><div class="container">
    <!-- Add a Card for the Table -->
    <div class="card">
        <div class="card-header">
            <h2>Admin List:</h2>
        </div>
        <div class="card-body">
            <!-- Table to display user records -->
            <table class="table table-striped table-bordered table-hover" id="myTable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No users found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= esc($user['admin_id']) ?></td>
                                <td><?= esc($user['email']) ?></td>
                                <td>
                                    <?php 
                                        foreach ($roles as $role) {
                                            if ($role['id'] == $user['role_id']) {
                                                $role_name = $role['role'];  
                                                break;
                                            }
                                        }
                                        echo esc($role_name);
                                    ?>
                                </td>
                                <td><?= esc($user['created_at']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editRoleModal"
                                            data-user-id="<?= esc($user['admin_id']) ?>"
                                            data-current-role-id="<?= esc($user['role_id']) ?>"
                                            data-user-email="<?= esc($user['email']) ?>">Edit</button>
                                    <a href="<?= site_url('auth/admin/delete/' . $user['admin_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Editing Role -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Edit User Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('auth/updateRole') ?>" method="POST" id="roleForm">
                <div class="modal-body">

                    <input type="hidden" id="userId" name="user_id">

                    <!-- Role Selection Dropdown -->
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role_id">
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= esc($role['id']) ?>"><?= esc($role['role']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="userEmail">User Email</label>
                        <input type="text" class="form-control" id="userEmail" disabled>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let table = new DataTable('#myTable');

document.addEventListener('DOMContentLoaded', function () {
    // Initialize the Edit Role Modal functionality
    initializeEditRoleModal();
});
</script>
