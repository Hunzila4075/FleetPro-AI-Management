#  FleetPro AI: Biometric Bus Management System

FleetPro AI is a modern fleet management dashboard featuring a **Biometric AI Security Layer**. This project replaces traditional password-based logins with high-speed Facial Recognition by bridging **PHP** and **Python**.

---

##  Key Features
* **Biometric Unlock:** Silent background facial scanning using a "headless" Python AI engine.
* **Turbo-Speed Detection:** Optimized for low-latency logins (under 2 seconds).
* **Dual-Authentication:** Support for both manual (SQL-based) and biometric (AI-based) login.
* **Real-time Bridge:** Uses a MySQL "Gate" system to synchronize standalone Python scripts with web sessions.

---

##  Tech Stack
- **Web:** PHP, JavaScript (Fetch API), CSS3 (Modern UI)
- **Database:** MySQL
- **AI/ML:** Python, OpenCV, Face_Recognition library

---

##  System Architecture
The system operates on a unique cross-language polling architecture:
1. **Frontend:** User clicks "Biometric Unlock" on the PHP page.
2. **Backend:** PHP triggers the Python AI script in the background.
3. **AI Engine:** Python captures frames, recognizes the face, and updates the `ai_gate` table in MySQL.
4. **Automation:** The PHP page detects the database change and automatically redirects the user to the dashboard.



---

##  Installation & Setup
1. **Database:** Import the `bus_system.sql` file into your MySQL server.
2. **PHP:** Place the web files in your server directory (e.g., `C:/laragon/www/bus_system/`).
3. **Python:** - Install dependencies: `pip install opencv-python face_recognition mysql-connector-python`
   - Update `ai_face_lock.py` with your database credentials and image path.
4. **Run:** Access `login.php` via your local server (e.g., `localhost/bus_system/login.php`).

---

##  Project Impact
This project demonstrates how to integrate high-level Machine Learning models into traditional web environments, providing a secure and efficient user experience for transportation logistics.

---
---
**Developed by Hunzila Khan** 🔗 [LinkedIn Profile](https://www.linkedin.com/in/hunzila-khan-6a43822a0/) | 📂 [GitHub Portfolio](https://github.com/Hunzila4075)
