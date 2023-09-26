<img align="left" src="https://github.com/BlackyDrum/laravel-chatroom/assets/111639941/ea09295d-bde2-4279-935a-aa920c410854" />

<br />

<img src="https://github.com/BlackyDrum/laravel-chatroom/assets/111639941/0d4d9da6-d0ab-47c8-8ee0-90ccbe40a0e7"><br />

**Made with Laravel, Vue and Inertia**

<br />

[![Generic badge](https://img.shields.io/badge/Status-In_Progress-orange.svg)](https://shields.io/) [![Generic badge](https://img.shields.io/badge/License-MIT-<COLOR>.svg)](https://shields.io/) 
 
<br />

<img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"> <img src="https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vuedotjs&logoColor=4FC08D"> <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white"> <img src="https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white">

![example-chat](https://github.com/BlackyDrum/laravel-chatroom/assets/111639941/0a314005-1f12-4e52-ab2a-aa33818c62f9)



## Requirements
<ul>
    <li>PHP: ^8.1</li>
    <li>Node: ^18.12</li>
    <li>Composer: ^2.5</li>
</ul>

## Installation
**Follow these steps to get the chatroom up and running on your local machine:**
1. **Clone the repository:**
```
$ git clone https://github.com/BlackyDrum/laravel-chatroom.git
```
2. **Navigate to the project directory:**
```
$ cd laravel-chatroom
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

## OAuth Authentication
To enable login with Google or GitHub, you need to create OAuth apps on their respective platforms and set the client ID, client secret and client callback in the ``.env`` file.


## Admin commands
Admins can execute the following commands in the chatroom
<table>
    <thead>
        <td>Command</td>
        <td>Description</td>
    </thead>
    <tr>
        <td>/ban {id}</td>
        <td>Bans a user with the specified ID</td>
    </tr>
        <tr>
        <td>/unban {id}</td>
        <td>Unbans a user with the specified ID</td>
    </tr>
</table>

