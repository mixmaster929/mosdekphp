<?php
include('includes/database.php');
function getTyresFromSFTP() {

    $con = dbCon();

    // CLEAN tabel
    $sql = "TRUNCATE TABLE shop_tyres_api";
    if (!mysqli_query($con, $sql)) {
        echo "Error emptying table: " . mysqli_error($con);
    }
    // get delay from shop_misc-tabel
    $sql = "SELECT `attribute3` FROM `shop_misc` WHERE `property` = 'shopTyresApiDelay'";
    $result = mysqli_query($con, $sql);
    
    $delay = 3; //standard
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $delay = $row['attribute3'];
    } 
    // get recomended brands shop_misc-tabel
    $sql = "SELECT `attribute1` FROM `shop_misc` WHERE `property` = 'shopTyresApiRecomendedBrands'";
    $result = mysqli_query($con, $sql);
    
    $dbRecBrands = ''; //standard
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $dbRecBrands = $row['attribute1'];
    }      
    
    // get brands shop_misc-tabel
    $sql = "SELECT `attribute1` FROM `shop_misc` WHERE `property` = 'shopTyresApiBrands'";
    $result = mysqli_query($con, $sql);
    
    $allowedProdusent = array(); // Initiera som en tom array
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $brands = array_map('trim', explode(",", $row['attribute1']));
            
            // Lägg till märkena i arrayen utan dubletter
            foreach ($brands as $brand) {
                if (!in_array($brand, $allowedProdusent)) {
                    $allowedProdusent[] = $brand;
                }
            }
        }
    }   
    $allowedCities = ['Oslo', 'Tønsberg'];
    // Fetch stock data from second SFTP link
    $stockData = [];
    if (($handle = fopen("https://sftp.dekkpro.no/web/client/pubshares/LZfT8ixVhrVu7hW3rfkXym?compress=false", "r")) !== FALSE) {
        while (($dataRow = fgetcsv($handle, 1000, ";")) !== FALSE) {
            $city = $dataRow[0];
            $varenr = $dataRow[2];
            $stock = $dataRow[3];
            //norweigian letter...
            if (in_array($city, $allowedCities)|| strpos($city, 'nsberg') !== false) {
                if (!isset($stockData[$varenr])) {
                    $stockData[$varenr] = 0;
                }
                $stockData[$varenr] += (int) $stock;
            }
        }
        fclose($handle);
    }
    
    $data = [];
    $row = 1;
    $rad = 1;
    
    $allowedKategori = ['Sommerdekk', 'M+S Pigg', 'M+S'];
    //$allowedProdusent = array_map('trim', explode(",", $dbBrands));
    $allowedProdusentLowercase = array_map('strtolower', $allowedProdusent);
    $recProdusent = array_map('trim', explode(",", $dbRecBrands));
    $recProdusentLowercase = array_map('strtolower', $recProdusent);
    
    if (($handle = fopen("https://sftp.dekkpro.no/web/client/pubshares/FaXrE8DdnmNzZWTwj2JWWU?compress=false", "r")) !== FALSE) {
        while (($dataRow = fgetcsv($handle, 1000, ";")) !== FALSE) {
            if ($row == 1) { // Header row
                $headers = $dataRow;
                $row++;
                continue;
            }

            $kategori = $dataRow[array_search("Kategori", $headers)];
            $produsent = $dataRow[array_search("Produsent", $headers)];
            $produsentLowercase = strtolower($produsent);

            // Check if the row matches the criteria
            if (in_array($kategori, $allowedKategori) && in_array($produsentLowercase, $allowedProdusentLowercase)) {
                if (in_array($produsentLowercase, $recProdusentLowercase)) {
                  $recommendBrand = 1;
                }else $recommendBrand = 0;
                $data[] = [
                    "ID" => $rad++, //id
                    "Varenr" => $dataRow[array_search("Varenr.", $headers)], //varenr
                    "Produsent" => $produsent, //brand
                    "Kategori" => $kategori, //season
                    "Produkt" => $dataRow[array_search("Produkt", $headers)], //model
                    "Bredde" => $dataRow[array_search("Bredde", $headers)], //width
                    "Profil" => $dataRow[array_search("Profil", $headers)], //profile
                    "RulleMotsatand" => $dataRow[array_search("RulleMotsatand", $headers)], //fuel
                    "Diameter" => $dataRow[array_search("Diameter", $headers)], //inches
                    "Li" => $dataRow[array_search("Li", $headers)], //load
                    "Si" => $dataRow[array_search("Si", $headers)], //speed
                    "Grip" => $dataRow[27], //grip 
                    "DB" => $dataRow[array_search("DB", $headers)], //db
                    "EuClass" => $dataRow[array_search("EuClass", $headers)], //euClass
                    "EuDirective" => $dataRow[array_search("EuDirective", $headers)], //euDirective
                    "Listepris" => $dataRow[array_search("Listepris", $headers)], //price
                    "Bildelenk" => $dataRow[array_search("Bildelenk", $headers)], //image
                    "Ean" => $dataRow[array_search("EANKode", $headers)], //ean
                    "Stock" => isset($stockData[$dataRow[array_search("Varenr.", $headers)]]) ? $stockData[$dataRow[array_search("Varenr.", $headers)]] : 0,
                    "Recommended" => $recommendBrand //$recommended
                ];
            }
            $row++;
        }
        fclose($handle);
    }

    // Prepare Insert
    $stmt = $con->prepare("INSERT INTO shop_tyres_api (id, category, varenr, brand, season, model, width, profile, fuel, inches, `load`, speed, grip, noise, euClass, euDirective, price, image,ean, delay, stock,recommended) VALUES (?, ?,?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?)");

    // Retrieve the mapping of Produsent to category
    $sql = "SELECT `attribute1` AS 'Produsent', `attribute3` AS 'Category' FROM `shop_misc` WHERE `property` = 'shopTyresApiBrandDiscount'";
    $result = mysqli_query($con, $sql);
    
    // Create an associative array to store the mapping
    $produsentToCategoryMapping = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $produsentToCategoryMapping[$row['Produsent']] = $row['Category'];
        }
    }
    
    // Iterate over $data and determine category and season
    foreach ($data as $row) {
        switch ($row["Kategori"]) {
            case 'M+S Pigg':
                $season = 'winterStudded';
                break;
            case 'M+S':
                $season = 'winter';
                break;
            case 'Sommerdekk':
                $season = 'summer';
                break;
            default:
                $season = '';
                break;
        }
    
        $category = $produsentToCategoryMapping[$row["Produsent"]] ?? ''; // Use the mapping, default to empty string if not found
        $roundedBredde = round($row["Bredde"]);
        $roundedProfil = round($row["Profil"]);
        $roundedDiameter = round($row["Diameter"]);
        $roundedListepris = round($row["Listepris"]);
        
        if ($row["Stock"] > 3) {
            $stmt->bind_param("isssssssssssssssisssii",  
                $row["ID"],
                $category,
                $row["Varenr"],
                $row["Produsent"],
                $season,
                $row["Produkt"],
                $roundedBredde,
                $roundedProfil,
                $row["RulleMotsatand"],
                $roundedDiameter,
                $row["Li"],
                $row["Si"],
                $row["Grip"],
                $row["DB"],
                $row["EuClass"],
                $row["EuDirective"],
                $roundedListepris,
                $row["Bildelenk"],
                $row["Ean"],
                $delay,
                $row["Stock"],
                $row["Recommended"]
            );
        
            if (!$stmt->execute()) {
                $r = ['error' => $stmt->error];
                echo json_encode($r);
                return;
            }else {
                $r = ['success get tyres from norgesdekk'];
            }	
        }
    }
    echo json_encode($r);
    return;
    mysqli_stmt_close($stmt);
    mysqli_close($con);
}
getTyresFromSFTP();
?>