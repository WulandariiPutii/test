<!DOCTYPE html>
<html>

<head>
    <title>
        Welcome to area nasabah
    </title>
</head>
<style>
th, td {
    height: 50px;
    width: 150px;
}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
</style>

<body>

    <h1>
        Daftar menjadi nasabah
    </h1>

    <?php
        $nama = $nik = $no_hp = $no_rekening = $saldo = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nama = input_data($_POST["nama"]);
            $nik = input_data($_POST["nik"]);
            $no_hp = input_data($_POST["no_hp"]);
            $no_rekening = input_data($_POST["no_rekening"]);
            $saldo = input_data($_POST["saldo"]);
        }

        function input_data($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = ($data);
            return $data;
        }

        function post_data($url, $nama, $nik, $no_hp, $no_rekening, $saldo){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"{\n\t\"nama\":\"$nama\",
            \n\t\"nik\":\"$nik\",
            \n\t\"no_hp\":\"$no_hp\",
            \n\t\"no_rekening\":\"$no_rekening\",
            \n\t\"saldo\":\"$saldo\"}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        }

        function get_data($url){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        }

    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

        <pre>NAMA           : <input type="text" required="required" name="nama"
                value=""><br></pre>
        <pre>NIK            : <input type="text" required="required" name="nik"
                value=""><br></pre>
        <pre>NO HP          : <input type="text" required="required" name="no_hp"
                value=""><br></pre>
        <pre>NO REKENING    : <input type="text" required="required" name="no_rekening"
                value=""><br></pre>
        <pre>SALDO          : <input type="text" required="required" name="saldo"
                value=""><br></pre>

        <input type="submit" name="button"
                value="Submit"/><br>
    </form>
    <?php
        if(isset($_POST['button'])) {
            $data = post_data("http://192.168.178.63:5010/post_data", $nama, $nik, $no_hp, $no_rekening, $saldo);
            echo "<br>Data Berhasil Dikirim<br>";
        }
    ?>

    <h1>
        Data Nasabah
    </h1>
    <?php
        $dataNasabah = get_data("http://192.168.178.63:5010/get_data");
        $obj = json_decode($dataNasabah, true);
        echo ' <table>
                    <tr>
                        <th>NAMA</th>
                        <th>NIK</th>
                        <th>NO HP</th>
                        <th>NO REKENING</th>
                        <th>SALDO</th>
                    </tr>
                </table>';
        foreach($obj as $item) {
            $nama = $item["nama"];
            $nik2 = $item["nik"];
            $no_hp2 = $item["no_hp"];
            $no_rekening2 = $item["no_rekening"];
            $saldo = $item["saldo"];
            echo ' <table>
                        <tr>
                        <td>'.$nama.'</td>
                        <td>'.$nik2.'</td>
                        <td>'.$no_hp2.'</td>
                        <td>'.$no_rekening2.'</td>
                        <td>'.$saldo.'</td>
                    </tr>
                </table>';
        }
    ?>
</body>
</html>