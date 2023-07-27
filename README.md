
# Project_Certification

Hello, welcome to my certification project (Professional License RNCP31114 TP).

The project is about a library application that allows users to reserve books and manages stock and reservations on the library's side.


## Tech Stack

**Client:** TailwindCSS, Twig, Webpack Encore

**Server:** PHP 8.2, Symfony 6.2, MySQL


## Installation

- Clone this repository

- Go the project directory
```bash
  cd the-project
  ```
- Open the project with your favorite code editor
- Create a .env.local file, which is a copy of the .env file but with your database informations

- Install dependencies

```bash
  composer install 
  ```
```bash
  yarn install 
```
  
  - Set up the local database 
  ```bash
  symfony console doctrine:database:create 
  ```
```bash
  symfony console doctrine:migrations:migrate 
```
```bash
  symfony console doctrine:fixtures:load 
```
- Start the local server
 ```bash
  symfony serve -d 
  ```
  ```bash
  yarn watch
```
## Authors

- [@Alex](https://github.com/Chadowww)


