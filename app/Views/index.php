<?php if (!empty($message)): ?>
    <div class="alert alert-success">
        <?= esc($message) ?>
    </div>
<?php endif; ?>
<main class="content px-3 py-2">
    <div class="container-fluid">
        <div class="mb-3">
            <h4>Dashboard</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-6 d-flex">
        <div class="card flex-fill border-0 illustration">
            <div class="card-body p-0 d-flex flex-fill">
            <div class="row g-0 w-100">
                <div class="col-6">
                <div class="p-3 m-1">
                    <h4>Welcome Back</h4>
                </div>
                </div>
                <div class="col-6 align-self-end text-end">
                <img src="<?= base_url('assets/images/customer-support.jpg') ?>" class="img-fluid illustration-img" alt="">
                </div>
            </div>
            </div>
        </div>
        </div>
        <div class="col-12 col-md-6 d-flex">
            <div class="card flex-fill border-0">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                        <!--If Admin is viewing it they see all job post stats--> <?php if(isset($email) && $role_id == 1){ ?> <h4 class="mb-2"> <?php echo isset($count) ? $count : '' ?> </h4>
                        <p class="mb-2">Total Job Posts</p>
                        <div class="mb-0">
                            <span class="badge text-success me-2">+ <?= $totalcountMonthly ?> </span>
                            <span class="text-muted">This Month</span>
                        </div>
                        </div> <?php } ?>
                        <!--If Manager is viewing it they see only their job post stats--> <?php if(isset($email) && $role_id == 2){ ?> <h4 class="mb-2"> <?php echo isset($countManager) ? $countManager : '' ?> </h4>
                        <p class="mb-2">Total Job Posts</p>
                        <div class="mb-0">
                        <span class="badge text-success me-2">+ <?= isset($managerMonthlyCount) ? $managerMonthlyCount : '' ?> </span>
                        <span class="text-muted">This Month</span>
                        </div>
                    </div> <?php } ?>
                </div>
            </div>
        </div>
    </div>  <!--Row end-->
</main>