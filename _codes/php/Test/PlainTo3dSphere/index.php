<!DOCTYPE html>
<html>
<head>
    <title>Pixel Img</title>
</head>
<style>
    #box-container{
        text-align: center;
    }

    .box{
        display: inline-block;
        /*width: 50%;*/
        margin: 5px auto;
    }
    .didascalia{
        text-align: center;
        margin: 5px;
    }
    .imge{
        border: 1px solid black;
        padding: 5px;
    }
</style>
<body>

    <p>
        Prendo un'immagine e poi la modifico pixel per pixel.
    </p>
    <p>
        Spiegazione utile a <a href="http://www.codinglabs.net/article_world_view_projection_matrix.aspx" target="_blank">http://www.codinglabs.net/article_world_view_projection_matrix.aspx</a>.
    </p>

    <div id="box-container">
        <div class="box">
            <div class="imge"><img src="square.gif"/></div>
            <!-- <div class="imge"><img src="images.jpg"/></div> -->
            <!-- <div class="imge"><img src="images.png"/></div> -->
            <div class="didascalia">Immagine Originale</div>
        </div>
        <div class="box">
            <div class="imge"><canvas></canvas></div>
            <div class="didascalia">Immagine Modificata</div>
        </div>
    </div>


</body>
</html>

<script>
// From http://www.phpied.com/pixel-manipulation-in-canvas/
function CanvasImage(canvas, src) {
  // load image in canvas
  var context = canvas.getContext('2d');
  var i = new Image();
  var that = this;
  i.onload = function(){
    canvas.width = i.width;
    canvas.height = i.height;
    context.drawImage(i, 0, 0, i.width, i.height);
 
    // remember the original pixels: from rectangle
    that.original = that.getData();
    // that.sphere3D();
    that.pictureToSphere();
  };
  i.src = src;
  
  // cache these
  this.context = context;
  this.image = i;
  this.canvas = canvas;
}


CanvasImage.prototype.getData = function() {
  return this.context.getImageData(0, 0, this.image.width, this.image.height);
};

CanvasImage.prototype.setData = function(data) {
  return this.context.putImageData(data, 0, 0);
};
 
CanvasImage.prototype.reset = function() {
  this.setData(this.original);
}

CanvasImage.prototype.getPixel = function(imageData, x, y){
    var obj = {r: 0, g: 0, b:0 ,a:0 };
    if(x < 0 || y < 0 || x >= imageData.width || y >= imageData.height) return obj;
    index = (x + y * imageData.width) * 4;
    obj.r = imageData.data[index+0] ;
    obj.g = imageData.data[index+1] ;
    obj.b = imageData.data[index+2] ;
    obj.a = imageData.data[index+3] || 1;
    return obj;
}

CanvasImage.prototype.setPixel = function(imageData, x,y, obj){
    index = (x + y * imageData.width) * 4;
    imageData.data[index+0] = obj.r;
    imageData.data[index+1] = obj.g;
    imageData.data[index+2] = obj.b;
    imageData.data[index+3] = obj.a || 1;
}

/**
 * Orientazione degli assi:
 * schermo: 
 *   sistema destrogiro
 *   piano x, y, con angolo phi (0;2pi)
 *   asse z uscente dallo schermo e angolo theta quello formato tra raggio e piano xy, positivo per z positivi (-pi/2;+pi2)
 *
 *    x = r cos(theta) * cos(phi)
 *    y = r cos(theta) * sin(phi)
 *    z = r sin(theta)
 *
 *    phi = atan(y/x) === atan2(y,x)
 *
 *    cos(theta)  = sqrt( (x^2+y^2)/(r^2) )
 *    cosi' non mi basta, perche' non ho informazioni per capire se prendere +arccos() oppure -arccos().
 *    il segno lo posso ricavare, ad esempio dal segno di z
 *
 *
 *
 * Prospettiva:Perspective projection
 *
 * visto che il rendering deve essere svolto rispetto a una specifica prospettiva, devo considerare 3 oggetti:
 *
 * l'osservatore, ovvero la camera: cioe' dove le immagini convergono
 * 
 */

