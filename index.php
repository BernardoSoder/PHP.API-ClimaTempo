<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clima Tempo</title>
        <style>
            body {
                font-family: 'Trebuchet MS', Arial, sans-serif;
                background-color: #f0f8ff;
                color: #333;
                margin: 0;
                padding: 20px;
            }
            .container {
                max-width: 600px;
                margin: auto;
                background: white;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                padding: 20px;
                text-align: center;
            }
            h1 {
                font-size: 24px;
                margin-top: 20px;
            }
            .icon {
                width: 70px;
                height: 70px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 8px; 
                text-align: center;
            }
            th {
                background-color: #4CAF50;
                color: white;
            }
            .wind-direction {
                position: relative;
                display: inline-block;
            }
            .arrow {
                width: 20px;
                height: 20px;
                position: absolute;
                top: 50%;
                left: -30px;
                margin-top: -10px;
            }
            .days {
                margin-top: 20px;
                text-align: left;
            }
            .days table {
                margin-top: 10px;
            }
            .days th {
                background-color: #4CAF50;
                color: white;
            }
            .days td {
                text-align: left;
                font-size: 14px;  
            }
            .data {
                font-size: 12px;
            }
            .tempo-icons {
                vertical-align: middle;
            }
        </style>
    </head>
    <body>
    <?php
    $url = "http://apiadvisor.climatempo.com.br/api/v1/forecast/locale/5092/days/15?token=1418866c6fa268d067523d1f2c01971b";
    $curl = curl_init();
    curl_setopt_array($curl, [CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true]);
    $response = curl_exec($curl);
    curl_close($curl);
    $cidade = json_decode($response, true);
    ?>
    <div id="climaTempo" class="container">
        <h1><?=$cidade['name'], " - ", $cidade['state']?></h1>
        <?php
            $dataHora = $cidade['data'][0]['date'];
            $hora = date('H', strtotime($dataHora));
            $noite = ($hora < 6 || $hora >= 18);
            $condicaoTexto = $cidade['data'][0]['text_icon']['text']['pt'];
            $iconeMadrugada = $cidade['data'][0]['text_icon']['icon']['dawn'];
            $iconeManha = $cidade['data'][0]['text_icon']['icon']['morning'];
            $iconeDia = $cidade['data'][0]['text_icon']['icon']['day'];
            $iconeTarde = $cidade['data'][0]['text_icon']['icon']['afternoon'];
            $iconeNoite = $cidade['data'][0]['text_icon']['icon']['night'];
            $temperaturaMin = $cidade['data'][0]['temperature']['min'];
            $temperaturaMax = $cidade['data'][0]['temperature']['max'];
            $chanceDeChuva = $cidade['data'][0]['rain']['probability'];
            $nascerDoSol = date('H:i', strtotime($cidade['data'][0]['sun']['sunrise']) - 3 * 3600);
            $porDoSol = date('H:i', strtotime($cidade['data'][0]['sun']['sunset']) - 3 * 3600);
            $direcaoVento = $cidade['data'][0]['wind']['direction'];
            $direcaoVentoGraus = $cidade['data'][0]['wind']['direction_degrees'];
            $velocidadeVento = $cidade['data'][0]['wind']['velocity_avg'];
            $umidade = $cidade['data'][0]['humidity']['max'];
            $pressao = $cidade['data'][0]['pressure']['pressure'];
            $sensaotermica = $cidade['data'][0]['thermal_sensation']['min'];
        ?>
        <div style="display: inline-block; text-align: center;">
            <img src="./img/iconesClima/70px/<?=$iconeMadrugada?>.png" class="icon" style="display: inline;">
            <p>Madrugada</p>
        </div>

        <div style="display: inline-block; text-align: center;">
            <img src="./img/iconesClima/70px/<?=$iconeManha?>.png" class="icon" style="display: inline;">
            <p>Manhã</p>
        </div>
        <div style="display: inline-block; text-align: center;">
            <img src="./img/iconesClima/70px/<?=$iconeTarde?>.png" class="icon" style="display: inline;">
            <p>Tarde</p>
        </div>

        <div style="display: inline-block; text-align: center;">
            <img src="./img/iconesClima/70px/<?=$iconeNoite?>.png" class="icon" style="display: inline;">
            <p>Noite</p>
        </div>
        <div class="info"><?=$condicaoTexto?></div>
        
        <table>
            <tr>
                <th>Temperatura (Min / Máx)</th>
                <td>
                    <img src="./img/blueArrow.png" style="vertical-align: middle;" width="20" height="20"> <?=$temperaturaMin?> ºC / 
                    <img src="./img/redArrow.png" style="vertical-align: middle;" width="20" height="20"> <?=$temperaturaMax?> ºC
                </td>
            </tr>
            <tr>
                <th>Vento</th>
                <td>
                    <div class="wind-direction">
                        <img src="./img/blackArrow.png" class="arrow" style="transform: rotate(<?=$direcaoVentoGraus?>deg);">
                        <?=$direcaoVento ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Chance de Chuva</th>
                <td>
                    <?php
                    if ($chanceDeChuva <= 10) {
                        echo '<img src="./img/blackDrops.png" style="position: relative; top: 4px;" width="20" height="20">';
                    } elseif ($chanceDeChuva <= 50) {
                        echo '<img src="./img/oneDrops.png" style="position: relative; top: 4px;" width="20" height="20">';
                    } elseif ($chanceDeChuva <= 70) {
                        echo '<img src="./img/twoDrops.png" style="position: relative; top: 4px;" width="20" height="20">';
                    } else {
                        echo '<img src="./img/threeDrops.png" style="position: relative; top: 4px;" width="20" height="20">';
                    }
                    ?>
                    <?=$chanceDeChuva?> %
                </td>
            </tr>
            <tr>
                <th>Velocidade do Vento</th>
                <td><?=$velocidadeVento?> km/h</td>
            </tr>
            <tr>
                <th>Umidade</th>
                <td><?=$umidade?> %</td>
            </tr>
            <tr>
                <th>Pressão</th>
                <td><?=$pressao?> hPa</td>
            </tr>
            <tr>
                <th>Sensação Térmica</th>
                <td><?=$sensaotermica?> ºC</td>
            </tr>
            <tr>
                <th>Nascer do Sol</th>
                <td><?=$nascerDoSol?></td>
            </tr>
            <tr>
                <th>Pôr do Sol</th>
                <td><?=$porDoSol?></td>
            </tr>
        </table>

        <div class="days">
            <h2>Próximos 6 Dias</h2>
            <table>
                <tr>
                    <th>Data</th>
                    <th>Previsão</th>
                    <th>Temperatura</th>
                    <th>Chance de Chuva</th>
                </tr>
                <?php foreach (array_slice($cidade['data'], 1) as $key => $dia): ?>
                    <tr>
                        <td class="data" style="font-size: 12px;"><?= $dia['date'] ?></td>
                        <td><?= $dia['text_icon']['text']['pt'] ?></td>
                        <td style="font-size: 11px;">
                            <img src="./img/blueArrow.png" style="position: relative; top: -1px;" class="tempo-icons" width="12" height="12"> <?=$dia['temperature']['min']?> ºC / 
                            <img src="./img/redArrow.png" style="position: relative; top: -1px;" class="tempo-icons" width="12" height="12"> <?=$dia['temperature']['max']?> ºC
                        </td>
                        <td style="font-size: 12px; position: relative; left: 20px;">
                            <?php
                            if ($dia['rain']['probability'] <= 10) {
                                echo '<img src="./img/blackDrop.png" style="position: relative; top: 2px;" width="15" height="15">';
                            } elseif ($dia['rain']['probability'] <= 50) {
                                echo '<img src="./img/oneDrop.png" style="position: relative; top: 2px;" width="15" height="15">';
                            } elseif ($dia['rain']['probability'] <= 70) {
                                echo '<img src="./img/twoDrops.png" style="position: relative; top: 2px;" width="15" height="15">';
                            } else {
                                echo '<img src="./img/threeDrops.png" style="position: relative; top: 2px;" width="15" height="15">';
                            }
                            ?>
                            <?=$dia['rain']['probability']?> %
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    </body>
</html>
