<?php

namespace Logo\Storage;

/**
 *  Classe api per il comando Password.
 * 
 * In generale il comportamento delle query avviene secondo due modalita':
 * 
 * - se la query e' una select
 *   allora viene restituito un array, eventualmente vuoto
 * 
 * - se la query e' una create/delete
 *   allora viene restituito un booleano corrispondente al successo della query
 *   ed eventualmente viene sollevata/gestita un'eccezione via "manageException" del trait BotConnector
 * 
 */
class PasswordStorage{
    
    // guadagno il membro $pdo per svolgere le query al database
    use \Logo\Storage\BotConnector;

    /** coerenza col database */
    private $tables = [
        'user'=>'user', 
        'service'=>'user_services'
    ];

    public function __construct(){
        // Creo l'oggetto PDO tramite BotConnector
        $this->initConnector();
        
        // Verifico l'esistenza delle tabelle
        $this->controllaDB();
    }
    
    /**
     * Se non esistono, creo le tabelle.
     * 
     * Utile solo la prima volta che si esegue questo work:
     * in un contesto reale utilizzerei uno script di inizializzazione da eseguire solo la prima volta.
     */
    private function controllaDB(){
        $sqlUser = "CREATE TABLE IF NOT EXISTS `".$this->tables['user']."` ("
                ."id INT NOT NULL AUTO_INCREMENT,"
                ."nome VARCHAR(20) NOT NULL,"
                ."utente VARCHAR(20) NOT NULL,"
                ."PRIMARY KEY ( id )"
        .")";
        
        $sqlService = "CREATE TABLE IF NOT EXISTS `".$this->tables['service']."` ("
                ."id INT NOT NULL AUTO_INCREMENT,"
                ."id_utente INT NOT NULL,"
                ."servizio VARCHAR(10) NOT NULL,"
                ."password VARCHAR(100) NOT NULL,"
                ."PRIMARY KEY ( id ),"
                ."FOREIGN KEY ( id_utente ) REFERENCES ".$this->tables['user']."(id) ON DELETE CASCADE"
        .") ENGINE=INNODB;";
        
        try{
            $this->pdo->exec($sqlUser);
            $this->pdo->exec($sqlService);
        } catch (\PDOException $ex) {
            $this->manageException($ex, "PDO Tables Creation Error");
        }
        
    }
    
    
    /**
     * Interrogo il DB per ottenere la tabella di JOIN direttamente come array:
     * ogni riga contiene l'utente e un suo servizio.
     * 
     * @param string $nome      nome Cliente
     * @param string $utente    nome utente
     * @return array
     */
    public function ottieniUtente($nome, $utente) {
        $tuser = $this->tables['user'];
        $tservice = $this->tables['service'];
        $sql =  "SELECT $tuser.*, $tservice.servizio, $tservice.password FROM $tuser "
                . "LEFT JOIN $tservice ON $tuser.id = $tservice.id_utente"
                . " WHERE $tuser.nome=? AND $tuser.utente=? ";
        try{
        $sql = $this->pdo->prepare($sql);
            $sql->execute([$nome, $utente]);
            $rows =  $sql->fetchAll(\PDO::FETCH_ASSOC);
        }catch(\PDOException $ex){
            $rows = [];
        }
        
        return $rows;
        
    }
    
    
    /**
     * Creo un nuovo utente
     * 
     * @param string $nome      nome Cliente
     * @param string $utente    nome Utente
     * @return array            
     */
    public function creaUtente($nome, $utente){
        $rows = $this->ottieniUtente($nome, $utente);
        if(count($rows) === 0){
            $sql = "INSERT INTO `" .$this->tables['user']. "` (nome, utente) VALUES (?,?) ";
            $sql = $this->pdo->prepare($sql);
            try{
                $sql->execute([ $nome, $utente ]);
                $rows = $this->ottieniUtente($nome, $utente);
            }catch(\PDOException $ex){
                $this->manageException($ex);
                $rows = [];
            }
        }
        
        return $rows;
        
    }
    
    
    /**
     * Creo un servizio 
     * 
     * @param type $idUtente
     * @param type $servizio
     * @param type $password
     * @return boolean
     */
    public function creaServizio($idUtente, $servizio, $password){
        $sql = "INSERT INTO `" .$this->tables['service']. "` (id_utente, servizio, password) VALUES (?,?,?) ";
        $sql = $this->pdo->prepare($sql);
        try{
            $sql->execute([ $idUtente, $servizio, $password ]);
            return $sql;
        }catch(\PDOException $ex){
            $this->manageException($ex);
            return false;
        }
    }
    
    
    /**
     * Ritorno una lista di utenti selezionati tramite Cliente e Servizio
     * @param type $nome
     * @param type $servizio
     * @return array
     */
    public function ottieniUtentiViaClienteServizio($nome,$servizio){
        $tuser = $this->tables['user'];
        $tservice = $this->tables['service'];
        $sql = "SELECT $tuser.*, $tservice.servizio, $tservice.password FROM $tuser "
                . "LEFT JOIN $tservice ON $tuser.id = $tservice.id_utente"
                . " WHERE $tuser.nome=? AND $tservice.servizio=? ";
        try {
            $sql = $this->pdo->prepare($sql);
            $sql->execute([$nome, $servizio]);
            $rows = $sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $ex) {
            $rows = [];
        }
        
        return $rows;
        
    }
    
    
    /**
     * Ottengo la lista di TUTTI gli utenti, indipendentemente dal Cliente o dal Servizio
     * 
     * @return array
     */
    public function ottieniLista(){
        $tuser = $this->tables['user'];
        $tservice = $this->tables['service'];
        $sql =  "SELECT * FROM $tuser "
                ."LEFT JOIN $tservice ON $tuser.id = $tservice.id_utente "
                ."ORDER BY $tuser.nome, $tuser.utente";
        $sql = $this->pdo->prepare($sql);
        try {
            $sql->execute();
            return $sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $ex) {
            $this->manageException($ex);
            return [];
        }
    }
    
    
    /**
     * cancello un servizio per tutti gli utenti di un dato cliente
     * 
     * @param type $nome
     * @param type $servizio
     * @return boolean
     */
    public function cancellaServizio($nome, $servizio){
        $tuser = $this->tables['user'];
        $tservice = $this->tables['service'];
        $sql = "DELETE $tservice FROM $tuser "
                ."LEFT JOIN $tservice ON $tuser.id = $tservice.id_utente "
                ."WHERE $tuser.nome=? AND $tservice.servizio=?";
        $sql = $this->pdo->prepare($sql);
        try {
            $sql->execute([$nome, $servizio]);
            return $sql;
        } catch (\PDOException $ex) {
            $this->manageException($ex);
            return false;
        }
    }
    
   
    public function __destruct() {
        // distruggo la connessione PDO di BotConnector
        $this->destroyConnector();
    }

}
