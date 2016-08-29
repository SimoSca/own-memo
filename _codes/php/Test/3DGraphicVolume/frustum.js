/**
 * ctx.transform(a, b, c, d, e, f);
 * matrice del tipo: 
 *  a c e
 *[ b d f ] 
 *  0 0 1
 *
 * Nota le coordinate omogenee!
 */

var Frustum = function(canvasId){
	this.c = document.getElementById(canvasId);
	this.ctx = this.c.getContext('2d');
	
	// array contenente i 4 vertici ordinati
	// coppie di punti (array), in formato [x,y]
	this.vtx = [];
	// variabili private utili per scrivere le formule
	var BN, BF, TN, TF, F, N;

	this.setVtx = function(n, bn, tn, f, bf, tf){
		// !importante l'ordine!
		// parto dal bottom sinitro, e in versoorario termino con bottom destro
		// i punti li chiamo con le lettere maiuscole, quindi BN, TN, TF, BF
		this.vtx = [[n, bn, 1],[n, tn,1],[f,tf,1],[f,bf,1]];
	}
	this.updatePoints =  function(){
		var v = this.vtx;
		BN = v[0], TN = v[1], TF = v[2], BF = v[3], N = v[0][0], F = v[3][0];
	}

	// trovo il vertice e lo disegno
	// (linea che trofo intersecando le rete = di)
	this.findVertex = function(){
		this.updatePoints();
		if( (TF[1] - TN[1] < 0.1 ) && (BF[1] - BN[1] < 0.1 ) ) return;
		var y = parseFloat(- TF[1] * BN[1] + BF[1] * TN[1])/parseFloat(BF[1]-BN[1]+TN[1]-TF[1]);
		var x = F + (F-N)*parseFloat(y-TF[1])/parseFloat(TF[1]-TN[1]);
		this.vertex = [x, y];
		// console.log("Vx: %s e Vy: %s", x, y);
		var ctx = this.c.getContext('2d');
		ctx.beginPath();
		ctx.setLineDash([5,2]);
		ctx.moveTo(x, y);
		ctx.lineTo(TF[0],TF[1]);
		ctx.lineTo(BF[0],BF[1]);
		ctx.closePath();
		ctx.stroke();
		ctx.closePath();
	}


	this.toCartesian = function(){
		var c = this.c;
		var w = 300;
		var h = 150;
		c.width = w;
		c.height = h;
		var ctx = c.getContext('2d');
		// vedi matrice trasformazioni omogenee di grafica 3d (ristrette al caso 2d)
		ctx.transform(1,0,0,-1,w/2+0.5, h/2+0.5);
		ctx.beginPath();
		// asse x
		ctx.moveTo(-w/2,0);
		ctx.lineTo(w/2, 0);
		// asse y
		ctx.moveTo(0,-h/2);
		ctx.lineTo(0,h/2);
		ctx.stroke();
		ctx.closePath();
		this.findVertex();
	}


	this.draw = function(){

		this.toCartesian();

		var c = this.ctx;
		c.fillStyle = 'plum';
		c.beginPath();
		c.moveTo(this.vtx[0][0], this.vtx[0][1])
		for(var i = 1; i < this.vtx.length; i++){
			c.lineTo(this.vtx[i][0], this.vtx[i][1]);
		}
		c.closePath();
		c.fill();
	}

	// applico la matrice a tutti i vertici
	this.applyMatrix =  function(m){
		for(var i = 0 ; i< this.vtx.length; i++){
			this.vtx[i] = matrixXvector(m, this.vtx[i]);
		}
	}

	// normalizzo i punti per portarli da coordinate omogenee a coordinate reali
	this.toReal = function(){
		for(var i = 0 ; i< this.vtx.length; i++){
			var d = this.vtx[i].length;
			for(var j = 0; j< d; j++){
				this.vtx[i][j] = parseFloat(this.vtx[i][j]/this.vtx[i][d-1]);
			}
		}
	}

}

