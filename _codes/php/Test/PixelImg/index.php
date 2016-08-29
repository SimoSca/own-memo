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
        Prendo un'immagine e poi la modifico pixel per pixel
    </p>

    <div id="box-container">
        <div class="box">
            <div class="imge"><img src="rana.jpg"/></div>
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

    that.transform();
  };
  i.src = src;
  
  // cache these
  this.context = context;
  this.image = i;
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
    var obj = {};
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


CanvasImage.prototype.transform = function() {
    var olddata = this.original;
    var oldpx = olddata.data;
    var newdata = this.context.createImageData(olddata);
    var newpx = newdata.data

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
            // console.log("P("+x+","+y+")");
            // funzione vera e propria
            var px = this.getPixel(olddata, x, y);
            /* effetto gradiente orizzontale
            px.a = px.a * x /olddata.width;
            this.setPixel(newdata, x, y, px);
            */
           
           /* effetto distorto a bandiera
           // di quanto voglio che oscilli verticalmente
           var deltaH = 20;
           // vettore d'onda, per il periodo
           var K = 2*Math.PI / olddata.width;
           this.setPixel(newdata, x , y + parseInt(Math.sin(x*K)*deltaH), px);
           */
          /* colori invertiti: negativo pellicola */
          for(var prop in px){
            if(prop == 'a') continue;
            px[prop] = 255 - px[prop];
          }
          this.setPixel(newdata, x, y, px);
        }
    }

    this.setData(newdata);
}

var transformador = new CanvasImage(
  document.getElementsByTagName('canvas')[0],
  'rana.jpg'
);



</script>