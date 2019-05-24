import keras
from keras.datasets import mnist
import tensorflow as tf
import cv2
import sys

def prepareImage(filename):
    img_arr = cv2.imread(filename, cv2.IMREAD_GRAYSCALE)
    img_arr = cv2.resize(img_arr, (28, 28))
    img_arr.astype('float32')
    
    img_arr = img_arr.reshape(1, 28, 28, 1)
    
    img_arr = img_arr.astype('float32')
    img_arr /= 255
    
    return img_arr

nums = ['CERO', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE']
model = tf.keras.models.load_model('/tf/notebook/handwritting_trained_from_zero.model')
p = model.predict_classes([prepareImage('/tf/uploads/' + sys.argv[1])])
print(nums[p[0]])
sys.stderr.close()