// moltiplicazione matrice 3x3 e vettore di 3x1
function matrixXvector(m, v){
	var d = 3;
	var tmp = [0,0,0];
	for(var i =0; i<d; i++){
		for(j = 0; j<d; j++){
			tmp[i] += m[i][j] * v[j];
		}
	}
	return tmp;
}



//// Draw initial Canvas
var startF = new Frustum("start");

var bn = 10;
var tn = 20;
var bf = 30;
var tf = 70;
var n = 10;
var f = 100;

startF.setVtx(n, bn, tn, f,bf,tf);
startF.draw(); 

//// ora sposto il vertice nel centro: sfrutto i passaggi ottenuti da startF
// console.log(startF.vertex)
// matrice di traslazione
var Tv = [ [1, 0 , -startF.vertex[0]], [0, 1, -startF.vertex[1]], [0 , 0, 1] ];
var translateVertex = new Frustum("translate-vertex");
// parte dalla figura del punto precedente
translateVertex.vtx = startF.vtx;
// ora trasformo le coordinate tramite T (le aggiorna anche)
translateVertex.applyMatrix(Tv);
// ora disegno il risultato
translateVertex.draw()



//// ora sposto il vertice nel centro: sfrutto i passaggi ottenuti da translateVertex
// console.log(translateVertex.vertex)
// matrice di traslazione
var simmetrizeShear = new Frustum("simmetrize-shear");
// parte dalla figura del punto precedente
simmetrizeShear.vtx = translateVertex.vtx;
var v = simmetrizeShear.vtx;
// le coordinate mi servono per trovare lo shear
var n = v[0][0];
var bn = v[0][1];
var tn = v[1][1];
var tgPsi = parseFloat(bn+tn)/parseFloat(2*n);
// ora applico lo shear in y
var Sy = [ [1, 0 , 0], [ - tgPsi, 1, 0], [0 , 0, 1] ];
simmetrizeShear.applyMatrix(Sy);
// ora disegno il risultato
simmetrizeShear.draw()


//// ora ripartendo da simmetrizeShear posso creare il cubo
var toCube = new Frustum("to-cube");
// parte dalla figura del punto precedente
toCube.vtx = simmetrizeShear.vtx;
var v = toCube.vtx;
// le coordinate mi servono per trovare lo shear
var n = v[0][0];
var f = v[3][0];
var alpha = parseFloat(f+n)/n;
var beta = -f;
// ora applico lo shear in y
var C = [ [alpha, 0 , beta], [0 , 1, 0], [1/n , 0, 0] ];
toCube.applyMatrix(C);
// ora devo normalizzare perche' la matrice C mi cambia la terza componente
toCube.toReal();
// ora disegno il risultato
toCube.draw()


//// ora centro orizzontalmente
var toCenter = new Frustum("to-center");
// parte dalla figura del punto precedente
toCenter.vtx = toCube.vtx;
var v = toCenter.vtx;
// le coordinate mi servono per trovare lo shear
var n = v[0][0];
var f = v[3][0];
// ora applico lo shear in y
var C = [ [1, 0 , - (f+n)/2.0], [0 , 1, 0], [0 , 0, 1] ];
toCenter.applyMatrix(C);
// ora devo normalizzare perche' la matrice C mi cambia la terza componente
toCenter.toReal();
// ora disegno il risultato
toCenter.draw()


// infine scalo per avere lunghezza totale 2 in ogni dimensione
//// ora centro orizzontalmente
var toScale = new Frustum("to-scale");
// parte dalla figura del punto precedente
toScale.vtx = toCenter.vtx;
var v = toScale.vtx;
// le coordinate mi servono per trovare lo shear
var n = v[0][0];
var f = v[3][0];
var bn = v[0][1];
var bf = v[1][1];
// ora applico lo shear in y
var size = 2.0 * 10;
var C = [ [size/(f-n), 0 , 0], [0 , size/(bf-bn), 0], [0 , 0, 1] ];
toScale.applyMatrix(C);
// ora devo normalizzare perche' la matrice C mi cambia la terza componente
toScale.toReal();
// ora disegno il risultato
toScale.draw()
