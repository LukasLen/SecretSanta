# Secret Santa

A simple Secret Santa administration platform that let's you add/manage users and create/download games.

<p align="center">
  <img width="80%" title="Secret Santa Admin Panel" alt="Secret Santa Admin Panel" src="https://github.com/LukasLen/secretsanta/blob/master/screenshot.png">
</p>

## Installing

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Instructions

A step by step series of examples that tell you how to get your secret santa copy running.

* Import the file "sql/secetsanta.sql" to your database. The following commands are for mysql. Note: You can also import these files through your Database Management Client; for example phpMyAdmin.
  ```
  source sql/secetsanta.sql
  ```
* To import test data use the "sql/insert test data.sql" file. It will create three users and a game.
  ```
  source sql/insert test data.sql
  ```
* Modify the "config.php" file to your needs - Important: Set database values and email credentials

Now you can open up the Secret Santa application in your browser and login with these details:  
Email: ```admin@mail.com```  
Password: ```admin```

To login to the test users use:  
Emails:
```
samuel.serif@mail.com
will.barrow@mail.com
justin.case@mail.com
```  
Password: ```test```

### Deployment

To deploy on a live system make sure of the following:

* Webroot is in the folder "root" (Or restrict access to the files not in root, however, setting the webroot to root is suggested.)
* Check your "config.php" settings
* Change the admins name, email, and password
* Remove test data

## Built With

* [Datatables](https://datatables.net/) - Table management
* [Bootstrap](https://getbootstrap.com/) - Page layout
* [Bootswatch Litera](https://bootswatch.com/litera/) - Design

## Authors

* **Lukas Lenhardt** - *Initial work* - [LukasLen](https://github.com/LukasLen)
* **Benjamin Buzek** - *Initial work* - [benbuzek](https://github.com/benbuzek)
* **Alexander Gaddy** - *Initial work* - [gaddal](https://github.com/gaddal)

See also the list of [contributors](https://github.com/LukasLen/secretsanta/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments
* [Santa Favicon](https://www.favicon.cc/?action=icon&file_id=783185)
* [Background vector created by Starline - Freepik.com](https://www.freepik.com/free-photos-vectors/background)
