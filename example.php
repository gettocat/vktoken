<?php
session_start();
include 'vkapi.class.php';
include 'vktoken.class.php';

// VK_APP_ID should be defined or replaces with id of application on vk.com/apps
// VK_APP_SECRET should be defined or replaces with secret of app.
$token = vktoken::create(VK_APP_ID, VK_APP_SECRET);
//

$settings = $_SESSION; //params from db for example.
$user = null;

if ($arr = $_POST['auth']) {
    if ($arr['link']) {
        $res = $token->getToken($arr['link']);
        if ($res['token']) {
            //save $res['token'] into $settings
            $_SESSION['token'] = $settings['token'] = $res['token'];
            //save into db.
            //$res['user'] - it is field of token user
            $user = $res['user'];
        }
    }
}


if ($settings['token']) {
    if (!$user)
        $user = vktoken::getUserInfo($settings['token']);
    $img = "<div class='row'><div class='col-md-2'><img src='" . $user['photo_100'] . "' /></div><div class='col-md-10'>" . $user['first_name'] . " " . $user['last_name'] . "<br />{$settings['token']}</div></div>";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>VkToken</title>

        <!-- Bootstrap -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <h1>User authed by:</h1>
        <?php echo $img ?>

        <div class="row">
            <div class="col-md-12 text-center">
                <a target="_blank" class="btn btn-primary" href="<?php echo $token->link() ?>">Get Code</a>
            </div>
        </div>

        <form action="" method="POST" class="form-horisontal">

            <div class="form-group">
                <div class="col-md-9">
                    <input type="text" class="form-control" name="auth[link]" placeholder="http://api.vk.com/blank.html#code=5a38b024efa465fb51 ">
                </div>    

                <div class="col-md-3">
                    <input type="submit" class="btn btn-success" value="Отправить ссылку">
                </div>    
            </div>    

        </form>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    </body>
</html>