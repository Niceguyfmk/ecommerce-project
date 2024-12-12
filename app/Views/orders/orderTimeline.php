<?php if (!empty($message)): ?> <div class="alert alert-success"> <?= esc($message) ?> </div> <?php endif; ?> 

<div class="container">
    <div class="d-flex flex-column overflow-auto h-100 text-dark" style="background-color: ivory;">
        <div class="container h-50 px-4 py-5 mx-auto">
            <div class="card bg-light shadow-lg border border-dark rounded-lg py-3 px-5 my-5">
                <div class="row d-flex justify-content-between mx-5 pt-3 my-3">
                    <div class="container text-center">
                        <p class="h3 text-success mb-3"> Customer: <?= $statusChanges[0]['name'] ?? 'No Order Found' ?> </p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <p class="h5 text-dark">
                            <!-- we use the unique order id -->
                            <i class="text-primary fa-solid fa-cart-shopping fa-lg mr-1"></i> Order ID : <span class="text-success font-weight-bold">
                                <i class="text-secondary fa-solid fa-hashtag mr-1"></i><?= $statusChanges[0]['unique_id'] ?></span>
                            </p>
                        </div>
                        <div class="d-flex flex-column text-sm-right h5">
                            <p class="h5 text-dark">
                            <i class="text-primary fa-solid fa-calendar fa-lg mr-2"></i> Last Updated : <span class="text-success font-weight-bold">
                                <i class="text-secondary fa-solid mr-1"></i><?= $statusChanges[0]['created_at'] ?></span>
                            </p>
                            <p class="h5 text-dark">
                            <i class="text-primary fa-solid fa-car fa-lg mr-2"></i> Tracking ID  : <span class="text-success font-weight-bold">
                                <i class="text-secondary fa-solid fa-hashtag mr-1"></i><?= $statusChanges[0]['order_tracking_id'] ?></span>
                            </p> 
                        </div>
                    </div>
                    
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="container-fluid p-2 align-items-center">
                                <div class="d-flex justify-content-around align-items-center">
                                        <?php
                                        $order_status = $statusChanges[0]['order_tracking_status']; // Assuming order_tracking_status contains the current status.
                                        ?>

                                        <!-- Order Confirmed -->
                                        <button class="btn <?php echo ($order_status === "Order Confirmed" || $order_status === "Order Shipped" || $order_status === "Out for Delivery" || $order_status === "Order Delivered") ? 'bg-success' : 'bg-secondary'; ?> text-white rounded-circle" data-bs-toggle="tooltip" title="Order Confirmed">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                        <span class="bg-<?php echo ($order_status === "Order Confirmed" || $order_status === "Order Shipped" || $order_status === "Out for Delivery" || $order_status === "Order Delivered") ? 'success' : 'secondary'; ?> w-50 p-1 mx-n1 rounded mt-auto mb-auto"></span>

                                        <!-- Order Shipped -->
                                        <?php if($order_status === "Order Shipped" || $order_status === "Out for Delivery" || $order_status === "Order Delivered"): ?>
                                            <button class="btn <?php echo ($order_status === "Order Shipped" || $order_status === "Out for Delivery" || $order_status === "Order Delivered") ? 'bg-success' : 'bg-secondary'; ?> text-white rounded-circle" data-bs-toggle="tooltip" title="Order Shipped">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn bg-secondary text-white rounded-circle" data-bs-toggle="tooltip" title="Order Shipped">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        <?php endif; ?>
                                        <span class="bg-<?php echo ($order_status === "Order Shipped" || $order_status === "Out for Delivery" || $order_status === "Order Delivered") ? 'success' : 'secondary'; ?> w-50 p-1 mx-n1 rounded mt-auto mb-auto"></span>

                                        <!-- Out for Delivery -->
                                        <?php if($order_status === "Out for Delivery" || $order_status === "Order Delivered"): ?>
                                            <button class="btn <?php echo ($order_status === "Out for Delivery" || $order_status === "Order Delivered") ? 'bg-success' : 'bg-secondary'; ?> text-white rounded-circle" data-bs-toggle="tooltip" title="Out for Delivery">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn bg-secondary text-white rounded-circle" data-bs-toggle="tooltip" title="Out for Delivery">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        <?php endif; ?>
                                        <span class="bg-<?php echo ($order_status === "Out for Delivery" || $order_status === "Order Delivered") ? 'success' : 'secondary'; ?> w-50 p-1 mx-n1 rounded mt-auto mb-auto"></span>

                                        <!-- Order Delivered -->
                                        <button class="btn <?php echo ($order_status === "Order Delivered") ? 'bg-success' : 'bg-secondary'; ?> text-white rounded-circle" data-bs-toggle="tooltip" title="Order Delivered">
                                            <i class="fa-solid fa-check"></i>
                                        </button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center flex-wrap my-3 py-4 mx-n2" style="justify-content: space-between;">
                    <div class="d-inline-flex align-items-center">
                        <i class="text-primary fa-solid fa-clipboard-check fa-2xl mx-4 mb-3"></i>
                        <p class="text-dark font-weight-bolder py-1 px-1 mx-n2"> Order <br> Confirmed </p>
                    </div>
                    <div class="d-inline-flex align-items-center">
                        <i class="text-warning fa-solid fa-solid fa-boxes-packing fa-2xl mx-4 mb-3"></i>
                        <p class="text-dark font-weight-bolder py-1 px-1 mx-n2"> Order <br> Shipped </p>
                    </div>
                    <div class="d-inline-flex align-items-center">
                        <i class="text-info fa-solid fa-truck-arrow-right fa-2xl mx-4 mb-3"></i>
                        <p class="text-dark font-weight-bolder py-1 px-1 mx-n2"> Out for <br> Delivery </p>
                    </div>
                    <div class="d-inline-flex align-items-center">
                        <i class="text-success fa-solid fa-house-chimney fa-2xl mx-4 mb-3"></i>
                        <p class="text-dark font-weight-bolder py-1 px-1 mx-n2"> Order <br> Delivered </p>
                    </div>
                </div>
                <?php
                    $message = '';
                    switch($order_status):
                        case "Order Delivered":
                            $message = '<div class="alert alert-success"><h2>Order has been successfully delivered</h2></div>';
                            break;
                        case "Order Failed":
                            $message = '<div class="alert alert-danger"><h2>Order Failed</h2></div>';
                            break;
                        default:
                            $message = '<div class="alert alert-info"><h2>Order Status: ' . htmlspecialchars($order_status) . '</h2></div>';
                    endswitch;
                    echo $message;
                ?>
            </div>
        </div>
    </div>

</div>