<!DOCTYPE html>

<!--
    Resenje 1: index1.php
    Cuvanje podataka u ul
-->

<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citati</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



    <style>

        /* Make the image fully responsive */
        .carousel-inner img {
            width: 100%;
            height: 100%;
        }

        .carousel-item {
            height: 150px;
            transition: 1s; /* Bitno zbog tranzicije slika (umesto trenutnog pojavljivanja) */
        }

        @media only screen and (min-width: 576px) {
            .carousel-item {
                height: 220px;
            }
        }        

        .citati {
            display: none;
        }

        nav button span {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0px 4px;
        }

        #ekran {
            background-color: wheat;
            border: 2px solid #aaa;
            border-left: 12px solid wheat;
            border-radius: 10px 40px 20px 40px;
            box-shadow: 0px 0px 20px gray;
            transition: 2s;
        }

        #citat {
            font-size: 20px;
            padding: 10px;
        }

        #citatA {
            color: #333;
        }

        #citatB {
            color: transparent;
        }                
        
        #autor {
            font-weight: bold;
            text-align: right;
         }

        #avatar {
            border-radius: 50%;
            border: 3px solid white;
            background-color: white;
            box-shadow: 0px 0px 20px gray;
            padding: 3px;
        }

        #customRange {
            margin-top: 30px;
        }

        .footer {
            color: white;
            background-color: blue;
            text-align: center;
        }

        .footer p {
            margin: 0px;
        }

    </style>

</head>

