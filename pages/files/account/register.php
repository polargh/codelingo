<?php
if ($isPost) {
    if ($_POST['email'] && $_POST['password'] && $_POST['name']) {
        $getAccount = $db->prepare("SELECT * FROM users WHERE email = ?");
        $getAccount->bind_param("s", $_POST['email']);
        $getAccount->execute();
        $getAccount = $getAccount->get_result();
        if (!$getAccount->num_rows) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            $insertUser = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $insertUser->bind_param("sss", $_POST['name'], $_POST['email'], $password);
            $insertUser->execute();

            $getAccount = $db->prepare("SELECT * FROM users WHERE email = ?");
            $getAccount->bind_param("s", $_POST['email']);
            $getAccount->execute();
            $getAccount = $getAccount->get_result();
            $getAccount = $getAccount->fetch_assoc();

            $id = $utils->getUUID();
            $time = time();
            
            $createSession = $db->prepare("INSERT INTO sessions (id, user_id, date) VALUES (?, ?, ?)");
            $createSession->bind_param("sss", $id, $getAccount['id'], $time);
            $createSession->execute();
            
            setcookie("session", $id, 0, "/");

            $res['success'] = true;
        } else {
            $res['error'] = "There's already a user with that email address.";
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
            <h5 class="fw-bold text-center">Let's get learning.</h5>
            <form class="mb-3 width-500" action="javascript:register($('[name=name]').val(), $('[name=email]').val(), $('[name=password]').val())">
            <label><i class="fa-regular fa-user"></i> Name</label>
                <input name="name" class="form-control" placeholder="Name" type="text">
                <label><i class="fa-regular fa-at mt-2"></i> Email</label>
                <input name="email" class="form-control" placeholder="Email" type="email">
                <label><i class="fa-regular fa-key-skeleton mt-2"></i> Password</label>
                <input name="password" class="form-control" placeholder="Password" type="password">
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>