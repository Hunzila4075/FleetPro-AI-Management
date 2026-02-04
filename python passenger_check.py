import face_recognition
import cv2
import numpy as np
import os

# 1. Load Known Faces
known_face_encodings = []
known_face_names = []

# Automatically load every image in the known_faces folder
path = "known_faces"
for file in os.listdir(path):
    if file.endswith((".jpg", ".png", ".jpeg")):
        image = face_recognition.load_image_file(f"{path}/{file}")
        encoding = face_recognition.face_encodings(image)[0]
        known_face_encodings.append(encoding)
        # Use filename as the name (e.g., darwesh.jpg -> Darwesh)
        known_face_names.append(file.split('.')[0].capitalize())

print(f"✅ Loaded {len(known_face_names)} passengers: {known_face_names}")

# 2. Start Webcam
video_capture = cv2.VideoCapture(0)

while True:
    ret, frame = video_capture.read()
    if not ret: break

    # Process a smaller frame for speed
    small_frame = cv2.resize(frame, (0, 0), fx=0.25, fy=0.25)
    rgb_small_frame = cv2.cvtColor(small_frame, cv2.COLOR_BGR2RGB)

    # Find faces and compare
    face_locations = face_recognition.face_locations(rgb_small_frame)
    face_encodings = face_recognition.face_encodings(rgb_small_frame, face_locations)

    for (top, right, bottom, left), face_encoding in zip(face_locations, face_encodings):
        matches = face_recognition.compare_faces(known_face_encodings, face_encoding)
        name = "Unknown"

        # Find the best match
        face_distances = face_recognition.face_distance(known_face_encodings, face_encoding)
        if len(face_distances) > 0:
            best_match_index = np.argmin(face_distances)
            if matches[best_match_index]:
                name = known_face_names[best_match_index]

        # Draw box and Name
        top, right, bottom, left = top*4, right*4, bottom*4, left*4
        color = (0, 255, 0) if name != "Unknown" else (0, 0, 255)
        cv2.rectangle(frame, (left, top), (right, bottom), color, 2)
        cv2.putText(frame, name, (left, top - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.7, color, 2)

    cv2.imshow('Bus System - Recognition', frame)
    if cv2.waitKey(1) & 0xFF == ord('q'): break

video_capture.release()
cv2.destroyAllWindows()