<div class="hero-index">
    <div class="container py-5">
        <h1 class="display-1 text-center">The platform to quickstart your coding career.</h1>
        <div class="mt-5 text-center">
            <?php if ($user) {?>
                <a href="/app" class="btn btn-light shadow">Get Started, <?php echo $user->data['name']?></a>
            <?php } else {?>
                <a href="/account/login" class="btn btn-light shadow ms-2 me-2">Login</a>
                <a href="/account/register" class="btn btn-light shadow ms-2 me-2">Register</a>
            <?php }?>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <img src="https://codelingo.chezzer.dev/assets/img/img.png" alt="" height="400px">
        </div>
        <div class="col-sm-6">
            <div class="ms-5" style="margin-top: 15vh">
                <h4 class="fw-bold">Duolingo, for code.</h4>
                <p>The new way to learn to code, this is codelingo. Codelingo is a new way to learn new coding languages, without any prior experience! Get started by registering your account today.</p>
            </div>
        </div>
    </div>
</div>
