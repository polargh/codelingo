<?php
if ($isPost) {
    if ($user) {
        if ($_POST['action'] == "select") {
            if ($_POST['id']) {
                $selectLanguage = $db->prepare("INSERT INTO users_languages (lang_id, user_id) VALUES (?, ?)");
                $selectLanguage->bind_param("ss", $_POST['id'], $user->data['id']);
                $selectLanguage->execute();
    
                $res['success'] = true;
            } else {
                $res['error'] = "Please select an language.";
            }
        } else if ($_POST['action'] == "add-language") {
            if ($_POST['id']) {
                $check = $db->prepare("SELECT * FROM users_languages WHERE lang_id = ? AND user_id = ?");
                $check->bind_param("ss", $_POST['id'], $user->data['id']);
                $check->execute();
                $check = $check->get_result();
                if (!$check->num_rows) {
                    $insert = $db->prepare("INSERT INTO users_languages (lang_id, user_id) VALUES (?, ?)");
                    $insert->bind_param("ss", $_POST['id'], $user->data['id']);
                    $insert->execute();

                    $res['success'] = true;
                } else {
                    $res['error'] = "You already own this language.";
                }
            } else {
                $res['error'] = "Internal Error";
            }
        } else {
            $res['error'] = "Internal error, no action.";
        }
    } else {
        $res['error'] = "Not logged in.";
    }
    header("content-type:application/json");
    echo json_encode($res);
    die();
}
?>

<?php

if(!$user) {
    echo '<div class="container mt-5">ya kinda gotta be logged in to use this.. <a href="/">go back</a> or <a href="/account/login">login</a>!</div>';
    die();
}

$languages = $db->prepare("SELECT * FROM users_languages WHERE user_id = ?");
$languages->bind_param("s", $user->data['id']);
$languages->execute();
$languages = $languages->get_result();
if (!$languages->num_rows) {
?>
<div class="container mt-5">
    <div class="progress" style="height: 16px; border-radius: 20px !important;">
        <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="1 mt-4">
        <div class="text-align-center">
            <h3 class="fw-bold">Welcome to codelingo!</h3>
            <p>Choose your first language</p>
        </div>
        <div class="row text-align-center mt-4 languages">
            <?php
                $languageList = $db->query("SELECT * FROM languages");
                while ($language = $languageList->fetch_assoc()) {
                    ?>
                        <div class="col-sm-3" onclick="selectLanguage(<?php echo $language['id']?>)" data-lang-id="<?php echo $language['id']?>">
                            <?php echo $language['icon']?>
                            <p class="text-secondary pt-2"><?php echo $language['name']?></p>
                        </div>
                    <?php
                }
            ?>
            <div class="d-flex mt-5">
                <div class="mx-auto">
                    <button class="btn btn-secondary mb-5" onclick="continueLanguage()">Continue</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php die();}?>

<div class="container mt-5">
    <h1>Welcome back, <?php echo $user->data['name']?></h1>
    <h4 class="mt-5">Your Languages </h4>
    <div class="row user-languages">
        <?php
            $ownedLanguages = [];
            while($userLanguage = $languages->fetch_assoc()) {
                $ownedLanguages[] = $userLanguage['lang_id'];

                $language = $db->prepare("SELECT id, name FROM languages WHERE id = ?");
                $language->bind_param("s", $userLanguage['lang_id']);
                $language->execute();
                $language = $language->get_result();
                $language = $language->fetch_assoc();
                ?>
                <div class="col-sm-4">
                    <a href="/app/course/<?php echo $language['id']?>" class="text-dark text-decoration-none <?php echo $userLanguage['completed'] ? "completed" : ""?>">
                        <div class="card card-body animate-card">
                            <?php 
                                if ($userLanguage['completed']) {
                                    echo "completed";
                                } else {
                                    $progress = $db->prepare("SELECT * FROM users_completed_questions WHERE course_id = ? AND user_id = ?");
                                    $progress->bind_param("ss", $language['id'], $user->data['id']);
                                    $progress->execute();
                                    $progress = $progress->get_result();
                                    $progress = $progress->num_rows;

                                    $questions = $db->prepare("SELECT language FROM questions WHERE language = ?");
                                    $questions->bind_param("s", $language['id']);
                                    $questions->execute();
                                    $questions = $questions->get_result();
                                    $questions = $questions->num_rows;
                                    
                                    $progress = $progress / $questions * 100;

                                    echo "{$progress}% completed";
                                }
                            ?>
                            <b><?php echo $language['name']?></b>
                        </div>
                    </a>
                </div>
                <?php
            }
        ?>
        <div class="col-sm-4">
            <div class="card card-body">
                add language
                <select name="new-language" onchange="newLanguage()" class="form-control">
                    <option value="">Select Language...</option>
                    <?php
                        $languages = $db->query("SELECT * FROM languages");
                        while ($language = $languages->fetch_assoc()) {
                            if (!in_array($language['id'], $ownedLanguages)) {
                                echo "<option value=\"{$language['id']}\">{$language['name']}</option>";
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>
</div>