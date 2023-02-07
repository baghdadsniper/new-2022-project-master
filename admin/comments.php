<?php
/*


manage comments page


*/

session_start();
$pageTitle = 'comments';
if (isset($_SESSION['comment']) && $_GET['page'] == 'Pending') {

    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
    //start manage page
    if ($do == 'Manage') {
        //select all comments except admin
        $stmt = $con->prepare("SELECT 
                                  comments.*, items.Name AS Item_Name, users.Username AS Member
                                 FROM
                                  comments
                                 INNER JOIN
                                   items
                                   ON
                                   items.Item_ID = comments.item_id
                                   INNER JOIN
                                   users
                                   ON
                                   users.UserID = comments.user_id
                                   ORDER BY
                                   c_id DESC
                                  ");
        //excute statement
        $stmt->execute();

        //assign to vars
        $comments = $stmt->fetchAll();



?>
        <h1 class="text-center">
            manage comments
        </h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>comment</td>
                        <td>Item name</td>
                        <td>user name</td>
                        <td>added date</td>
                        <td>control</td>
                    </tr>
                    <?php
                    foreach ($comments as $comment) {
                        echo "<tr>";
                        echo "<td>" . $comment['c_id'] . "</td>";
                        echo "<td>" . $comment['comment'] . "</td>";
                        echo "<td>" . $comment['Item_Name'] . "</td>";
                        echo "<td>" . $comment['Member'] . "</td>";
                        echo "<td>" . $comment['comment_date'] . "</td>";
                        echo "<td>
                           <a href='comments.php?do=Edit&comid=" . $comment['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i>edit</a>
                           <a href='comments.php?do=Delete&comid=" . $comment['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>delete</a>";

                        if ($comment['status'] == 0) {
                            echo  "<a href='comments.php?do=Approve&comid=" . $comment['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>approve</a>";
                        }

                        echo "/td>";
                        echo "</td>";
                    }
                    ?>
                </table>
            </div>
        </div>


        <?php } elseif ($do == 'Edit') { //edit page
        $comid =  isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

        $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");
        $stmt->execute(array($comid));
        $comment = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($stmt->rowCount() > 0) {


        ?>

            <h1 class="text-center">
                Edit comment
            </h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="comid" value="<?php echo $comid ?>">
                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label" required>comment</label>
                        <div class="col-sm-10 col-md-4 ">
                            <textarea class="form-control" name="comment" id="" cols="30" rows="10"><?php echo $comment['comment']; ?></textarea>
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
            echo "<h1 class='text-center'>Update comment</h1>";
            echo "<div class='container'>";
            $comid = $_POST['comid'];
            $comment = $_POST['comment'];
            //update the database

            $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
            $stmt->execute(array($comid, $comment));

            //echo success message
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record updated</div>';
            redirectHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">sorry you cant browse this page directly</div>';
            redirectHome($theMsg);
        }
        echo "</div>";
    } elseif ($do == 'Delete') {
        echo "<h1 class='text-center'>delete comment</h1>";
        echo "<div class='container'>";
        //delete comment page
        $comid =  isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        $check = checkItem('comid', 'comments', $comid);
        if ($check > 0) {
            $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zid");
            $stmt->bindParam(":zid", $comid);
            $stmt->execute();
            //echo success message
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record deleted</div>';
            redirectHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">this id is not exist</div>';

            redirectHome($theMsg);
        }
        echo "</div>";
    } elseif ($do == 'Approve') {
        echo "<h1 class='text-center'>Activate comment</h1>";
        echo "<div class='container'>";
        //delete comment page
        $comid =  isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        $check = checkItem('comid', 'comments', $comid);
        if ($check > 0) {
            $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
            $stmt->execute(array($comid));
            //echo success message
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record updated</div>';
            redirectHome($theMsg, 'back');
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
