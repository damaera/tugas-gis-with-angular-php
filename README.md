#Tugas GIS

##Requirement
- PHP 5.3+
- MySQL
- Apache server

##Built With
- Slim PHP for create restful api
- Angular JS for client side mvc
- Mysql for database

##Config
- connectDB
```
function connectDB() {
    $dbhost="127.0.0.1";//change this
    $dbuser="root";//change this
    $dbpass="";//change this
    $dbname="simpedal";//change this
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);  
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}
```

##User
- username : admin, password : 1234
- username : guest, password : whatever
