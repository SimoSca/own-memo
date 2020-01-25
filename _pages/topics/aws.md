---
layout:     default
title:      AWS Amazon
permalink:  /topics/amazon-aws/
---


Sections:

- [IAM](#iam)
- [TROUBLESHOOTS](#troubleshoots)


IAM
---

Esempio di creazione utenze `Admin` con **MFA** device.

Questo link [https://blog.jayway.com/2017/11/22/aws-cli-mfa/](https://blog.jayway.com/2017/11/22/aws-cli-mfa/)
porta a una risorsa che spiega bene come fare (ho provato e Funzia!).
Oltre alla logica utilizzata, vi e' poi questo link [https://github.com/matsev/aws-cli-mfa](https://github.com/matsev/aws-cli-mfa),
che porta al repo git col file di esempio da caricare in `CloudFormation` (vedi in questo repository la sezione `_codes`).

Alcune letture utili:

- [https://github.com/broamski/aws-mfa](https://github.com/broamski/aws-mfa),
    tool per la cli (script `python`)

- [aws-resource-iam-role.html](https://docs.aws.amazon.com/en_us/AWSCloudFormation/latest/UserGuide/aws-resource-iam-role.html), 
    sito ufficiale di `aws` in cui spiega sinteticamente l'uso dei ruoli
    
- [enforce-mfa-other-account-access-bucket/](https://aws.amazon.com/it/premiumsupport/knowledge-center/enforce-mfa-other-account-access-bucket/),
    altra modalita' che se pur in maniera differente, in ogni caso suggerisce alcuni template di `CloudFormation`
    
- [authenticate-mfa-cli](https://aws.amazon.com/it/premiumsupport/knowledge-center/authenticate-mfa-cli/),
    altra modalita' per raggiungere lo scopo di cui sopra, ma ad `hoc` per utente. 
    Contiene pero' delle info relative a come utilizzare il `token` e impostare un duration in seconds
    
- [id_credentials_mfa_enable_cliapi.html](https://docs.aws.amazon.com/en_us/IAM/latest/UserGuide/id_credentials_mfa_enable_cliapi.html),
    istruzioni via cli
    


TROUBLESHOOTS
--------------

### Istanza ec2: ssh access denied

Dopo aver fatto delle modifiche a filesystem d'esecuzione dell'istanza ec2, 
e' capitato che al `reboot` non riuscissi piu' ad accedere via ssh... 

In primis avendo a disposizione molti `snapshots`, anzitutto volevo essere certo di poter eventualmente ripristinare la situazione da uno precedente,
cosi' ho provato a creare un'immagine `IAM` e poi da essa lanciare una nuova istanza `ec2` e... boom! il gioco e' fatto e funzionante!
(per dettagli vedere [https://www.edureka.co/blog/restore-ec2-from-snapshot/](https://www.edureka.co/blog/restore-ec2-from-snapshot/)) 


Ora inizia la parte piu' interessante, ovvero non come svolgere un `restore` da uno `snapshot`, bensi' come **riparare** l'istanza non piu' funzionante!

Anzitutto ricordiamoci una cosa:

> Un istanza ec2 di fatto e' un oggetto `AWS`, sul quale vengono `attached` uno o piu' volumi EBS, di cui uno solge da `root` (`/`)


Cosi', se si sa come ripristinare sull'istanza non piu' funzionate (EC2-A):

- accedere alle configurazioni di EC2-A e annotare l'id del volume-ebs (per poterlo identificare nei prossimi passaggin) 
    e anche la sua partizione di mount (es `/dev/xvda`), che servira' quando alla fine si dovra' ripristinare la macchina stessa

- creare nuova istanza ec2 qualsiasi (EC2-B)

- eseguire detach del volume (per ora attaccato a EC2-A) e attaccarlo a EC2-B

- esegire mount del volume (es `/mnt/restore`)
 
- sistemare il codice in `/mnt/restore/...` cosi' da realizzare revert delle modifiche per fixare il tutto

- eseguire detach da EC2-B e riattach su EC2-A (nel vecchio punto di mount `/dev/xvda`)


> `ATTACH`/`DETACH`
>
> si va nell'elenco volumi, si identifica quello d'interesse (id) e poi col tasto destro si seleziona attach/detach

> Per il mount:
> - vedere le partizioni presenti (`lsblk`) e svolgere mount come indicato in [https://devopscube.com/mount-ebs-volume-ec2-instance/](https://devopscube.com/mount-ebs-volume-ec2-instance/)
> - in caso di problemi di mount attenzione alla partizione usata: con `lsblk` ad esempio `/dev/xvdf1` risulta come blocco di `/dev/xvdf`, 
> quindi va utilizzato `/dev/xvdf1` e non `/dev/xvdf1`, vedi [https://serverfault.com/questions/632905/cannot-mount-an-existing-ebs-on-aws](https://serverfault.com/questions/632905/cannot-mount-an-existing-ebs-on-aws)



Public Bucket S3
-----------------

> Rendere pubblico un bucket potrebbe essere pericoloso: devi essere sicuro che non ci siano assets sensibili!!!

Per pubblicare un bucket s3, ovvero renderlo accessibile da tutto il web seguire i seguenti passaggi:

0 - accedere al pannello dello specifico bucket s3

1 - `Proprieta' > Hosting siti Web Statici` -> devo abilitare hosting di bucket

2 - `Autorizzazioni` > devo inserire una policy bucket tipo:

````
{
    "Version": "2008-10-17",
    "Id": "AllPublic",
    "Statement": [
        {
            "Sid": "Stmt1380877761162",
            "Effect": "Allow",
            "Principal": {
                "AWS": "*"
            },
            "Action": "s3:GetObject",
            "Resource": "arn:aws:s3:::test-laravel-s3/*"
        }
    ]
}
````

3 - `Autorizzazioni` > settare opportunamente l'accesso pubblico


Eventualmente si puo' omettere il punto **(2)** (inserimento della policy), ma in tal caso visto che il file caricato nel bucket di default non risulta `pubblicato`,
e' necessario svolgere azioni extra, ad esempio:

- **pubblicare esplicitamente**, andando nella gestione bucket sul file specifico e pubblicandolo quindi dal pannello

- **programmatically**, ad esempio con **Laravel** e il driver `Storage S3`, si puo' caricare e pubblicare in una sola volta con un comando del tipo:
    ````php
    Storage::disk('s3')->put('/users/'.Auth::user()->uuid.'/avatars/small/'.$filename, fopen($small, 'r+'), 'public');
    ````
  
  