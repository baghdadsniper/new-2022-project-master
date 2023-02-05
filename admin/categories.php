<?php
ob_start();
session_start();
$pageTitle = 'Categories';
if (isset($_SESSION['Username'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage') {

        $sort = 'ASC';
        $sort_array = array('ASC', 'DESC');

        if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {

            $sort = $_GET['sort'];
        }

        $stmt2 = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");

        $stmt2->execute();

        $cats = $stmt2->fetchAll(); ?>



         <h1 class="text-center">manage categories</h1>
        <div class="container .categories">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-edit"></i>manage categories
                    <div class="option pull-right">
                    <i class="fa fa-sort"></i>ordering:[
                        <a class="<?php if ($sort == 'ASC') {
                                        echo 'active';
                                    } ?>" href="?sort=ASC">ASC</a> |
                        <a class="<?php if ($sort == 'DESC') {
                                        echo 'active';
                                    } ?>" href="?sort=DESC">DESC</a>]
                        <i class="fa fa-eye"></i>view: [
                                    <span class="active" data-view="full">classic</span>
                                    |
                                    <span data-view="classic">full</span>]
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    foreach ($cats as $cat) {
                        echo "<div class='cat>'";
                        echo "<div class='hidden-buttons'>";
                        echo "<a href='categories.php?do=Edit&catid='" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i>edit</a>";
                        echo "<a href='categories.php?do=Delete&catid='" . $cat['ID'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i>delete</a>";
                        echo "<div>";
                        echo "<h3>" . $cat['Name'] . "<h3>";
                        echo "<div class='full-vies'>";
                        echo "<p>";
                        if ($cat['Description'] == '') {
                            echo 'thiscategory has no description';
                        } else {
                            echo $cat['Description'];
                        }
                        echo "</p>";
                        if ($cat['Visibility'] == 1) {
                            echo '<span class="visibility"><i class="fa fa-eye"></i>hidden</span>';
                        }
                        if ($cat['Allow_Commenting'] == 1) {
                            echo '<span class="commenting"><i class="fa fa-close"></i>comment disabled</span>';
                        }
                        if ($cat['Allow_Ads'] == 1) {
                            echo '<span class="ads"><i class="fa fa-close"></i>ads disabled</span>';
                        }
                        echo "</div";
                        echo "</div";
                        echo "<hr>";
                    }
                    ?>
                </div>
            </div>
            <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> add new category</a>
        </div>
    <?php
    } elseif ($do == 'Add') {
    ?>
        <h1 class="text-center">
            add new category
        </h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-4 ">
                        <input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="name of the category">
                    </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-4 ">
                        <input name="description" type="text" class=" form-control" placeholder="describe the category">
                    </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">ordering</label>
                    <div class="col-sm-10 col-md-4 ">
                        <input type="text" name="ordering" class="form-control" placeholder="number to order the categories">
                    </div>
                </div>

                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">Visible</label>
                    <div class="col-sm-10 col-md-4 ">
                        <div>
                            <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                            <label for="vis-yes">yes</label>
                        </div>
                        <div>
                            <input id="vis-no" type="radio" name="visibility" value="1" checked />
                            <label for="vis-no">no</label>
                        </div>
                    </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">allow commenting</label>
                    <div class="col-sm-10 col-md-4 ">
                        <div>
                            <input id="com-yes" type="radio" name="commenting" value="0" checked />
                            <label for="com-yes">yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="commenting" value="1" />
                            <label for="com-no">no</label>
                        </div>
                    </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">allow ads</label>
                    <div class="col-sm-10 col-md-4 ">
                        <div>
                            <input id="ads-yes" type="radio" name="ads" value="0" checked />
                            <label for="ads-yes">yes</label>
                        </div>
                        <div>
                            <input id="ads-no" type="radio" name="ads" value="1" />
                            <label for="ads-no">no</label>
                        </div>
                    </div>
                </div>
                <div class="form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10 col-md-4 ">
                        <input type="submit" name="submit" value="Add category" class="btn-lg btn-primary ">
                    </div>
                </div>
            </form>
        </div>
        <?php
    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>insert categories</h1>";
            echo "<div class='container'>";

            $name = $_POST['name'];
            $desc = $_POST['description'];
            $order = $_POST['ordering'];
            $visible = $_POST['visibility'];
            $comment = $_POST['commenting'];
            $ads = $_POST['ads'];
            //check if there is no errors proceed the update operation
            //check if category exist in database
            $check = checkItem("Name", "categories", $name);

            if ($check == 1) {
                $theMsg = '<div class="alert alert-danger">sorry this category is exist</div>';
                redirectHome($theMsg, 'back');
            } else {

                //insert into the database
                $stmt = $con->prepare("INSERT INTO
                        categories(Name , Description, Ordering, Visibility, Allow_Commenting, Allow_Ads)
                        VALUES(:zname, :zdesc, :zorder, :zvisible , :zcomment , :zads)");
                $stmt->execute(array(
                    'zname' => $name,
                    'zdesc' => $desc,
                    'zorder' => $order,
                    'zvisible' => $visible,
                    'zcomment' => $comment,
                    'zads' => $ads
                ));
                //echo success message
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record inserted</div>';

                redirectHome($theMsg, 'back', 4);
                echo "</div>";
            }
        } else {
            $theMsg = 'sorry you cant browse this page directly';
            redirectHome($theMsg, 'back', 4);
        }
    } elseif ($do == 'Edit') {
        $userid =  isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

        $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");

        $stmt->execute(array($catid));

        $cat = $stmt->fetch();

        $count = $stmt->rowCount();


        if ($stmt->rowCount() > 0) {
        ?>
            <h1 class="text-center">
                edit category
            </h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="catid" value="<?php echo $catid ?>">
                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-4 ">
                            <input type="text" name="name" class="form-control" value="<?php echo $cat['Name'] ?>" required="required" placeholder="name of the category">
                        </div>
                    </div>
                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-4 ">
                            <input name="description" type="text" class=" form-control" value="<?php echo $cat['Description'] ?>" placeholder="describe the category">
                        </div>
                    </div>
                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">ordering</label>
                        <div class="col-sm-10 col-md-4 ">
                            <input type="text" name="ordering" class="form-control" value="<?php echo $cat['Ordering'] ?>" placeholder="number to order the categories">
                        </div>
                    </div>

                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">Visible</label>
                        <div class="col-sm-10 col-md-4 ">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cat['Visibility'] == 0) {
                                                                                                    echo 'checked';
                                                                                                } ?> />
                                <label for="vis-yes">yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['Visibility'] == 1) {
                                                                                                echo 'checked';
                                                                                            } ?> />
                                <label for="vis-no">no</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">allow commenting</label>
                        <div class="col-sm-10 col-md-4 ">
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" <?php if ($cat['Allow_Comment'] == 0) {
                                                                                                    echo 'checked';
                                                                                                } ?> />
                                <label for="com-yes">yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value="1" <?php if ($cat['Allow_Comment'] == 1) {
                                                                                                echo 'checked';
                                                                                            } ?> />
                                <label for="com-no">no</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">allow ads</label>
                        <div class="col-sm-10 col-md-4 ">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" checked <?php if ($cat['Allow_Ads'] == 0) {
                                                                                                    echo 'checked';
                                                                                                } ?> />
                                <label for="ads-yes">yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1" checked <?php if ($cat['Allow_Ads'] == 1) {
                                                                                                    echo 'checked';
                                                                                                } ?> />
                                <label for="ads-no">no</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10 col-md-4 ">
                            <input type="submit" name="submit" value="save" class="btn-lg btn-primary ">
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
    } elseif ($do == 'Update') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update category</h1>";
            echo "<div class='container'>";
            $id = $_POST['catid'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $order = $_POST['ordering'];
            $visible = $_POST['visibility'];
            $comment = $_POST['commenting'];
            $ads = $_POST['ads'];


            $stmt = $con->prepare("UPDATE categories
                                        SET
                                          Name = ?,
                                          Description = ?,
                                          Ordering = ?,
                                          Visibility = ?,
                                          Allow_Comment = ?,
                                          Allow_Ads = ?,
                                        WHERE
                                          ID = ?");
            $stmt->execute(array($name, $desc, $order, $visible, $comment, $ads, $id));

            //echo success message
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record updated</div>';
            redirectHome($theMsg, 'back', 4);
        } else {
            $theMsg = '<div class="alert alert-danger">sorry you cant browse this page directly</div>';
            redirectHome($theMsg);
        }
        echo "</div>";
    } elseif ($do == 'Delete') {

        echo "<h1 class='text-center'>delete category</h1>";
        echo "<div class='container'>";
        //delete category page
        $id =  isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        $check = checkItem('ID', 'categories', $catid);
        if ($check > 0) {
            $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
            $stmt->bindParam(":zid", $catid);
            $stmt->execute();
            //echo success message
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record deleted</div>';
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
ob_end_flush();
?>