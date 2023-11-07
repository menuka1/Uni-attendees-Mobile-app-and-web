<!-- Header -->
<title><?= $HEAD ?? false ?></title>
<link rel="stylesheet" href="<?=STATICS?>vendors/mdi/css/materialdesignicons.min.css">
<link rel="stylesheet" href="<?=STATICS?>vendors/flag-icon-css/css/flag-icon.min.css">
<link rel="stylesheet" href="<?=STATICS?>vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="<?=STATICS?>vendors/font-awesome/css/font-awesome.min.css" />
<link rel="stylesheet" href="<?=STATICS?>vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?=STATICS?>css/style.css">
<link rel="shortcut icon" href="<?=STATICS?>images/favicon.png" />
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<!-- End Header -->

<!-- Tracking Codec -->
<?php if (ENVIRONMENT == "production" && UTIL->checkTrack()) { ?>
    <!-- Hotjar Tracking Code -->
    <script>
        (function(h,o,t,j,a,r)
        {
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:3443644,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=true;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
    <!-- End Hotjar Tracking Code -->
    
    <!-- Meta Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '2545071292307870');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none" alt="fb" src="https://www.facebook.com/tr?id=2545071292307870&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Meta Pixel Code -->
<?php } ?>