import cv2
import face_recognition
import mysql.connector
import os
import time

# --- 1. STABLE DATABASE CONNECTION ---
def get_db_connection():
    try:
        return mysql.connector.connect(
            host="localhost",
            user="root",
            password="1234",
            database="bus_system",
            autocommit=True  # Fixes "status not updating" issue
        )
    except mysql.connector.Error as err:
        print(f"❌ DB Connection Error: {err}")
        return None

db = get_db_connection()

# --- 2. FLEXIBLE IMAGE PATH (Fixes JPG vs JPEG mistake) ---
# We check for both extensions so the script doesn't crash
possible_files = [
    r"C:\laragon\www\bus_system\known_faces\darwesh.jpg",
    r"C:\laragon\www\bus_system\known_faces\darwesh.jpg.jpeg",
    r"C:\laragon\www\bus_system\known_faces\darwesh.jpeg"
]

image_path = next((f for f in possible_files if os.path.exists(f)), None)

if not image_path:
    print("❌ Error: Could not find your photo in known_faces/ folder.")
    exit()

print(f"✅ Loading Identity: {image_path}")
admin_image = face_recognition.load_image_file(image_path)
admin_encodings = face_recognition.face_encodings(admin_image)

if not admin_encodings:
    print("❌ Error: No face found in your saved photo. Use a clearer picture.")
    exit()

admin_encoding = admin_encodings[0]

# --- 3. STABLE CAMERA & RECOGNITION ---
video_capture = cv2.VideoCapture(0)
print("🚀 AI Scanner Active. Look directly at the camera.")

while True:
    ret, frame = video_capture.read()
    if not ret: continue

    # Optimization: Resize for speed, convert to RGB for recognition
    small_frame = cv2.resize(frame, (0, 0), fx=0.25, fy=0.25)
    rgb_small_frame = cv2.cvtColor(small_frame, cv2.COLOR_BGR2RGB)

    face_locations = face_recognition.face_locations(rgb_small_frame)
    face_encodings = face_recognition.face_encodings(rgb_small_frame, face_locations)

    for face_encoding in face_encodings:
        # TOLERANCE: 0.6 is standard. 0.5 is strict. 0.4 is very strict.
        # If it's not recognizing you, try 0.55 or 0.6.
        matches = face_recognition.compare_faces([admin_encoding], face_encoding, tolerance=0.55)

        if True in matches:
            print("🔓 [MATCH FOUND] Updating Web Gate...")
            
            try:
                # Check if DB connection is still alive
                if not db or not db.is_connected():
                    db = get_db_connection()
                
                cursor = db.cursor()
                cursor.execute("UPDATE ai_gate SET status = 1 WHERE id = 1")
                # No need for commit if autocommit is True
                print("✅ Signal Sent!")
            except Exception as e:
                print(f"❌ SQL Update Failed: {e}")

            # Feedback on camera window
            cv2.rectangle(frame, (0, 0), (640, 60), (0, 255, 0), -1)
            cv2.putText(frame, "WELCOME DARWESH - ACCESS GRANTED", (50, 40), 
                        cv2.FONT_HERSHEY_SIMPLEX, 0.8, (255, 255, 255), 2)
            cv2.imshow('FleetPro AI Gate', frame)
            cv2.waitKey(2000) 

    cv2.imshow('FleetPro AI Gate', frame)
    if cv2.waitKey(1) & 0xFF == ord('q'): break

video_capture.release()
cv2.destroyAllWindows()