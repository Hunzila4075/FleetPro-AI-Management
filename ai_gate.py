import cv2
import face_recognition
import mysql.connector
import os
import time

# --- 1. STABLE DATABASE CONNECTION FUNCTION ---
def connect_db():
    try:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="1234",
            database="bus_system",
            autocommit=True # Ensures data is sent immediately
        )
        return conn
    except mysql.connector.Error as err:
        print(f"❌ DB Connection Failed: {err}")
        return None

db = connect_db()

# --- 2. FLEXIBLE IMAGE PATH CHECK ---
# Checking both .jpg and .jpg.jpeg to prevent crashing
possible_paths = [
    r"C:\laragon\www\bus_system\known_faces\darwesh.jpg",
    r"C:\laragon\www\bus_system\known_faces\darwesh.jpg.jpeg"
]

image_path = None
for path in possible_paths:
    if os.path.exists(path):
        image_path = path
        break

if not image_path:
    print("❌ Error: Could not find darwesh.jpg or darwesh.jpg.jpeg. Check your folder!")
    exit()

print(f"✅ Identity Found at: {image_path}")
admin_image = face_recognition.load_image_file(image_path)
admin_encoding = face_recognition.face_encodings(admin_image)[0]

# --- 3. STABLE SCANNER LOGIC ---
video_capture = cv2.VideoCapture(0)

# Check if camera is actually available
if not video_capture.isOpened():
    print("❌ Error: Camera light cannot turn on. Is another app using it?")
    exit()

print("🚀 AI Scanner: ACTIVE. Camera light should be ON.")

while True:
    ret, frame = video_capture.read()
    if not ret:
        print("Failed to grab frame. Re-initializing...")
        continue

    # Process smaller frame for speed
    small_frame = cv2.resize(frame, (0, 0), fx=0.25, fy=0.25)
    rgb_small_frame = cv2.cvtColor(small_frame, cv2.COLOR_BGR2RGB)

    face_locations = face_recognition.face_locations(rgb_small_frame)
    face_encodings = face_recognition.face_encodings(rgb_small_frame, face_locations)

    for face_encoding in face_encodings:
        matches = face_recognition.compare_faces([admin_encoding], face_encoding, tolerance=0.5)

        if True in matches:
            print("🔓 [MATCH] - Sending signal...")
            
            # Stable SQL Execution
            try:
                if not db.is_connected():
                    db = connect_db()
                cursor = db.cursor()
                cursor.execute("UPDATE ai_gate SET status = 1 WHERE id = 1")
                db.commit()
                print("✅ Signal sent successfully.")
            except Exception as e:
                print(f"❌ SQL Error: {e}")

            # Feedback on frame
            cv2.rectangle(frame, (0,0), (640,100), (0,255,0), -1)
            cv2.putText(frame, "ACCESS GRANTED", (150, 70), cv2.FONT_HERSHEY_SIMPLEX, 1.2, (255, 255, 255), 3)
            cv2.imshow('FleetPro AI Gate', frame)
            cv2.waitKey(2000)

    cv2.imshow('FleetPro AI Gate', frame)
    if cv2.waitKey(1) & 0xFF == ord('q'): break

video_capture.release()
cv2.destroyAllWindows()
db.close()