<div align='right'>
    <a href="./README.md">Inglês |</a>
    <a href="./PORTUGUESE.md">Português</a>
</div>

<div align='center'>
    <h1>Marketplace</h1>
    <a href="https://www.linkedin.com/in/leonardo-akio/" target="_blank"><img src="https://img.shields.io/badge/LinkedIn%20-blue?style=flat&logo=linkedin&labelColor=blue" target="_blank"></a> 
    <img src="https://img.shields.io/badge/version-v0.1-blue"/>
    <img src="https://img.shields.io/github/contributors/akioleo/Marketplace_Laravel"/>
    <img src="https://img.shields.io/github/stars/akioleo/Marketplace_Laravel?style=sociale"/>
    <img src="https://img.shields.io/github/forks/akioleo/Marketplace_Laravel?style=social"/>
    <img src="https://img.shields.io/badge/License-MIT-blue"/>
</div>
<br>

The project is based on a marketplace with `laravel 6`, where we have a stores and products registration, with routes for admin, shopkeepers and the store itself, where customers can make purchases directly on the system.

## Table of Contents
- [Getting Started](#getting-started)
	- [Installation](#installation)
	- [Configuration](#configuration)
	- [Versions](#versions)
- [Structure](#structure)
- [Development](#development)
    - [Database relationships](#database-relationships)
        - [User-Store](#user-store) 
        - [Store-Products](#store-products)
        - [Products-Categories](#products-categories)
	- [Part 2: Heading](#part-2-heading)
- [Contributing](#contributing)
- [License](#license)


## Getting Started
Open and view the Project using the `.zip` file provided
<br/>
Or to get started, clone the repository to your directory

    git clone https://github.com/akioleo/Marketplace_Laravel.git
    
Start the local development server
```bash
> php artisan serve
```   

### Installation
Install all the dependencies using composer
```bash
> composer install
```
Generate a new artisan key
```bash
> php artisan key:generate
```

### Configuration
Create a new *.env* archive based on *.env.example*
```bash
> php -r "copy('.env.example', '.env');
```
In new `.env` file type your database credentials in these lines<br/>
*Obs: **DB_CONNECTION** changes by the database used. Example: Postgre database (**pgsql**), sqlite, sqlsrv*

    DB_CONNECTION=mysql  
    DB_HOST=127.0.0.1  
    DB_PORT=3306  
    DB_DATABASE=laravel  
    DB_USERNAME=root  
    DB_PASSWORD=
 

Include personal <a href="https://acesso.pagseguro.uol.com.br/sandbox">PagSeguro</a> credentials

    DB_CONNECTION=mysql  
    DB_HOST=127.0.0.1  
    DB_PORT=3306  
    DB_DATABASE=laravel  
    DB_USERNAME=root  
    DB_PASSWORD=
 
Run the database migrations to create predefined database tables 
```bash
> php artisan migrate  
```   
Run seeds to populate the database with data we want to develop in future
```bash
> php artisan db:seed  
```    
### Versions
We can check tools versions to avoid some errors 

    php --version  |  composer --version  |  laravel --version
    
***Note*** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command
```bash
> php artisan migrate:refresh
```
## Structure 

```bash
├── app
├── artisan
├── composer.json
├── composer.lock
├── config
├── database
├── package.json
├── phpunit.xml
├── public
├── resources
├── routes
├── server.php
├── storage.
├── tests
├── vendor
├── webpack.mix.js
├── yarn.lock
```


## Development

### Database relationships

#### User-Store
1:1 relationship - where one user ***hasOne*** store and one store ***belongsTo*** an user
```php
$this->hasOne(Store::class);
$this->belongsTo(User::class);
```
#### Store-Products
1:N relationship - where one store ***hasMany*** products and one product ***belongsTo*** an store
```php
$this->hasMany(Product::class);
$this->belongsTo(Store::class);
```
#### Products-Categories
N:N relationship - where products ***belongsToMany*** categories and categories ***belongsToMany*** products
```php
$this->belongsToMany(Product::class);
$this->belongsToMany(Category::class);
```


### Part 2: Heading

* Mention the steps here

## Contributing

We'd love to have your helping hand on `Marketplace`. If you have any contribuition, we can rate some pull requests.


## License

`Marketplace` is open source software [licensed as MIT][license].

[license]: https://github.com/git/git-scm.com/blob/main/MIT-LICENSE.txt
