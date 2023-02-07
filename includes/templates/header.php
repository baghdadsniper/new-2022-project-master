<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    
    
<link rel="stylesheet" href="<?php echo $css?>front.css">
<link rel="stylesheet" href="<?php echo $css?>font-awesome.min.css">
<link rel="stylesheet" href="<?php echo $css?>jquery-ui.css">
<link rel="stylesheet" href="<?php echo $css?>jquery.selectBoxIt.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <title><?php getTitle()?></title>
</head>
<body>
  <div class="upper-bar">
    <div class="container">
      <?php 
      if(isset($_SESSION['user'])) {
        echo 'welcome ' . $_SESSION['user'] . ' ';
        echo '<a href="profile.php">my profile</a>';
        echo '- <a href="logout.php">logout</a>';
        echo '- <a href="newad.php.php">new ad</a>';
        if($userStatus == 1) {
         // echo 'your membership needs an activate by the admin';
        }
      }else{
      ?>
      <a href="login.pgp">
        <span class="pull-right">
          login/signup
        </span>
      </a>
      <?php }?>
    </div>
  </div>
<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">Homepage</a>
    </div>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav navbar-right">
<?php  
foreach (getCat() as $cat) {
  echo '<li>
  <a href="categories.php?pageid=' . $cat['ID'] . '&pagename=' . str_replace(' ', '-', $cat['Name']) . '">
  ' . $cat['Name'] . '
  </a><
  /li>';
}
?>
      </ul>
    </div>
  </div>
</nav>
 