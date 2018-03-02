<!-- Desenvolvido por Cleiton Paris para IXCSoft -->
<html>
<head>
    <meta charset="UTF-8">
    <title>Alteracao de dados demo</title>
    <script>
        function submit_by_id() {
            document.getElementById("form_id").submit();
        }
    </script>

</head>
<body>


<?php

$senha = $_POST["senha"];

require(dirname(__DIR__).'/includes/ixc_parametros.php');
require(dirname(__DIR__).'/includes/db/mysql.php');
require(dirname(__DIR__).'/includes/funcoes.php');
require(dirname(__DIR__).'/includes/autoload.php');

function mod($dividendo,$divisor) {
    return round($dividendo - (floor($dividendo/$divisor)*$divisor));
}

function cpf($compontos) {
    $n1 = rand(0,9);
    $n2 = rand(0,9);
    $n3 = rand(0,9);
    $n4 = rand(0,9);
    $n5 = rand(0,9);
    $n6 = rand(0,9); 
    $n7 = rand(0,9);
    $n8 = rand(0,9);
    $n9 = rand(0,9);
    $d1 = $n9*2+$n8*3+$n7*4+$n6*5+$n5*6+$n4*7+$n3*8+$n2*9+$n1*10;
    $d1 = 11 - ( mod($d1,11) );
    if ( $d1 >= 10 ) {
        $d1 = 0 ;
    }
    $d2 = $d1*2+$n9*3+$n8*4+$n7*5+$n6*6+$n5*7+$n4*8+$n3*9+$n2*10+$n1*11;
    $d2 = 11 - ( mod($d2,11) );
    if ($d2>=10) { $d2 = 0 ;}
        $retorno = '';
        if ($compontos==1) {
            $retorno = ''.$n1.$n2.$n3.".".$n4.$n5.$n6.".".$n7.$n8.$n9."-".$d1.$d2;
        }
        else {
            $retorno = ''.$n1.$n2.$n3.$n4.$n5.$n6.$n7.$n8.$n9.$d1.$d2;
        }
        return $retorno;
    }

    function cnpj($compontos) {
        $n1 = rand(0,9);
        $n2 = rand(0,9);
        $n3 = rand(0,9);
        $n4 = rand(0,9);
        $n5 = rand(0,9);
        $n6 = rand(0,9);
        $n7 = rand(0,9);
        $n8 = rand(0,9);
        $n9 = 0;
        $n10= 0;
        $n11= 0;
        $n12= 1;
        $d1 = $n12*2+$n11*3+$n10*4+$n9*5+$n8*6+$n7*7+$n6*8+$n5*9+$n4*2+$n3*3+$n2*4+$n1*5;
        $d1 = 11 - ( mod($d1,11) );
        if ( $d1 >= 10 ) {
            $d1 = 0 ;
        }
        $d2 = $d1*2+$n12*3+$n11*4+$n10*5+$n9*6+$n8*7+$n7*8+$n6*9+$n5*2+$n4*3+$n3*4+$n2*5+$n1*6;
        $d2 = 11 - ( mod($d2,11) );
        if ($d2>=10) {
            $d2 = 0 ;
        }
        $retorno = '';
        if ($compontos==1) {
            $retorno = ''.$n1.$n2.".".$n3.$n4.$n5.".".$n6.$n7.$n8."/".$n9.$n10.$n11.$n12."-".$d1.$d2;
        }
        else {
            $retorno = ''.$n1.$n2.$n3.$n4.$n5.$n6.$n7.$n8.$n9.$n10.$n11.$n12.$d1.$d2;
        }
        return $retorno;
}




$db = new DB;
$db -> conecta(SERVIDOR, USUARIO, SENHA, BANCO);


