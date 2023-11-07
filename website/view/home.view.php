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
                    <h2 class="text-dark font-weight-bold mb-2"> Overview dashboard </h2>
                    <div class="d-sm-flex justify-content-xl-between align-items-center mb-2">
                        <label style="padding: 5px" for="batch">Batch </label>
                        <select class="form-control" id="batch">
                            <?php if (!empty($batches) && property_exists($batches, "result") && $batches->result) {
                                foreach ($batches->result as $batch) { ?>
                                    <option value="<?=$batch->id?>" <?=(!empty($selectedBatch) && $selectedBatch==$batch->id)?"selected":""?>><?=$batch->batch?></option>
                                <?php }
                            } ?>
                        </select>
                        <label style="padding: 5px" for="lecture"> Lecture </label>
                        <select class="form-control" id="lecture">
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="map" style="border:0; width: calc(100vw - 350px); height: calc(100vh - 200px)">
                        </div>
                    </div>
                </div>
                <?php if (!empty($attendance) && property_exists($attendance, "result") && $attendance->result) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Attendance</h4>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>StudentId</th>
                                            <th>Student</th>
                                            <th>Attendance</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($attendance->result as $mark) { ?>
                                                <tr>
                                                    <td><?=$mark->student_id?></td>
                                                    <td><?=$mark->user_name?></td>
                                                    <td><?=array("⁉️ Absent", "❌ Invalid", "✅ Present")[$mark->attendance - 1]?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            <!--<footer class="footer">
                <div class="footer-inner-wraper">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © bootstrapdash.com 2020</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap dashboard templates</a> from Bootstrapdash.com</span>
                    </div>
                </div>
            </footer>-->
            <!-- partial -->
        </div>
        <?php require_once VIEWFOLDER."include/footer.php"; ?>
        <!--Custom JS Here-->
        <script>
            let map;

            async function initMap() {
                const { Map, Size } = await google.maps.importLibrary("maps");
                const { Marker } = await google.maps.importLibrary("marker");
                // const {SymbolPath} = await google.maps.importLibrary("core");


                const nsbm = {lat: 6.820935, lng: 80.039824};
                const attendance = <?=(!empty($attendance) && property_exists($attendance, "result") && $attendance->result) ? json_encode($attendance->result) : "\"\""?>;

                map = new Map(document.getElementById("map"), {
                    zoom: 17,
                    center: {lat: 6.8202297, lng: 80.0369526},
                })

                const circleIcon = {
                    url: 'data:image/svg+xml,' + encodeURIComponent(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">' +
                        '<circle cx="50" cy="50" r="40" fill="red" fill-opacity="0.5" stroke="red" stroke-width="2" />' +
                        '</svg>'
                    ),
                    scaledSize: new google.maps.Size(500, 500), // Size of the icon
                };
                const red = {
                    url: "<?=STATICS?>images/loc1.png", // Set the URL for the icon
                    scaledSize: new google.maps.Size(40, 40), // Size of the icon
                }
                const blue = {
                    url: "<?=STATICS?>images/loc2.png", // Set the URL for the icon
                    scaledSize: new google.maps.Size(40, 40), // Size of the icon
                }

                const circle = new google.maps.Circle({
                    center: nsbm, // Example: San Francisco
                    radius: 200, // Radius in meters (adjust as needed)
                    strokeColor: "red", // Border color
                    strokeOpacity: 0.5, // Border opacity
                    strokeWeight: 2, // Border width
                    fillColor: "red", // Fill color
                    fillOpacity: 0.2, // Fill opacity
                    map: map,
                });

                console.log(attendance);
                attendance.forEach(attendee => {
                    let position = nsbm
                    if (attendee['latitude'] !== 0) position.lat = parseFloat(attendee['latitude']);
                    if (attendee['longitude'] !== 0) position.lng = parseFloat(attendee['longitude']);
                    const marker = new Marker({
                        position: position, // Example: San Francisco
                        map: map,
                        title: attendee['student_id'], // Optional: A tooltip for the marker
                        icon: (attendee['attendance'] === 2) ? blue : red
                    });
                })

            }

            initMap()
        </script>
        <script>
            const lectures = <?=(!empty($lectures) && property_exists($lectures, "result") && $lectures->result) ? json_encode($lectures->result) : ""?>;
            const selectedLecture = <?=$selectedLecture??"0"?>;
            const selectedBatch = <?=$selectedBatch??"0"?>;

            let batchSelector = document.getElementById("batch");
            let lectureSelector = document.getElementById("lecture");

            function onBatch() {
                if (lectures !== "") {
                    const selectedOption = batchSelector.options[batchSelector.selectedIndex];
                    const batch = parseInt(selectedOption.value, 10);
                    let chosenLectures = lectures.filter(item => item.batch === batch);
                    lectureSelector.innerHTML = "";
                    const option = document.createElement("option");
                    option.value = "0";
                    option.text = "Select";
                    option.selected = (parseInt(option.value, 10) === selectedLecture);
                    lectureSelector.appendChild(option);
                    chosenLectures.forEach(lecture => {
                        const option = document.createElement("option");
                        option.value = lecture.id;
                        option.text = lecture.lecture;
                        option.selected = parseInt(option.value, 10) === selectedLecture;
                        lectureSelector.appendChild(option);
                    });
                }
            }

            function onLecture() {
                const selectedOption1 = batchSelector.options[batchSelector.selectedIndex];
                const batch = parseInt(selectedOption1.value, 10);
                const selectedOption2 = lectureSelector.options[lectureSelector.selectedIndex];
                const lecture = parseInt(selectedOption2.value, 10);

                window.location.assign("<?=ROOTPATH?>?batch=" + batch + "&lecture=" + lecture);
            }

            onBatch();
            batchSelector.onchange = onBatch;
            lectureSelector.onchange = onLecture;
        </script>
    </body>
</html>
