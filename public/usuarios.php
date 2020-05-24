<?php
require_once '../vendor/autoload.php';

session_start();

if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /../login.php');
}

$usuarioHelper = new Models\Usuario();

$usuarioHelper->setData($_POST);

if(isset($_POST['update']) ) {
    $usuarioHelper->update();
}

if(isset($_POST['create'])){
    $usuarioHelper->save();
}


$result = $usuarioHelper->fetchAll();

foreach ($result as $key => $usuario) {
    $indice = $key + 1;
    $tableUser .= <<<HTML
 <tr> 
    <td>${indice}</td>
    <td>${usuario['login']}</td>
    <td>${usuario['nome']}</td>
    <td>${usuario['senha']}</td>
    <td>
        <button class="btn btn-warning" onclick="modalUpdate(${usuario['id']})" >Editar</button>
        <button class="btn btn-danger" onclick="modalDelete(${usuario['id']}, this)" >Deletar</button>
    </td>
 </tr>
HTML;

}


?>
<?php include_once('views/header.php') ?>
<main role="main" class="container">

    <div class="container mt-5">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h2>Usuários</h2>
                    </div>
                    <div class="col-sm-6">
                        <button href="#addEmployeeModal" class="btn btn-success float-right" data-toggle="modal" onclick="modalCreate()"><span
                                    class="glyphicon glyphicon-plus"></span><span>Adicionar novos usuários</span></button>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Login</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Senha</th>
                    <th scope="col">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php echo $tableUser; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>


<div class="modal hide fade bs-example-modal-lg" id="modalUsuario">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Cliente</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Fechar</span></button>

            </div>
            <form role="form" method="post" action="usuarios.php" id="formulario_clientes">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="login">Login</label>
                        <input type="text" class="form-control" id="login" name="login">
                    </div>
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome">
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha">
                    </div>
                    <input type="hidden" name="role" value="user"/>
                    <input type="hidden" name="id" id="id" value=""/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                    <button id="submitModalUsuario" type="submit" class="btn btn-primary" name="update">Salvar</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php include_once('views/footer.php') ?>

<script>
    function carregaDadosClienteJSon(id, action){
        $.post('ajax/baseAjax.php', {
            action : action,
            model: 'Usuario'
            id: id
        }, function (data){
            $('#nome').val(data.nome);
            $('#login').val(data.login);
            $('#senha').val(data.senha);
            $('#id').val(data.id);
        }, 'json');
    }

    function prepareForCreate(){
        $('#nome').val('');
        $('#login').val('');
        $('#senha').val('');
        $('#id').val(0);
        $('#submitModalUsuario').attr('name', 'create');
    }

    function modalUpdate(id){
        carregaDadosClienteJSon(id, 'update');

        $('#submitModalUsuario').attr('name', 'update');

        $('#modalUsuario').modal('show');

    }

    function modalCreate(){
        prepareForCreate();

        $('#modalUsuario').modal('show');
    }

    function modalDelete(id, element){

        const result = confirm("Want to delete?");

        let message = '';

        if (result) {
            $.post('ajax/baseAjax.php', {
                action : 'hasRelations',
                model: 'Usuario',
                id: id
            }, async (relationExists) => {
                if(relationExists){
                    message = 'Usuário está sendo utilizado.';
                } else {
                    await $.post('ajax/baseAjax.php', {
                        action : 'delete',
                        model: 'Usuario',
                        id: id
                    },(deleted) => {

                        message = 'Aconteceu um erro e não foi possível deletar.';
                        if(deleted){
                            message = 'Usuário foi deletado.';
                            $(element).closest( "tr" ).remove();
                        }
                    }, 'json');
                }
                alert(message);
            }, 'json');


        }

    }
</script>