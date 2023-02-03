<?php
ob_start();
session_start();
$pageTitle='Categories';
if(isset($_SESSION['Username'])){
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if($do == 'Manage') {
        $stmt2 = $con->prepare("SELECT * FROM categories");

        $stmt2->execute();

        $cats = $stmt2->fetchAll();?>



<h1 class="text-center">manage categories</h1>
<div class="container .categories">
    <div class="panel panel-default">
        <div class="panel-heading">
            manage categories
        </div>
        <div class="panel-body">
            <?php
            foreach($cats as $cat){
                echo "<div class='cat>'";
                    echo "<h3>" . $cat['Name'] . "<h3>";
                    echo "<p>"; 
                    if($cat['Description'] == '')
                    {echo 'thiscategory has no description';}else
                    {echo $cat['Description'];}echo "</p>";
                    if($cat['Visibility'] == 1){echo '<span class="visibility">hidden</span>';}
                    if($cat['Allow_Commenting'] == 1){echo '<span class="commenting">comment disabled</span>';}    
                    if($cat['Allow_Ads'] == 1){echo '<span class="ads">ads disabled</span>';}    
                echo "</div";
                echo "<hr>";
            }
            ?>
        </div>
    </div>
</div>
<?php
    }elseif ($do == 'Add'){
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
                        <input type="text" name="ordering" class="form-control"  placeholder="number to order the categories">
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
                        <input id="com-no" type="radio" name="commenting" value="1" checked />
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
                        <input id="ads-no" type="radio" name="ads" value="1" checked />
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
    }elseif ($do == 'Insert'){
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
    }elseif ($do == 'Edit'){
        
    }elseif ($do == 'Update'){
        
    }elseif ($do == 'Delete'){
        
    }
    include $tpl . 'footer.php';

}else{
    header('Location: index.php');
    exit();
}
ob_end_flush();
?>