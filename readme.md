# php_helpers
This are a few helper functions in PHP, One for sockets and another More less a mini-ORM 

THE SOCKET CLASS
================
This class is a one that arguments the usage of sockets, it has two Http methods it supports that is 
POST and GET
See Below:


POST
---------------------
```php
 $sock = new \helpers\sockets\Sock('https://example.com/');
    $data = array('sid' => '165DEM313E51485347795159927097DEMO','someID' => 'demo','hash' => '0313add30bcec487d9019f3f4236d7f927f0513cabc323299dbc7b3c9a76bf03');
    $get = $sock->method('POST', $data); //POST call with datas
    $read = $sock->read_data(); //if you wanna wait for the response data in the content-type that it came with 
    $read = $sock->read(); //if you wanna WAIT for the response or see it 
```
GET
------
```php
$sock = new \helpers\sockets\Sock('https://google.com');
    $get = $sock->method('GET'); //Just pass the  'GET' no need for data to be passed through
    $read = $sock->read(); //if you wanna WAIT for the response or see it 
    $sock->close();
```
> It Uses PSR4 namespacing just include the autoload.php file and the namespacing will be in effect used to call the classes without requiring the specifioc files.


THE ORM(Class)
==============

> More less a simple php -> mysql queryBuilder which allows for method chaining


e.g.
```php
$db = new \helpers\database\ORM_Model(array('dbname'=>'local_db','host'=>$local_host, 'user'=>$local_username, 'pass'=>$local_password));

$db->select('table', array('columname','col2','colthree'))->where('somecolumn','value')->orderby('dated','DESC')->limit(1)->get();//Get the most recent record

$db->select('table', array('columname','col2','colthree'))->where('somecolumn','value')->orderby('dated','DESC')->getAll();//Get the multiple records

```

