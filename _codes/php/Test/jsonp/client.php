    <html>
    <head>
   </head>
     <body>

     <h2>Provala ad esempio da LOCALHOST<h5>(magari controllando la console del browser...)</h5></h2>

    <input type="button" id="001" onclick="gO('server.php', 'getCompany')" value="Company Local"  />
    <input type="button" id="002" onclick="gO('http://ilnullatore.altervista.org/public/Test/jsonp/server.php', 'getCompany')" value="Company Remote"  />
    <input type="button" id="003" onclick="getJSON('server.php?cumpa=InSuperStar', getCompany, myJSONerror )" value="Company getJSON Local"  />
    <input type="button" id="004" onclick="getJSON('http://ilnullatore.altervista.org/public/Test/jsonp/server.php?cumpa=InSuperStar', getCompany, myJSONerror )" value="Company getJSON su Nullatore"  />

    <br/>
    <h3>
    <div id="101">

    </div>
    </h3>
    <br/>
    <ul>
    <li>Nei primi due pulsanti sono implementate le chiamate in stile JSONP, ovvero chiamate che funzionano anche su altri server (no origin-policy problems!)</li>
    <li>Negli ultimi due esempi invece ho testato una classica chiamata getJSON, che e' una funzione qui creata ad hoc e che sfrutta XMLhttpRequest: 
            in questo caso la chiamata ad un dominio esterno (remote) non funziona! </li>
    </ul>
    <br/>        
    
    <div>Il trucco nella JSONP e' che: <i>The idea of JSONP is actually pretty simple: toss a script tag into the DOM with a reference to a resource that returns JSON data. 
        Have the server return said JSON with "padding" (the "P" part of JSONP) that executes a function wrapping the incoming data. 
        In order for this to work properly, the server API must also support JSONP. </i></div>  



    <script type="text/javascript">

    // errore nelle chiamate getJSON e getJSON_P create sotto
    function myJSONerror(status){ elem.innerHTML='Something went wrong.'; }

    // callback di default
    function getCompany(data){
        var message="The company you work for is "+JSON.stringify(data);
        elem.innerHTML=message;
    }


    var elem=document.getElementById("101");

    // funzione che sfrutta jsonp
    function gO(url, callback){

    script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = url+'?callback='+callback;
    elem.appendChild(script);
    elem.removeChild(script);
    }




    // implementazione JSON //////////////////////////////////////////////////////////////////////////////
    var getJSON = function(url, successHandler, errorHandler) {
      var xhr = typeof XMLHttpRequest != 'undefined'
        ? new XMLHttpRequest()
        : new ActiveXObject('Microsoft.XMLHTTP');
      var responseTypeAware = 'responseType' in xhr;
      xhr.open('GET', url, true);
      if (responseTypeAware) {
        xhr.responseType = 'jsonp';
      }
      xhr.onreadystatechange = function() {
        var status = xhr.status;
        var data;
        // http://xhr.spec.whatwg.org/#dom-xmlhttprequest-readystate
        if (xhr.readyState == 4) { // `DONE`
          if (status == 200) {
            successHandler && successHandler(
              responseTypeAware
                ? xhr.response
                : JSON.parse(xhr.responseText)
            );
          } else {
            errorHandler && errorHandler(status);
          }
        }
      };
      xhr.send();
    };

    
    </script>
    </body>
    </html>