<?php
	ob_start();
	session_start();
	$pageTitle = 'create new item';
	include 'init.php';
	if (isset($_SESSION['user'])) {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formErrors = array();

            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $cat = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);


        
            if (strlen($name) < 4) {
                $formErrors[] = 'name cant be <strong>less than 4 characters</strong>';
            }
            if (strlen($desc) < 10) {
                $formErrors[] = 'desc cant be <strong>less than 10 characters</strong>';
            }
            if (empty($price)) {
                $formErrors[] = 'price cant be <strong>empty</strong>';
            }
            if (strlen($country) < 2) {
                $formErrors[] = 'country cant be <strong>less than 2 characters</strong>';
            }
            if (empty($status)) {
                $formErrors[] = 'status cant be empty';
            }if (empty($cat)) {
                $formErrors[] = 'category cant be empty';
            }
        }

?>
<h1 class="text-center"><?echo $pageTitle?></h1>
<div class="create-ad block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading"><?echo $pageTitle?></div>
			<div class="panel-body">
				<div class="row">
                    <div class="col-md-9">
                    <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                <div class="form-group-lg">
                    <label for="" class="col-sm-3 control-label ">Name</label>
                    <div class="col-sm-10 col-md-10 ">
                        <input type="text" name="name" class="form-control live" required="required" placeholder="name of the item" data-class=".live-name">
                    </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-3 control-label ">Description</label>
                    <div class="col-sm-10 col-md-10 ">
                        <input type="text" name="description" class="form-controllive" placeholder="description of the item" data-class=".live-desc">
                    </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-3 control-label  ">price</label>
                    <div class="col-sm-10 col-md-10 ">
                        <input type="text" name="price" class="form-control live" required="required" placeholder="price of the item" data-class=".live-price">
                    </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-3 control-label">country</label>
                    <div class="col-sm-10 col-md-10 ">
                        <input type="text" name="country" class="form-control" required="required" placeholder="country of made">
                    </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-3 control-label">status</label>
                    <div class="col-sm-10 col-md-10 ">
                        <select name="status" id="">
                            <option value="0">...</option>
                            <option value="1">new</option>
                            <option value="2">like new</option>
                            <option value="3">used</option>
                            <option value="4">very old</option>
                        </select>
                </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-3 control-label">category</label>
                    <div class="col-sm-10 col-md-10 ">
                        <select name="category" id="">
                            <option value="0">...</option>
                            <?php 
                            $stmt2 = $con->prepare("SELECT * FROM categories");
                            $stmt2->execute();
                            $cats = $stmt2->fetchAll();
                            foreach($cats as $cat){
                               echo "<option value='" . $cat['ID'] ."'>" . $cat['Name'] ."</option>";
                            }
                            ?>
                        </select>
                </div>
                </div>
                <div class="form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10 col-md-10 ">
                        <input type="submit" name="submit" value="Add item" class="btn-sm btn-primary ">
                    </div>
                </div>
            </form>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail item-box live-preview" >
                            <span class="price-tag">$ <span class="live-price">
                              0  
                            </span></span>
                            <img src="" alt="" class="img-responsive">
                            <div class="caption">
                                <h3 class="live-title">title</h3>
                               <p class="live-desc">description</p> 
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                if(! empty($formErrors)) {
                    foreach ($formErrors as $error){
                        echo '<div class="alert alert-danger">' . $error . '</div>';
                    }
                }
                ?>
			</div>
		</div>
	</div>
</div>
<?php
	} else {
		header('Location: login.php');
		exit();
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>