<!DOCTYPE html> 
<html lang="en">              
    <head>                                                                    
        <meta charset="UTF-8">                                                
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Predict image</title>
                                                         
        <script                                                        
        src="https://code.jquery.com/jquery-3.4.1.min.js"              
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
               
        <style>
                       
        *{             
            padding: 0;            
            margin: 0;             
            box-sizing: border-box;
        }       
                                   
        #canvas{                   
            border: 1px solid #000;
        }       
                
        </style>
           
    </head>
    <body>                                      
                                                       
        <canvas id="canvas">NO JS</canvas><br />       
        <a href="#" id="saveImage">Check</a><br />

        <p id="result"></p>

        <div id="train">
            <input type="number" id="correctValue" min=0 max=9 name="correctValue" />
            <input type="hidden" name="file_prefix" id="prefix" />
            <input type="submit" id="trainData" value="Enviar" name="train">
        </div>

        <script>
            let trainForm = document.getElementById("train");
            trainForm.style.display = 'none';
            let prefixImage = '';
            let result = document.getElementById("result"); 
            let correctNumber = '';
            let cv = document.getElementById("canvas");
            cv.width = 280;               
            cv.height = 280;              
            let ctx = cv.getContext('2d');
                                     
            let paint = false;       
            let clickX = new Array();   
            let clickY = new Array();   
            let clickDrag = new Array();                 
                                                         
            cv.addEventListener('mousedown', function(e){
                paint = true;                          
                let mouseX = e.pageX - this.offsetLeft;
                let mouseY = e.pageY - this.offsetTop;                        
                                                                              
                addClick(e.pageX - this.offsetLeft, e.pageY - this.offsetTop);
                redraw();
               
            });                                                                
                                                                              
            cv.addEventListener('mouseup', function(e){                       
                paint = false;                                                
            });                                                               
                                                                              
            cv.addEventListener('mouseleave', function(e){                    
                paint = false;                                                
            });                                                               
                                                                              
            cv.addEventListener('mousemove', function(e){                     
                if(paint){                                                    
                    addClick(e.pageX - this.offsetLeft, e.pageY - this.offsetTop, true);
                    redraw();                                                           
                }                                                                       
            })                                                                          
                                                                                        
            function addClick(x, y, dragging){                                          
                clickX.push(x);                                                         
                clickY.push(y);                                                         
                clickDrag.push(dragging);                                               
            }                                                                           
                                                                                        
            function redraw(){                                                          
                ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height); // Clears the canvas
                                                                                              
                ctx.strokeStyle = "#df4b26";                                                  
                ctx.lineJoin = "round";                                                       
                ctx.lineWidth = 20;                                                           
                                                                                              
                for(var i=0; i < clickX.length; i++) {                                        
                    ctx.beginPath();                                                          
                    if(clickDrag[i] && i){                                                    
                        ctx.moveTo(clickX[i-1], clickY[i-1]);                                 
                    }else{                                                                    
                        ctx.moveTo(clickX[i]-1, clickY[i]);                                   
                    }                                                                         
                    ctx.lineTo(clickX[i], clickY[i]);                                         
                    ctx.closePath();                                                          
                    ctx.stroke();                                                             
                }                                                                             
            }                                                                                 
            let saveBtn = document.getElementById('saveImage');                               
            saveBtn.addEventListener('click', function(e){    

                let date = new Date();
                prefixImage = date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate() + "_" + date.getHours() + "." + date.getMinutes() + "." + date.getSeconds();

                document.getElementById('prefix').value = prefixImage;

                result.innerText = "Checking please wait....";                                
                                                                                              
                let data = cv.toDataURL('image/png');                                         
                $.ajax({                                                                      
                   type: 'POST',                                                              
                   url: './saveImage.php',                                                    
                   data: {                                     
                       canvasImage: data,
                       prefix: prefixImage                                                   
                   }                                                                          
                }).done(function(data){
                    result.innerText = data;
                    result.innerHTML += "No es un numero que esperabas? Escribe el numero correcto abajo para ajudarnos mejorar el sistema:"
                    trainForm.style.display = 'block';
                })                                                                         
            });   
            
            let trainData = document.getElementById("trainData");
            trainData.addEventListener('click', function(e){

                $.ajax({                                                                      
                   type: 'POST',                                                              
                   url: './train_cnn_model.php',                                                    
                   data: {                                     
                       correctValue: document.getElementById("correctValue").value,
                       prefix: prefixImage                                                   
                   }                                                                          
                }).done(function(data){
                    result.innerHTML += data;
                })         

            });
                                                                                              
        </script>                                                                             
    </body>                                                    
</html>
