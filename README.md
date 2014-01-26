Author: Andy Hawkins
Email: YW5keUBhOTA0Z3V5LmNvbQ==
Facebook: http://fb.me/andyhawkins
HackerNews: http://hackerne.ws/user?id=a904guy
 
This file will allow you to create a project that will create global functions that allow you to instantly access and singleton classes from their class name
 
In the variables below I've defined a project folder called '/myproject/', and inside that folder there is an 'obj/' folder which contains your classes:
 
	/myproject/obj/
        	       db.class.php
	               cache.class.php
 
 
Once this code is executed, you can then call either easily from your code like so:
 
	db()->set_creds('l33t','h@#0r');
 
	if(is_null($data = cache()->get("mysqlquery")))
	{
	    $data = db()->query("Select...");
	    cache()->set("mysqlquery",$data);
	}
 
	var_dump($data);
 
etc.
 
You can even pass constructor variables to these functions to create the singletons, they are keep separately, so no two returned methods with parameters defined collide.
 
	db('mysql')
	db('sqlite')
 
Will return their respective classes initialized using the supplied variables. 


// Configuration
 
	$_CODE = array(); // Used to store object instances created.
 
	$_CODE['code_pwd'] = '/myproject/';
	$_CODE['objs_pwd'] = 'obj/';
 

// Descriptions

	function getPath()

getPath ensures that the path given cannot break from the chroot path inside $_CODE['code_pwd'] or it will return false;
   
I would recommend using this in any project so that if you have to use variables within an include (dear jebus, DON'T!)
it will atleast make sure they are chrooted to your project path


	class singleton {}

The singleton class will scan the folder in question looking for files ending in .class.php, it will then create a function using the file/class name that can be called anywhere. I'll be burned alive for implementing "eval", but I assure you there is no way to exploit it. If you a wizard and do so, please email me, and I'll buy you a beer.
 
It will not actually read the contents of the files for the sake of speed, so Naming convention has to match the filename db = db.class.php (This can be changed below easily).
 
Although you can make class revisions easily without breaking your code by specifying in db.class.php that class_alias('db_v2','db') and call the new class by using db() still.
 
NOTE: I haven't used this to work with namespaces yet, but it could easily be done so with a folder, and file naming convention logic.
 
	function o()

Factory Function o called from dynamically created Functions initialized in the Singleton Class to return the object.
This function also handles keep track of the previously initialized objects and return them as needed.
 

	function func_clean_args()

Simple function to rearrange the func_get_args, to work with ReflectionClass::newInstanceArgs()
This could be turned into a map but what the hell it works great and is fast;
