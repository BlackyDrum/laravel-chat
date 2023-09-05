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

