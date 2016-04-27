<?php
session_start();

 class Chat
 {
     private $conn = '';
     
     function __construct()
     {
          include 'db.php';
          $this->conn = new MySQLi($host, $user , $pass, $db); 
          if ($this->conn->connect_error) {
               die('Connect Error, '. $this->conn->connect_errno . ': ' . $this->conn->connect_error);
           } 
     }
     
     public function login($email, $pass)
     {
         $result['success'] = 0; 
         $conn = $this->conn;
         if($conn)
         {
             $sql = "select * from tbl_users where email = ? and password=?";
             $smt = $conn->prepare($sql);
             $smt->bind_param('ss', $email, $pass);
             $smt->execute();
             $res = $smt->get_result();
             $count = 0;
             while($row = $res->fetch_array())
             {     
                  $count++;
                  $email = $row["email"];
                  $pid = $row["pid"]; 
             } 
             if($count>0)
             {
                 $result['data']['email'] = $email;
                 $result['data']['pid'] = $pid;
                 $_SESSION["email"] = $email;
                 $_SESSION["pid"] = $pid;
                 $result['success'] = 1; 
             }  
         }        
         return $result;
     }
     
     public function getUsers($pid)
     {
         $result['success'] = 0; 
         $array = [];
         $conn = $this->conn;
         if($conn)
         {
            $sql = "select 
                    u.pid as pid,
                    u.email as email,
                    count(m.pid) as no_of_messages
                    from tbl_users u
                    left outer join tbl_messages as m on (m.sender_id = ? and m.recepient_id = u.pid) or (m.sender_id = u.pid and m.recepient_id = ?)
                    where u.pid != ? group by u.pid";
             $smt = $conn->prepare($sql);   
              $smt->bind_param('iii', $pid, $pid, $pid);
             $smt->execute();
             $res = $smt->get_result();
             $count = 0;
             while($row = $res->fetch_array())
             {     
                  $count++;
                  $startORcontinue = "start chatting";
                  if($row["no_of_messages"]>0)
                  {
                    $startORcontinue = "continue chatting"; 
                  }
                  array_push($array, array("pid"=>$row["pid"], "email"=>$row["email"], "startorcontinue"=>$startORcontinue));                  
             } 
             if($count>0)
             { 
                 $result['success'] = 1;
                 $result['Data'] = $array;
             }  
         }        
         return $result;              
     }
     
     public function getMessages($loggeduser_id, $openeduser_id)
     {
         $conn = $this->conn;
         $array = [];
         $result['success'] = 0;
         if($conn)
         {
             $sql = "select 
                    u.pid as sender_id,
                    u.email as sender_email,
                    m.message as message,
                    m.pid as message_id
                    from tbl_messages as m
                    inner join tbl_users as u on u.pid = m.sender_id
                    where (recepient_id =? and sender_id = ?) or (recepient_id =? and sender_id = ?)";
             $smt = $conn->prepare($sql); 
             $smt->bind_param('iiii', $loggeduser_id, $openeduser_id, $openeduser_id, $loggeduser_id);
             $smt->execute();
             $res = $smt->get_result();
             $count = 0;
             while($row = $res->fetch_array())
             {     
                  $count++;
                  $sender = $row["sender_email"];
                  if($row["sender_id"]==$_SESSION['pid'])
                  {
                      $sender = "me";
                  } 
                  array_push($array, array("pid"=>$row["message_id"], "message"=>$row["message"], "sender"=>$sender));                  
             } 
             if($count>0)
             { 
                 $result['success'] = 1;
                 $result['Data'] = $array;
             }  
         } 
         return $result;  
     }
     
     public function sendMessage($sender_id, $recepient_id, $message)
     { 
         $conn = $this->conn;
         $result['success'] = 0; 
          //insert into tbl_customers
         $sql1 = 'insert into tbl_messages(sender_id, recepient_id, message, timesend) values(?, ?, ?, now())';
         $smt1 =  $conn->prepare($sql1);
         $smt1->bind_param('sss', $sender_id, $recepient_id, $message);
         $smt1->execute(); 
         if(($conn->affected_rows)>0)
         {
            $result['success'] = 1; 
         }
        
        return $result;
     }
     
     public function getNewmessage($recepient_id, $sender_id)
     {
         $conn = $this->conn;
         $array = [];
         $result['success'] = 0;
         if($conn)
         {
             $sql = "select 
                    m.pid as pid,
                    m.message as message,
                    u.email as sender_email 
                    from 
                    tbl_messages as m
                    inner join tbl_users as u on u.pid = m.sender_id
                    where recepient_id =? and sender_id = ? and
                    TIME_TO_SEC(TIMEDIFF(now(), m.timesend)<=2) and
                    readYN = 0
                    ";
             $smt = $conn->prepare($sql); 
             $smt->bind_param('ii', $recepient_id, $sender_id);
             $smt->execute();
             $res = $smt->get_result();
             $count = 0;
             while($row = $res->fetch_array())
             {     
                  $count++;   
                  array_push($array, array("pid"=>$row["pid"], "message"=>$row["message"], "sender"=>$row["sender_email"]));     
                  $this->messageRead($row["pid"]);
             } 
             if($count>0)
             { 
                 $result['success'] = 1;
                 $result['Data'] = $array;  
             }  
             
         } 
         return $result;           
     }
     
     function getUnreadMessage($recepient_id)
     {
         $conn = $this->conn;
         $result['success'] = 0;
         $array = [];
         $sql = "select 
                m.pid,
                m.message,
                u.pid as sender_id
                from tbl_messages as m
                inner join tbl_users as u on u.pid = m.sender_id
                where m.readYN =0 and  recepient_id = ?";
         $smt = $conn->prepare($sql); 
         $smt->bind_param('i', $recepient_id);
         $smt->execute();
         $res = $smt->get_result();
         $count = 0; 
         while($row = $res->fetch_array())
         {
            $count++;   
            array_push($array, array("pid"=>$row["pid"], "message"=>$row["message"], "sender_id"=>$row["sender_id"]));                 
         }
         if($count>0)
         { 
            $result['success'] = 1;
            $result['Data'] = $array;  
         }         
        return $result;
     }
     
     public function messageRead($pid)
     {
         $result['success'] = 0;
         $conn = $this->conn;
         $sql1 = 'update tbl_messages set readYN = 1 where pid = ?';
         $smt1 =  $conn->prepare($sql1); 
         $smt1->bind_param('i', $pid);
         $smt1->execute();  
         if($conn->affected_rows>0)
         {
             $result['success'] = 1;
         }
         return $result;
     }
     public function registerUser($email, $password)
     {
        $conn = $this->conn;
        $result['success'] = 0; 
        $sql = "insert into tbl_users(email, password) values (?, ?)"; 
        $smt = $conn->prepare($sql);
        $smt->bind_param("ss", $email, $password);
        $smt->execute(); 
        $pid = $conn->insert_id;
        if($conn->affected_rows>0)
        {
            $_SESSION["email"] = $email;
            $_SESSION["pid"] = $pid;
            $result['success'] = 1;
            $result['Data'] = array("pid"=>$pid, "email"=>$email);
        }
        return $result;
     }
     
     public function checkifLoggedIn()
     {
         if((isset($_SESSION['pid']) && !empty($_SESSION['pid'])) && (isset($_SESSION['email']) && !empty($_SESSION['email'])))
         {
             return true;
         } 
     }
     public function logout()
     { 
         if(session_destroy())
         {
             return array("session"=>"destroyed");
         }
     }
     
 }    
 
 //handle requests
 $chatOb = new Chat(); 
 $jsonResult = array("success"=>0, "message"=>"Not logged in");
 switch($_REQUEST['request'])
 {
     case 'login':  $jsonResult = $chatOb->login($_REQUEST['email'], $_REQUEST['password']);
         break;
     case 'getusers': if($chatOb->checkifLoggedIn())
                        $jsonResult = $chatOb->getUsers($_SESSION["pid"]);  
        break;
     case 'logout':$jsonResult = $chatOb->logout();
         break;     
     case 'getmessages': $jsonResult = $chatOb->getMessages($_SESSION['pid'], $_REQUEST['openeduser_id']);
         break;
     case 'sendMessage' : $jsonResult = $chatOb->sendMessage($_SESSION['pid'], $_REQUEST['recepient_id'], $_REQUEST['message']);
         break;
     case 'getNewmessage' : $jsonResult = $chatOb->getNewmessage($_SESSION['pid'], $_REQUEST['openeduser_id']);
         break;
     case 'registerUser': $jsonResult = $chatOb->registerUser($_REQUEST['email'], $_REQUEST['password']);
         break;
     case 'getUnreadMessage': $jsonResult = $chatOb->getUnreadMessage($_SESSION['pid']);
         break; 
    case 'messageRead': $jsonResult = $chatOb->messageRead($_REQUEST["messageread_id"]);
         break;
 }   
 echo json_encode($jsonResult); 
 
?>
