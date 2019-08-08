<form class="form-signin needs-validation" method="post" action="/login/login" novalidate>
    <h1 class="h3 mb-3 font-weight-normal">Se connecter</h1>
    <div class="mb-3">
        <label for="username">Username</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">@</span>
            </div>
            <input name="username" type="text" class="form-control" id="username" placeholder="Username" required>
            <div class="invalid-feedback" style="width: 100%;">
                Le pseudo est obligatoire.
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label for="password">Password</label>
        <input name="password" type="password" class="form-control" id="password" placeholder="Mot de passe" required>
        <div class="invalid-feedback">
            Le mot de passe est obligatoire.
        </div>
    </div>
    <?php
    if (!empty(\App\Core\Session::exists('login.errors'))) {
        echo '<p class="text-danger text-center">'.\App\Core\Session::flash('login.errors').'</p>';
    }
    ?>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Se connecter</button>
</form>
