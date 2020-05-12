<?php
require_once '../vendor/autoload.php';

session_start();

if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
}

$treinoHelper = new Models\Treino();

$usuarioHelper = new Models\Usuario();

$treinoHelper->setData($_POST);

if(isset($_POST['update']) ) {
    $treinoHelper->update();
}

if(isset($_POST['create'])){
    $treinoHelper->save();
}


$treinos = $treinoHelper->fetchAll();

foreach ($treinos as $key => $treino) {
    $indice = $key + 1;
    $usuario = $usuarioHelper->fetchById($treino['id']);
    $ativado = $treino['ativado'] ? 'sim' : 'nao';
    $treinoTable .= <<<HTML
 <tr> 
    <td>${indice}</td>
    <td>${treino['nome']}</td>
    <td>${usuario['nome']}</td>
    <td>${ativado}</td>
    <td>
        <button class="btn btn-warning" onclick="modalUpdate(${treino['id']})" >Editar</button>
        <button class="btn btn-danger" onclick="modalDelete(${treino['id']}, this)" >Deletar</button>
    </td>
 </tr>
HTML;

}

$usuarios = $usuarioHelper->fetchAll();

$usuarioSelect = '<select name="usuario_id">';

foreach($usuarios as $key => $usuario) {
    $usuarioSelect .= <<<HTML
        <option value="${usuario['id']}">${usuario['nome']}</option>
    HTML;
}

$usuarioSelect .= '</select>';


?>
<?php include_once('views/header.php') ?>
<main role="main" class="container">

    <div class="container mt-5">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h2>Treinos</h2>
                    </div>
                    <div class="col-sm-6">
                        <button href="#addEmployeeModal" class="btn btn-success float-right" data-toggle="modal" onclick="modalCreate()"><span
                                class="glyphicon glyphicon-plus"></span><span>Adicionar novos treinos</span></button>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Usuário</th>
                    <th scope="col">Ativado</th>
                    <th scope="col">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php echo $treinoTable; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>


<div class="modal hide fade bs-example-modal-lg" id="modalExercicio">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Treino</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Fechar</span></button>

            </div>
            <form role="form" method="post" action="/treinos.php" id="formulario_treinos">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome">
                    </div>
                    <div class="form-group">
                        <label for="usuario_id">Usuário</label>
                        <?php echo $usuarioSelect; ?>
                        <label for="ativado">Ativado</label>
                        <input type="checkbox" class="custom-control-input" id="ativado" name="ativado">
                    </div>
                    <input type="hidden" name="id" id="id" value=""/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                    <button id="submitModalExercicio" type="submit" class="btn btn-primary" name="update">Salvar</button>
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
            model: 'Treino',
            id: id
        }, function (data){
            $('#id').val(data.id);
            $('#nome').val(data.nome);
            $('#usuario_id').val(data.usuario_id);
            $('#ativado').val(data.ativado);
        }, 'json');
    }

    function prepareForCreate(){
        $('#nome').val('');
        $("#usuario_id").val([]);
        $('#ativado').val(true);
        $('#id').val(0);
        $('#submitModalExercicio').attr('name', 'create');
    }

    function modalUpdate(id){
        carregaDadosClienteJSon(id, 'update');

        $('#submitModalExercicio').attr('name', 'update');

        $('#modalExercicio').modal('show');

    }

    function modalCreate(){
        prepareForCreate();

        $('#modalExercicio').modal('show');
    }

    function modalDelete(id, element){

        const result = confirm("Want to delete?");

        let message = '';

        if (result) {
            $.post('ajax/baseAjax.php', {
                action : 'checkForRelation',
                model: 'Treino',
                id: id
            }, async (relationExists) => {
                if(relationExists){
                    message = 'Treino está sendo utilizado.';

                } else {

                    await $.post('ajax/baseAjax.php', {
                        action : 'delete',
                        model: 'Treino',
                        id: id
                    },(deleted) => {

                        message = 'Aconteceu um erro e não foi possível deletar.';
                        if(deleted){
                            message = 'Treino foi deletado.';
                            $(element).closest( "tr" ).remove();
                        }
                    }, 'json');

                    alert(message);
                }
            }, 'json');


        }

    }
</script>