# Laravel Settings Package
- Laravel settings control as like as WordPress options

## Table of Contents
- [Laravel Settings Package](#laravel-settings-package)
  - [Table of Contents](#table-of-contents)
  - [Introduction](#introduction)
  - [Installation](#installation)
    - [Publish Migration, Factory, Config, and Seeder](#publish-migration-factory-config-and-seeder)
    - [Run Migration](#run-migration)
    - [Run Seeder](#run-seeder)
  - [Usage](#usage)
    - [Check if a Key Exists in Database](#check-if-a-key-exists-in-database)
    - [Get Settings Field](#get-settings-field)
    - [Get All Settings Fields](#get-all-settings-fields)
    - [Set Settings](#set-settings)
    - [Update Settings](#update-settings)
    - [Update or Create Settings](#update-or-create-settings)
    - [Delete Settings](#delete-settings)
  - [Settings Table CRUD](#settings-table-crud)
  - [Contribution Guide](#contribution-guide)
  - [License](#license)

## Introduction
The Laravel Settings package simplifies the management of application settings in your Laravel project. This README provides installation instructions, usage examples, and additional information.

## Installation
To get started, install the package using Composer:

```shell
composer require anisaronno/laravel-settings
```

### Publish Migration, Factory, Config, and Seeder
You need to publish migration files, factories, configuration files, and a seeder:

```shell
php artisan vendor:publish --tag=settings-migration
```

### Run Migration
Apply the migrations to set up the settings table:

```shell
php artisan migrate
```

### Run Seeder
Seed the settings table with initial data:

```shell
php artisan db:seed --class=AnisAronno\\LaravelSettings\\Database\\Seeders\\LaravelSettingsSeeder
```

## Usage
The package provides methods for managing settings. Here are the available functions:

### Check if a Key Exists in Database
You can use the `hasSettings` method to check if a key exists in the database:

```php
hasSettings(string $key);
```

### Get Settings Field
Retrieve a specific setting using its key:

```php
getSettings(string $key);
```

### Get All Settings Fields
Fetch all settings fields:

```php
getAllSettings();
```

### Set Settings
Create or update a setting:

```php
setSettings(string $key, string $value);
```

### Update Settings
Update an existing setting:

```php
updateSettings(string $key, string $value);
```

### Update or Create Settings
Update or Create setting:

```php
upsertSettings(string $key, string $value);
```

### Delete Settings
Update an existing setting:

```php
deleteSettings(string $key);
```

## Settings Table CRUD
To manage your settings table, you can use the following routes:

- Get all settings: `api/v1/settings` (GET) (name: `settings.index`) - No middleware required
- Get a single setting: `api/v1/settings/{setting_key}` (GET) (name: `settings.show`) - No middleware required
- Store a new setting: `api/v1/settings` (POST) (name: `settings.store`)
- Update a setting: `api/v1/settings/update/{setting_key}` (POST) (name: `settings.update`)
- Delete a setting: `api/v1/settings/{setting_key}` (DELETE) (name: `settings.destroy`)

You can customize the authentication guard for the routes by publishing the config file and changing the 'guard' key to your desired authentication guard:

```php
'guard' => ['auth'],
```

Make sure to publish the config file using the following command:

```shell
php artisan vendor:publish --tag=laravel-settings
```

To view the complete route list, run:

```shell
php artisan route:list
```

## Contribution Guide
Please follow our [Contribution Guide](https://github.com/anisAronno/multipurpose-admin-panel-boilerplate/blob/develop/CONTRIBUTING.md) if you'd like to contribute to this package.

## License
This package is open-source software licensed under the [MIT License](https://opensource.org/licenses/MIT).