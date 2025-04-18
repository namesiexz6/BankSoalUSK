# BankSoalUSK - How to Run the Project with Docker Compose

## Quick Start

1. **Install [Docker Desktop](https://www.docker.com/products/docker-desktop/)**  
   Supports Windows, Mac, and Linux.

2. **Clone this project from GitHub**
   ```sh
   git clone https://github.com/namesiexz6/BankSoalUSK.git
   cd BankSoalUSK
   ```

3. **Copy the .env file (if you don't have one)**
   ```sh
   cp .env.example .env
   ```
   Or use your own `.env` file.

4. **Run the project**
   ```sh
   docker compose up --build
   ```

5. **Open your browser and go to**
   ```
   http://localhost:8000
   ```
   
6. **Register new account for test this aplication**
   ```
   http://localhost:8000/register
   ```

---

## Notes

- **To stop the project**  
  Press `Ctrl+C` in the terminal or run  
  ```sh
  docker compose down
  ```

- **No need to install PHP, Composer, XAMPP, or MySQL manually.**  
  Everything runs automatically in Docker.

- **If you change the code and want to rebuild the container:**  
  Press `Ctrl+C` to stop, then run `docker compose up --build` again.

- **If you want to reset the database and seed sample data:**  
  ```sh
  docker compose exec app php artisan migrate:fresh --seed
  ```

- **If you encounter any errors, please provide the error details for further assistance.**

---

## Screenshots

### Home page
![image](https://github.com/user-attachments/assets/0993e31b-9da6-4c32-9550-b1c433718cf6)

### Questions page (Before search)
![image](https://github.com/user-attachments/assets/ee3cc40b-35d3-4a35-90c5-78430f019432)

### Questions page (After search)
![image](https://github.com/user-attachments/assets/82247889-d611-4eb6-b48b-06251583c64f)

### Questions page (Questions List)
![image](https://github.com/user-attachments/assets/577e76f4-7bc8-42d6-b2bc-ac9ab044c0b5)

### Questions page (View Question)
![image](https://github.com/user-attachments/assets/343abc9b-3965-4849-8ad8-84704ae3fdfe)

### Questions page (Community)
![image](https://github.com/user-attachments/assets/5d1fec32-6bda-4628-99e3-eaccc2f15e29)

![image](https://github.com/user-attachments/assets/d6dff918-6326-4bc2-a981-d8df66b66a45)

![image](https://github.com/user-attachments/assets/8d370545-6ab6-426b-a88f-4d03ac99fc11)

### Management page (Question management)
![image](https://github.com/user-attachments/assets/d3f33167-8973-4090-8a5a-49dd5db9fbac)

### Management page (Upload Question by PDF file)
![image](https://github.com/user-attachments/assets/8f50ebfc-5042-4e1b-9bd7-40a2e25ba4e4)

### Management page (Make and upload Question by Text Editor)
![image](https://github.com/user-attachments/assets/768ed579-d3d0-4147-ad7c-944bcb5f6d14)
