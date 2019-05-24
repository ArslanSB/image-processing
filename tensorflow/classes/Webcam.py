import cv2

class Webcam(object):

    def __init__(self):
        self.video = cv2.VideoCapture(-1)
    
    def __del__(self):
        self.vide.release()

    def get_frame(self):
        success, image = self.video.read()
        print("Success Code: ")
        print(success)
        ret, jpeg = cv2.imencode(".jpg", image)
        return jpeg.tobytes()