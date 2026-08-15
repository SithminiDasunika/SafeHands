document.addEventListener('DOMContentLoaded', () => {

    initShaderCanvas();

    initPaymentMethodSelection();

    initPaymentForm();

});

/*=========================================
  BACKGROUND ANIMATION
=========================================*/

function initShaderCanvas() {

    const canvas = document.getElementById('shader-canvas');

    if (!canvas) return;

    function resizeCanvas() {

        canvas.width = window.innerWidth;

        canvas.height = window.innerHeight;

    }

    resizeCanvas();

    window.addEventListener('resize', resizeCanvas);

    const gl = canvas.getContext('webgl');

    if (!gl) return;

    const vertexShaderSource = `
        attribute vec2 a_position;
        varying vec2 v_uv;

        void main(){

            v_uv = a_position * 0.5 + 0.5;

            gl_Position = vec4(a_position,0.0,1.0);

        }
    `;

    const fragmentShaderSource = `
        precision mediump float;

        varying vec2 v_uv;

        uniform float u_time;

        void main(){

            float wave =
                0.05*sin(v_uv.x*5.0+u_time*0.5)
                +0.03*sin(v_uv.y*8.0);

            vec3 c1=vec3(0.96,0.97,1.0);

            vec3 c2=vec3(1.0);

            vec3 color=mix(c1,c2,v_uv.y+wave);

            gl_FragColor=vec4(color,1.0);

        }
    `;

    function createShader(type, source) {

        const shader = gl.createShader(type);

        gl.shaderSource(shader, source);

        gl.compileShader(shader);

        return shader;

    }

    const program = gl.createProgram();

    gl.attachShader(program, createShader(gl.VERTEX_SHADER, vertexShaderSource));

    gl.attachShader(program, createShader(gl.FRAGMENT_SHADER, fragmentShaderSource));

    gl.linkProgram(program);

    gl.useProgram(program);

    const vertices = new Float32Array([
        -1,-1,
         1,-1,
        -1, 1,
         1, 1
    ]);

    const buffer = gl.createBuffer();

    gl.bindBuffer(gl.ARRAY_BUFFER, buffer);

    gl.bufferData(gl.ARRAY_BUFFER, vertices, gl.STATIC_DRAW);

    const position = gl.getAttribLocation(program,"a_position");

    gl.enableVertexAttribArray(position);

    gl.vertexAttribPointer(position,2,gl.FLOAT,false,0,0);

    const timeLocation = gl.getUniformLocation(program,"u_time");

    function render(time){

        gl.viewport(0,0,canvas.width,canvas.height);

        gl.uniform1f(timeLocation,time*0.001);

        gl.drawArrays(gl.TRIANGLE_STRIP,0,4);

        requestAnimationFrame(render);

    }

    requestAnimationFrame(render);

}

/*=========================================
  PAYMENT METHOD
=========================================*/

function initPaymentMethodSelection(){

    const methods=document.querySelectorAll(".method-option");

    methods.forEach(method=>{

        method.addEventListener("click",()=>{

            methods.forEach(m=>m.classList.remove("selected"));

            method.classList.add("selected");

        });

    });

}

/*=========================================
  PAYMENT FORM
=========================================*/

function initPaymentForm(){

    const form=document.getElementById("payment-form");

    if(!form) return;

    form.addEventListener("submit",function(e){

        e.preventDefault();

        const agree=document.getElementById("agree");

        if(agree && !agree.checked){

            alert("Please accept the Terms & Conditions.");

            return;

        }

        /* For Interim */

        window.location.href="confirmation.php";

    });

}