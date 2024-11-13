<h1><?= esc($message) ?></h1>
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="title-container">
                <div class="row justify-content">
                    <div class="row">
                        <h2>Products List:</h2>
                </div>
            </div>
    </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Password</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($data)): ?>
    <tr>
        <td colspan="5" class="text-center">No user found.</td>
    </tr>
<?php else: ?>
    <tr>
        <td><?= isset($data['id']) ? esc($data['id']) : 'N/A' ?></td>
        <td><?= esc($data['email']) ?></td>
        <td><?= esc($data['role']) ?></td>
        <td><?= esc($data['password']) ?></td>
        <td>
            <a href="<?= site_url('edit/' . $data['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="<?= site_url('delete/' . $data['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
        </td>
    </tr>
<?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

