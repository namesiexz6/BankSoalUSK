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

- **ไม่ต้องติดตั้ง PHP, Composer, XAMPP หรือ MySQL เพิ่มเติม**  
  ทุกอย่างจะรันใน Docker อัตโนมัติ

- **ถ้าแก้ไขโค้ดแล้วอยากให้ container อัปเดต**  
  กด `Ctrl+C` เพื่อหยุด แล้วรัน `docker compose up --build` ใหม่

- **หากต้องการหยุดโปรเจกต์**  
  กด `Ctrl+C` หรือใช้คำสั่ง  
  ```sh
  docker compose down
  ```

- **Docker Compose จะ build image จาก Dockerfile ให้อัตโนมัติ**  
  ไม่ต้อง build เอง

---

หากมีปัญหาในการใช้งาน สามารถแจ้งรายละเอียด error เพื่อขอคำแนะนำเพิ่มเติมได้
