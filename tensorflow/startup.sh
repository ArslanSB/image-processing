#!/bin/bash

# start jupyter by default
source /etc/bash.bashrc
jupyter notebook --notebook-dir=/tf --ip 0.0.0.0 --no-browser --allow-root &

# install necessary packages
apt-get update
apt-get install -y libglib2.0-0 libsm6 libxext6 libxrender-dev

pip install opencv-python
pip install flask
pip install keras
pip install tensorflow
pip install pandas
pip install numpy
pip install pillow

# start the server
python /tf/flask_server.py
