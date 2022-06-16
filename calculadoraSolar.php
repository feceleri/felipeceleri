<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cep = $_POST['cep'];

    $viaCep =  file_get_contents('https://viacep.com.br/ws/' . $cep . '/json/');
    $json = json_decode($viaCep);
    $place = str_replace(" ", "+", urlencode($json->logradouro)) . "+-+" . str_replace(" ", "+", urlencode($json->bairro)) . "," . urlencode($json->localidade) . "," . $cep;

    $link = "http://www.google.com.br/maps/place/" . $place;

    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );

    $response = file_get_contents($link, false, stream_context_create($arrContextOptions));
    preg_match_all('/APP_INITIALIZATION_STATE=(.*?)"m"/s', $response, $location);
    $coordinates = $location[1][0];
    $coordinates = explode(",", str_replace('[[[', "", preg_replace('/]([\s\S\d]*)/', '', $coordinates)));

    echo json_encode($coordinates);


    // $url = "http://www.cresesb.cepel.br/index.php";
    // $ch = curl_init($url);

    // curl_setopt(
    //     $ch,
    //     CURLOPT_HTTPHEADER,
    //     array(
    //         'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
    //         'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
    //         'Cache-Control: max-age=0',
    //         'Connection: keep-alive',
    //         'Content-Type: application/x-www-form-urlencoded',
    //         'Cookie: _ga=GA1.2.1006564176.1655149671; AdoptVisitorId=CwUwJgjAHMBsAMBaCIDGVHAIZQogRgExZiYDMhA7JTAJxlSqFA==; switchgroup1=none; AdoptConsent=N4Ig7gpgRgzglgFwgSQCIgFwgAwGYAc0UArAGwC0+AxgCbHkAsAZlQIbkCcLATORMdlZNu2UsQDsHXCAA0IAPYAHBMgB2AFVYBzGJgDaIVAC0AXmBMITAKQCeVWSACaDLRwAeAQQAyEAK42HXABZAEUqADkARisAaXFiBwArAA1fCABxAEcIRWIAJRAAXTklBAB5XwRNHX1CgF8gA===; _gid=GA1.2.2085603817.1655328023; switchgroup_news=0; _gat_gtag_UA_139329842_1=1',
    //         'Origin: http://www.cresesb.cepel.br',
    //         'Referer: http://www.cresesb.cepel.br/index.php',
    //         'Upgrade-Insecure-Requests: 1',
    //         'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36'
    //     )
    // );
    // // curl_setopt($ch, CURLOPT_HEADER, false);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);


    // $fields = array(
    //     'latitude_dec' => '23.672282804231',
    //     'latitude' => '-23.672282804231',
    //     'hemi_lat' => '0',
    //     'longitude_dec' => '46.66260761907',
    //     'longitude' => '-46.66260761907',
    //     'formato' => '1',
    //     'lang' => 'pt',
    //     'section' => 'sundata'
    // );

    // curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);



    // $result  = curl_exec($ch);
    // curl_close($ch);

    // if($result){
    //     echo $result;
    // }

    // // Further processing ...
    // if ($result  == "OK") {
    //     echo 'OK';
    // } else {
    //     echo "erro";
    // }



    // echo "<a href='https://www.google.com/search?q=" . $coordinates[2] . "%2C" . $coordinates[1] . "' target='_blank'>" . $coordinates[2] . "," . $coordinates[1] . "</a>";
} else {



    $page =  file_get_contents('https://www.greener.com.br/wp-json/wp/v2/pages/47809');
    $json = json_decode($page);
    preg_match_all('/class="jet-bar-chart-container" data-settings="{(.*?) data-tooltip/s', $json->content->rendered, $matches);

    $settings = $matches[0][0];
    $settings = str_replace('" data-tooltip', "", str_replace('class="jet-bar-chart-container" data-settings="', "", $settings));
    $settings = trim($settings);
    $json = $settings;
    $json = json_decode(htmlspecialchars_decode($json));

    $array = [];
    for ($i = 0; $i < sizeof($json->data->datasets[0]->data); $i++) {

        $mesAno = explode("/", trim($json->data->labels[$i]));
        $mes = $mesAno[0];
        $ano = $mesAno[1] + 2000;

        $array += [$mes . '-' . $ano => $json->data->datasets[0]->data[$i]];
    }

    $maisRecente = [
        0,
        ""
    ];
    foreach ($array as $key => $value) {
        $dataAtual = strtotime($key);
        if ($dataAtual > $maisRecente[0]) {
            $maisRecente[0] = $dataAtual;
            $maisRecente[1] = $value;
        }
    }
    echo date('M/Y', $maisRecente[0]);
    echo "<br>";
    echo  $maisRecente[1];


    // echo "<a href='https://www.google.com/search?q=".$coordinates[2]."%2C".$coordinates[1]."' target='_blank'>".$coordinates[2].",".$coordinates[1]."</a>";





?>
    <!doctype html>
    <html lang="pt-Br">

    <head>
        <title>Estimativa de custo projeto solar</title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    </head>

    <body>
        <div class="container">
            <p>
                Como a irradiação solar é diferente em cada região, precisamos primeiramente saber o índice de irradiação na localização do projeto.<br>
                Esse valor também é conhecido como índice de sol pleno (HSP).<br>
                O <a href="http://www.cresesb.cepel.br/index.php?section=sundata">CRESESB</a> <small>(Centro de Referência para as Energias Solar e Eólica Sérgio de Salvo Brito)</small> é um laboratório que faz parte da CEPEL <small>(Instituto de pesquisa científica no Rio de Janeiro)</small> e divulga esse índice por coordenadas geográficas.<br>
                Chamaremos esse índice de <strong>HSP</strong> que é a maior média anual de (kWh/m2.dia).
            </p>
            <p>
                Para calcularmos o tamanho do nosso sistema fotovoltáico (<strong>Ts</strong>) precisamos da seguinte fórmula:<br>
                <strong>Ts</strong><small> estimado</small> = (<strong>Consumo</strong>/<strong>30</strong>dias)/<strong>HSP</strong>
                <br>
                Considerando que pode haver uma perda de 17 a 20% em nosso sistema, iremos acrescer 20% em nosso <strong>Ts</strong><small> estimado</small>.<br>
                Nossa fórmula final será:<br>
                <strong>Ts</strong> = (<strong>Ts</strong><small> estimado</small> = (<strong>Consumo</strong>/<strong>30</strong>dias)/<strong>HSP</strong>)*1,2
            </p>
            <div class="row">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/uzLw-AJNPkw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" style="margin: auto;margin-top: 30px;margin-bottom: 30px;"></iframe>
            </div>
            <p>
                Com base no vídeo acima calcule o custo estimado do seu projeto.<br>
            <ul>
                <li><strong>Consumo</strong> - Consumo desejado para o projeto. Ex(média de consumo dos últimos 12 meses)
                </li>
                <li><strong>Watts placa</strong> - Potência da placa solar deseja</li>
                <li><strong>Dias/mês</strong> - Quantidade de dias por mês calculado</li>
                <li><strong>Irradiação solar</strong> - Maior média anual de (kWh/m2.dia) com base nos pontos latitudinal e longitudinal do local do projeto</li>
                <li><strong></strong></li>
                <li><strong></strong></li>
                <li><strong></strong></li>
                <li><strong></strong></li>
                <li><strong></strong></li>
                <li><strong></strong></li>
                <li><strong></strong></li>
                <li><strong></strong></li>
            </ul>
            </p>
            <form id="a" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="inputCEP">CEP</label>
                        <input type="number" class="form-control" id="inputCEP" name="inputCEP" placeholder="CEP">
                    </div>
                    <div class="form-group col-md-5">
                        <label for="inputConsumo">Consumo</label>
                        <input type="number" class="form-control" id="inputConsumo" placeholder="Consumo desejado">
                    </div>
                    <div class="form-group col-md-5">
                        <label for="inputPotPlaca">Watts placa</label>
                        <input type="number" class="form-control" id="inputPotPlaca" placeholder="Potência placa em watts">
                    </div>
                    <div class="form-group col-md-2" style="align-self: end;">
                        <button type="submit" class="btn btn-primary">Calcular</button>
                    </div>
                </div>

            </form>

            <div id="resultado">

            </div>
        </div>

        <!-- JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.1.1.min.js">
        </script>
        <script>
            $("#inputCEP").focusout(function() {
                var postData = {
                    "cep": $("#inputCEP").val()
                };
                if (postData) {
                    jQuery.ajax({
                        type: "POST",
                        data: postData,
                        success: function(data) {
                            var latitude = data[2];
                            var longitude = data[1];

                            newData = {
                                'latitude_dec': '23.672282804231',
                                'latitude': '-23.672282804231',
                                'hemi_lat': '0',
                                'longitude_dec': '46.66260761907',
                                'longitude': '-46.66260761907',
                                'formato': '1',
                                'lang': 'pt',
                                'section': 'sundata'
                            }

                            jQuery.ajax({
                                type: "POST",
                                url: 'http://www.cresesb.cepel.br/index.php',
                                data: newData,
                                success: function(data) {
                                    $("#resultado").text(data);
                                }
                            });
                        }
                    });
                }
            });
        </script>
    </body>

    </html>
<?php
}
?>