<!DOCTYPE html>
<html lang="cs-cz">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="styl.css" />
        <title>Evidence pojištěnců</title>
    </head>

    <body>
        <header>
            <h1>Evidence pojištěnců</h1>
        </header>
        
       
        
        
        
        <form method="post">
            <fieldset>
                <legend>Registrace nového pojištěnce:</legend>
                <label for="jmeno">Jméno:</label>
                <input type="text" name="jmeno" /><br />
                <label for="prijmeni">Příjmení:</label>
                <input type="text" name="prijmeni" /><br />
                <label for="vek">Věk:</label>
                <input type="text" name="vek" /><br />  
                <label for="telefonni_cislo">Telefonní číslo:</label>
                <input type="text" name="telefonni_cislo" /><br />   
                <input class="tlacitko" type="submit" value="Registrovat" /> 
            </fieldset> 
        </form>
       
        <br />
 <div class="hlaska">       
        <?php
        
// Načtení wrapperu
require_once('Db.php');
Db::connect('localhost', 'evidence_pojistencu', 'root', '');
if ($_POST)
{
Db::query('
    INSERT INTO pojistenci (jmeno, prijmeni, vek, telefonni_cislo)
    VALUES (?,?,?,?)
', $_POST['jmeno'], $_POST['prijmeni'], $_POST['vek'], $_POST['telefonni_cislo']);
echo('<p>Byl jste úspěšně zaregistrován</p>'."<img src=\"Obrazky/smileys.gif\"/>");
}

    ?>
 </div>
        
        
<h2>Seznam pojištěnců</h2>
        <table
          <thead class="hlavicka">
            <tr>
                <th>Jméno</th>
                <th>Příjmení</th>
                <th>Věk</th>
                <th>Telefonní číslo</th>
            </tr>
          </thead>
        </table>

        <?php


$pojistenci = Db::queryAll('
  SELECT *
  FROM pojistenci
');
echo('<table border="1">');
foreach ($pojistenci as $po)
{
    echo('<tr><td>' . htmlspecialchars($po['jmeno']));
    echo('</td><td>' . htmlspecialchars($po['prijmeni']));   
    echo('</td><td>' . htmlspecialchars($po['vek']));
    echo('</td><td>' . htmlspecialchars($po['telefonni_cislo']));
    echo('</td></tr>');
}
echo('</table>');

        ?>
        
 <footer>
Vytvořil &copy;Martin Čermák 2022 pro <a href="https://itnetwork.cz">itnetwork.cz</a>
</footer>


    </body>
</html>
