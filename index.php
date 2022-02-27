<?php
require_once("inc/global.inc.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://kit-pro.fontawesome.com/releases/v6.0.0-beta1/css/pro.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.typekit.net/xkz8zfl.css">
    <link rel="stylesheet" href="/assets/css/styles.css?<?php echo filemtime("{$_DIR}/assets/css/styles.css") ?>">

    <link rel="shortcut icon" href="/favicon.ico?<?php echo filemtime("{$_DIR}/favicon.ico") ?>" type="image/x-icon">
    <link rel="icon" href="/favicon.ico?<?php echo filemtime("{$_DIR}/favicon.ico") ?>" type="image/x-icon">

    <title>Codelingo</title>
    <meta name="description" content="Codelingo is an interactive new way to learn how to code in an easy and fun way.">
    <meta name="keywords" content="codelingo,code,coding,interactive,help,learning,education">
</head>

<body>
    <header class="head pt-2 pb-2">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 d-flex">
                    <div class="my-auto">
                        <a href="/">
                            <div class="lingo logo-brand text-dark text-decoration-none">codelingo</div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 d-flex">
                    <div class="my-auto ms-auto fw-bold try display">
                        <?php
                        if ($user) {
                            echo "<a class=\"text-dark text-decoration-none\" href=\"/app\">{$user->data['name']}</a>";
                        } else {
                        ?>
                            <a href="/account/login" class="btn btn-primary">Login</a>
                            <a href="/account/register" class="btn btn-primary">Register</a>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main></main>

    <footer>
        <div class="pt-5 pb-5">
            <div class="container">
                <div class="lingo">codelingo</div>
                <p>Coded by <a href="https://itspolar.dev" target="_blank">Polar</a> and <a href="https://chezzer.dev" target="_blank">Chezzer</a> for the 2022 winter <a href="https://hacks.buildergroop.com/" target="_blank">Builderhacks</a>.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.rawgit.com/visionmedia/page.js/master/page.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/assets/js/router.js?<?php echo filemtime("{$_DIR}/assets/js/router.js") ?>"></script>
    <script src="/assets/js/main.js?<?php echo filemtime("{$_DIR}/assets/js/main.js") ?>"></script>
</body>

</html>