// spalmo l'immagine sulla sfera, ovvero la porto in 3D
// in questo caso disegno solo la calotta con z positivo
CanvasImage.prototype.pictureToSphere = function(){
  
  var olddata = this.original;

  // lunghezza di un quarto di arco
  var arc = Math.min(this.canvas.width, this.canvas.height)/2.0 ;
  var radius = arc * 2 / Math.PI ;

  var center = {
    x: arc,
    y: arc
  }

  this.sphere = {
    radius: radius,
    radius2: radius*radius,
    center: center,
    points: [] // coordinate relative al centro della sfera
  }
  
  // lavoro in griglia perche' voglio applicare una trasformazione
  // ciascun punto 2D e' la proiezione di un punto 
  for(var x=0 ; x < olddata.width; x++){
      for(var y=0 ; y < olddata.height; y++){

        var px = this.getPixel(olddata, x, y);

        // la x e la y rimangono coordinate invariate, pertanto devo solo ricavare la z, che in questo caso considero solo positiva
        var sph = this.sphere;
        var p ={
          x: x - center.x,
          y: y - center.y,
          pixel: px
        }


        // quando inverto la tangente gli angoli sono a meno di PI, ma io li voglio su un range di 2PI
        p.phi = Math.PI * (1-Math.sign(p.x))/2 + Math.atan(p.y/p.x);
                
        // distanza tra il punto e il centro
        var pc = (p.x*p.x) + (p.y*p.y) ;
        
        // altrimenti sovrascrivo con la parte che andrebbe dietro
        if(pc > arc*arc) continue;

        // angolo con asse z (azimutale)
        var alpha = Math.sqrt( (p.x*p.x + p.y*p.y)/(sph.radius2) );
        // angolo col piano XY
        p.theta = Math.PI/2.0 - alpha;
        
        p.zSectionRadius = sph.radius * Math.cos(p.theta);
        p.x = p.zSectionRadius * Math.cos(p.phi);
        p.y = p.zSectionRadius * Math.sin(p.phi);
        p.z = sph.radius * Math.sin(p.theta);
        
        sph.points[(x + y * olddata.width)] = p;

      }
  }

  console.log(this.sphere)
  this.drawProjected();
}

CanvasImage.prototype.drawProjected = function(){

  // this.context.imageSmoothingEnabled = true;

  var olddata = this.original;
  var oldpx = olddata.data;
  var newdata = this.context.createImageData(olddata);
  var newpx = newdata.data

  var center = this.sphere.center;
  var sph = this.sphere;

  // suppongo che la camera sia orientata come gli assi della sfera, con z usente dallo schermo (punta verso lo spettatore) 
  // che la sfera sia a una distanza di 100 dalla camera (co)(camera object)
  // e che il piano sia ad una distanza 70 dalla camera (cs)(camera screen)
  // grazie a questo, mi basta usare semplicemente il teorema del triangolo rettangolo per le proiezioni
  
  var projected = [];
  // offset z dello screen rispetto alla camera (origine)
  var zcSc = -250;
  // offset z della sfera rispetto alla camera: deve essere superiore al raggio!
  var zcSp = zcSc  - sph.radius;
  // variabili di controllo: il massimo raggio che dovrei poter raggiungere nella proiezione, almeno in teoria. Lo calcolo partendo dal punto di contatto tra la retta tangente alla sfera e passante per l'origine del mio sistema
  var knot = {};
  // raggio della sezione che produce la massima proiezione
  knot.maxRz = (sph.radius / Math.abs(zcSp)) * Math.sqrt(zcSp*zcSp - sph.radius*sph.radius);
  // massimo raggio sullo screen: corrispondente alla sezione visibile
  knot.maxRsc = Math.abs(zcSc / ( Math.abs(zcSp) - Math.sqrt(sph.radius*sph.radius - knot.maxRz*knot.maxRz)) ) * knot.maxRz;
  // massimo rapporto, ovvero tangente del triangolo di similiturine sulla camera
  knot.maxRatio = Math.abs(knot.maxRsc/zcSc);
  // minimo angolo che devo formare col piano XY: direttamente relazionato a maxRz
  knot.minTheta = Math.atan( knot.maxRatio );
  console.log(sph.radius + ' ' + knot.maxRsc + ' ' + knot.maxRz + ' ' + knot.minTheta + ' ' + knot.maxRatio);

  // loop sulla sfera
  for(var i in sp = sph.points){

    // zoffset
    var zo = zcSp + sp[i].z;

    // rapporto di similitudine
    var ratio = zcSc / zo ;
    // distanza tra il punto p e l'asse z, ovvero il raggio della sezione, che combacia con la lunghezza di un cateto del triangolo proiettivo
    var pz = sp[i].zSectionRadius;

    // fattore di prospettiva: non vedo tutta la calotta frontale, ma solo una porzione .
    // equivalentemente avrei potuto settare la condizione su minTheta
    if(pz > knot.maxRz){
      // console.log('pz: ' + pz)
      continue;
    }

    // raggio dello screen  
    var rs = ratio * pz;
    var xs = rs * Math.cos(sp[i].phi);
    var ys = rs * Math.sin(sp[i].phi);  

    // nel caso voglia disegnare la prima trasformazione senza la proiezione
    // xs = sp[i].x;
    // ys = sp[i].y;
    
    // lo centro nella window
    xs += this.canvas.width/2.0;
    ys += this.canvas.height/2.0;

    xs = Math.floor( xs + 0.5);
    ys = Math.floor( ys + 0.5);
    this.setPixel(newdata, xs, ys, sp[i].pixel);
  }


  this.setData(newdata);


  // newdata e' la mia nuova immagine sgranata, pertanto ora su di essa svolgo la mia interpolazione:
  this.setData( this.interpolation(newdata) );
}


