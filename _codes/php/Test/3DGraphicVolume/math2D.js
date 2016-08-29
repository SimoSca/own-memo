// see also
// http://www.html5canvastutorials.com/labs/html5-canvas-graphing-an-equation/

var Point = function(x, y){
	var x = parseFloat(x);
	var y = parseFloat(y)
	this.p = [x, y];
	this.x = x;
	this.y = y;
};

var Segment = function(p1,p2){
	this.p1 = p1;
	this.p2 = p2;
	this.length = Math.sqrt( (p1.p[0] - p2.p[0])*(p1.p[0] - p2.p[0]) + (p1.p[1] - p2.p[1])*(p1.p[1] - p2.p[1]) );
	this.middle = new Point( parseFloat(p1[0]+p2[0])/2.0 , parseFloat(p1[1]+p2[1])/2.0 );
	this.draw = function(ctx){
		var cx = ctx || this.ctx;
		cx.beginPath();
		cx.lineWidth = this.lineWidth || 2;
		if(this.strokeStyle) cx.strokeStyle = this.strokeStyle;
		cx.moveTo(this.p1.x, this.p1.y);
		cx.lineTo(this.p2.x, this.p2.y);
		cx.stroke();
		cx.closePath();
	}
	// set prop
	this.setProp = function(json){
		for(var prop in json) this[prop] = json[prop];
	}
	
	this.arrowAtP1 = function(){
		var ctx = this.ctx;
		console.log(this.length)
		var angle = Math.acos((this.p1.y - this.p2.y) / this.length);
		console.log(angle)
    	if (this.p1.x < this.p2.x) angle = 2 * Math.PI - angle;
    	this.drawArrow(angle, this.p1.x, this.p1.y);
    }

    this.arrowAtP2 = function(){
		var ctx = this.ctx;
		var angle = Math.acos((this.p2.y - this.p1.y) / this.length);
    	if (this.p2.x < this.p1.x) angle = 2 * Math.PI - angle;
    	this.drawArrow(angle, this.p2.x, this.p2.y);
    }

    this.drawArrow = function(angle, px, py){
    	var ctx = this.ctx;
    	var size = 3;
    	ctx.save();
    	ctx.beginPath();
    	ctx.translate(px, py);
    	ctx.rotate(-angle);
    	// ctx.fillStyle = '#0000ff';
    	// ctx.lineWidth = 2;
    	// ctx.strokeStyle = '#ff0000';
    	ctx.moveTo(0, -size);
    	ctx.lineTo(-size, -size);
    	ctx.lineTo(0, 0);
    	ctx.lineTo(size, -size);
    	ctx.lineTo(0, -size);
    	ctx.closePath();
    	ctx.fill();
    	ctx.stroke();
    	ctx.restore();
	}
}

// object Element
// o -> canvas (javascript dom element) need
// 		width in pixel
// 		height in pixel
// 		
var Graph = function(obj){
	for(var prop in obj) this[prop] = obj[prop];
	this.ctx = this.canvas.getContext('2d');

	this.toCartesian = function(){
		var c = this.canvas;
		var w = this.width || 300;
		var h = this.height || 200;
		c.width = w;
		c.height = h;
		var ctx = this.ctx;
		// vedi matrice trasformazioni omogenee di grafica 3d (ristrette al caso 2d)
		ctx.transform(1,0,0,-1,w/2+0.5, h/2+0.5);// lo 0.5 e' il solito trucco per avere linee pulite
		ctx.beginPath();
		// asse x
		this.drawAxes(ctx, w, h);
		// asse y
		ctx.moveTo(0,-h/2);
		ctx.lineTo(0,h/2);
		ctx.stroke();
		ctx.closePath();
	}

	this.drawAxes = function(ctx, w, h){
		ctx.moveTo(-w/2,0);
		ctx.lineTo(w/2, 0);
		var unit = 15.0 ; // tot pixel rappresentano un unita'
		var tickL = 10.0; // lunchezza di un tick
		var l = 0.0;
		var count = 0;
		while(l < w/2.0){
			ctx.moveTo(l, tickL/2.0);
			ctx.lineTo(l, -tickL/2.0);
			ctx.moveTo(-l, tickL/2.0);
			ctx.lineTo(-l, -tickL/2.0);
			// if(count>0) ctx.fillText(count, l - 10 , -tickL/2.0);
			l += unit;
			count++;
		}
	}

	this.directDraw = function(callback){
		var cx = this.ctx;
		cx.beginPath();
		if(callback) callback();
		cx.closePath();
	}
}

// var p1 = new Point(10, -30);
// var p2 = new Point(-40, -70);
// var seg = new Segment(p1,p2);

// var ctest = document.getElementById("canvas-test");
// ctest =  new Graph({canvas: ctest, width: '300', height:'150'});
// ctest.toCartesian();

// seg.ctx = ctest.ctx; 
// seg.draw();
// seg.arrowAtP2();