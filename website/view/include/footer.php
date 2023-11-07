<!-- Footer -->
<!-- page-body-wrapper ends -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<!--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyD2Wty15KnL8TSbj3BMR_DK9G33sEMbQAI"></script>
<script nomodule>
    let directionsDisplay, map;

    function initialize() {
        directionsDisplay = new google.maps.DirectionsRenderer();
        var chicago = new google.maps.LatLng(41.850033, -87.6500523);
        var mapOptions = { zoom:7, mapTypeId: google.maps.MapTypeId.ROADMAP, center: chicago }
        map = new google.maps.Map(document.getElementById("map"), mapOptions);
        directionsDisplay.setMap(map);
    }
</script>-->

<script src="<?=STATICS?>vendors/js/vendor.bundle.base.js"></script>
<script src="<?=STATICS?>vendors/chart.js/Chart.min.js"></script>
<script src="<?=STATICS?>vendors/jquery-circle-progress/js/circle-progress.min.js"></script>
<script src="<?=STATICS?>js/off-canvas.js"></script>
<script src="<?=STATICS?>js/hoverable-collapse.js"></script>
<script src="<?=STATICS?>js/misc.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2Wty15KnL8TSbj3BMR_DK9G33sEMbQAI"></script>
<script src="<?=STATICS?>js/index.js"></script>
<script>(g=>{
        let h, a, k, p = "The Google Maps JavaScript API", c = "google", l = "importLibrary", q = "__ib__",
            m = document, b = window;b=b[c]||(b[c]={});
        const d = b.maps || (b.maps = {}), r = new Set, e = new URLSearchParams,
            u = () => h || (h = new Promise(async (f, n) => {
                await (a = m.createElement("script"));
                e.set("libraries", [...r] + "");
                for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                e.set("callback", c + ".maps." + q);
                a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                d[q] = f;
                a.onerror = () => h = n(Error(p + " could not load."));
                a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                m.head.append(a)
            }));
        d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f, ...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
    ({key: "AIzaSyD2Wty15KnL8TSbj3BMR_DK9G33sEMbQAI", v: "beta"});</script>
<!-- End Footer -->

<!-- Tracking Codec -->
<?php require_once VIEWFOLDER."include/track.php"; ?>

<!-- Auto Hard Refresh -->
<?php if (UTIL->checkTrack()) { ?>
    <script>
        // Using JavaScript to perform auto hard refresh without reloading images
        function autoHardRefreshWithoutImages() {
            let images = document.getElementsByTagName('img'); // Get all image elements on the page
            let timestamp = new Date().getTime(); // Get current timestamp

            // Loop through each image and append timestamp as a query parameter to the image URL
            for (let i = 0; i < images.length; i++) {
                let imageUrl = images[i].src; // Get image URL
                let separator = (imageUrl.indexOf('?') !== -1) ? '&' : '?'; // Check if image URL already has query parameters

                // Append timestamp as a query parameter to the image URL
                images[i].src = imageUrl + separator + 'timestamp=' + timestamp;
            }
        }

        // Call the function to trigger the auto hard refresh without reloading images
        autoHardRefreshWithoutImages();
    </script>
<?php } ?>
<!-- End Auto Hard Refresh -->