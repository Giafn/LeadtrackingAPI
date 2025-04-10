
# 📊 Lead Tracking API

Tugas Laravel API untuk sistem pelacakan leads, digunakan sebagai backend tugas integrasi dengan Google Form dan sistem login menggunakan JWT.

----------

## 🌐 Online Endpoint

Base URL:

```
https://leadtrack.giafauzan.my.id/api

```

----------

## 🔐 Autentikasi

### 1. Login (JWT)

Digunakan untuk login dan mendapatkan JWT token.

-   **Endpoint:** `POST /login`
    
-   **Headers:** None
    
-   **Body:**
    
    ```json
    {
      "email": "admin@realestate.com",
      "password": "password"
    }
    
    ```
    
-   **Response (Success):**
    
    ```json
    {
	    "success":true,
	    "user":{
		    "id":"faae806e-a752-4e3a-a4b5-e1b555224665",
			"name":"Admin",
			"email":"admin@realestate.com"
			},
		"token":"eyJ0eXAiOiJKV1QiLCJhbGci.."
	}
    
    ```
    

----------

## 📅 Leads

### 2. Get All Leads (JWT Required)

-   **Endpoint:** `GET /leads`
    
-   **Headers:**
    
    ```
    Authorization: Bearer <JWT_TOKEN>
    
    ```
    
-   **Query Parameters (optional):**
    
    -   `status`: Filter berdasarkan status (New,Prospect,Proses Dokumen & Legal,Selesai)
        
    -   `search`: Pencarian bebas ke semua kolom
        
    -   `per_page`: Jumlah item per halaman
        
    -   `page`: Nomor halaman
        
-   **Example:**
    
    ```
    GET /leads?status=New&search=john&per_page=10&page=2
    
    ```
    

----------

### 3. Create Lead (Token Only)

-   **Endpoint:** `POST /leads`
    
-   **Headers:**
    
    ```
    Token: secret
    
    ```
    
-   **Body:**
    
    ```json
    {
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "08123456789",
      "location": "Jl. Merdeka No.123"
    }
    
    ```
    

> Token dapat diubah di file `.env` menggunakan key `GOOGLE_FORM_TOKEN`.

----------

### 4. Update Lead Status (JWT Required)

-   **Endpoint:** `PUT /leads/{leadId}/status`
    
-   **Headers:**
    
    ```
    Authorization: Bearer <JWT_TOKEN>
    
    ```
    
-   **Body:**
    
    ```json
    {
      "new_status": "Selesai",// New,Prospect,Proses Dokumen & Legal,Selesai
      "notes": "Lunas"
    }
    
    ```
    

----------

## 📄 Google Form Integration

-   Google Form: [https://forms.gle/hu79TwLVrRz1iToB6](https://forms.gle/hu79TwLVrRz1iToB6)
    
-   Form ini akan memicu Google Apps Script yang mengirimkan data ke endpoint `POST /leads` dengan header `Token`.
    

----------

## 🚀 Repository

-   GitHub: [https://github.com/Giafn/LeadtrackingAPI](https://github.com/Giafn/LeadtrackingAPI)
    

----------

## 🔧 Seeder Default Login

Digunakan untuk testing login JWT:

-   **Email:** `admin@realestate.com`
    
-   **Password:** `password`
    

----------