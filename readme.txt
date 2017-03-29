It contains five files:
1. index.php
2. checklogin.php
3. home.php
4. order.php
5. logout.php

When the application starts, the first page that appears is index.php which contains a form, asking for name and keyword. 

Once user submits, all the validations and setting up session takes place in checklogin.php and then it redirected to home.php if the user is in the database else it will be redirected to the login page itself, after an alert saying that Incorrect Username. 

Home.php will contain all the products that have the keyword. And from there the products can be ordered by the user by entering the quantity of the products that the user wants. 

Once the user submits the products that he wants, he can see the summary of the products which he wanted, and out of them which are in stock and which are not. For the ones, which are in stock they get ordered, the rest dont. 

Logout.php is the page where the session is cleared and the user is logged out. 

To use the code, you need to have an existing database, which is mysql, and it should have the database named marketplace. Also apache server needs to be set and running. Plce these files in the directory where all web apps are contained in the server folder and Thats it. You are good to go. 

type in the url in the browser that application is up and running. 