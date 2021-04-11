<!--Usando o Html Components-->
<?php
use System\HtmlComponents\Modal\Modal;
use System\HtmlComponents\FlashMessage\FlashMessage;
use System\Session\Session;
use App\Config\ConfigPerfil;
?>

<style type="text/css">
	.imagem-perfil {
		width:40px;
	    height:40px;
	    object-fit:cover;
	    object-position:center;
	    border-radius:50%;
	}
  #form {

    float:right;
  }
</style>


<div class="row">

  <div class="card col-lg-12 content-div">
    <div class="card-body">
      <h5 class="card-title">
        <?php iconFilter();?>
        Filtros
      </h5>
    </div>

    <form method="POST" id="form">

      <!-- token de segurança -->
      <input type="hidden" name="_token" value="<?php echo TOKEN; ?>" />

      <div class="row">

        <?php if (ConfigPerfil::superAdmin() != session::get('idPerfil')):?>
          <div class="col-md-4">
          </div>
        <?php endif;?>

        <div class="col-md-4">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" name="email" id="email" placeholder="Pesquisar por Email">
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status" id="status">
              <option value="todos">Todos</option>
              <option value="ativos" selected>Ativos</option>
              <option value="desativados">Desativados</option>
            </select>
          </div>
        </div>

        <?php if (ConfigPerfil::superAdmin() == session::get('idPerfil')):?>
          <div class="col-md-4">
            <div class="form-group">
              <label for="id_usuario">Empresas</label>
              <select class="form-control" name="id_empresa" id="id_empresa">
              <option value="todos">Todas</option>
              <?php foreach ($empresas as $empresa) : ?>
                <option value="<?php echo $empresa->id; ?>">
                  <?php echo $empresa->nome; ?>
                </option>
              <?php endforeach; ?>
              </select>
            </div>
          </div>
        <?php endif;?>

      </div>
      <!--end row-->

      <button type="submit" class="btn btn-sm btn-success text-right pull-right" id="buscar-pedidos"
        style="float:right"
        onclick="return buscarUsuarios()">
          <i class="fas fa-search"></i> Buscar
      </button>

    </form>
    <br>

  </div>
</div>

<script src="<?php echo BASEURL; ?>/public/assets/js/core/jquery.min.js"></script>
<script src="<?php echo BASEURL;?>/public/js/helpers.js"></script>

<div class="row">
  <div class="card col-lg-12 content-div">
		<div class="card-body">
	    <h5 class="card-title"><i class="fas fa-users"></i> Usuários</h5>
	  </div>
    <!-- Mostra as mensagens de erro-->
	  <?php FlashMessage::show();?>
    <div id="append-usuariosChamadosViaAjax"></div>
  </div>
</div>

<?php Modal::start([
    'id' => 'modalUsuarios',
    'width' => 'modal-lg',
    'title' => 'Cadastrar Usuários'
]);?>

<div id="formulario"></div>

<?php Modal::stop();?>

<script>
	function modalUsuarios(rota, usuarioId) {
        var url = "";

        if (usuarioId) {
            url = rota + "/" + usuarioId;
        } else {
            url = rota;
        }

        $("#modalUsuarios").modal({backdrop: 'static'});
        $("#formulario").load(url);
    }

  function buscarUsuarios() {
    usuarios();
    return false;
  }

  usuarios();
  function usuarios() {
    $('#append-usuariosChamadosViaAjax').html('<div class="col-md-12"><br><center><h3>Carregando...</h3></center></div>');
    var rota = "<?php echo BASEURL; ?>/usuario/usuariosChamadosViaAjax";
    $.post(rota, {
       '_token': '<?php echo TOKEN; ?>',
       'email': $("#email").val(),
       'id_empresa' : $("#id_empresa").val(),
       'email' : $("#email").val(),
       'status' : $("#status").val()
    },
    function(resultado) {
      $('#append-usuariosChamadosViaAjax').empty();
      $('#append-usuariosChamadosViaAjax').append(resultado);
    });
  }
</script>