// media coi primi 8 vicini: non rimpiazzo il 
// interpolazione primitiva mediando nei primi vicini
CanvasImage.prototype.interpolation =  function(imgData){
  var olddata = imgData;
  var newdata = this.context.createImageData(olddata);

  for(var x=0 ; x < olddata.width; x++){
      for(var y=0 ; y < olddata.height; y++){

        // prendo gli n-vicini
        var count = 0;
        var px = this.getPixel(olddata, -1, -1); // ottengo un px impostato a zero
        for(i = -1; i <= 1; i++){
          for(j = -1; j<= 1; j++){
            var tmpcount = count;
            count++;
              var nowPx = this.getPixel(olddata, x +i, y+j);
              for(var prop in px){
                px[prop] = px[prop]  + nowPx[prop];
              }
          }
        }
        for(var prop in px) px[prop] = px[prop] / count;
        // px.a = ( px.r ==0 && px.g == 0 && px.b == 0) ? 0 : 255 ;
        this.setPixel(newdata, x, y, px);
      }
  }
  return newdata;
}


CanvasImage.prototype.sphere3D= function() {
    var olddata = this.original;
    var oldpx = olddata.data;
    var newdata = this.context.createImageData(olddata);
    var newpx = newdata.data

    var radius = Math.min(this.canvas.width, this.canvas.height)/2.0 ;
    var radius2 = radius*radius;
    var center = {
      x: radius,
      y: radius
    }
    console.log(radius)

    // Utile se voglio usare un filtro sui colori senza sapere nulla della griglia
    // var res = [];
    // var len = newpx.length;
    // for (var i = 0; i < len; i += 4) {
    //     res = fn.call(this, oldpx[i], oldpx[i+1], oldpx[i+2], oldpx[i+3], factor, i);
    //     newpx[i]   = res[0]; // r
    //     newpx[i+1] = res[1]; // g
    //     newpx[i+2] = res[2]; // b
    //     newpx[i+3] = res[3]; // a
    // }

    // lavoro in griglia perche' voglio applicare una trasformazione
    for(var x=0 ; x < olddata.width; x++){
        for(var y=0 ; y < olddata.height; y++){

            var px = this.getPixel(olddata, x, y);

          /* colori invertiti: negativo pellicola */
          for(var prop in px){
            if(prop == 'a') continue;
            px[prop] = 255 - px[prop];
          }
          // distanza tra il punto e il centro
          var pc = (x-center.x)*(x-center.x) + (y-center.y)*(y-center.y) ;
          if(pc > radius2) px.a = 0;

          this.setPixel(newdata, x, y, px);
        }
    }
 
    this.setData(newdata);
 
}

var transformador = new CanvasImage(
  document.getElementsByTagName('canvas')[0],
  document.getElementsByTagName('img')[0].src
);



</script>