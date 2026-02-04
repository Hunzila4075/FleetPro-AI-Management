import cv2
import face_recognition
import mysql.connector
import os
import time

def get_db():
    try:
        return mysql.connector.connect(
            host="localhost", user="root", password="1234", 
            database="bus_system", autocommit=True
        )
    except mysql.connector.Error as err:
        return None

db = get_db()
cursor = db.cursor()
cursor.execute("UPDATE ai_gate SET status = 0 WHERE id = 1")
db.commit()

image_path = r"C:\laragon\www\bus_system\known_faces\darwesh.jpg.jpg"
admin_image = face_recognition.load_image_file(image_path)
admin_encoding = face_recognition.face_encodings(admin_image)[0]

video_capture = cv2.VideoCapture(0)

while True:
    ret, frame = video_capture.read()
    if not ret: continue
    small_frame = cv2.resize(frame, (0, 0), fx=0.25, fy=0.25)
    rgb_small_frame = cv2.cvtColor(small_frame, cv2.COLOR_BGR2RGB)
    face_encodings = face_recognition.face_encodings(rgb_small_frame)

    for face_encoding in face_encodings:
        matches = face_recognition.compare_faces([admin_encoding], face_encoding, tolerance=0.6)
        if True in matches:
            cursor.execute("UPDATE ai_gate SET status = 1 WHERE id = 1")
            db.commit()
            cv2.rectangle(frame, (0, 0), (640, 480), (0, 255, 0), 20)
            cv2.imshow('FleetPro AI', frame)
            cv2.waitKey(2000)
            video_capture.release()
            cv2.destroyAllWindows()
            os._exit(0) # Closes the window automatically

    cv2.imshow('FleetPro AI', frame)
    if cv2.waitKey(1) & 0xFF == ord('q'): break
video_capture.release()
cv2.destroyAllWindows()