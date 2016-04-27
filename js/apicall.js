$(document).ready(function(e){

//binding button click action
$("#btnLogin").click(function(){	 
	 var params = {'request':'login', 'email':$("#email").val(), 'password':$("#password").val()} 
	 var returned_data = makeRequest(params);
	 if(returned_data.success==1)
	 {
		 window.location.href = 'users.html?action=getusers&loggedas='+$("#email").val();
	 }
	 else
	 {
		 $(".login_message").html("Login error");
	 }	  
 })
 $("#btnsendMessage").click(function(){ 
     $('.messages_list ul').append('<li class="me">me : '+$("#message").val()+'</li>');	
	 var params = {'request':'sendMessage', 'message':$("#message").val(), 
	               'recepient_id':parseURL(window.location.href).searchObject.openeduser_id}; 
	 console.log(makeRequest(params)); 
	 })
$("#btnRegister").click(function(){
	 var params = {'request':'registerUser', 'email':$("#email").val(), 'password':$("#password").val()};
	 var returned_data = makeRequest(params);
	 console.log(makeRequest(params));
	 if(returned_data.success==1)
	 {
		 window.location.href = 'users.html?action=getusers&loggedas='+$("#email").val();
	 }	 
	})
	
//make ajax request based on action, get the current url "action" parameter from urlparser
var action = parseURL(window.location.href).searchObject.action;
var loggedas = parseURL(window.location.href).searchObject.loggedas;
switch(action)
{
 case "getusers" : 	
            $('.loggedas').html("Welcome, You are logged in as : "+loggedas);
			var params = {'request':'getusers'}; 
			var returnedData = makeRequest(params).Data;
			//create a list from result
			for(var x=0; x<returnedData.length; x++)
			{
			console.log(returnedData[x]);
			var listItem = '<li>'+returnedData[x].email
						  +'<a href="messages.html?action=getmessages&openeduser_id='+returnedData[x].pid+
						  '&openeduser_email='+returnedData[x].email+'"> | '+returnedData[x].startorcontinue+'</a>'
						  +'</li>';
			$('.loggedusers_list ul').append(listItem);
			}
		   //get unread messages
			var params2 = {'request':'getUnreadMessage'}; 
			var returnedData2 = makeRequest(params2).Data;
			$('.inboxcount').html(returnedData2.length);	
			for(var x=0; x<returnedData2.length; x++)
			{
				var listItem = '<li>'+
				           '<a href="messages.html?action=getmessages&openeduser_id='+returnedData2[x].sender_id+
				           '&messageread_id='+returnedData2[x].pid+'">'+returnedData2[x].message+'</a>'+
						   '</li>';
				$('.inboxmsg-list ul').append(listItem);			   
			}
   break;
   
 case "getmessages" :     
         var clickeduser_id = parseURL(window.location.href).searchObject.openeduser_id;
		 var openeduser_email = parseURL(window.location.href).searchObject.openeduser_email;
         var params = {'request':'getmessages', 'openeduser_id':clickeduser_id}; 
		 $(".opened_user").html(openeduser_email);
		 //if theres messages, create list
		 if(makeRequest(params).success>0)
		 {
			 var returnedData =  makeRequest(params).Data;  
			 for(var x=0; x<returnedData.length; x++)
			 { 
			   if(returnedData[x].sender=='me')
			     {
					var classname = 'me'; 
			     }
				 else
				 {
					var classname = 'friend';  
				 }			 
			    var listItem = '<li class="'+classname+'">'+
				                  returnedData[x].sender+' : '+returnedData[x].message+
								'</li>';
				 $('.messages_list ul').append(listItem);
			 } 
		 }
		 else
		 {
			 $('.messages_list ul').append("Start chatting");
		 }
		 
		 //if user clicked unread message
		 var messageread_id = parseURL(window.location.href).searchObject.messageread_id;
		 if(messageread_id!=null || messageread_id!=undefined)
		 {
			 var params = {'request':'messageRead','messageread_id':messageread_id};
			 console.log('update message');
			 console.log(makeRequest(params));
		 }
		 //continuosly check for new messages
		 var count = 0;
		 setInterval(incomingMessage,2000);
   break;
   
  default:
}
 
//ajax call
function makeRequest(params)
{
	console.log(params);
	var result;
	 $.ajax({
			 	async : false,
				type: 'POST',
				url: '../controller/main.php',
				data: params,
				dataType: 'json',
				beforeSend:function()
				{
				     $(".login_message").html('sending...');
				},
				success:function(data)
				{             
					 $(".login_message").html('done');  					 
					 returnResult(data);    
				} 
	 });
	 
	 function returnResult(data)
	 {
		 result = data;
	 }
	 
	 return result;
}	 

//special ajax call to check incoming messages, async:false, 
function incomingMessage()
{
	 var clickeduser_id = parseURL(window.location.href).searchObject.openeduser_id;
	 var params = {'request':'getNewmessage', 'openeduser_id':clickeduser_id};

	 $.ajax({ 
				type: 'POST',
				url: '../controller/main.php',
				data: params,
				dataType: 'json',
				beforeSend:function()
				{ 
				},
				success:function(data)
				{              					 
					if(data.success>0)
					{ 
					 var returnedData = data.Data; 
					 for(var x=0; x<returnedData.length; x++)
					 {
							 var listItem = '<li>'+returnedData[x].sender+' : '+returnedData[x].message+'</li>';
							 $('.messages_list ul').append(listItem);		 
					 }
					}
					else
					{
					 console.log("no new message");
					}
				} 
	 }); 
} 

//view/hide messages
$('.inbox').click(function(){
	  $('.inboxmsg-list').slideToggle();
	})
})
 