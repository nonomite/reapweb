    <?php 

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $configfile = __DIR__. '\config.ini';

    $config = parse_ini_file($configfile, true);

    $dbhost = $config ['database']['host'];
    $dbname = $config ['database']['name'];
    $dbuser = $config ['database']['username'];
    $dbpass = $config ['database']['password'];




    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    if ($conn->connect_error) {
        error_log($conn->connect_error);
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->set_charset("utf8mb4");

    ?>