if ($senha == 'ixc') {


    $x = array();

    $lab = array();
    $valor = array();


    $newdate = strtotime('first day of this month', time());
    $newdate = strtotime('-1 year', $newdate);
    $inicio = date('Y-m-d', strtotime('+1 month', $newdate));


    $WHERE = array();
    //$WHERE[] = array('TB' => 'id', 'OP' => '=', 'P' => 1);
    $rs_cli = $db->select('cliente', "*", $WHERE);

    $cpf_cnpj = '';
    $cid = '';

    while ($row = $db->fetch($rs_cli)) {

        if ($row['tipo_pessoa'] == 'F') {
            $cpf_cnpj = cpf(1);
        } else {
            $cpf_cnpj = cnpj(1);
        }

        $where = array();
        $where[] = array('TB' => 'id', 'OP' => '=', 'P' => $row['id']);
        $campos = array('razao' => 'Cliente ' . $row['id'], 'fantasia' => 'Cliente ' . $row['id'], 'endereco' => 'Endereco ' . $row['id'], 'bairro' => 'Centro', 'fone' => '',
            'telefone_comercial' => '', 'telefone_celular' => '', 'hotsite_email' => 'email' . $row['id'] . '@ixcsoft.com.br', 'email' => 'email' . $row['id'] . '@ixcsoft.com.br',
            'cnpj_cpf' => $cpf_cnpj, 'cep' => '89801-000', 'ie_identidade' => '');
        $db->update('cliente', $campos, $where);

        if ($cid != $row['cidade']) {
            $where = array();
            $where[] = array('TB' => 'id', 'OP' => '=', 'P' => $row['cidade']);
            $campos = array('nome' => utf8_decode('Chapecó'));
            $db->update('cidade', $campos, $where);
            $cid = $row['cidade'];
        }


    }

    $WHERE = array();
    $WHERE[] = array('TB' => 'id_planejamento', 'OP' => '=', 'P' => 4);
    $rs_pa = $db->select('planejamento_analitico', "*", $WHERE);

    while ($row_pa = $db->fetch($rs_pa)) {

        $where = array();
        $where[] = array('TB' => 'id', 'OP' => '=', 'P' => $row_pa['id']);
        $campos = array('planejamento_analitico' => 'Cliente ' . $row_pa['id']);
        $db->update('planejamento_analitico', $campos, $where);
    }

    $WHERE = array();
    $rs_ra = $db->select('radusuarios', "*", $WHERE);

    while ($row_ra = $db->fetch($rs_ra)) {

        $where = array();
        $where[] = array('TB' => 'id', 'OP' => '=', 'P' => $row_ra['id']);
        $campos = array('login' => 'login' . $row_ra['id'], 'senha_router1' => '', 'senha_router2' => '', 'senha_rede_sem_fio' => '');
        $db->update('radusuarios', $campos, $where);
    }

    $where = array();
    $campos = array('ip' => '', 'mac' => '');
    $db->update('radusuarios', $campos, $where);

    $where = array();
    $where[] = array('TB' => 'id', 'OP' => '=', 'P' => 1);
    $campos = array('razao' => 'Empresa Demo', 'fantasia' => 'Empresa Demo', 'endereco' => 'Endereco Demo', 'cep' => '89224-000', 'ie' => '', 'cnpj' => '08.917.085/0001-89', 'ato_anatel' => '', 'telefone' => '(49) 3344-6001', 'bairro' => 'Centro', 'email' => 'demonstracao@ixcsoft.com.br', 'site' => 'www.ixcsoft.com.br', 'contato' => 'Cleiton');
    $db->update('filial', $campos, $where);

}



if ($senha != 'ixc') {

    ?>

            <form action="#" method="post" name="form_name" id="form_id" >
                <h2>Informe a senha para executar o script de alteracao de dados</h2>
                <label>Senha :</label>
                <input type="text" name="senha" id="senha" placeholder="Senha" />
                <input type="button" name="submit_id" id="btn_id" value="Executar" onclick="submit_by_id()"/>
            </form>

    <?php

}
else {
    echo 'Script executado com sucesso!';
}

?>




</body>
</html>
