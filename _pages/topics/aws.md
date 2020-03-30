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



CLOUDFRONT
----------

- [CloudFront as reverse proxy](https://medium.com/@davidgurevich_11928/cloudfront-as-a-reverse-proxy-fb6a8dcbed96)

- [CloudFront Pricing advantages](https://aws.amazon.com/blogs/networking-and-content-delivery/dynamic-whole-site-delivery-with-amazon-cloudfront/)

- [CloudFront Cache and Distribution params](https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/distribution-web-values-specify.html#DownloadDistValuesForwardHeaders)

- [Mod Proxy](https://www.digitalocean.com/community/tutorials/how-to-use-apache-as-a-reverse-proxy-with-mod_proxy-on-centos-7), ovvero come utilizzare cloudfront: gestione lato **origin server**

Some Test:

> Supponiamo che il CNAME impostato su cloudfront sia a.com e che l'origin di cloudfront sia b.org; a.com ovviamente e' un CNAME al DNS di cloudfront

inizialmente, ovvero che le configurazioni di defaulti di CloudFront, sull'origin server avevo un problema perche' eventuali
redirect lato applicativo rendirizzavano ad esempio a `b.org/auth/token`, anziche' a `a.com/auth/token`, esponendo cosi' l'endpoint che
cloudfront doveva nascondere.
Facendo poi alcune prove ho visto che nelle configurazioni del `behaviour`, nella sezione degli Headers, e' necessario mettere almeno l' `Host` header in whitelist,
in questo modo cloudfront riesce a proxare anche quello Header e cosi' i redirect avvengono correttamente.

Altro punto e' relativo all' https... come per molti proxy, visto che i certificati li gestisco direttamente su cloudfront, 
voglio evitare di fare questo lavoro anche lato server... per questo su cloudfront ho forzato l'origin per essere contattata solo sulla porta 80.
Con questa configurazione pero' ho il problema che `a.com` e' in HTTPS, ma `b.org` risponde alla porta 80, quindi in HTTP.
Fortunatamente per ovviare a questo fatto mi e' bastato impostare `HTTPS on` nel VirtualHost di `apache`.

Rimane il fatto che se sul server voglio filtrare per IP, ovvero fare in modo che solo cloudfront possa contattare il server, senza esporlo,
devo aggiungere tutti gli ip di cloufront, ottenibili con 
````
curl https://ip-ranges.amazonaws.com/ip-ranges.json | jq -r '.prefixes[] | select(.service=="CLOUDFRONT") | .ip_prefix'
curl https://ip-ranges.amazonaws.com/ip-ranges.json | jq -r '.prefixes[] | select(.service=="CLOUDFRONT") | .ip_prefix'  | tr '\n' ' '
````

ma anche cosi' ho due problemi:

- questa lista deve essere aggiornata periodicamente
- chiunque sia a conoscenza di `b.org`, puo' creare una propria distribution che punti a tale dominio 

Per la seconda casistica, potrei quantomeno aggiungere in cloufront un custom header contenente un token di convalida e poi verificarlo lato server,
ovvero se il token e' `X-CUSTOM-TOKEN` con valore `ab12`, allora lato server per accettare le richieste dovrei verificare la presenza e il valore di questo token.

Altra eventualita' e' l'impiego di un loadbalancer (`ELB` o `ALB`), cosi' lato server potrei svolgere un filtro piu' semplice.


### Extra

Alcuni tipi di configurazioni `apache` particolari:

**HTTPS if CloudFront**

````
SetEnvIf User-Agent ^Amazon Cloudfront$ HTTPS=on
````

**Force Host Header**

Inside `VirtualHost`:

````
SetEnvIf X-AwsCloudFront-Host ^(.+)$ custom_override_host=$1
SetEnvIf X-AwsCloudFront-Host ^$ custom_override_host=<my-default-host.com>
RequestHeader set Host %{custom_override_host}e
````

**Forward-For**

Ricordarsi che dietro proxy, e' bene loggare l'ip di origine della richiesta, ad esempio con:

````
LogFormat "%v:%p %h %{X-Forwarded-For}i %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"" proxy
CustomLog "logs/access_log" proxy
````

> Note dal servizio d'assistenza:
> - [https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/RequestAndResponseBehaviorCustomOrigin.html#RequestCustomIPAddresses](https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/RequestAndResponseBehaviorCustomOrigin.html#RequestCustomIPAddresses)
> - [https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/lambda-event-structure.html](https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/lambda-event-structure.html)

### Evoluzione

Col trick dell' `X-Forwarded-For` funziona, ma non ancora come dovrebbe. 
In sostanza lo scopo e' che l'applicativo non debba sapere che davanti a lui vi sono uno o piu' proxy 
per evitare che in esso vengano forzate logiche come il check dell' `X-Forwrded-For` e simili.
In pratica bisogna eliminare quandomeno la parte di architettura intrinseca per fare in modo che l'`X-Forwarded-For` e il `$_SERVER['REMOTE_ADDR']`
non vengano influenzati dall'architettura, che in questo caso si traduce nel fare in modo che CloudFront e il Load Balancer dabbano "scomparire" lato applicativo,
termine riassumibile col concetto di **Trusted Proxies**.

Nel caso specifico di una configurazione `apache`, questo vuol dire utilizzare il modulo `mod_remoteip` per rimuovere dal most right side dell' `X-Forwarded-For`
i `trusted proxies`, come illustrato in [https://stackoverflow.com/questions/51393782/how-to-get-client-ip-of-requests-via-cloudfront](https://stackoverflow.com/questions/51393782/how-to-get-client-ip-of-requests-via-cloudfront).

Tutto questo avviene per evitare lo `spoof` dell' `X-Forwarded-For`, con questo esempio di configurazione: 

````
RemoteIPHeader X-Forwarded-For
RemoteIPTrustedProxyList conf/trusted-proxies.lst
RemoteIPInternalProxyList conf/internal-trusted-proxies.lst
#RemoteIPProxyProtocol On #with ELB?
````

Dove il file `trusted-proxies.lst` potrebbe essere aggiornato periodicamente mediante un cron che esegua il comando che ho inserito sopra,
e che successivamente ho scoperto essere identico a quello suggerito a [questa pagina](https://docs.aws.amazon.com/general/latest/gr/aws-ip-ranges.html).
Il file `internal-trusted-proxies.lst` invece serve per rimuovere gli ip di network interno, ad esempio potrei avere un `10.0.1.0/8` o simili se il load balancer usa una subnet interna.

> Esempio di cron riportato in [_codes/aws/](_codes/aws/) 

Infine terminate queste operazioni, sempre in un eventuale script di aggiornamento, non bisogna dimenticare di inserire un reload di apache,
ad esempio su AMI linux con `systemctl reload httpd` (meglio un reload che un restart, ma magari aggiungere prima un check con `httpd -t`). 

Vedi anche [https://aws.amazon.com/it/premiumsupport/knowledge-center/elb-capture-client-ip-addresses/](https://aws.amazon.com/it/premiumsupport/knowledge-center/elb-capture-client-ip-addresses/).

> Nota: per lo `spoof` devo anche garantire che effettivamente il server risponda solo ed esclusivamente al loadbalancer,
> altrimenti perderei il trust. Fortunatamente questo posso realizzarlo dando all' ALB un security group (diciam ELB-sg) 
> e quindi sul security group dell'istanza EC2 impostare come regola inbound del traffico HTTP(S) l' ELB-sg come unico attendibile. 

### Load Balancer (ALB)

L'impiego del loadbalancer con cloudfront richiede alcune accortezze, principalmente relative alla procedura di creazione dello stesso.
Vediamo:

- ho creato un `ALB` (application load balancer)
- come tipologia ho usato un `internet-facing`: inizialmente ho usato `internal`, ma poi non me lo faceva selezionare tra le `origin di cloudfront`
- bisogna impostare almeno due subnet, di cui una con internet gateway

Ora la cosa curiosa relativa alle subnet: io ne ho impostata una con `internet gateway`, quindi definiamola PUBBLICA e una senza, che considero PRIVATA.
Con questa configurazione cloudfront (o meglio il loadbalancer), a volte riusciva a soddisfare la richesta, altre volte no.
Dopo qualche ragionamento ho capito che sostanzialmente falliva perche' provava a utilizzare la subnet PRIVATA, 
quindi ho risolto creando una nuova subnet con `internet gateway` e andandola a sostituire a quella privata.

> TODO: capire meglio perche' con la privata non funzionava.
  
Lato web server l'unica cosa degna di nota e' il fatto che l' ALB lo contatta direttamente tramite IP, 
quindi su apache va a intercettare l'eventuale `<VirtualHost _default_:80> ... </VirtualHost>`.


### LOAD BALANCER (ALB, ELB)

Per i load balancer ho trovato questa guida che pone un esempio su come gestirlo per avere separazione tra subnet privata e pubblica:

- [https://itellity.wordpress.com/2014/09/11/creating-an-elb-load-balancer-with-private-subnet-instances-in-a-vpc/](https://itellity.wordpress.com/2014/09/11/creating-an-elb-load-balancer-with-private-subnet-instances-in-a-vpc/)

e questo semplice ma efficace video

- [https://www.youtube.com/watch?v=9Ut0cEWV9NQ](https://www.youtube.com/watch?v=9Ut0cEWV9NQ)

Questo ha uno schemino interessante:

- [http://thebluenode.com/exposing-private-ec2-instances-behind-public-elastic-load-balancer-elb-aws](http://thebluenode.com/exposing-private-ec2-instances-behind-public-elastic-load-balancer-elb-aws)



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

# + permessi, in particolare il PutObjectAcl mi serve per poter rendere pubblico/privato il singolo oggetto (file)
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Sid": "VisualEditor0",
            "Effect": "Allow",
            "Action": [
                "s3:PutObject",
                "s3:GetObject",
                "s3:ListBucket",
                "s3:DeleteObject",
                "s3:PutObjectAcl"
            ],
            "Resource": [
                "arn:aws:s3:::test-laravel-s3/*",
                "arn:aws:s3:::test-laravel-s3"
            ]
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



CloudFormation and S3
---------------------
 
La necessita' e' di rendere disponibili delle immagini nella firma mail puntano direttamente a una bellissima sottodirectory “tildeimages” del sito,
e quello che volevo era fare in modo che quelle immagini fossero sempre raggiungibili anche in caso di down del sito.

Ho risolto con CloudFront, creando un origine che punta a un bucket s3 dedicato a ospitare solo queste immagini, e successivamente su CloudFront ho assegnato un nuovo behavior per gestire i path di tipo  “/tildeimages/*”, ed ecco i passaggi:

### Creazione Bucket s3

Creato  un bucket s3 (mysite-static) e caricato I files nel path “s3://mysite-static/firme/tildeimages”. Ho aggiunto “firme” come directory di partenza cosi’ da poter mappare in maniera esplicita le directory mediante il behavior, 
cosi’ se un domani dovessimo aggiungere altri enpoint da staticizzare potremmo sempre usa questo bucket.
La cosa importante che sottolineo e’ il fatto di non aver reso pubblico il bucket, 
ne tantomeno di averlo configurato per rispondere come semplice web server perche’ ho sfruttato gli “Origin Access Identity” di aws (e che fino a poco fa non conoscevo).


### CloudFront – Origin

Sinteticamente ho configurato cosi

    - Origin Domani Name: select specific s3 bucket 

- Origin path: /firme


- Restrict Bucket Access: yes
- Origin Access Identity (create or use an existing... depends on you usage)
- Grant Read Permission on Bucket: (first time you could select Yes, so that will be generated the policy as above)
                                 in other cases take "No, I Will Update Permissions"

Quindi per garantire l’accesso al bucket senza sforzo ho usato un “Origin Access Identity”, cosi’ da non poterlo raggiungere in alcun modo.
La cosa interessante e’ che in questa fase se in “Grant Read Permission” si sceglie “Yes, …” allora nel bucket s3 viene creata la policy

Posso laciare gli accessi pubblici bloccati, perche' su cloudfront ho impostato un Origin Access Identity. Ma nelle policy bucket devo avere
````
{
    "Version": "2008-10-17",
    "Id": "PolicyForCloudFrontPrivateContent",
    "Statement": [
        {
            "Sid": "1",
            "Effect": "Allow",
            "Principal": {
                "AWS": "arn:aws:iam::cloudfront:user/CloudFront Origin Access Identity XXXXXXXXXX"
            },
            "Action": "s3:GetObject",
            "Resource": "arn:aws:s3:::mysite-static/*"
        }
    ]
}
````

Dove XXXXXXX lo trovo nelle Origin Access Identity su CloudFront. 


### CloudFront – Beavior

Sinteticamente le principali configurazioni:

- Path Pattern: tildeimages/*

- Origin or Origin Group: (what created previously)
- Object Caching: selected "Customize" and taked the defaults


E questo e’ tutto.

Due note: 

-   inizialmente dopo aver creato il bucket e configurato il tutto, avveniva un redirect all’url del bucket, ma questo e’ un problema noto e che si risolve automaticamente dopo al + un’ora dalla creazione del bucket (https://forums.aws.amazon.com/thread.jspa?threadID=216814).
-   Con questo sistema non e’ possibile customizzare i Response Headers (ad esempio il Server risulta essere s3 anziche’ l'eventuale server impostato con apache2/nginx), quindi eventualmente bisognerebbe collegare una LambdaEdge, che penso abbia un costo irrisorio calcolando che non penso avremo milioni di requests per recuperare le immagini nella firma mail 😊

