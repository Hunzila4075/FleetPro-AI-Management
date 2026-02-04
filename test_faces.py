import face_recognition
import cv2
import numpy as np

# 1. Start the Webcam
video_capture = cv2.VideoCapture(0)

print("--- System Started ---")
print("Looking for faces... Press 'q' to stop.")

while True:
    # Capture frame-by-frame
    ret, frame = video_capture.read()
    if not ret:
        print("Can't receive frame. Exiting...")
        break

    # 2. Resize frame to 1/4 size for faster face detection
    small_frame = cv2.resize(frame, (0, 0), fx=0.25, fy=0.25)
    
    # 3. Convert image from BGR (OpenCV) to RGB (face_recognition)
    rgb_small_frame = cv2.cvtColor(small_frame, cv2.COLOR_BGR2RGB)

    # 4. Find all face locations in the current frame
    face_locations = face_recognition.face_locations(rgb_small_frame)

    # 5. Display the results
    for (top, right, bottom, left) in face_locations:
        # Scale back up face locations since we resized to 1/4 size
        top *= 4
        right *= 4
        bottom *= 4
        left *= 4

        # Draw a green box around the face
        cv2.rectangle(frame, (left, top), (right, bottom), (0, 255, 0), 2)
        cv2.putText(frame, "Face Detected", (left, top - 10), 
                    cv2.FONT_HERSHEY_SIMPLEX, 0.6, (0, 255, 0), 2)

    # Show the video window
    cv2.imshow('Bus System - Test Face Detection', frame)

    # Press 'q' on the keyboard to quit!
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

# Cleanup
video_capture.release()
cv2.destroyAllWindows()