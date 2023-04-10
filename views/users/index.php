<?php include_once __DIR__ . './../partials/header.php'; ?>
<div class="mt-5">
    <div class="container py-5 bg-white rounded shadow-sm border-1">
        <h1 class="text-center">Welcome, <?= $_SESSION['username'] ?> </h1>
        <h5 class="text-center">Your email addess: <?= $_SESSION['email'] ?> </h5>
    </div>
</div>