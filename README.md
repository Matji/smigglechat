# smigglechat
This Chat application uses ajax long polling technique.
The front end uses ajax to consume PHP Rest api that returns a JSON response
#DB config
Import the sample db <b>root/dbchat.sql</b>, Inside folder config/db.php
modify $user and $password to you own
#Folder Structure
 1. /controller - consist of the php(api) files. main.php and db.php
 2. /views - consist of different views. login, register and etc.
 3. index.php - This is the root file that checks if a user is logged in or not, then redirects accordingly
 4. /js/apicall.js - This is file that handles the request and response from/to the api using ajax
 5. The file <b>apicall.js</b> handles the application's request and response
 6.  Inside <b>apicall.js</b> is a function makeRequest(params), Its a re-usable ajax call based on the users action, Login, Register, getusers etc  
 7.  For url parameters i used a special url parser <b>/js/urlparser.js</b>
     <pre>
       function makeRequest(params)
       {
         //makes ajax call to main.php
       }
     </pre>
    An example of login action
    <pre>
      var params = {'request':'login',...} //the variable "request" is very important, Its used by the php script to know what action to perfom
      var returned_data = makeRequest(params);
    </pre>
    
    #Back end(API)
    
    <b>/controller/main.php</b> This is the file that does all the work
    This file consist of a simple class <b>Chat</b> with different functions e.g login(), getMessages(), registerUser() etc.
    These funcions are called based on the user's request parameter that is send by the ajax call, $_REQUEST["request"]
    
    #Compatibility
     PHP 5+
     
     Browsers - All major browsers 
    
    
    
