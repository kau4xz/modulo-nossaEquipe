<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Projeto API</title>
    <!-- CSS Local -->
    <!--<link rel="stylesheet" type="text/css" href="../swagger-docs/swagger-ui/swagger-ui.css" />-->
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css" />
    <style>
        html { box-sizing: border-box; overflow: -moz-scrollbars-vertical; overflow-y: scroll; }
        *, *:before, *:after { box-sizing: inherit; }
        body { margin: 0; background: #fafafa; }
    </style>
</head>
<body>
    <div id="swagger-ui"></div>

    <!-- Scripts Locais -->
    <!-- <script src="../swagger-docs/swagger-ui/swagger-ui-bundle.js"></script>
    <script src="../swagger-docs/swagger-ui/swagger-ui-standalone-preset.js"></script> -->

    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js" crossorigin></script>
    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-standalone-preset.js" crossorigin></script>

    <script>
    <?php

    $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    $dirName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

    if (str_contains($requestUri, '/public')) {
        $baseUrl = preg_replace('#/swagger(/.*)?$#', '', $dirName);
    } else {
        $baseUrl = preg_replace('#/(public|swagger)(/.*)?$#', '', $dirName);
    }
    $baseUrl = rtrim($baseUrl, '/');
    ?>
    window.onload = function() {
        const ui = SwaggerUIBundle({
            url: "<?php echo $baseUrl ?>/api/v1/docs/json",
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "StandaloneLayout"
        });
        console.log(ui);
        window.ui = ui;
    };
    </script>
</body>
</html>
