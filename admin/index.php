<?php
session_start();
$noNavbar = '';
$pageTitle = 'login';
if (isset($_SESSION['Username'])) {
    header('Location: dashboard.php');
};
include 'init.php';
//check if user coming from http post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedPass = sha1($password);

    //check if user exists in database

    $stmt = $con->prepare("SELECT
                                 UserID, Username, Password 
                           FROM 
                                 users 
                           WHERE 
                                 Username = ? 
                           AND 
                                 Password = ? 
                           AND 
                                 GroupID = 1 
                           LIMIT 1");
                           
    $stmt->execute(array($username, $hashedPass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    //if count > 0 this means the database contains record about this username
    if ($count > 0) {
        $_SESSION['Username'] = $username; // register session name
        $_SESSION['ID'] = $row['UserID']; //register session id
        header('Location: dashboard.php'); // redirect to dashboard page
        exit();
    }
}
?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class="text-center">Admin login</h4>
    <input class="form-control form-control-lg" type="text" name="user" placeholder="Username" autocomplete="off" />
    <input class="form-control form-control-lg" type="password" name="pass" placeholder="password" autocomplete="new-password" />
    <input class="btn btn-lg btn-primary btn-block" type="submit" value="login" />
</form>
<?php
include $tpl . 'footer.php';
?>