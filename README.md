# BankSoalUSK - วิธีรันโปรเจกต์ด้วย Docker Compose

## ขั้นตอนการใช้งาน

1. **ติดตั้ง [Docker Desktop](https://www.docker.com/products/docker-desktop/)**  
   รองรับทั้ง Windows, Mac และ Linux

2. **Clone โปรเจกต์นี้จาก GitHub**
   ```sh
   git clone https://github.com/namesiexz6/BankSoalUSK.git
   cd BankSoalUSK
   ```

3. **คัดลอกไฟล์ .env (ถ้ายังไม่มี)**
   ```sh
   cp .env.example .env
   ```
   หรือใช้ไฟล์ `.env` ที่มีอยู่แล้ว

4. **สั่งรันโปรเจกต์**
   ```sh
   docker compose up --build
   ```

5. **เปิดเว็บเบราว์เซอร์ไปที่**
   ```
   http://localhost:8000
   ```

---

## หมายเหตุ

- **หากต้องการหยุดโปรเจกต์**  
  กด `Ctrl+C` หรือใช้คำสั่ง  
  ```sh
  docker compose down
  ```
---

หากมีปัญหาในการใช้งาน สามารถแจ้งรายละเอียด error เพื่อขอคำแนะนำเพิ่มเติมได้

![image](https://github.com/user-attachments/assets/0993e31b-9da6-4c32-9550-b1c433718cf6)

![image](https://github.com/user-attachments/assets/ee3cc40b-35d3-4a35-90c5-78430f019432)


