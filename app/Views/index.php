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
                                <h4>Welcome Back
                                    <?php 
                                        if (isset($adminData)) { 
                                            echo($adminData['username']); 
                                        } else {
                                            echo "Admin Username not found!!"; 
                                        }
                                    ?>
                                </h4>
                            </div>
                        </div>
                        <div class="col-6 align-self-end text-end">
                            <img src="<?= base_url('assets/images/customer-support.jpg') ?>" class="img-fluid illustration-img" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  <!--Row end-->

    <div class="row">
        <!-- Card 1 -->
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Traffic</h5>
                    <span class="h2 font-weight-bold mb-0">350,897</span>
                </div>
                <div class="col-auto">
                    <div class="icon icon-shape bg-danger text-white shadow">
                        <i class="fas fa-chart-line"></i>    
                    </div>
                </div>
                </div>
                <p class="mt-3 mb-0 text-muted text-sm">
                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                <span class="text-nowrap">Since last month</span>
                </p>
            </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Customers</h5>
                    <span class="h2 font-weight-bold mb-0"><span class="badge text-success me-2">+<?= isset($totalUsers) ? $totalUsers : 'None'; ?></span></span>
                </div>
                <div class="col-auto">
                    <div class="icon icon-shape bg-warning text-white shadow">
                    <i class="fas fa-user"></i>
                    </div>
                </div>
                </div>
                <p class="mt-3 mb-0 text-muted text-sm">
                <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> 1.10%</span>
                <span class="text-nowrap">Since last week</span>
                </p>
            </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Total Sales</h5>
                    <span class="h2 font-weight-bold mb-0">                
                        <span class="badge text-success mr-2">
                            +$<?= isset($totalSales) ? $totalSales : 'None'; ?>
                        </span>
                    </span>
                </div>
                <div class="col-auto">
                    <div class="icon icon-shape bg-success text-white shadow">
                    <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                </div>
                <p class="mt-3 mb-0 text-muted text-sm">
                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 50.3%</span>
                <span class="text-nowrap">Since yesterday</span>
                </p>
            </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Total Orders</h5>
                    <span class="h2 font-weight-bold mb-0"><span class="badge text-success me-2">+<?= isset($totalOrders) ? $totalOrders : 'None'; ?></span></span>
                </div>
                <div class="col-auto">
                    <div class="icon icon-shape bg-info text-white shadow">
                    <i class="fas fa-chart-bar"></i>
                    </div>
                </div>
                </div>
                <p class="mt-3 mb-0 text-muted text-sm">
                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 5.67%</span>
                <span class="text-nowrap">Since last quarter</span>
                </p>
            </div>
            </div>
        </div>
    </div><!--Row end-->
    <!-- Analytics -->    
    <div class="row">
        <!-- Product Performance Chart --> 
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Product Performance</h5>
                    <canvas id="productPerformanceChart" style="height: 270px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Revenue Breakdown Chart -->
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Revenue Breakdown</h5>
                    <canvas id="categoryRevenueChart" style="max-height: 270px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Traffic and Acquisition Chart -->
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Traffic & Acquisition</h5>
                    <h2 style="color: darksalmon;">Can Utilise Google Analytics if live</h2>
                    
                </div>
            </div>
        </div>
    </div>
                                    


</main>

<!-- Include your script at the end -->
<script>

var ctx = document.getElementById('productPerformanceChart').getContext('2d');
var productPerformanceChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= $productNames ?>, // The product names will appear on the x-axis
        datasets: [{
            label: 'Total Sales',
            data: <?= $productSales ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        plugins: {
            legend: {
                labels: {
                    color: '#42a5f5', // Change the color of the label text in the legend
                    font: {
                        size: 16, // Font size for the label text in the legend
                    }
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#42a5f5' // Change the color of the tick labels (x-axis labels)
                }
            },
        }
    }
});

console.log(<?= $revenues ?>);
var revenue = document.getElementById('categoryRevenueChart').getContext('2d');
var categoryRevenueChart = new Chart(revenue, {
    type: 'pie',
    data: {
        labels: <?= $categories ?>,
        datasets: [{
            data: <?= $revenues ?>,
            backgroundColor: ['#FF5733', '#33FF57', '#3357FF', '#F1C40F', '#9B59B6']
        }]
    }
});
</script>