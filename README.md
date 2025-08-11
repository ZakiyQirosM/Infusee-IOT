# Infusee-AIoT Website Module

## 📌 Overview
is a web-based system built using Laravel to manage, monitor, and display data from IoT (Infusee) devices integrated with Machine Learning. This module is part of the Infusee-AIoT ecosystem, designed for real-time infusion monitoring in medical environments.

This repository focuses **only** on the web interface & backend API (Laravel).  
For IoT and ML modules, see:
- [Infusee-AIOT IoT Module]()
- [Infusee-AIOT ML Module]()

---

## Contributor
| Full Name | Affiliation | Email | LinkedIn |
|-----------|-------------|-------|----------|
| Zakiy Qiros Muhammad | Universitas Negeri Surabaya | zakiyqm@gmail.com | [link](https://www.linkedin.com/in/zakiy-qiros-muhammad-255a46309/) |
| Dicka Widiyapurnama | Universitas Negeri Surabaya | @gmail.com | [link]() |

## Setup
### Prerequisite Packages (Dependencies)
- 

## 🚀 Installation
1. **Clone Repository**
   ```
   bash
   git clone https://github.com/ZakiyQirosM/Infusee-AIOT-Web.git
   cd Infusee-AIOT-Web
   ```
2. **Install Dependencies**
    ```
    bash
    composer install
    npm install
    ```
3. **Environment Setup**
    ```
    bash
    cp .env.example .env
    php artisan key:generate
    ```
4. **Database Migration**
    ```
    bash
    php artisan migrate
    ```
5. **Run the Application**
    ```
    bash
    php artisan serve
    ```
   
## Supporting Documents
### Presentation Deck
- Link: [Presentation Deck](https://drive.google.com/drive/folders/1JgbOCcnIjJaw0wBBHcMKpqI83xUvOQuF?usp=sharing)

### Business Model Canvas
![BMC](Pictures/BMC.png)

### Short Video
Provide a link to your short video, that should includes the project background and how it works.
- Link: [Short Video](https://youtube.com/shorts/aCx1_MFqNic?feature=share)


## How to Cite
If you find this project useful, we'd grateful if you cite this repository:
```
@article{
...
}
```

## License
For academic and non-commercial use only.

## Acknowledgement
This project entitled <b>"GALURA - Galucoma Detection with Advanced Retinal Analysis"</b> is supported and funded by Startup Campus Indonesia and Indonesian Ministry of Education and Culture through the "**Kampus Merdeka: Magang dan Studi Independen Bersertifikasi (MSIB)**" program.
