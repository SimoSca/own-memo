<?php

/**
 * Plugin Alfred per la gestione del comando "password".
 *
 * La classe salva nel membro privato $api un'istanza di 
 * 
 *      new \Logo\Storage\PasswordStorage();
 * 
 * per interagire col database.
 * 
 */

class PasswordPlugin extends Plugin{
    
    public $command = "password";
    
    /**
     *
     * @var \Logo\Storage\PasswordStorage per gestione del database
     */
    private $api;

    // do the action and return the response as string
    public function doCommand($params){
        
        try{
            $this->api = new \Logo\Storage\PasswordStorage();
        } catch (\Exception $ex) {
            return "Alfred dice: \npurtroppo non &egrave; possibile acccedere alle informazioni.";
        }
        
	$command = $params[1];
        $countError = "Alfred dice:\n il numero di parametri inseriti per questo comando &egrave; errato. \nSi prega di controllare.";
        
	switch ($command) {
            case 'aggiungi':
                if(count($params) !== 6){
                    return $countError;
                }else{
                    return $this->pwdAggiungi($params[2], $params[3], $params[4], $params[5]);
                }
                break;
                
            case 'lista':
                return $this->pwdLista();
                break;
            
            case 'cancella':
                if(count($params) === 4){
                    return $this->pwdCancella($params[2], $params[3]);
                }else{
                    return $countError;
                }
                break;
            
            default:
                if(count($params) === 3){
                    return $this->pwdUtentiViaClienteServizio($params[1], $params[2]);
                }else{
                    return $countError;
                }
                break;
	}
        
    }
    
    
    /**
     * Gestione parametro "aggiungi"
     * 
     * @param string $nome      nome Cliente
     * @param string $servizio  servizio da aggiungere
     * @param string $utente    nome Utente
     * @param string $password  password utente per il servizio specificato
     * @return string
     */
    private function pwdAggiungi($nome, $servizio, $utente, $password){
        
        // ottengo l'utente
        $user = $this->api->ottieniUtente($nome, $utente);
        
        // se non esiste, allora lo creo
        if(count($user) === 0){
            $user = $this->api->creaUtente($nome, $utente);
        }
        
        // controllo se per l'utente e' gia' definito il servizio
        $yetSetted = false;
        foreach($user as $u){
            if(isset($u['servizio']) && $u['servizio'] === $servizio){
                $yetSetted = true;
            }
        }
        
        // gestione casistiche
        if($yetSetted){
            return "Alfred dice: \n$utente di $nome ha gi&agrave; una password $servizio.";
        }else{
            $sql = $this->api->creaServizio($user[0]['id'], $servizio, $password);
            if($sql){
                return "Alfred dice: \ninserita con successo password $servizio per $utente di $nome.";
            }else{
                return "Alfred dice: \ncomando non eseguito. \nSi prega di riprovare.";
            }
        }
        
    }
    
    
    /**
     * Gestione parametro "lista"
     * 
     * @return string
     */
    private function pwdLista(){
        
        // interrogo il database
        $rows = $this->api->ottieniLista();
        
        // stringa di default
        $response = "Alfred dice: ";
        
        if(!$rows) return $response."\npurtroppo non riesco ad ottenere la lista.\nRiprova piu tardi.";
        
        foreach ($rows as $entry){
            extract($entry);    
            // Se ho cancellatto tutti i servizi di un utente, esso rimane ancora nella tabella UTENTI,
            // ma ai fini della lista non serve visualizzarlo.
            if($servizio == '') continue;
            
            $response .= "\n $nome $utente $servizio ****";
        }
        
        return $response;
        
    }
    
    
    /**
     * Ottengo tutti gli utenti di un cliente su uno specifico servizio
     * 
     * @param string $nome        nome Cliente
     * @param string $servizio    nome Servizio
     * @return string
     */
    private function pwdUtentiViaClienteServizio($nome, $servizio){
        
        // interrogo il database
        $user = $this->api->ottieniUtentiViaClienteServizio($nome, $servizio);
        $ret = "Alfred dice:";
        
        // gestisco le casistiche
        if(!$user){
            return $ret."\nnon sono presenti utenti $servizio per $nome.";
        }else{
            foreach ($user as $u){
                extract($u);
                $ret.= "\n$nome $servizio $utente $password";
            }
            return $ret;
        }
        
    }
    
    
    /**
     * Gestione parametro "cancella"
     * 
     * @param string $nome      nome Utente
     * @param string $servizio  nome Servizio
     * @return string
     */
    private function pwdCancella($nome, $servizio){
        $ret = "Alfred dice: ";
        $retError = $ret."\npurtroppo &egrave; avvenuto un errore di cancellazione.";
        try{
            $sql = $this->api->cancellaServizio($nome, $servizio);
            $ret = ($sql) ? $ret."\ncancellazione avvenuta con successo." : $retError ;
            return $ret;
        } catch (\Exception $ex) {
            return $retError;
        }
        
    }


    public function help($params = null){
        
	return "Utilizzo:"
            . " \n@alfred ".$this->command 
                ." aggiungi NOME SERVIZIO UTENTE PASSWORD - ti permette di aggiungere un SERVIZIO e una relativa PASSWORD per l'UTENTE. NOME &egrave; il nome Cliente. "
            . " \n@alfred ".$this->command 
                ." NOME SERVIZIO - ti permette di ottenere un elenco di tutti gli UTENTI e le loro PASSWORD relativamente al SERVIZIO specificato. NOME &egrave; nome Cliente. "
            . " \n@alfred ".$this->command 
                ." lista - ti permette di visualizzare un elenco completo degli UTENTI e i relativi SERVIZI. "
            . " \n@alfred ".$this->command 
                ." cancella NOME SERVIZIO - passando il NOME Cliente e il SERVIZIO da cancellare ti permette di eliminare tutte le voci registrate su tale SERVIZIO."
            . "\n\n Attualmente sul singolo UTENTE non sono supportate opzioni di \"modifica\" PASSWORD e \"cancellazione\" SERVIZIO. "
            ;
        
    }

}
