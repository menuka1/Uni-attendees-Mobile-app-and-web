<?php if (UTIL->checkTrack()) { ?>
    <script>
        function join(a, b) {
            try {
                // Create objects to hold key-value pairs for a and b
                let queryParamsA = {};
                let queryParamsB = {};

                // Parse the query parameter strings into objects
                if (a !== '') {
                a.split("&").forEach(function(param) {
                    let parts = param.split("=");
                    queryParamsA[parts[0]] = parts[1];
                });
                }

                if (b !== '') {
                b.split("&").forEach(function(param) {
                    let parts = param.split("=");
                    queryParamsB[parts[0]] = parts[1];
                });
                }

                // Merge the two objects
                let mergedQueryParams = Object.assign({}, queryParamsA, queryParamsB);

                // Generate the merged query parameter string
                return Object.keys(mergedQueryParams).map(function (key) {
                    return key + "=" + mergedQueryParams[key];
                }).join("&");
            } catch(e) {
                return '';
            }
        }

        function addRecent() {
            let url = new URL(window.location.href);
            url.search = join('<?=$_SESSION['UTM']['RECENT']?>', (new URLSearchParams(url.search)).toString())
            window.history.replaceState(null, null, url.href);
        }

        function updateUTM() {
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);
            <?php
            foreach ($_SESSION['UTM'] as $param => $value) {
                if ($param != "RECENT" && $param != "PARAMS") {
                    if ($value !== false) { ?>
                        if (params.has('<?=strtolower($param)?>')) {
                            params.set('<?=strtolower($param)?>', '<?=$value?>');
                        } else {
                            params.append('<?=strtolower($param)?>', '<?=$value?>');
                        }
                    <?php }
                }
            }
            ?>
            url.search = params.toString();
            window.history.replaceState(null, null, url.href);
        }

        function addSpecialParam(key, value) {
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);
            if (params.has(key)) {
                params.set(key, value);
            } else {
                params.append(key, value);
            }
            url.search = params.toString();
            window.history.replaceState(null, null, url.href);
        }

        addRecent();
        updateUTM();

        <?php
        // Update UTM recent
        $a = $_SESSION['UTM']['RECENT'];
        $b = $_SERVER['QUERY_STRING'];

        function joinParams($a, $b): string
        {
            // Create arrays to hold key-value pairs for a and b
            $queryParamsA = array();
            $queryParamsB = array();
            // Put your exceptions parameters here
            $exceptions = array('url', 'callback');

            // Parse the query parameter strings into arrays
            if (!empty($a)) {
                $params = explode("&", $a);
                foreach ($params as $param) {
                    $parts = explode("=", $param);
                    if (!in_array(trim($parts[0]), $exceptions)) {
                        $queryParamsA[$parts[0]] = $parts[1];
                    }
                }
            }

            if (!empty($b)) {
                $params = explode("&", $b);
                foreach ($params as $param) {
                    $parts = explode("=", $param);
                    if (!in_array(trim($parts[0]), $exceptions)) {
                        $queryParamsB[$parts[0]] = $parts[1];
                    }
                }
            }

            // Merge the two arrays
            $mergedQueryParams = array_merge($queryParamsA, $queryParamsB);

            // Generate the merged query parameter string
            return http_build_query($mergedQueryParams);
        }

        $params = array();
        foreach ($_SESSION['UTM'] as $param => $value) {
            if ($param != "RECENT" && $param != "PARAMS") {
                if ($value !== false) {
                    $params[strtolower($param)] = $value;
                }
            }
        }

        global $spec_key, $spec_val;
        if (isset($spec_key) && isset($spec_val)) {
            $params[$spec_key] = $spec_val;
        }

        $c = http_build_query($params);

        // Update UTM recent
        $_SESSION['UTM']['RECENT'] = joinParams(joinParams($a, $b), $c);
        ?>
    </script>
<?php } ?>