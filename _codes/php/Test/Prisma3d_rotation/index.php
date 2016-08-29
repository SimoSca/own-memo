
<!DOCTYPE html>
<html>
<head>
	<title>Prisma3d</title>
</head>
<style>
@media (orientation: landscape) {
	body{
		background-color: #D0E3F5;
	}
	#container3d{
		min-height: 70vh;
		width: 50vh;
	}
}

@media (orientation: portrait) {
	body{
		background-color: yellow;
	}	
	#container3d{
		min-height: 70wh;
		width: 50wh;
	}
}

#container3d{
	margin: auto;
	background-color: green;
	border: 1px solid black;
	overflow: hidden;
	/* parametri necessari per fare in modo che l'altezza degli elementi che contiene sia il 100% */
	position:relative;
	/* parte degli effetti 3d */
	perspective: 600px;
}

/* le facce e il loro contenitore devono adeguarsi alla finestra principale */
#base, [id*="face-"]{
	width: 100%;
	min-height: 100%;
	/* parametri necessari per fare in modo che l'altezza sia il 100% */
	position: absolute;
	right: 0;
	top: 0;
}

#base{
	transform-style: preserve-3d;
	transform: rotateY(0rad);
}

[id*="face-"]{
	background-color: plum;
	border: 1px solid purple;
}

.faceText{
	font-size: 2em;
	margin: auto;
	vertical-align:middle;
}


</style>

<body>

<p>
	Ricorda: portait per larghezza minore o uguale dell'altezza (verticalizzato) , landscape altrimenti (ovvero rettangolo orizzontale).
</p>

<div id="container3d">

	<div id="base" ></div>

	<!-- <div id="face-1"></div> -->
	<!-- <div id="face-2"></div>
	<div id="face-3"></div> -->

</div>

<div>Angolo: <span id="angle-display">0</span></div>
<input id="angle-range" type="range" min="0" max="6.28" step="0.20" value="0">
<div>(6.2 == 2Pi == 360 grad)</div>




<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.6/hammer.min.js"></script>

<script>

// formula generica per ottenere i parametri utili: se conosco la larghezza (l), che coincide con la largezza della faccia,
// e il numero di facce (n), so dove posizionare il centro e l'angolo che le facce devono formare tra di loro

var prismaData = function(l, n){
	var obj = {};
	// somma angoli interni poligono regolare: 180 * (n -2), ma divido tutto per N:
	// trovo l'angolo tra due facce adiacenti
	obj.angleAdiacent = Math.PI - (2*Math.PI/n);
	// l'angolo al centro tra le normali alle facce, ovvero di quando una faccia deve ruotare sispetto al centro
	obj.angleRotation = 2*Math.PI/n;
	var b = obj.angleAdiacent/2 ;
	console.log(b);
	// raggio circonferenza circoscritta:
	obj.radius = l/(2*Math.cos(b));
	// distanza tra la faccia e il centro della circonferenza circoscritta
	obj.distance = l * Math.tan(b) / 2;
	// mi serve per hammer: traduco lo spostamento in x del mouse/dito nell'angolo di rotazione
	// angle over length
	obj.aOl = obj.angleRotation / l ;
	console.log(obj);
	return obj;
}


var _frame = document.getElementById('container3d');
var _l = _frame.offsetWidth;
var _n = 3;

var _base = document.getElementById('base');
_base.addEventListener('transitionend', function(event) {
    // alert("CSS Property completed: " + event.propertyName);
    this.style.transition = '';
}, false );


// il prisma viene posizionato rispetto a _base,
// pertanto e' la base che viene traslata alla giusta profondita'
var drawPrisma = function(l, n){
	var d = prismaData(l, n);
	// anzitutto sposto l'origine del sistema in modo che coincida con l'origine della circonferenza circoscritta
	// _base.style.transformOrigin = "50% 50% -" + d.distance + 'px';
	_base.style.transform = "translateZ(-" + d.distance + "px)";
	// setto una proprieta che posso usare durante l'aggiornamento della rotazione del div
	_base.himTransformZ = "translateZ(-" + d.distance + "px)";

	for(var i = 0; i < n; i++){
		// gestisco la faccia nel dom
		var name = 'face-' + i;
		var face = document.createElement('div');
		face.setAttribute('id', name);
		_base.appendChild(face);

		// ora applico le opportune trasformazioni
		// l'angolo e' quello formato con l'asse z
		var a  = i * d.angleRotation;
		var z = d.distance * Math.cos(a);
		var x = d.distance * Math.sin(a);
		face.style.transform = "translate3d(" + x + "px,0px," + z +"px) rotateY(" + a + "rad)";
		face.innerHTML = '<div class="faceText">' +  name + "</div>";
	}

	return d;

}

var _pData = drawPrisma(_l,_n);

// in radianti: angolo di rotazione del prisma
var _angle = 0;


document.getElementById('angle-range').oninput = function(e){
	document.getElementById('angle-display').innerHTML = this.value;
	_angle = parseFloat(this.value);
	_base.style.transform = _base.himTransformZ + ' rotateY(' + _angle + 'rad)';
}

// document.getElementById("myDIV").style.transform = "rotate(7deg)";

/////////////// Parte delle gesture via Hammer
// non funziona per movimenti puramente verticali
var hammertime = new Hammer(_frame);
hammertime.on('panmove', function(e) {
    // console.log(e);
    var angle = parseFloat(_angle) + parseFloat(_pData.aOl * e.deltaX);
 	_base.style.transform = _base.himTransformZ + ' rotateY(' + angle + 'rad)';   
});
hammertime.on('panend', function(e){
	var trf = _base.style.transform;
	trf.replace(/rotateY\(.*?(-?\d+)([\.,]\d+)?.*?\)/gi, function(m, g1, g2){
		// angolo attuale
		var v = parseFloat(g1 + g2);
		// modulo 2 PI: con numeri negativi spazzolo tra [-2PI, 2PI]
		// v %= (2 * Math.PI); 
		// console.log(v)
		// angolo al centro del prisma rispetto a una faccia
		var prAngle = _pData.angleRotation;
		// N/D = Q con resto di R
		// console.log(prAngle)
		// resto, positivo o negativo
		var R = v % prAngle;
		// quoziente, positivo o negativo
		var signumV = v/Math.abs(v);
		var Q = signumV * Math.floor( Math.abs(v) / prAngle);
		// console.log('Q: ' + Q + ' , R: ' + R);
		// decido se arrotondare: 
		// se non supero la meta' ampiezza, rimango su quell'angolo
		if( Math.abs(R/(prAngle/2))  < 1  ){
			_angle = Q * prAngle ;
		}else{
			_angle = (Q + signumV) * prAngle;
		}

		_base.style.transition = 'all 1s';
		_base.style.transform = _base.himTransformZ + ' rotateY(' + _angle + 'rad)';   	
		// NB: la transizione viene automaticamente rimossa dall'event listener sopra utilizzato

	});

})

</script>



</body>
</html>