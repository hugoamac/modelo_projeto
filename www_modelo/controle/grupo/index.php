<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/controle/verifica_usuario.php"); 

$request=  Helper::PostGet();
$oGrupo = new Grupo();

$vvGrupo = $oGrupo->findAll();


?>
<!DOCTYPE html>
<html>
    <head>
        <? require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/controle/anexos.inc.php"); ?>
    </head>
    <body>
        <div id="main">
            <!-- DIV HEADER -->
            <? require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/controle/topo.inc.php"); ?>
            <!-- DIV CONTAINER -->
            <div id="container">
                <!-- DIV MENU_LEFT-->
                <? require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/controle/menu.inc.php"); ?>
                <!-- DIV CONTENT-->
                <div id="content">
                    <ul id="breadcrumbs">
                        <li>Grupos</li>                     
                    </ul>

                    <h1>Grupos</h1>

                    <br/>

                    <a href="form.php?sOP=Adicionar" class="Botao">Adicionar</a>

                    <? if (count(Session::getInstance()->getFlashMessenger()) > 0): ?>
                        <p class="<?= isset($request["sAlerta"]) ? $request["sAlerta"] : "" ?>"><?= Session::getInstance()->getFlashMessenger()->msg ?></p>
                    <? endif; ?>

                    <table class="Lista">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Grupo</th>                      
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            <? if ($vvGrupo): ?>
                                <? foreach ($vvGrupo as $vsGrupo): ?>
                                    <tr>
                                        <td><?= $vsGrupo["id"] ?></td>
                                        <td><?= $vsGrupo["nome"] ?></td>                 
                                        <td>
                                            <a href="form.php?id=<?= $vsGrupo["id"] ?>&sOP=Editar" class="Botao">Editar</a>
                                            <a href="processa.php?id=<?= $vsGrupo["id"] ?>&sOP=Apagar" class="Apagar Botao">Apagar</a>
                                        </td>
                                    </tr>      
                                <? endforeach; ?>
                            <? else: ?>
                                <tr>
                                    <td colspan="3">Ops ! nenhum registro encontrado.</td>
                                </tr>
                            <? endif; ?>
                        </tbody>
                    </table>

                </div>
                <div class="clear"></div>
            </div>
            <!-- DIV FOOTER-->
            <? require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/controle/footer.inc.php"); ?>

        </div>
    </body>
</html>

