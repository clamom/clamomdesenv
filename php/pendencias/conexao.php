<?php
 /*
 * Classe Singleton para Conexao ao banco de dados
 *
 * @author Marcos de P. Muniz
 * @version 1.0
 * @implementado por Márcio Aparecido Sitoni teste 8
 * @data implementação.: 03.04.2014 teste teste 2 teste3 teste4 teste 5 teste 6 teste 7
 */
class Conexao 
{

    /** xxxx
     * Instancia de conexao PDO  xxxx
     * @var PDO
     */
    private static $instance = null;
    /**
     * Tipo do banco de dados
     * 
     * Pode ser:
     * <li>MySQL</li>
     * <li>PostgreSQL</li>
     * <li>SQL Server</li>
     * <li>Oracle</li>
     * <li>SQLite</li>
     * @var string
     */
    private static $dbType = "mysql";
    /**
     * Host do banco de dados
     * @var string
     */
    private static $host = "localhost";
    /**
     * Usuario de conexao ao banco de dados
     * @var string
     */
    private static $user = "root";
    /**
     * Senha de conexao ao banco de dados
     * @var string
     */
    private static $senha = "clamom2012";
    /**
     * Nome do banco de dados
     * @var string
     */
    private static $db = "db_rh_desen";
    /**
     * Se a conexao deve ser persistente
     * @var boolean
     */
    protected static $persistent = false;
    /**
     * Lista de tabelas do banco de dados
     * 
     * Esta lista serve para padronizar a utilizacao das tabelas nas consultas
     * para caso seja necessario alterar o nome de alguma tabela o impacto na
     * programacao seja o minimo possivel.
     * @var array
     */
    private static $tabelas = array(
            'projetos' => 'tb_projeto'
     );   
    
    /**
     * Retorna a instancia de conexao ao banco de dados
     * 
     * Caso a instancia de conexao ja exista, apenas a retorna, caso ainda
     * nao exista, cria a instancia e a retorna.
     * 
     * @return PDO
     */
    public static function getInstance() 
    {

        if(self::$persistent != FALSE)
            self::$persistent = TRUE;
        
        if(!isset(self::$instance)){
            try {            
                
                self::$instance = new \PDO(self::$dbType . ':host=' . self::$host . ';dbname=' . self::$db
                        , self::$user
                        , self::$senha
                        , array(\PDO::ATTR_PERSISTENT => self::$persistent));
                
            } catch (\PDOException $ex) {
                exit ("Erro ao conectar com o banco de dados: " . $ex->getMessage());
            }
        }
        
        return self::$instance;
        
    }

    /**
     * Fecha a instancia de conexao ao banco de dados
     */
    public static function close() 
    {
        if (self::$instance != null)
            self::$instance = null;
    }
    
    /**
     * Retorna a tabela correspondente a chave informada. 
     * 
     * @param string $chave Nome da chave do array $tabelas que armazena a tabela a ser retornada
     * @return string
     */
    public static function getTabela($chave)
    {
        return self::$tabelas[$chave];
    }

}
?>
