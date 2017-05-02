<div class="panel panel-primary">
    <div class="panel-heading">Профиль</div>
    <div class="panel-body">
        <table class="table table-striped table-hover">
            <tr>
                <td class="col-xs-2">Фамилия:</td>
                <td><?= $user['firstname'] ?></td>
            </tr>
            <tr>
                <td class="col-xs-2">Имя:</td>
                <td><?= $user['lastname'] ?></td>
            </tr>
            <tr>
                <td class="col-xs-2">E-mail:</td>
                <td><?= $user['email'] ?></td>
            </tr>
            <tr>
                <td class="col-xs-2">Телефон:</td>
                <td><?= $user['phone'] ?></td>
            </tr>
        </table>
<!--        --><?//= debug($user) ?>
    </div>
</div>