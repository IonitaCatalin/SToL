<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sezatoare</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../public/css/sezatoare.css" />
  </head>
  <body>
    <nav>        
        <h1>La Șezătoare</h1>
    </nav>    
    <main>
        <?php
            foreach($msg as $m){
                if($m["utilizator"] == $_SESSION["utilizator"])
                    $clasa = "mesajul_meu";
                else
                    $clasa = "mesaj";

                echo '<div class="' . $clasa .'">';
                    echo $m["utilizator"] . ': ' . $m["mesaj"];
                if($clasa == "mesajul_meu"){
                    echo '<form id="stergeMesaj" method="POST" action="">';
                    echo '<input type="hidden" name="actiune" value="stergeMesaj">';
                    echo '<input type="hidden" name="id" value="'.$m["id"].'">';
                    echo '<button class="buton_tip_emoji" type="submit">❌</button></form>';

                    echo '<form id="modificaMesaj" method="POST" action="">';
                    echo '<input type="hidden" name="actiune" value="triggerModificaMesaj">';
                    echo '<input type="hidden" name="id" value="'.$m["id"].'">';
                    echo '<button class="buton_tip_emoji" type="submit">✏️</button></form>';
                }
                echo '</div>';
            }

            if($edit == NULL) {
                echo '<form class="formularTrimitereMesaj" method="POST" action="">';
                echo '<input type="hidden" name="actiune" value="adaugaMesaj">';
                echo '<input type="text" name="mesaj" />';
                echo '<input type="submit" value="Trimite" />';
                echo '</form>';
            } else {
                foreach($msg as $m) {
                    if($m["id"] == $edit) {
                        echo '<form class="formularTrimitereMesaj" method="POST" action="">';
                        echo '<input type="hidden" name="actiune" value="salveazaMesaj">';
                        echo '<input type="hidden" name="id" value="'.$m["id"].'">';
                        echo '<input type="text" name="mesaj" value="'.$m["mesaj"].'" />';
                        echo '<input type="submit" value="Salveaza" />';
                        echo '</form>';                        
                    }
                }
            }
        ?>
    </main>
    </body>
</html>