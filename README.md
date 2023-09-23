<img align="left" src="https://github.com/BlackyDrum/simple-chat/assets/111639941/56e2acdc-f67b-4632-b75d-0ebc281793a9" />

<br />

<img src="https://github.com/BlackyDrum/laravel-chatroom/assets/111639941/b2ee0682-5130-4680-bb05-27bdaa268b47"><br />

**Made with Laravel, Vue and Inertia**

<br />

[![Generic badge](https://img.shields.io/badge/Status-In_Progress-orange.svg)](https://shields.io/) [![Generic badge](https://img.shields.io/badge/License-MIT-<COLOR>.svg)](https://shields.io/) 
 
<br />

<img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"> <img src="https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vuedotjs&logoColor=4FC08D"> <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white"> <img src="https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white">



<p>
Description...
</p>

## Requirements
<ul>
    <li>PHP: ^8.1</li>
    <li>Node: ^18.12</li>
    <li>Composer: ^2.5</li>
</ul>

## Installation
**Follow these steps to get the simple chat up and running on your local machine:**
1. **Clone the repository:**
```
$ git clone https://github.com/BlackyDrum/simple-chat.git
```
2. **Navigate to the project directory:**
```
$ cd simple-chat
```
3. **Install the dependencies:**
```
$ composer install
```
4. **Create a copy of the .env.example file and rename it to .env. Update the necessary configuration values such as the Database and Pusher credentials:**
```
$ cp .env.example .env
```
5. **Generate an application key:**
```
$ php artisan key:generate
```
6. **Run the database migrations:**
```
$ php artisan migrate
```
7. **Install JavaScript dependencies:**
```
$ npm install
```
8. **Build the assets:**
```
$ npm run dev
```
9. **Start the development server:**
```
$ php artisan serve
```
10. **Start the websocket server:**
```
$ php artisan websockets:serve
```
11. **Visit http://localhost:8000 in your web browser to access the application.**

