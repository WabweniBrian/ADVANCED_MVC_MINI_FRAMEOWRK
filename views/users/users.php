<?php include_once __DIR__ . './../partials/header.php'; ?>
<div class="container py-4">
    <h1>All Users</h1>

    <form action="">
        <div class="input-group mb-3">
            <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?= $search ?>">
            <button class=" btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </form>

    <div class="p-2 shadow-sm rounded bg-white">
        <div class="table table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <?php if ($users) : ?>
                    <tbody>
                        <?php foreach ($users as $i => $user) : ?>
                            <tr>
                                <th scope="row"><?= $i + 1 ?></th>
                                <td><?= $user['username'] ?></td>
                                <td><?= $user['email'] ?></td>
                                <td>
                                    <a href="/users/view?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-success">View</a>
                                    <a href="/users/update?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="/users/delete" method="post" class="d-inline">
                                        <input name="id" type="hidden" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    <?php else : ?>

                    </tbody>
            </table>
        </div>
        <h1 class="text-center text-danger">No Users</p>
        <?php endif; ?>
    </div>
</div>