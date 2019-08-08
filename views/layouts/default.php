<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>T’chat hiit consulting</title>
    <link rel="icon" type="image/jpg" href="/assets/images/favicon.jpg" />
    <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet"/>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
    <h5 class="my-0 mr-md-auto font-weight-normal">T’chat Hiit Consulting</h5>
    
    <?php if(false === (new \App\Core\Auth())->isLogged()) : ?>
        <a class="btn btn-outline-primary mr-1" href="/login">Se connecter</a>
        <a class="btn btn-outline-primary" href="/register">S'inscrire</a>
    <?php else : ?>
        <span class="btn btn-outline-primary mr-1"><?php echo (new \App\Core\Auth())->getUser()->username; ?></span>
        <a class="btn btn-outline-primary" href="/login/logout">Se déconnecter</a>
    <?php endif; ?>
</div>

<?php echo $content; ?>

<script src="/assets/vendor/jquery/jquery.min.js"></script>
<script src="/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/vendor/momentjs/moment-with-locales.js"></script>
<script src="/assets/js/app.js"></script>
<script src="/assets/js/discussion.js"></script>
<?php if((new \App\Core\Auth())->isLogged()) : ?>
<script>
    var discussion = new Discussion({
        currentUser: <?php echo (new \App\Core\Auth())->getUser()->id ?>
    });
    discussion.bindEvents();
</script>
<?php endif; ?>
</body>
</html>
