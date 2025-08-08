# Sakola Project
A collection of tools to support school administrative completeness

## Prerequisites
- PHP 8.2+
- Node.js 22
- NPM
- Composer
- MySQL Database

## API Setup
Install CodeIgniter and its dependencies
```
composer install
```
Run migration to install database
```
php spark migrate --all
```
User Manager is available to be used in Postman with this endpoint:
```
POST    user/create
POST    user/update/{id}
POST    user/delete
```
Add institution in `tb_institusi` and its users in `tb_user_institusi`<br/>
`--------- DONE ---------` <br/>
Now the API is ready to use.

## User Interface Setup
Install dependencies
```
npm install
```
Run with development mode:
```
npm run dev
```
Or if you would like to build to your server, modify settings in `build.config.js`. Switch `mode` to `build` or `production` and then run 
```
npm run build
```
Place all of built files inside your web server root<br/>
`--------- DONE ---------` <br/>
Now the app is ready to use
