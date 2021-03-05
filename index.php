<!DOCTYPE html>

<!--
    Resenje 3: index.php
    Preuzimanje podataka u JSON
    https://coja.um.rs/itbcdz/P01/
-->

<html lang="en" class="h-100">

<head>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
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
            max-height: 220px;
        }

        .carousel-item {
            transition: 1s; /* Bitno zbog tranzicije slika (umesto trenutnog pojavljivanja) */
        }

        nav button span {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0px 4px;
        }

        nav button:hover span {
            transform: scale(1.3);
            transition: 0.5s;
        }

        .badge {
            cursor: pointer;
            font-weight: bold;
            transition: 2s;
            font-size: 10px;
            transform: translate(20px, -10px);            
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
            color: #333;
        }

        #citatB {
            color: transparent;
        }                
        
        #avatar {
            border-radius: 50%;
            border: 3px solid white;
            background-color: white;
            box-shadow: 0px 0px 20px gray;
            padding: 3px;
            margin-bottom: -50px;
        }

        .footer p {
            margin: 0px;
        }

    </style>

</head>

<body class="d-flex flex-column h-100">

    <div id="carousel" class="carousel slide" data-ride="carousel">
        <ul class="carousel-indicators">
            <?php
                $brojSlika = 3; //Relativizacija broja slika u slajderu
                // Formiranje indikatora, prvi treba da ima klasu active
                $active = " class='active'";
                for ($i = 0; $i < $brojSlika; $i++) {
                    echo "\n\t\t\t<li data-target='#carousel' data-slide-to='$i'$active></li>";
                    $active = "";
                }
            ?>

        </ul>

        <div class="carousel-inner">
            <?php
                $folder = "carousel";
                $files = array_diff(scandir($folder), array('..', '.'));
                shuffle($files);
                $active = " active"; // Prva slika treba da ima i klasu active
                for ($i = 0; $i < $brojSlika; $i++) {
                    echo "\n\t<div class='carousel-item$active'>";
                    echo "\n\t\t<img src='$folder/$files[$i]' alt='$files[$i]'>";
                    echo "\n\t</div>";
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
    

    <div class="container">
        <div class="row my-2">

            <nav class="col-sm-3 px-4">
                <div class="row">
                    <?php
                        // Svi podaci u JSON
                        $files = array_diff(scandir("citati"), array('..', '.'));
                        $sviCitati = new stdClass();
                        foreach ($files as $file) {
                            $kategorija = ucfirst(substr($file, 2, -4));
                            $lines = file("citati/$file", FILE_IGNORE_NEW_LINES);
                            $sviCitati->$kategorija = $lines;
                            
                            echo "\n\t\t<button class='btn col-4 col-sm-12 p-1 text-left'><span></span>$kategorija</button>";
                        }
                    ?>

                </div>
            </nav>

            <main class="col-sm-9 mt-4">
                <div id="ekran" class="col-11 col-md-7 m-auto">
                    <p class="m-0 text-right">
                        <span id="info" class="badge badge-pill badge-secondary p-2">Info</span>
                        <span class="badge badge-pill badge-secondary p-2">&#9655;</span>
                    </p>
                    <p id="citat"><span id="citatA"></span><span id="citatB"></span></p>
                    <p id="autor" class="text-center font-weight-bold"></p>
                </div>

                <img class="col-3 col-sm-2 offset-9 offset-md-8" id="avatar" src="" alt="avatar">
            </main>
 
        </div>
    </div>

    <footer class="footer bg-primary text-white text-center mt-auto">
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

var sviCitati = <?php echo json_encode($sviCitati, JSON_PRETTY_PRINT);?>;

            var strKategorija;
            var intCitat, intBrCitata;
            var boje = ["green", "red", "blue", "orange", "#999", "#aaa", "#bbb", "#ccc"];
            
            $('nav button').each(function(){
                $(this).children().css("background-color", boje[$(this).index()]);
            });

            // Random Citat iz kategorije
            $('nav button').click(function() {
                var boja = boje[$(this).index()];
                $('#ekran').css("border-left-color", boja);
                $('#avatar').css("border-color", boja);
                $('.badge').css("background-color", boja);

                strKategorija = $(this).text();
                intBrCitata = sviCitati[strKategorija].length / 2;
                intCitat = Math.floor(Math.random() * intBrCitata);

                dataEkran();
            });
            // Klik na random button prilikom ucitavanja stranice
            var rnd = Math.floor(Math.random() * $('nav button').length);
            $('nav button').eq(rnd).focus().click();           

            // Sledeci Citat iz kategorije (ciklicno)
            $('.badge').click(function() {
                intCitat = (1 + intCitat) % intBrCitata;

                dataEkran();
            });            

            function dataEkran() {
                $('#avatar, #autor').css("transition", "0s");
                $('#avatar').css("transform", "translate(8%, -55%) scale(0)");
                $('#autor').css("opacity", "0");

                $('#citatA').text('');
                $('#citatB').text(sviCitati[strKategorija][intCitat * 2]);
                $('#autor').text(sviCitati[strKategorija][intCitat * 2 + 1]);
                $('#info').text((intCitat + 1) + " / " + intBrCitata);

                setTimeout(osveziEkran, 10);
            }

            function osveziEkran() {
                $('#avatar').css("transition", "2s");
                $('#avatar').css("transform", "translate(8%, -55%) scale(1)");
                $('#autor').css("transition", "5s");
                $('#autor').css("opacity", "1"); 

                rnd = 1 + Math.floor(Math.random() * 9); // Random Avatat 1-9
                $('#avatar').attr("src", "avatari/s0" + rnd + ".jpg");

                typeWriter();
            }

            function typeWriter() {
                // Formiranje vidljivog i sakrivenog dela citata
                if (ch = $('#citatB').text()[0]) {
                    $('#citatB').text(function(i, v){
                        return v.replace(ch, '');
                    });

                    $('#citatA').text(function(i, v){
                        return v + ch;
                    });
                    
                    setTimeout(typeWriter, 24 * Math.floor(Math.random() * 6));
                }                
            }            

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