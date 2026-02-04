import face_recognition
import os
import sys

# Paths
known_path = r"C:\laragon\www\bus_system\known_faces\darwesh.jpg.jpg"
captured_path = r"C:\laragon\www\bus_system\temp_capture.jpg"

def verify():
    if not os.path.exists(captured_path):
        return "NO_FILE"

    try:
        # Load your saved photo
        known_image = face_recognition.load_image_file(known_path)
        known_encoding = face_recognition.face_encodings(known_image)[0]

        # Load the photo the browser just took
        unknown_image = face_recognition.load_image_file(captured_path)
        unknown_encodings = face_recognition.face_encodings(unknown_image)

        if len(unknown_encodings) > 0:
            match = face_recognition.compare_faces([known_encoding], unknown_encodings[0], tolerance=0.6)
            if match[0]:
                return "MATCH"
        
        return "NO_MATCH"
    except Exception as e:
        return "ERROR"

if __name__ == "__main__":
    print(verify())