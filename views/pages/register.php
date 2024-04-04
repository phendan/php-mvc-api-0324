<h1>Register</h1>

<form action="" method="post">
    <div>
        <?php if (isset($errors['username'])): ?>
            <div class="error"><?= $errors['username'][0] ?></div>
        <?php endif; ?>

        <label for="username">Username</label>
        <input type="text" name="username" id="username" placeholder="Your Handle" />
    </div>

    <div>
        <?php if (isset($errors['email'])): ?>
            <div class="error"><?= $errors['email'][0] ?></div>
        <?php endif; ?>

        <label for="email">E-Mail</label>
        <input type="text" name="email" id="email" placeholder="you@somewhere.com" />
    </div>

    <div>
        <?php if (isset($errors['password'])): ?>
            <div class="error"><?= $errors['password'][0] ?></div>
        <?php endif; ?>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" autocomplete="new-password" />
    </div>

    <div>
        <?php if (isset($errors['passwordAgain'])): ?>
            <div class="error"><?= $errors['passwordAgain'][0] ?></div>
        <?php endif; ?>

        <label for="password-again">Repeat Password</label>
        <input type="password" name="passwordAgain" id="password-again" autocomplete="new-password" />
    </div>

    <input type="submit" value="Register" />
</form>
