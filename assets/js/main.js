function login(email, password) {
	$.post(
		"/pages/router.php?path=/account/login",
		{
			email,
			password,
		},
		res => {
			if (res.success) {
				page.redirect("/app");
				setTimeout(() => {
					location.reload();
				}, 550);
			} else {
				alert(res.error);
			}
		}
	);
}

function register(name, email, password) {
	$.post(
		"/pages/router.php?path=/account/register",
		{
			name,
			email,
			password,
		},
		res => {
			if (res.success) {
				page.redirect("/app");
				setTimeout(() => {
					location.reload();
				}, 550);
			} else {
				alert(res.error);
			}
		}
	);
}

function selectLanguage(id) {
	$("[data-lang-id]").removeClass("active");
	$(`[data-lang-id=${id}]`).addClass("active");
}

function selectAnswer(id) {
	$("[data-answer-id]").removeClass("active");
	$(`[data-answer-id=${id}]`).addClass("active");
}

function continueLanguage() {
	let id = Number($("[data-lang-id].active").attr("data-lang-id"));
	if (id) {
		$.post(
			"/pages/router.php?path=/app",
			{
				action: "select",
				id,
			},
			res => {
				if (res.success) {
					page.redirect("/app");
				} else {
					alert(res.error);
				}
			}
		);
	} else {
		alert("Please choose a language.");
	}
}

function checkQuestion(question, option) {
    $.post(
        "/pages/router.php?path=/app/course/:id",
        {
            action: "check",
            question,
            option,
            course: page.params.id,
            question_number: page.params.question_number
        }, res => {
            if (res.success) {
                if (res.completed) {
                    page.redirect("/app");
                } else {
                    page.redirect(page.current)
                }
            } else {
                alert(res.error);
            }
        }
    );
}

function newLanguage() {
    $.post(
        "/pages/router.php?path=/app/",
        {
            action: "add-language",
            id: $("[name=new-language]").find(":selected").val()
        }, res => {
            if (res.success) {
                page.redirect(page.current);
            } else {
                alert(res.error);
            }
        }
    );
}