<?php
if ($isPost) {
    if ($_POST['email'] && $_POST['password']) {
        $getAccount = $db->prepare("SELECT * FROM users WHERE email = ?");
        $getAccount->bind_param("s", $_POST['email']);
        $getAccount->execute();
        $getAccount = $getAccount->get_result();
        $getAccount = $getAccount->fetch_assoc();
        if ($getAccount) {
            if (password_verify($_POST['password'], $getAccount['password'])) {
                $id = $utils->getUUID();
                $time = time();
                
                $createSession = $db->prepare("INSERT INTO sessions (id, user_id, date) VALUES (?, ?, ?)");
                $createSession->bind_param("sss", $id, $getAccount['id'], $time);
                $createSession->execute();
                
                setcookie("session", $id, 0, "/");

                $res['success'] = true;
            } else {
                $res['error'] = "Incorrect password.";
            }
        } else {
            $res['error'] = "Couldn't find that user.";
        }
    } else {
        $res['error'] = "Please enter your login credentials.";
    }

    header("content-type:application/json");
    echo json_encode($res);
    die();
}
?>

<div class="container mt-5">
    <div class="d-flex">
        <div class="m-auto">
            <h5 class="fw-bold text-center">You a regular here?</h5>
            <form class="mb-3 width-500" action="javascript:login($('[name=email]').val(), $('[name=password]').val())">
                <label><i class="fa-regular fa-at"></i> Email</label>
                <input name="email" class="form-control" placeholder="Email" type="email">
                <label><i class="fa-regular fa-key-skeleton mt-2"></i> Password</label>
                <input name="password" class="form-control" placeholder="Password" type="password">
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>