<?php
namespace App\Models;

use System\Model\Model;
use System\Auth\Auth;
use App\Config\ConfigPerfil;

class Usuario extends Model
{
  use Auth;

  protected $table = 'usuarios';
  protected $timestamps = true;

  public function __construct()
  {
  	parent::__construct();
  }

  public function usuarios($idEmpresa, $idUsuarioLogado = false, $idPerfilUsuarioLogado = false, $email = false, $status = false)
  {
    $superAdmin = ConfigPerfil::superAdmin();
    $administrador = ConfigPerfil::adiministrador();
    $gerente = ConfigPerfil::gerente();
    $vendedor = ConfigPerfil::vendedor();

    # Condição pesauisa usuarios por email
    $queryCondicionalEmail = false;
    if ($email) {
      $queryCondicionalEmail = " AND usuarios.email LIKE '%{$email}%'";
    }

    # Condição filtra usuarios por status
    $queryCondicinalStatus = " AND usuarios.status = 1";
    if ($status == 'desativados') {
      $queryCondicinalStatus = " AND usuarios.status = 0";
    } elseif ($status == 'todos') {
      $queryCondicinalStatus = false;
    }

    # Se o perfil do Usuário logado for de (superAdmin), traz todos os usuarios do sistema
    if ($idPerfilUsuarioLogado && $idPerfilUsuarioLogado == $superAdmin) {
      $queryEmpresa = false;
      if ($idEmpresa) {
        $queryEmpresa = " WHERE usuarios.id_empresa = {$idEmpresa}";
      }

      return $this->query(
        "SELECT
        usuarios.id AS id, usuarios.nome,
        usuarios.email, usuarios.id_sexo,
        usuarios.created_at, usuarios.imagem, usuarios.status,
        sexos.descricao, perfis.descricao AS perfil,
        empresas.nome AS nomeEmpresa

        FROM usuarios INNER JOIN sexos ON
        usuarios.id_sexo = sexos.id
        INNER JOIN perfis ON usuarios.id_perfil = perfis.id
        INNER JOIN empresas ON usuarios.id_empresa = empresas.id
        {$queryEmpresa}
        {$queryCondicionalEmail}
        {$queryCondicinalStatus}"
      );
    }

    # Se o perfil do Usuário logado for de vendedor, mostra apenas os dados do proprio Usuário
    $queryCondicionalVendedor = false;
    if ($idPerfilUsuarioLogado && $idPerfilUsuarioLogado == $vendedor) {
      $queryCondicionalVendedor = " AND usuarios.id = {$idUsuarioLogado}";
    }

    # Se o perfil do Usuário logado for de gerente
    $queryCondicionalGerente = false;
    if ($idPerfilUsuarioLogado && $idPerfilUsuarioLogado == $gerente) {
      $queryCondicionalGerente = " AND usuarios.id_perfil = {$vendedor}";
    }

  	return $this->query(
  		"SELECT
      usuarios.id AS id, usuarios.nome,
      usuarios.email, usuarios.id_sexo,
      usuarios.created_at, usuarios.imagem, usuarios.status,
      sexos.descricao, perfis.descricao AS perfil,
      empresas.nome AS nomeEmpresa

      FROM usuarios INNER JOIN sexos ON
  		usuarios.id_sexo = sexos.id
      INNER JOIN perfis ON usuarios.id_perfil = perfis.id
      INNER JOIN empresas ON usuarios.id_empresa = empresas.id
      WHERE usuarios.id_perfil NOT IN({$superAdmin})
      {$queryCondicionalVendedor}
      {$queryCondicionalGerente}
      {$queryCondicionalEmail}
      {$queryCondicinalStatus}"
  	);
  }

  public function verificaSeEmailExiste($email)
  {
    if ( ! $email) {
      return false;
    }

    $query = $this->query("SELECT * FROM usuarios WHERE email = '{$email}'");
    if (count($query) > 0) {
        return true;
    }

    return false;
  }

  public function seDadoNaoPertenceAoUsuarioEditado($nomeDoCampo, $valor, $idUsuario)
  {
    $dadosUsuario = $this->findBy("{$nomeDoCampo}", $valor);
    if ($dadosUsuario && $idUsuario != $dadosUsuario->id) {
        return true;
    }

    return false;
  }

  public function usuariosPorIdEmpresa($idEmpresa)
  {
    return $this->query("SELECT * FROM usuarios WHERE id = {$idEmpresa}");
  }
}
