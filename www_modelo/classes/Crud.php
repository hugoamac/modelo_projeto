<?php

class Crud {

    /**
     * Propriedade responsável por receber a tabela no pattern table data gateway
     * */
    public $table;

    /**
     * Propriedade responsável por receber o objeto PDO
     * */
    protected $db;

    public function __construct($conexao = BANCO) {
        switch ($conexao) {

            case "ONLINE":

                try {
                    $this->db = new PDO("mysql:host=" . HOST_ONLINE . ";dbname=" . DATABASE_ONLINE, USER_ONLINE, PASS_ONLINE);
                } catch (PDOException $e) {

                    print "Erro:" . $e->getMessage . "<br/>";
                    die();
                }
                break;
            default :
                try {
                    $this->db = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASS);
                } catch (PDOException $e) {

                    print "Erro:" . $e->getMessage . "<br/>";
                    die();
                }
                break;
        }
    }

    /**
     * Método responsável para recuperar a instancai do Objeto PDO <br/>
     * @return object <b>PDO</b>
     * */
    public function getDb() {

        return $this->db;
    }

    /**
     * Método responsável para inserir dados no banco<br/>
     * @param array $dados <p>os dados a serem inseridos</p>
     * @return <b>int</b> <p>Se os dados forem inseridos com sucesso
     * ou <b> false </b> caso contrário.</p>     
     * */
    public function insert(Array $dados) {

        foreach ($dados as $col => $val) {
            if (!in_array($col, $this->describe()))
                unset($dados[$col]);
        }

        $cols = array_keys($dados);
        $vals = array_values($dados);

        $cols = implode(",", $cols);
        $vals = "'" . implode("','", $vals) . "'";

        $sql = "INSERT INTO `{$this->table}` ({$cols}) VALUES ({$vals}) ";

        if ($this->db->query($sql))
            return (int) $this->db->lastInsertId();

        return FALSE;
    }

    /**
     * Método responsável para recupera somente uma linha no banco.<br/>
     * @param array $where <p>com os critério coluna valor</p>
     * @return <b>array</b> Se os dados forem encontrados
     * ou <b> FALSE </b> caso contrário.     
     * */
    public function find(Array $where) {

        foreach ($where as $col => $val) {
            if (!in_array($col, $this->describe())) {
                unset($where[$col]);
            } else {
                $campos[] = "{$col} = '{$val}'";
            }
        }

        $and = count($where) > 1 ? " and " : "";
        $where = "WHERE " . implode($and, $campos);

        $sql = " SELECT * FROM `{$this->table}` {$where} ";


        $q = $this->db->prepare($sql);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $q->execute();

        return $q->fetch();
    }

    /**
     * Método responsável para recupera um conjunto de linhas do banco<br/>
     * @param array $where <p>com os critério coluna valor</p>
     * @param array $operator <p>com os operadores coluna operador</p>
     * @param string $order <p>com os critério de ordenação da consulta</p>
     * @param string $limit <p>com o critério limite da consulta</p>
     * @param string $offset <p>com o critério intervalo de linhas</p>
     * @return <b>array array</b> Se os dados forem encontrados
     * ou <b> FALSE </b> caso contrário.
     * */
    public function findAll($where = array(), $operator = array(), $order = null, $limit = null, $offset = null) {


        if (count($where) > 0) {
            foreach ($where as $col => $val) {
                if (!in_array($col, $this->describe())) {
                    unset($where[$col]);
                } else {

                    if (isset($operator[$col])) {
                        $campos[] = "{$col} {$operator[$col]} '{$val}'";
                    } else {
                        $campos[] = "{$col} = '{$val}'";
                    }
                }
            }

            $and = count($where) > 1 ? " and " : "";
            $where = "WHERE " . implode($and, $campos);
        }

        $where = ($where != null ? "{$where}" : "");
        $orderby = ($order != null ? "ORDER BY {$order}" : "");
        $limit = ($limit != null ? "LIMIT {$limit}" : "");
        $offset = ($offset != null ? "OFFSET {$offset}" : "");

        $sql = " SELECT * FROM `{$this->table}` {$where} {$orderby} {$limit} {$offset} ";



        $q = $this->db->prepare($sql);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $q->execute();

        return $q->fetchAll();
    }

    /**
     * Método responsável para atualizar dados do banco<br/>
     * @param array $dados <p>com os dados a serem atualizados
     * @param array $where <p>com os critério coluna valor</p>
     * @param array $operator <p>com os operadores coluna operador</p>
     * @return <b>boolean TRUE</b> Se os dados forem atualizados
     * ou <b> FALSE </b> caso contrário.
     * */
    public function update(Array $dados, Array $where, $operator = array()) {


        foreach ($where as $col => $val) {
            if (!in_array($col, $this->describe())) {
                unset($where[$col]);
            } else {

                if (isset($operator[$col])) {
                    $campos_where[] = "{$col} {$operator[$col]} '{$val}'";
                } else {
                    $campos_where[] = "{$col} = '{$val}'";
                }
            }
        }

        $and = count($where) > 1 ? " and " : "";
        $where = "WHERE " . implode($and, $campos_where);

        foreach ($dados as $ind => $val) {
            if (!in_array($ind, $this->describe())) {


                unset($dados[$ind]);
            } else {
                $campos[] = "{$ind} = '{$val}'";
            }
        }

        $campos = implode(", ", $campos);

        $sql = " UPDATE `{$this->table}` SET {$campos}  {$where} ";



        return $this->db->query($sql);
    }

    /**
     * Método responsável para excluir dados do banco<br/>
     * @param array $where <p>com os critério coluna valor</p>
     * @param array $operator <p>com os operadores coluna operador</p>
     * @return <b>boolean TRUE</b> Se os dados forem excluidos
     * ou <b> FALSE </b> caso contrário.
     * */
    public function delete(Array $where, $operator = array()) {

        foreach ($where as $col => $val) {
            if (!in_array($col, $this->describe())) {
                unset($where[$col]);
            } else {
                if (isset($operator[$col])) {
                    $campos_where[] = "{$col} {$operator[$col]} '{$val}'";
                } else {
                    $campos_where[] = "{$col} = '{$val}'";
                }
            }
        }

        $and = count($where) > 1 ? " and " : "";
        $where = "WHERE " . implode($and, $campos_where);

        $sql = " DELETE FROM `{$this->table}`  {$where} ";



        return $this->db->query($sql);
    }

    /**
     * Método responsável para descrever as entidades do banco<br/>
     * @return <b>array</b> com as colunas da tabela
     * */
    private function describe() {

        $sql = "describe {$this->table}";

        $q = $this->db->prepare($sql);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $q->execute();
        $vvDescribe = $q->fetchAll();
        foreach ($vvDescribe as $vsDescribe) {
            $vsCols[] = $vsDescribe["Field"];
        }

        return $vsCols;
    }

}

?>
