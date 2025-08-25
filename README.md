# Sakola Project
A collection of tools to support school administrative completeness

## Prerequisites
- PHP 8.2+
- Node.js 22
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
User Manager is available via these endpoints:
```
POST    user/create
POST    user/update/{id}
POST    user/delete
```
Add institution in `tb_institusi` and its users in `tb_user_institusi`<br/><br/>
_**Note**: You have to set database configurations in the `.env` file before running all migrations._

### Additional ENV Configuration

Several environment variables must be set in the `.env` file. You are free to assign any appropriate values to them. The variables include:

- `dev_username` and `dev_password`: Used to manage users directly via the provided API endpoint.  
- `pdf_mode`: Defaults to `production`. You can set it to `development` if you need to test PDF generation.  
- `institusi_id`: Required when `pdf_mode` is set to `development`. It should contain a valid `tb_institusi.id` from the database.  
- `encryption_key`: Used with the `encrypt()` and `decrypt()` helpers to protect values passed via URL, such as IDs, tokens, etc.  
- `s3_access_key`, `s3_secret_key`, and `cloudflare_id`: Credentials required to use the Cloudflare S3-compatible API.

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
Or if you would like to build to your server, modify settings in `build.config.js`. Switch `mode` to `build` or `production` and then run: 
```
npm run build
```
Place all of built files inside your web server root.<br/>
`--------- DONE ---------` <br/>
Now the app is ready to use
