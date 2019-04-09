<?php

include 'struct/classes/DB.php';
include 'struct/classes/builder.php';

$db = new DB();
$conn = $db->getProdConn('crm');
$ini_array = parse_ini_file("points.ini",true);
if (isset($_POST["button"])) {

    $myusername = $_POST['username'];
    $mypassword = $_POST['password'];
    $sql = "SELECT users.id, users.first_name, users.last_name, users.is_admin, email_address, user_hash FROM crm.users
            left join email_addr_bean_rel on users.id = bean_id
            left join email_addresses on email_address_id = email_addresses.id
            where primary_address = 1 and user_hash is not null and users.user_name = '".$myusername."'
            group by users.id";
    $result = $conn->query($sql)->fetch_assoc();
    $check = $db->checkPassword($mypassword,$result['user_hash']);
    if ($check) {
        session_start();
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['user_name'] = $result['first_name']. ' '. $result['last_name'];
        $_SESSION['user_is_admin'] = $result['is_admin'];
        $_SESSION['user_email'] = $result['email_address'];
        $_SESSION['from'] = $ini_array['Email']['From'];
        $_SESSION['fromname'] = $ini_array['Email']['FromName'];
        $_SESSION['pinnotiftime'] = $ini_array['DateTime']['NotificaPinOrario'];
        $_SESSION['version'] = $ini_array['Applicazione']['Versione'];
        header("location: menu.php");
    } else {
        $error = "Login e password errati o password scaduta";
    }
}

builder::Header('P.M.S. Points Management System - v. 2.0.01b','homebg.jpg');
?>

</head>
<body>
    <div class="homeBox">
        <div style="text-align: center;"><br><IMG SRC="images\logo_pms.png" width="350"></div>
            <form method="post" action="" style="width: 80%;margin: 0 auto;text-align: center;">
                <div class="form-group">
                <label><br />Nome Utente:</label><input type = "text" name = "username" class="form-control"/>
                </div>
                <div class="form-group">
                <label>Password:</label><input type = "password" name = "password" class="form-control" />
                </div>
                <input type = "submit" name="button" value = " Accedi " class="btn btn-primary" /><BR/><BR>
                <p style="font-size: small;font-weight: bold">Usare gli stessi dati di accesso del CRM</p>
            </form>
        <div style = "font-size:14px; color:#cc0000; margin-top:10px;text-align: center;font-weight: bold"><?php echo $error; ?></div>
    </div>

<?php builder::Scripts();

$db->dropConn($conn);
?>
