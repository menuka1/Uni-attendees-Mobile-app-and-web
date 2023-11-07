<?php if (ENVIRONMENT == "production" && UTIL->checkTrack()) { ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-PPC7EWB8Q6"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag()
        {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-PPC7EWB8Q6');
    </script>
    <!-- End Google tag (gtag.js) -->
<?php } ?>