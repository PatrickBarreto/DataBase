/**
     * Método responsável por receber um array associativo onde a chave é a opção e o valor, o valor. Cabe a quem chama passar corretamente o que cabe a ação.
     * 
     * AÇÕES ESPECFICIAS PARA CADA GERAL
     *  insert: [
     *          'ignore' => true | false,
     *          'fields' => '',
     *          'values' => [['coluna'=>'valor'],[...]]
     *          ],
     * 
     *  select: [
     *           'distinct' => false | true,
     *           'fields' => "*",
     *           'where' => '',
     *           'limit' => '',
     *           'order' => '',
     *           'group' => '',
     *           'having' => ''
     *          ],
     * 
     *  update: [
     *           'set' => [['coluna'=>'valor'],[...]],
     *           'where' => ''
     *          ],
     *  
     *  delete: [
     *           'where' => ''
     *          ]
     *