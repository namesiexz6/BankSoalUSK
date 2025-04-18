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

## Home page
![image](https://github.com/user-attachments/assets/0993e31b-9da6-4c32-9550-b1c433718cf6)

## Questions page ( Before search )
![image](https://github.com/user-attachments/assets/ee3cc40b-35d3-4a35-90c5-78430f019432)

## Questions page ( After search )
![image](https://github.com/user-attachments/assets/82247889-d611-4eb6-b48b-06251583c64f)

## Questions page ( Questions List )
![image](https://github.com/user-attachments/assets/577e76f4-7bc8-42d6-b2bc-ac9ab044c0b5)

## Questions page ( View Question )
![image](https://github.com/user-attachments/assets/343abc9b-3965-4849-8ad8-84704ae3fdfe)

## Questions page ( Comunity )
![image](https://github.com/user-attachments/assets/5d1fec32-6bda-4628-99e3-eaccc2f15e29)

![image](https://github.com/user-attachments/assets/d6dff918-6326-4bc2-a981-d8df66b66a45)

![image](https://github.com/user-attachments/assets/8d370545-6ab6-426b-a88f-4d03ac99fc11)

## Management page ( Question management )
![image](https://github.com/user-attachments/assets/d3f33167-8973-4090-8a5a-49dd5db9fbac)

## Management page ( Upload Question by PDF file )
![image](https://github.com/user-attachments/assets/8f50ebfc-5042-4e1b-9bd7-40a2e25ba4e4)

## Management page ( Make and upload Question by Text Editer )
![image](https://github.com/user-attachments/assets/768ed579-d3d0-4147-ad7c-944bcb5f6d14)






