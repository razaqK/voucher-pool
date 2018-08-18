# Voucher API [Documentation](https://documenter.getpostman.com/view/1419985/RWToQdhZ)

### Using Docker to install.
 - Clone `git@github.com:razaqK/voucher-pool.git`
 - Run `cd voucher-pool`
 - Run `docker-compose up`. This command build the images and start the containers
 - On you browser go to [localhost:port](http://127.0.0.1)
 - Note: the default port is 80. You can change the port to your desire in the docker-compose.yml file.

### To change port
 - Open the docker-compose.yml. you see ports key with value `80:80` 
 - Change the prefix 80 to any port of your choice `e.g 88:80`
 - Run `docker-compose up` to build and start the app.
 
### Run Test Manually
 - Run `docker exec -it voucherpool bash`
 - Run command `vendor/bin/phpunit` 
 
### API Documentation
Check the api documentation [here](https://documenter.getpostman.com/view/1419985/RWToQdhZ)

# Database Configuration
 - Run `php /var/www/vendor/bin/phinx migrate`
 - OR use the `database_dump` in `data` directory

### Database Schema
![alt database schema](https://raw.githubusercontent.com/razaqk/voucher-pool/master/data/voucher.png)

### Development on your local machine

#### Set your Application Environment

```
SetEnv APPLICATION_ENV "local"
```

#### Set DB credentials

```
SetEnv DB_HOST "127.0.0.1"
SetEnv DB_USERNAME "root"
SetEnv DB_PASSWORD "root"
SetEnv DB_DBNAME "voucher"
```

### Staging Server

#### Set your Application Environment

```
SetEnv APPLICATION_ENV "staging"
```

### Production

#### Set your Application Environment

```
SetEnv APPLICATION_ENV "production"
```
