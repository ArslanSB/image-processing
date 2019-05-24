#!/usr/bin/env python3
from flask import Flask, request, Response
from classes.Webcam import Webcam
import os

app = Flask(__name__)

@app.route('/handwritting_cnn', methods=['POST'])
def predict():
    data = request.get_json()

    filename = data['fileName']
    
    p = os.popen('python /tf/predictScripts/predict_handwriting.py ' + filename)
    return p.read()

@app.route('/train_handwritten_cnn', methods=['POST'])
def train():
    data = request.get_json()
    filename = data['fileName']
    label = data['label']

    p = os.popen('python /tf/train_models/train_handwritten_CNN_model.py ' + filename + " " + label)
    return p.read()

def gen(cam):
    while True:
        frame = cam.get_frame()
        yield (b'--frame\r\n'
               b'Content-Type: image/jpeg\r\n\r\n' + frame + b'\r\n\r\n')

@app.route("/")
def index():
    wc = Webcam()
    return Response(gen(wc), mimetype='multipart/x-mixed-replace; boundary=frame')

if __name__ == "__main__":
    app.run('0.0.0.0', 5000)