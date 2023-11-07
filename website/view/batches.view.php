<html lang="en">
<head>
    <?php require_once VIEWFOLDER."include/meta.php"; ?>
    <?php require_once VIEWFOLDER."include/header.php"; ?>
    <!--Custom CSS Here-->
</head>
<?php require_once VIEWFOLDER."include/gtrack.php"; ?>
<body>
<?php require_once VIEWFOLDER."include/navigator.php"; ?>
<?php require_once VIEWFOLDER."include/aside.php"; ?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="d-xl-flex justify-content-between align-items-start">
            <h2 class="text-dark font-weight-bold mb-2"> Batches </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add A Batch</h4>
                                <form action="" method="POST" class="forms-sample">
                                    <span><?=UTIL->promptError("batch")?></span>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Batch Name</label>
                                        <input name="batch" type="text" class="form-control" id="exampleInputUsername1" placeholder="Batch Name">
                                    </div>
                                    <button name="create-batch" value="true" type="submit" class="btn btn-primary mr-2">Submit</button>
                                    <!-- <button class="btn btn-light">Cancel</button> -->
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Batches</h4>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Batch Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($batches) && property_exists($batches, "result") && $batches->result) {
                                            foreach ($batches->result as $batch) { ?>
                                                <tr>
                                                    <td><?=$batch->id?></td>
                                                    <td><?=$batch->batch?></td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    <!-- <footer class="footer">
        <div class="footer-inner-wraper">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © bootstrapdash.com 2020</span>
                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap dashboard templates</a> from Bootstrapdash.com</span>
            </div>
        </div>
    </footer> -->
    <!-- partial -->
</div>
<?php require_once VIEWFOLDER."include/footer.php"; ?>
<?php require_once VIEWFOLDER."include/components.php"; ?>
<!--Custom JS Here-->
</body>
</html>
