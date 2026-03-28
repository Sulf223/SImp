// JavaScript Document

var context = document.getElementById('canvas').getContext('2d');

// Functions..........................................................

function drawGrid(context, color, stepx, stepy) {
   context.save()

   context.strokeStyle = color;
   context.lineWidth = 0.5;

   for (var i = stepx + 0.5; i < context.canvas.width; i += stepx) {
      context.beginPath();
      context.moveTo(i, 0);
      context.lineTo(i, context.canvas.height);
      context.stroke();
      context.closePath();
   }
   for (var i = stepy + 0.5; i < context.canvas.height; i += stepy) {
      context.beginPath();
      context.moveTo(0, i);
      context.lineTo(context.canvas.width, i);
      context.stroke();
      context.closePath();
   }
   context.restore();
}

// Initialization.....................................................

drawGrid(context, 'lightgray', 10, 10);

// Drawing attributes.................................................

context.font = '48pt Helvetica';
context.strokeStyle = 'blue';
context.fillStyle = 'red';
context.lineWidth = '2';       // line width set to 2 for text

// Text...............................................................

context.strokeText('Contur', 60, 110);
context.fillText('Umplere', 340, 110);

context.strokeText('Contur si', 650, 50);
context.fillText('Contur si', 650, 50);

context.strokeText('umplere', 660, 110);
context.fillText('umplere', 660, 110);

// Rectangles.........................................................

context.lineWidth = '5';       // line width set to 5 for shapes
context.beginPath();
context.rect(80, 150, 150, 100);
context.stroke();

context.beginPath();
context.rect(400, 150, 150, 100);
context.fill();

context.beginPath();
context.rect(750, 150, 150, 100);
context.stroke();
context.fill();

// Open arcs..........................................................

context.beginPath();
context.arc(150, 370, 60, 0, Math.PI*3/2);
context.stroke();

context.beginPath();
context.arc(475, 370, 60, 0, Math.PI*3/2);
context.fill();

context.beginPath();
context.arc(820, 370, 60, 0, Math.PI*3/2);
context.stroke();
context.fill();

// Closed arcs........................................................

context.beginPath();
context.arc(150, 550, 60, 0, Math.PI*3/2);
context.closePath();
context.stroke();

context.beginPath();
context.arc(475, 550, 60, 0, Math.PI*3/2);
context.closePath();
context.fill();

context.beginPath();
context.arc(820, 550, 60, 0, Math.PI*3/2);
context.closePath();
context.stroke();
context.fill();