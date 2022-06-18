# pokemon3d.net

<p align="center">
<img src="https://github.com/P3D-Legacy/pokemon3d.net/workflows/Laravel/badge.svg" alt="Test Status">
<img src="https://img.shields.io/github/issues/P3D-Legacy/pokemon3d.net" alt="Issues">
<img src="https://img.shields.io/github/v/tag/P3D-Legacy/pokemon3d.net" alt="Tag">
<img src="https://img.shields.io/github/contributors/P3D-Legacy/pokemon3d.net" alt="Contributors">
<img src="https://img.shields.io/github/license/P3D-Legacy/pokemon3d.net" alt="License">
<a href="https://discordapp.com/invite/EUhwdrq" target="_blank"><img src="https://img.shields.io/discord/299181628188524544" alt="Discord"></a>
<a href="https://pokemon3d.net" target="_blank"><img src="https://img.shields.io/website?down_color=red&down_message=offline&up_color=green&up_message=online&url=https%3A%2F%2Fpokemon3d.net" alt="Website"></a>
</p>

# About

Our new website is built with Laravel and Tailwind CSS. This new website has replaced the old design from 2014. With the new design, we have a much cleaner and more modern website. And we will more easily be adding more features to the website in the future.

# Contribute
Want to contribute to the project? Check out [CONTRIBUTING.md](CONTRIBUTING.md) for more info.

# Installation
Want to install the project? Here is a quick guide to installing the project.

Please check the official laravel installation guide for server requirements before you start, [official documentation](https://laravel.com/docs/9.x/installation).

Install all the dependencies using composer
``` bash
composer install
```
Install all the dependencies using npm
``` bash
npm install
```
Copy the example env file and make the required configuration changes in the .env file
``` bash
cp .env.example .env
```

Generate a new application key
``` bash
php artisan key:generate
```
We've made it easy for updating stuff for the application. Running this command will migrate the database, set settings, seed needed data, update API docs and more.
``` bash
php artisan p3d:update
```
Start the local development server on Windows:
``` bash
php artisan serve
```
You can now access the server at http://localhost:8000

Mac (Requires Laravel Valet):
``` bash
valet open
```

# Licence

This software is licensed under the GPL-3.0 License. Check out [LICENSE](LICENSE) for more info.
