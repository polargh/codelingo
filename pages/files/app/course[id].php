<?php
    if(!$user) {
        die();
    }

    $course = isset($_POST['course']) ? $_POST['course'] : $_PARAMS['id'];

    $questions = $db->prepare("SELECT * FROM questions WHERE language = ? ORDER BY RAND()");
    $questions->bind_param("s", $course);
    $questions->execute();
    $questions = $questions->get_result();

    if ($isPost) {
        if ($_POST['action'] == "check") {
            if ($_POST['question'] && $_POST['option'] && $_POST['course']) {

                $check = $db->prepare("SELECT id, question_id, answer FROM questions_options WHERE question_id = ? AND id = ? AND answer = 1");
                $check->bind_param("ss", $_POST['question'], $_POST['option']);
                $check->execute();
                $check = $check->get_result();

                if ($check->num_rows) {
                    $completeQuestion = $db->prepare("INSERT INTO users_completed_questions (question_id, user_id, course_id) VALUES (?, ?, ?)");
                    $completeQuestion->bind_param("sss", $_POST['question'], $user->data['id'], $_POST['course']);
                    $completeQuestion->execute();

                    $completedQuestions = $db->prepare("SELECT * FROM users_completed_questions WHERE course_id = ? AND user_id = ?");
                    $completedQuestions->bind_param("ss", $_POST['course'], $user->data['id']);
                    $completedQuestions->execute();
                    $completedQuestions = $completedQuestions->get_result();
                    
                    if ($completedQuestions->num_rows == $questions->num_rows) {
                        $update = $db->prepare("UPDATE users_languages SET completed = 1 WHERE user_id = ? AND lang_id = ?");
                        $update->bind_param("ss", $user->data['id'], $_POST['course']);
                        $update->execute();
                        $res['completed'] = true;
                    }

                    $res['success'] = true;
                } else {
                    $res['incorrect'] = true;
                    $res['error'] = "That question wasn't right, try again.";
                }
            } else {
                $res['error'] = "Internal Error";
            }
        } else {
            $res['error'] = "Internal Error";
        }
        
        header("content-type:application/json");
        echo json_encode($res);
        die();
    }
    

    $completedQuestions = $db->prepare("SELECT * FROM users_completed_questions WHERE course_id = ? AND user_id = ?");
    $completedQuestions->bind_param("ss", $_PARAMS['id'], $user->data['id']);
    $completedQuestions->execute();
    $completedQuestions = $completedQuestions->get_result();

    $percentage = ($completedQuestions->num_rows) / $questions->num_rows * 100;
    
    $completedQuestionsList = [];
    
    while ($completedQuestion = $completedQuestions->fetch_assoc()) {
        $completedQuestionsList[] = $completedQuestion['question_id'];
    }


    while ($q = $questions->fetch_assoc()) {
        if (!in_array($q['id'], $completedQuestionsList)) {
            $question = $q;
        }
    }

    $options = $db->prepare("SELECT * FROM questions_options WHERE question_id = ? ORDER BY RAND()");
    $options->bind_param("s", $question['id']);
    $options->execute();
    $options = $options->get_result();
?>
<div class="container mt-5">
    <div class="progress mb-4" style="height: 16px; border-radius: 20px !important;">
        <div class="progress-bar" role="progressbar" style="width: <?php echo $percentage?>%"></div>
    </div>
    <div class="mt-5 text-center">
        <h3 class="fw-bold"><?php echo $question['name']?></h3>
        <p style="opacity: 50%;">choose...</p>
        <div class="answers ps-5 pe-5" style="padding-top: 10vh">
            <div class="container">
                <div class="row options">
                    <?php
                        while ($option = $options->fetch_assoc()) {
                            ?>
                                <div class="col-sm-4" onclick="selectAnswer(<?php echo $option['id']?>)" data-answer-id="<?php echo $option['id']?>"><?php echo htmlentities($option['text'])?></div>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="mt-5">
            <div class="d-flex">
                <div class="mx-auto">
                    <a class="btn btn-success" onclick="checkQuestion(<?php echo $question['id']?>, $('[data-answer-id].active').attr('data-answer-id'))">CONTINUE</a>
                </div>
            </div>
        </div>
    </div>
</div>