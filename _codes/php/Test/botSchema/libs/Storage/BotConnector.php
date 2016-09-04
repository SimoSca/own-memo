<?php

namespace Logo\Storage;

/**
 * Trait per la connessione al DB.
 * 
 */
trait BotConnector {
    
    // MySql DB Connection Data
    private $host = 'localhost';
    private $username = 'Logo';
    private $password = '123stella';
    private $db = 'Logo_simone_scardoni';
    private $charset = 'utf8mb4';
    
    // PDO instance
    protected $pdo;

    /**
     * Inizializzazione della connessione
     */
    public function initConnector() {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $this->host, $this->db, $this->charset);
        try{
            $this->pdo = new \PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );//Error Handling
        } catch (\PDOException $ex) {
            $this->manageException($ex, "PDO Connection Error");
        }
    }
    
    
    /**
     * Chiusura della connessione
     */
    public function destroyConnector() {
        $this->pdo = null;
    }
    
    
    /**
     * Metodo di supporto per la gestione delle eccezioni:
     * 
     * @param Exception $ex
     * @param string $message messaggio della generica Eccezione
     */
    protected function manageException($ex, $message = null){
        // eventuale log dell'eccezione $ex, ad esempio implementato via MONOLOG.
        if(!is_null($message))throw new \Exception($message);
    }

}
