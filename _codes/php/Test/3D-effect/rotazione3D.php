<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>The HTML5 Herald</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">

  	<!-- <link rel="stylesheet" href="css/styles.css?v=1.0"> -->
	<!--  <script src="js/scripts.js"></script> -->
</head>

<body>

<div id="posiziono">
<center>
<div id="spazio3d">
	<div id="girandola-prospettiva-x">
		<div class="axis-xy"></div>
		<div class="axis-z"></div>
		<div class="spinner" id="girandola-base">
		<div class="axis-z"></div>
		<div class="axis-xy"></div>


			<div class="girandola-img spinner" id="sopra"></div>
			<div class="girandola-img" id="mezzo"></div>
		</div>
	</div>
</div>
</center>
</div>

<div>Vedi: <a href="https://davidwalsh.name/3d-transforms">riferimento</a>.</div>
https://robots.thoughtbot.com/transitions-and-transforms

<div> Turorial molto buono sulla prospettiva: http://callmenick.com/_development/transitions-transforms-animations/2-perspective/index3.html </div>
<p>
	la prospettiva e' definita in questo modo: gli assi x e y sono della prospettiva giaciono sol spiano dello schermo (perpective origin x e y), e il valore della prospettiva indica la quota sull'asse z (entrante nello schermo) in cui il punto di fuga e' lontano (la profondita' del corridoio).
</p>

<div>Caso per gli SVG: https://sarasoueidan.com/blog/svg-coordinate-systems/</div>

<style>

body{
	background-color: hsla(113, 86%, 50%, 0.06);
}

#posiziono{
	width: 100%;
	text-align: center;
	height: 280px;
}

[class*="axis-"]{
	width: 300px;
	height: 300px;
	position: absolute;
	top: 0;
	left: 0;
}
	

.axis-xy{
	border-top: 3px solid blue;
	border-left: 3px solid green;
}

.axis-z{
	transform-origin: top left;
	transform: rotatex(90deg);
	/*border-top: 3px solid blue;*/
	border-left: 3px solid purple;
}


.girandola-img{
	background-image: url("rsz_girandola.png");
	background-repeat: no-repeat;
	min-width: 233px;
	min-height: 216px;
}

#spazio3d{
	position: relative;
	margin: 332px -50% 0 -48px;
	perspective: 2000px; /* valore grande equivale a prospettiva poco accennata: via di fuga mooolto lontana*/
	perspective-origin: 50% 50%;
	width: 50%;
	height: 50%;
}

#spazio3d #girandola-prospettiva-x{
	height: 100%;
	width: 100%;
	perspective-origin: 50% 50%;
	transform: translateX(-50%) translateY(50%) rotateX(60deg);
	transform-style: preserve-3d;  /*In order for subsequent children to inherit a parent’s perspective, and live in the same 3-D space, the parent can pass along its perspective with transform-style: preserve-3d.  */
}

#spazio3d #girandola-prospettiva-x #girandola-base{
	transform-style: preserve-3d;	
}

/*#spazio3d #girandola-prospettiva-x #girandola-base div{
	transform-style: preserve-3d;	
}
*/

#sopra, #mezzo, #sotto{
	transform: none;
	position: absolute;
	/*transform-style: preserve-3d;*/
	/*transform: rotateX(-60deg);*/
}
#sopra{
	transform: translateZ(100px);
}

#mezzo{
	transform: translateZ(-100px);
}

#sotto{

}

.spinner{
	transform-origin: 116px 108px;
	animation: spinner 10s linear infinite;
}
@keyframes spinner {
    from {
      transform: rotateZ(0deg);
    }
    to {
      transform: rotateZ(-360deg);
    }
  }



</style>


</body>
</html>