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
            <h2 class="text-dark font-weight-bold mb-2"> Lectures </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add A Lecture</h4>
                                <form action="" method="POST" class="forms-sample">
                                    <span><?=UTIL->promptError("lecture")?></span>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Lecture Name</label>
                                        <input name="lecture" type="text" class="form-control" id="exampleInputUsername1" placeholder="Lecturer Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Date</label>
                                        <input name="date" type="date" class="form-control" id="exampleInputUsername1" placeholder="Lecturer Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Starting Time</label>
                                        <input name="start" type="time" class="form-control" id="exampleInputUsername1" placeholder="Lecturer Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Ending Time</label>
                                        <input name="end" type="time" class="form-control" id="exampleInputUsername1" placeholder="Lecturer Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelectGender">Batch</label>
                                        <select name="batch" class="form-control" id="exampleSelectGender">
                                            <?php if (!empty($batches) && property_exists($batches, "result") && $batches->result) {
                                                foreach ($batches->result as $batch) { ?>
                                                    <option value="<?=$batch->id?>"><?=$batch->batch?></option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>
                                    <button name="add-lecture" value="true" type="submit" class="btn btn-primary mr-2">Submit</button>
                                    <!-- <button class="btn btn-light">Cancel</button> -->
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Lectures</h4>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Lecture</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($lectures) && property_exists($lectures, "result") && $lectures->result) {
                                            foreach ($lectures->result as $lecture) { ?>
                                                <tr>
                                                    <td><?=$lecture->id?></td>
                                                    <td><?=$lecture->lecture?></td>
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
