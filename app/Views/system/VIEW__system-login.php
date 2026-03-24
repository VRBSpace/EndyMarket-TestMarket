<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
    </head>
    <body>
        <h1>Login</h1>
        <?php if (session() -> getFlashdata('msg')): ?>
            <div><?= session() -> getFlashdata('msg') ?></div>
        <?php endif; ?>
        <form action="<?= route_to('PatchFile') ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <br>  <br> <br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" autocomplete="off" required>
            <br>
            <button type="submit">Login</button>
        </form>

        <h1>Login LOG</h1>
        <form action="<?= route_to('LogViewer') ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <br>  <br> <br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" autocomplete="off" required>
            <br>
            <button type="submit">Login</button>
        </form>
    </body>
</html>