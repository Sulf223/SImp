
var image = new Image(),
    canvas = document.getElementById('canvas'),
    context = canvas.getContext('2d'),
    colorToggleCheckbox = document.getElementById('colorToggleCheckbox');

function drawInBlackAndWhite() {
   var data = undefined,
       i = 0;

   imagedata = context.getImageData(0, 0,
                                    canvas.width, canvas.height);
   data = imagedata.data;
   
   for(i=0; i < data.length - 4; i+=4) {
      average = (data[i] + data[i+1] + data[i+2]) / 3;
      data[i]   = average;
      data[i+1] = average;
      data[i+2] = average;
   }
   context.putImageData(imagedata, 0, 0);
}

function drawInColor() {
   context.drawImage(image, 0, 0,
      image.width, image.height, 0, 0, 
      context.canvas.width, context.canvas.height);
}

colorToggleCheckbox.onclick = function() {
   if (colorToggleCheckbox.checked) {
      drawInColor();
   }
   else {
      drawInBlackAndWhite();
   }
};

image.src = 'imagine.png';
image.onload = function() {
   drawInColor();
};