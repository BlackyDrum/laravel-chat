<img align="left" src="https://github.com/BlackyDrum/laravel-chatroom/assets/111639941/8b685afa-42b6-4240-96e3-9d63603ebaa6" />

<br />

<img src="https://github.com/BlackyDrum/laravel-chatroom/assets/111639941/ca5843d5-835f-4c13-8134-33cd58967c1f"><br />

**Engage in real-time conversations with others**

<br />

[![Generic badge](https://img.shields.io/badge/Status-Finished-green.svg)](https://shields.io/) [![Generic badge](https://img.shields.io/badge/License-MIT-<COLOR>.svg)](https://shields.io/) 

<br />

<img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"> <img src="https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vuedotjs&logoColor=4FC08D"> <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white"> <img src="https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white">

---

![example-chat-6](https://github.com/BlackyDrum/laravel-chatroom/assets/111639941/c63adab1-c3c9-4bfa-8d4d-662f6d228d84)

## Features
- Create chat rooms with optional passwords
- Real-time chat with other users
- OAuth authentication with Google and GitHub
- Admin commands for user management

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
To enable login with Google or GitHub, you need to create OAuth apps on their respective platforms and set the ``client ID``, ``client secret`` and ``client callback`` in the ``.env`` file.


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
    <tr>
        <td>/mute {id}</td>
        <td>Mutes a user with the specified ID</td>
    </tr>
    <tr>
        <td>/unmute {id}</td>
        <td>Unmutes a user with the specified ID</td>
    </tr>
</table>

