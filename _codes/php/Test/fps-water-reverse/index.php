<html>
<body>

<div id="loom">
		<div>Giri al secondo: <span id="fr">0.5</span></div>
		<input id="fr-range" type="range" min="0" max="20" step="0.03">
		<div>Frame per secondo: <span id="fps">2.3</span></div>
		<input id="fps-range" type="range" min="0" max="20" step="0.03">
	<center>
	<div class="canvas-container">
		<div class="title">Canvas della telecamera</div>
		<canvas id="myCanvasVisual"></canvas>
	</div>
	<div class="canvas-container">
		<div class="title">Canvas Realv</div>
		<canvas id="myCanvasReal"></canvas>
	</div>
	</center>
</div>

<style>
	body{
		background-color: #ECEC88;
	}

	.canvas-container{
		display: inline-block;
		width: 30%;
		margin: auto;
	}	

	#loom{
		width: 100%;
	}

	/*#myCanvas{
		position: absolute;
		top: 50px;
		left: 50%;
		margin: 10px auto;
		padding: 0;
		width: 300px;
		height: 300px;
	}*/
</style>

<script>

window.requestAnimFrame = (function(callback) {
   return 	window.requestAnimationFrame ||
   			window.webkitRequestAnimationFrame || 
   			window.mozRequestAnimationFrame || 
   			window.oRequestAnimationFrame || 
   			window.msRequestAnimationFrame ||
        	function(callback) {
        		window.setTimeout(callback, 1000 / 60);
    		};
})();



// disegno la canvas
var drawCanvas = function(id){
	// creo il contesto della canvas, e mi sposto al suo centro
	var canvas = document.getElementById(id);
	var ctx = canvas.getContext('2d');

	//NB: 	il css determina la dimensione del box, ma non del sistema di coordinate, ovvero della griglia
	//		per questo devo settare esplicitamente le dimensioni della canvas
	canvas.setAttribute('width', '300');
	canvas.setAttribute('height', '300');

	var transX = canvas.width * 0.5,
    	transY = canvas.height * 0.5;
	// disegno la finestra
	ctx.translate(transX, transY);
	ctx.beginPath();
	ctx.arc(0, 0 , transX,0,2*Math.PI, false);
	ctx.fillStyle = 'green';
	ctx.fill();
	ctx.closePath();
	ctx.beginPath();
	ctx.arc(transX-20, 0 ,20 ,0,2*Math.PI, false);
	ctx.fillStyle = 'blue';
	ctx.fill();
	ctx.closePath();

	this.canvas = canvas;
	this.ctx = ctx;

	// angle in radianti, e rotazione antioraria
	this.rotate = function(angle){
		this.canvas.setAttribute('style','transform:rotate(-'+angle+'rad);');	
	}
}


var canv = {
	'visual': new drawCanvas('myCanvasVisual'),
	'real': new drawCanvas('myCanvasReal')
}


// parametri globali
// frequenza angolare : giri al secondo. Ovvero frequenza di rotazione
var _fR = 0.5 ;
var _startTime = new Date().getTime();
// la videocamera mostra fpsV immagini al secondo
var _fpsV = 2.3 ;
var _timeData = {
	fps: _fpsV,
	startTime: 0
}

function render(){
	// deltaT e' in millisecondi, ma io voglio usare i secondi
	_timeData.elapsedTime = ( (new Date()).getTime() - _startTime ) /1000;
	// tempo congelato
	var tf = time_freez(_timeData);
	// l'angolo e' una funzione lineare del tempo: theta = omega * t
	var thetaVisual = tf * 2 * Math.PI * _fR; 
	var thetaReal = (_timeData.startTime + _timeData.elapsedTime) * 2 * Math.PI * _fR;
	canv.visual.rotate(thetaVisual);
	canv.real.rotate(thetaReal);
}


//---- PARTE FISICA
// il tempo di visualizzazione, essendo frame per seconds, non e' continuo ma discreto!
// se ad esempio ho 4 fps, e al tempo zero faccio la prima istantanea, allora essa sara' congelata per un tempo [0; 0.25), la seconda sara' tra [0.25; 0.5) e cosi' via
// in pratica discretizzo il tempo in sezioni la cui durata e' 1/fps ciascuna.
// Di conseguenza devo trovare la funzione che mi discretizza il tempo: a partire dal tempo del primo scatto t0  esso rimane fino t0 + 1/fps : per me la posizione, ovvero l'istantanea,
// in questo lasso di tempo e' solo quella di t0!!!

// tempo "congelato", ovvero il tempo a cui in teoria scatto la foto
function time_freez(obj){

	var t0 = typeof obj.startTime !== 'undefined' ? obj.startTime : 0 ;
	var t = obj.elapsedTime;
	if( (t-t0) <= 0 ) return 0;
	// ottengo il numero intero di frame, ovvero di foto
	var frameNum = Math.floor(obj.fps * (t-t0));
	// ritorno il tf, ovvero il tempo di concelamento
	return (t0 + frameNum/obj.fps);
}

(function animloop(){
  render();
  window.requestAnimFrame(animloop);
})();

// extra: uso un cursore tanto per giocherellare

document.getElementById('fr-range').onchange = function(e){
	document.getElementById('fr').innerHTML = this.value;
	_fR = this.value;
}

document.getElementById('fps-range').onchange = function(e){
	document.getElementById('fps').innerHTML = this.value;
	_timeData.fps = this.value;
}

</script>

</body>
<html>