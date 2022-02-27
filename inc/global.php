<?php
require_once("{$_DIR}/inc/config.inc.php");
require_once("{$_DIR}/inc/user.inc.php");
require_once("{$_DIR}/inc/utils.inc.php");

$db = mysqli_connect($config['sql']['host'], $config['sql']['username'], $config['sql']['password'], $config['sql']['database']);
$utils = new Utils();

if (!$db) {
    ?>
    <div>
        Sorry, the application is down at the moment, please check back later.
        <hr>
        <?php echo mysqli_connect_error()?>
    </div>
    <?php
    die();
}

$isPost = ($_SERVER['REQUEST_METHOD'] === 'POST');

$session = null;
$user = null;
if (isset($_COOKIE['session'])) {
    $session = $db->prepare("SELECT * FROM sessions WHERE id = ?");
    $session->bind_param("s", $_COOKIE['session']);
    $session->execute();
    $session = $session->get_result();
    if ($session->num_rows) {
        $session = $session->fetch_assoc();

        $time = time();
        $updateSession = $db->prepare("UPDATE sessions SET date = ? WHERE id = ?");
        $updateSession->bind_param("ss", $time, $session['id']);
        $updateSession->execute();

        $user = $db->prepare("SELECT * FROM users WHERE id = ?");
        $user->bind_param("s", $session['user_id']);
        $user->execute();
        $user = $user->get_result();
        $user = $user->fetch_assoc();

        $user = new User($user);
    } else {
        $session = null;
    }
}

?>