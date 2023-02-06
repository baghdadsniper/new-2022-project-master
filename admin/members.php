<?php
/*


manage members page


*/

session_start();
$pageTitle = 'Members';
if (isset($_SESSION['Username']) && $_GET['page'] == 'Pending') {

    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
    //start manage page
    if ($do == 'Manage') {
        $query = '';
        if(isset($_GET['page'])){
            $query = 'AND RegStatus = 0';
        }
        //select all users except admin
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query");
        //excute statement
        $stmt->execute();

        //assign to vars
        $rows = $stmt->fetchAll();



?>
        <h1 class="text-center">
            manage members
        </h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>username</td>
                        <td>email</td>
                        <td>full name</td>
                        <td>registered date</td>
                        <td>control</td>
                    </tr>
                    <?php
                    foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . $row['UserID'] . "</td>";
                        echo "<td>" . $row['Username'] . "</td>";
                        echo "<td>" . $row['Email'] . "</td>";
                        echo "<td>" . $row['FullName'] . "</td>";
                        echo "<td>" . $row['Date'] . "</td>";
                        echo "<td>
                           <a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>edit</a>
                           <a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>delete</a>";
                          
                          if ($row['RegStatus'] == 0){
                          echo  "<a href='members.php?do=Activate&userid=" . $row['UserID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>activate</a>";
                          }
                          
                           echo "/td>";
                        echo "</td>";
                    }
                    ?>
                </table>
            </div>
            <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> new member</a>
        </div>


    <?php } elseif ($do == 'Add') { ?>
        <h1 class="text-center">
            add new Member
        </h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">username</label>
                    <div class="col-sm-10 col-md-4 ">
                        <input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="username to login">
                    </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-4 ">
                        <input name="password" type="password" class="password form-control" autocomplete="new-password" required="required" placeholder="password must be hard & complex">
                        <i class="show-pass fa fa-eye fa-2x"></i>
                    </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-4 ">
                        <input type="email" name="email" class="form-control" required="required" placeholder="email must be valid">
                    </div>
                </div>

                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">Full name</label>
                    <div class="col-sm-10 col-md-4 ">
                        <input type="text" name="full" class="form-control" required="required" placeholder="full name appear in your profile page">
                    </div>
                </div>
                <div class="form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10 col-md-4 ">
                        <input type="submit" name="submit" value="Add member" class="btn-lg btn-primary ">
                    </div>
                </div>
            </form>
        </div>
        <?php

    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>insert Member</h1>";
            echo "<div class='container'>";
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $email = $_POST['email'];
            $name = $_POST['full'];

            $hashPass = sha1($_POST['password']);
            // validate the form
            $formErrors = array();
            if (strlen($user) < 4) {
                $formErrors[] = 'username cant be less than <strong>4 characters</strong>';
            }
            if (strlen($user) > 20) {
                $formErrors[] = 'username cant be more than <strong>20 characters</strong>';
            }
            if (empty($user)) {
                $formErrors[] = 'username cant be <strong>empty</strong>';
            }
            if (empty($pass)) {
                $formErrors[] = 'password cant be <strong>empty</strong>';
            }
            if (empty($name)) {
                $formErrors[] = 'fullname cant be <strong>empty</strong>';
            }
            if (empty($email)) {
                $formErrors[] = 'email cant be <strong>empty</strong>';
            }

            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            //check if there is no errors proceed the update operation
            if (empty($formErrors)) {
                //check if user exist in database
                $check = checkItem("Username", "users", $user);

                if ($check == 1) {
                    $theMsg = '<div class="alert alert-danger">sorry this user is exist</div>';
                    redirectHome($theMsg, 'back');
                } else {

                    //insert into the database
                    $stmt = $con->prepare("INSERT INTO
                users(Username, Password, Email, FullName, RegStatus, Date)
                VALUES(:zuser, :zpass, :zmail, :zname, 1, now())                ");
                    $stmt->execute(array(
                        'zuser' => $user,
                        'zpass' => $hashPass,
                        'zmail' => $email,
                        'zname' => $name
                    ));
                    //echo success message
                    echo "<div class='container'>";
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record inserted</div>';

                    redirectHome($theMsg);
                    echo "</div>";
                }
            }
        } else {
            $theMsg = 'sorry you cant browse this page directly';
            redirectHome($theMsg, 'back', 4);
        }
    } elseif ($do == 'Edit') { //edit page
        $userid =  isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($stmt->rowCount() > 0) {


        ?>

            <h1 class="text-center">
                Edit Member
            </h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid ?>">
                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">username</label>
                        <div class="col-sm-10 col-md-4 ">
                            <input type="text" name="username" value="<?php echo $row['Username'] ?>" class="form-control" autocomplete="off" required="required">
                        </div>
                    </div>
                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-4 ">
                            <input name="oldpassword" type="hidden" value="<?php echo $row['Password'] ?>">
                            <input name="newpassword" type="password" class="form-control" autocomplete="new-password" placeholder="leave blank if you dont want to change">
                        </div>
                    </div>
                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-4 ">
                            <input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required">
                        </div>
                    </div>

                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">Full name</label>
                        <div class="col-sm-10 col-md-4 ">
                            <input type="text" name="full" value="<?php echo $row['FullName'] ?>" class="form-control" required="required">
                        </div>
                    </div>
                    <div class="form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10 col-md-4 ">
                            <input type="submit" name="submit" value="Save" class="btn-lg btn-primary ">
                        </div>
                    </div>
                </form>
            </div>

<?php
        } else {
            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">there is no such id</div>';
            redirectHome($theMsg);
        }
        echo "</div>";
    } else if ($do == 'Update') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update Member</h1>";
            echo "<div class='container'>";
            $id = $_POST['userid'];
            $user = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['full'];
            //password trick
            //condition ? true : false;
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
            // validate the form
            $formErrors = array();
            if (strlen($user) < 4) {
                $formErrors[] = 'username cant be less than <strong>4 characters</strong>';
            }
            if (strlen($user) > 20) {
                $formErrors[] = 'username cant be more than <strong>20 characters</strong>';
            }
            if (empty($user)) {
                $formErrors[] = 'username cant be <strong>empty</strong>';
            }
            if (empty($name)) {
                $formErrors[] = 'fullname cant be <strong>empty</strong>';
            }
            if (empty($email)) {
                $formErrors[] = 'email cant be <strong>empty</strong>';
            }

            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            //check if there is no errors proceed the update operation
            if (empty($formErrors)) {

                //update the database

                $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?,Password = ? WHERE UserID = ?");
                $stmt->execute(array($user, $email, $name, $pass, $id));

                //echo success message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record updated</div>';
                redirectHome($theMsg, 'back', 4);
            }
        } else {
            $theMsg = '<div class="alert alert-danger">sorry you cant browse this page directly</div>';
            redirectHome($theMsg);
        }
        echo "</div>";
    } elseif ($do == 'Delete') {
        echo "<h1 class='text-center'>delete Member</h1>";
        echo "<div class='container'>";
        //delete member page
        $userid =  isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $check = checkItem('userid', 'users', $userid);
        if ($check > 0) {
            $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
            $stmt->bindParam(":zuser", $userid);
            $stmt->execute();
            //echo success message
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record deleted</div>';
            redirectHome($theMsg);
        } else {
            $theMsg = '<div class="alert alert-danger">this id is not exist</div>';

            redirectHome($theMsg);
        }
        echo "</div>";
    }elseif ($do == 'Activate') {
        echo "<h1 class='text-center'>Activate Member</h1>";
        echo "<div class='container'>";
        //delete member page
        $userid =  isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $check = checkItem('userid', 'users', $userid);
        if ($check > 0) {
            $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
            $stmt->execute(array($userid));
            //echo success message
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record updated</div>';
            redirectHome($theMsg);
        } else {
            $theMsg = '<div class="alert alert-danger">this id is not exist</div>';

            redirectHome($theMsg);
        }
        echo "</div>";
    }
    include $tpl . 'footer.php';
} else {
    header('Location: index.php');

    exit();
}
