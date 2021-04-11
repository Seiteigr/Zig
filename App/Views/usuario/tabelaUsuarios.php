<?php
use System\HtmlComponents\Modal\Modal;
use System\HtmlComponents\FlashMessage\FlashMessage;
use System\Session\Session;
use App\Config\ConfigPerfil;
?>

<?php if (count($usuarios) > 0):?>
  <table id="example" class="table tabela-ajustada table-striped" style="width:100%">
      <thead>
          <tr>
            <th>#</th>
              <th>Nome</th>
              <th class="hidden-when-mobile">E-mail</th>
              <th>Perfil</th>
              <th class="hidden-when-mobile">Empresa</th>
              <th style="text-align:right;padding-right:0">
                <?php $rota = BASEURL.'/usuario/modalFormulario';?>
                <?php if (Session::get('idPerfil') != ConfigPerfil::vendedor()):?>
                  <button onclick="modalUsuarios('<?php echo $rota;?>', false);"
                    class="btn btn-sm btn-success" title="Novo Usuário!">
                      <i class="fas fa-plus"></i>
                    </button>
                  <?php endif;?>
              </th>
          </tr>
      </thead>
      <tbody>

        <?php foreach ($usuarios as $usuario):?>
            <tr>
              <td>
                <?php if ( ! is_null($usuario->imagem) && $usuario->imagem != ''):?>
                  <center>
                    <img src="<?php echo $usuario->imagem;?>" width="40" class="imagem-perfil">
                  </center>
                <?php else:?>
                  <center><i class="fas fa-user" style="font-size:40px"></i></center>
                <?php endif;?>
              </td>
                <td><?php echo $usuario->nome;?></td>
                <td class="hidden-when-mobile"><?php echo $usuario->email;?></td>
                <td><?php echo $usuario->perfil;?></td>
                <td class="hidden-when-mobile"><?php echo $usuario->nomeEmpresa;?></td>
                <td style="text-align:right">

        <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-cogs"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

              <button class="dropdown-item" href="#"
              onclick="modalUsuarios('<?php echo $rota;?>', <?php echo $usuario->id;?>);">
                <i class="fas fa-edit"></i> Editar
              </button>

              <!--<a class="dropdown-item" href="#">
                <i class="fas fa-trash-alt" style="color:#cc6666"></i> Excluir
              </a>-->

            </div>
          </div>
                </td>
            </tr>
          <?php endforeach;?>
      <tfoot></tfoot>
  </table>
  <?php else:?>
    <center>
      <i class="far fa-grin-beam" style="font-size:50px;opacity:0.60"></i> <br> <br>
        Poxa, ainda não há nenhum Cliente cadastrado! <br>
        <?php $rota = BASEURL.'/usuario/modalFormulario';?>
          <button
          onclick="modalUsuarios('<?php echo $rota;?>', null);"
            class="btn btn-sm btn-success">
              <i class="fas fa-plus"></i>
                Cadastrar Usuário
            </button>
      </center>
<?php endif;?>

<br>