<body class="d-flex flex-column h-100">

    <div id="carousel" class="carousel slide" data-ride="carousel">
        <ul class="carousel-indicators">
            <li data-target="#carousel" data-slide-to="0" class="active"></li>
            <li data-target="#carousel" data-slide-to="1"></li>
            <li data-target="#carousel" data-slide-to="2"></li>
        </ul>

        <div class="carousel-inner">
            <?php
                $folder = "carousel";
                $files = array_diff(scandir($folder), array('..', '.'));
                shuffle($files);
                $active = " active"; // Prva slika treba da ima i klasu active
                for ($i = 0; $i < 3; $i++) {
                    echo '<div class="carousel-item' . $active . '">';
                    echo '<img src="' . $folder . '/' . $files[$i] . '" alt="' . $files[$i] . '">';
                    echo '</div>';
                    $active = "";
                }
            ?>
        </div>

        <a class="carousel-control-prev" href="#carousel" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#carousel" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </a>
    </div>

    <?php
        // Spisak svih .txt fajlova sa citatima
        $files = array_diff(scandir("citati"), array('..', '.'));
        $nav = [];

        // Formiranje <ul> i <nav> za svaki fajl
        foreach ($files as $file) {
            $kategorija = ucfirst(substr($file, 2, -4));
            $nav[] = $kategorija;

            $lines = file("citati/$file", FILE_IGNORE_NEW_LINES);

            echo "\n<ul id='citati$kategorija' class='citati'>";
            for ($i = 0; $i < count($lines) / 2; $i++) {
                echo "\n\t<li>" . $lines[$i*2] . "|" . $lines[$i*2+1]  . "</li>";
            }
            echo "\n</ul>";

        }
    ?>        

    <div class="container">
        <div class="row my-2">
            
            <nav class="col-sm-3 px-4">
                <div class="row">
                    <?php
                        foreach ($nav as $btn) {
                            echo "\n\t\t" . '<button class="btn col-4 col-sm-12 p-1 text-left"><span></span>' . $btn . '</button>';
                        }
                    ?>
                </div>

                <input type="range" class="custom-range" min="0" max="10" step="1" value="8" id="customRange">

            </nav>

            <main class="col-sm-9 my-4">
                <div class="row">
                    <div id="ekran" class="col-11 col-sm-7 m-auto">
                        <p id="citat" class="col-12"><span id="citatA"></span><span id="citatB"></span></p>
                        <div class="row col-12">
                            <p id="autor" class="col-9 col-sm-8"></p>
                        </div>
                    </div>
                </div>
                <img class="col-3 col-sm-2 offset-9 offset-sm-8" id="avatar" src="" alt="avatar">
            </main>
 
        </div>
    </div>
    



    <footer class="footer mt-auto">
        <div class="container">
            <div class="row">
                <?php
                // Vreme preuzeto sa servera ostaje 'zamrznuto'
                // Osvezavanje vremena na stranici treba da preuzme JavaScript
                $dani = array("Nedelja", "Ponedeljak", "Utorak", "Sreda", "Četvrtak", "Petak", "Subota");
                $dan = date('w');
                $datum = date('d.m.Y.');
                $vreme = date('H:i:s');
                echo "<p id='dan' class='col-3'>php - " . $dani[$dan] . "</p>";
                echo "<p id='datum' class='col-6'>php - $datum</p>";
                echo "<p id='vreme' class='col-3'>php - $vreme</p>";
                ?>
            </div>
        </div>
    </footer>

    <script>
        $(document).ready(function() {

            var i, txt;
            var boje = ["green", "red", "blue", "orange", "#999", "#aaa", "#bbb", "#ccc"];
            $('nav button').each(function(){
                $(this).children().css("background-color", boje[$(this).index()]);
            });
            
            $('button').click(function() {
                $('#avatar').css("transition", "0s");
                $('#avatar').css("transform", "translate(10%, -50%) scale(0)");
                $('#autor').css("transition", "0s");
                $('#autor').css("opacity", "0");                

                var boja = $(this).find('span').css("background-color");
                $('#ekran').css("border-left-color", boja);
                $('#avatar').css("border-color", boja);

                i = 0;
                var kategorija = $(this).text();

                var br_citata_kat = $('#citati' + kategorija + ' li').length;
                var random = Math.floor(Math.random() * br_citata_kat);
                var li = $('#citati' + kategorija + ' li').eq(random).text();
                var citat_autor = li.split('|');
                
                $('#citat').children().text('');
                $('#autor').text('');
                txt = citat_autor[0];
                $('#autor').text(citat_autor[1]);

                $rndAvatar = 1 + Math.floor(Math.random() * 9);
                $('#avatar').attr("src", "avatari/s0" + $rndAvatar + ".jpg");
                typeWriter();
            });

            var rndButton = Math.floor(Math.random() * $('nav button').length);
            $('button').eq(rndButton).focus().click();
            
            function typeWriter() {
                var speed = 24 * Math.floor(Math.random() * 6 );

                if (i < txt.length) {
                    $('#citatA').text(txt.slice(0, i+1));
                    $('#citatB').text(txt.slice(i+1));
                    i++;
                    setTimeout(typeWriter, speed);
                }
            }

            $('#avatar').on('load', function() {
                $('#avatar').css("transition", "2s");
                $('#avatar').css("transform", "translate(10%, -50%) scale(1)");
                $('#autor').css("transition", "5s");
                $('#autor').css("opacity", "1");   
            });

            // Datum i vreme
            var dani = {
                    "Sun": "Nedelja",
                    "Mon": "Ponedeljak",
                    "Tue": "Utorak",
                    "Wed": "Sreda",
                    "Thu": "Četvrtak",
                    "Fri": "Petak",
                    "Sat": "Subota"
                };

            setInterval(function() {
                var d = (new Date()).toString();
                $('#vreme').text(d.slice(16, 24));
                if (d.slice(21, 24) == ':00') {
                    ispisDatuma();
                }
            }, 1000);

            ispisDatuma();
            function ispisDatuma() {
                var d = (new Date()).toString();
                $('#dan').text(dani[d.slice(0, 3)]);
                $('#datum').text(d.slice(8, 10) + '.' + d.slice(3, 7) + d.slice(10, 15) + '.');
            }            
            

        });

    </script>


</body>

</html>