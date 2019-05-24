import tensorflow as tf
import keras
import numpy as np
from keras.utils import np_utils
import sys
import cv2

model = tf.keras.models.load_model('/tf/notebook/handwritting_trained_from_zero.model')

def prepareImage(filename):
    img_arr = cv2.imread(filename, cv2.IMREAD_GRAYSCALE)
    img_arr = cv2.resize(img_arr, (28, 28))
    img_arr.astype('float32')
    
    img_arr = img_arr.reshape(1, 28, 28, 1)
    
    img_arr = img_arr.astype('float32')
    img_arr /= 255
    
    return img_arr


images = np.array(prepareImage('/tf/uploads/' + sys.argv[1]))
images = images.reshape(1,28,28,1)

feature_data = np.array(images);
label_data = np.array([sys.argv[2]])

label_data = np_utils.to_categorical(label_data, 10)

batch = model.train_on_batch(feature_data, label_data)


model.save('/tf/notebook/handwritting_trained_from_zero.model')


print("Done training...")