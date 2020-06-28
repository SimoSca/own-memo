---
title:      Services & Platforms
permalink:  /developer/services-platforms/
---


Alcuni servizi/piattatorme che potrebbero tornare utili quando si sviluppa.

### Check

- [mxtoolbox.com](https://mxtoolbox.com/SuperTool.aspx?action=dns%3alogotel.it&run=networktools), per check su network, spf, etc.


### Logging

- [Sentry](https://sentry.io/welcome/), sembra un servizio molto buono e che supporta molti linguaggi. Il piano grautito va bene in fase di sviluppo di singoli progetti, ma e' abbastanza limitao.
- [Timber](https://timber.io/), molto bello: l'interfaccia sembra interessante ed ha un buon sistema di API, che compensa il fatto di non supportare molti linguaggi.
- [LogSentinel](https://logsentinel.com/), come `Timber`, ma non ho visto come sia la GUI
- [Jkoolcloud](https://www.jkoolcloud.com/product/jkql-query-language/), non l'ho provato perche' sembra molto complicato
- [Fluentd](https://www.fluentd.org/), sembra complicato pertanto non lo prendo in considerazione per un piccolo progetto
- [Kafka](https://kafka.apache.org/index.html), lo cito per completezza: sembra interessante ma non avendo tempo a disposizione non ho approfondito.


### Databases

- [Firebase](https://firebase.google.com/), l'ho visto citato in alcuni progetti, ma non ho approfondito



ELK
---

- creazione account x Elastic e Kibana

- creare un tipo di indice: ad es logs/ o projects/ oppure direttamente noidivittoria/
    ```` 
    PUT {{ELASTICSEARCH_HOST}}/documentale
    ````
  
- eventualmente creare pipelines:
    ````
    PUT {{ELASTICSEARCH_HOST}}/_ingest/pipeline/attachment
    BODY
    {
        "description": "Extract attachment information",
        "processors": [
            {
                "attachment": {
                    "field": "data"
                }
            },
            {
                "json": {
                    "field": "metadata"
                }
            }
        ]
    }
    ````
  
- in caso di pipeline, va specificata durante l'inserimento della entry:
    ````
    PUT {{ELASTICSEARCH_HOST}}/documentale/_doc/my_id?pipeline=attachment
    ````
  
- esempio complesso di query:
    ````
    GET {{ELASTICSEARCH_HOST}}/documentale/_search
    BODY 
    {
      "size": 10,
      "_source": {
        "excludes": [
          "attachment.content",
          "data"
        ]
      },
      "query": {
        "bool": {
          "should": [
            {
              "match_phrase": {
                "metadata.tags": "Due Tag"
              }
            },
            {
              "match_phrase": {
                "metadata.tags": "Dues Tag"
              }
            }
          ]
        }
      }
    }
    ````
  
Quindi per i log di apache posso fare cosi':

- uso il file fisico come sorgente input (/var/log/....)

- uso un collettore (logstash, filebeat, rsyslog, etc)

- il collettore oltre all'input puo' avere un parser

- nel collettore va specificato l'output, che sostanzialmente e' l'endpoint di elastic search



### LOGSTASH

Esempio di docker-compose:

````yaml
service:
    logstash:
        image: docker.elastic.co/logstash/logstash:7.6.2
        volumes:
            - ./overrides/tests/logstash/config/logstash.yml:/usr/share/logstash/config/logstash.yml:ro
            # - ./overrides/tests/logstash/config/:/usr/share/logstash/config/:ro
            - ./overrides/tests/logstash/pipeline:/usr/share/logstash/pipeline:ro
            - ./common-logs/:/logstash-logs/
````

Esempio di configurazione (`config/logstash.yml`):

````yaml
---
## Default Logstash configuration from logstash-docker.
## from https://github.com/elastic/logstash-docker/blob/master/build/logstash/config/logstash-oss.yml
#
http.host: "0.0.0.0"
#path.config: /usr/share/logstash/pipeline

# :error=>"Elasticsearch Unreachable: [http://elasticsearch:9200/]
xpack.monitoring.enabled: false


#  Successfully started Logstash API endpoint {:port=>9600}



# original config file
#http.host: "0.0.0.0"
#xpack.monitoring.elasticsearch.hosts: [ "http://elasticsearch:9200" ]
````

Esempio di pipeline (`pipeline/logstash.conf`):

````smartyconfig
input {
  # se abilitato [logstash.inputs.beats    ][main] Beats inputs: Starting input listener {:address=>"0.0.0.0:5044"}
  #beats {
  #  port => 5044
  #}

    # questo server per evitare:START, creating Discoverer, Watch with file and sincedb collections;
    file {
      path => "/logstash-logs/apache2/*.log"
      #start_position => "beginning"
      sincedb_path => "/dev/null"
    }
}


filter {
  if [path] =~ "access" {
    mutate { replace => { "type" => "apache_access" } }
    grok {
      match => { "message" => "%{COMBINEDAPACHELOG}" }
    }
  }
  date {
    match => [ "timestamp" , "dd/MMM/yyyy:HH:mm:ss Z" ]
  }
}

output {
    elasticsearch {
        hosts => ["https://XXXX:9200"]
        user => "<elasticsearch_user>"
        password => "<elasticsearch_pass>"
        # se non specificato, crea in automatico un indice del tipo logstash-aaaa.mm.gg
        #index => "linux-logs-test"
    }
}
````


Elastic Examples

````
GET _search
{
  "query": {
    "match_all": {}
  }
}

GET /_cat/indices

GET /_ingest/pipeline

PUT /test-index-rsyslog

GET /_cat/indices/test-index-rsyslog

GET /test-index-rsyslog

POST /test-index-rsyslog/_doc/
{
    "test" : "hello",
    "message" : "trying out Elasticsearch"
}
````

In `Management > Kibana` devo creare un nuovo `index-pattern` per poter visualizzare i logs nella sezione `Discover`,
ma la cosa importante da notare e' che posso farlo solamente DOPO aver inserito almento un record per tale index.
Quindi a quel punto posso usare un index-pattern del tipo `test-index-*`
