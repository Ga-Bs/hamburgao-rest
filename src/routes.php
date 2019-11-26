<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/usuario/[{id}]', function (Request $request, Response $response, array $args) use ($container) {
        $container->get('logger')->info("Slim-Skeleton '/usuario/' route");


        try {
            $conexao = $container->get('pdo');

            $sql = 'SELECT * FROM usuario ';

            if (isset($args['id'])) {

                $usuarioID = $args['id'];

                $sql .= ' WHERE id =' . $usuarioID;
            }

            $resultSet = $conexao->query($sql)->fetchAll();
            $retorno = array('sucesso' => true, 'dados' => $resultSet);
        } catch (Exception $e) {
            $retorno = array('sucesso' => false, 'dados' => $e->getMessage());
        }

        print_r(json_encode($retorno));
    });

    $app->post('/usuario/', function (Request $request, Response $response, array $args) use ($container) {
        $container->get('logger')->info("Slim-Skeleton '/usuario/' route");

        try {
            $conexao = $container->get('pdo');

            $params = $request->getParsedBody();

            $usuario = json_decode($params['usuario']);


            $nome = $usuario[0]->nome;
            $email = $usuario[0]->email;
            $senha = md5($usuario[0]->senha);


            if (isset($usuario[0]->id)) {
                $id = $usuario[0]->id;

                $sql = "UPDATE usuario SET nome='$nome',email='$email',senha='$senha' WHERE id=$id";
            } else {
                $sql = "INSERT INTO usuario VALUES('', '$nome','$email','$senha')";
            }

            $conexao->query($sql);

            $retorno = array('sucesso' => true, 'dados' =>  "Usuário inserido/atualizado com sucesso");
        } catch (Exception $e) {
            $retorno = array('sucesso' => false, 'dados' => $e->getMessage());
        }
        print_r(json_encode($retorno));
        exit;
    });
};
