# Laravel Settings Package

## Table of Contents
1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Usage](#usage)
    - [Get Settings Field](#get-settings-field)
    - [Get All Settings Fields](#get-all-settings-fields)
    - [Set Settings](#set-settings)
    - [Update Settings](#update-settings)
4. [Settings Table CRUD](#settings-table-crud)
5. [Contribution Guide](#contribution-guide)
6. [License](#license)

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

### Define User Model Relation (Optional)
If you want to relate settings to a user, add the following relation in your User model:

```php
public function settingsProperties(): HasMany
{
    return $this->hasMany(SettingsProperty::class, 'user_id', 'id');
}
```

## Usage
The package provides methods for managing settings. Here are the available functions:

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

## Settings Table CRUD
To manage your settings table, you can use the following routes:

- Get all settings: `api/settings` (GET)
- Get a single setting: `api/settings/{setting_key}` (GET)
- Store a new setting: `api/settings` (POST)
- Update a setting: `api/settings/{setting_key}` (POST)
- Bulk update settings: `api/settings/bulk/update` (POST)
- Delete a setting: `api/settings/{setting_key}` (DELETE)

To view the complete route list, run:

```shell
php artisan route:list
```

## Contribution Guide
Please follow our [Contribution Guide](https://github.com/anisAronno/multipurpose-admin-panel-boilerplate/blob/develop/CONTRIBUTING.md) if you'd like to contribute to this package.

## License
This package is open-source software licensed under the [MIT License](https://opensource.org/licenses/MIT).
```
