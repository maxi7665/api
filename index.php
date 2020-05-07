<?php
//require_once 'connection2.php';

//include_once 'SendMailSmtpClass.php';
require 'vendor/autoload.php';
use \Mailjet\Resources;
$fd = fopen("log.txt", 'a+') or die("не удалось открыть файл");
$string=date('l jS \of F Y h:i:s A').'| ';
foreach ($_POST as $key => $value) {
    $string.=$key.':'.$value.'  ';
}
$string.="\n";

fwrite($fd, $string);

function send_accept_mail(string $code="0000", 
	string $e_mail="mak-den-bystrov@yandex.ru", 
	string $name="NoName"){
		
  		$mj = new \Mailjet\Client('fe33ca15cc21000e8516f6d755a68b90','3e73e3ecffd6e0b048b5eaef164901f4',true,['version' => 'v3.1']);
  $body = [
    'Messages' => [
      [
        'From' => [
          'Email' => "maxi7665@gmail.com",
          'Name' => "Max"
        ],
        'To' => [
          [
            'Email' => $e_mail,
            'Name' => $name
          ]
        ],
        'Subject' => "Accept code",
        'TextPart' => "$code",
        'HTMLPart' => "",
        //"<h3>Dear passenger 1, welcome to <a href='https://www.mailjet.com/'>Mailjet</a>!</h3><br />May the delivery force be with you!",
        'CustomID' => "AppGettingStartedTest"
      ]
    ]
  ];
  $response = $mj->post(Resources::$Email, ['body' => $body]);
}



function empty_tournament():string {
	$json= "{\"json\":[{\"idTournament\":\"-1\",\"Date\":\"2020-01-01\",\"Time\":\"00:00:00\",\"GameName\":\"Dota\",\"TournamentName\":\"Tour\",\"Type\":\"1\"}]}";
	return $json;


}

function get_results(int $id = 1): string{
	$table="<table border='1'>
		<tr>
			<td>
			Hello!
			</td>
		</tr>
	</table>";

	return $table;
}

function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyz'//ABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}


function getIdByToken(string $token,$link):int{
    $query="select idUser from token where token=\"$token\";";
    while(mysqli_next_result($link)){mysqli_store_result($link);};
    $result = mysqli_query($link, $query) or die("Error" . mysqli_error($link));
    $a=mysqli_num_rows($result);
    $result=mysqli_fetch_row($result);
    if($a==0){
        return -1;
        //return $result[0];
    } else {
        return $result[0];
    };

}

function getRightsById(int $id,$link):int{
    $query="select Admin from users where idUser=$id;";
    $result=mysqli_query($link, $query) or die("Ошибка получения прав ".mysqli_error($link));
    $a=mysqli_num_rows($result);
    $result=mysqli_fetch_row($result);
    if($a==0){
        return -1;
        //return $result[0];
    } else {
        return $result[0];
    };

};

function auth($login, $hash, $link){//auth
    $query="select idUser,Surname from users where login=\"$login\" and Pass_hash=\"$hash\";";
    $result=mysqli_query($link, $query) or die("Error ".mysqli_error($link));
    $a=mysqli_num_rows($result);
    $result=mysqli_fetch_row($result);
    if($a==0){
        return -1;
        //return $result[0];
    } else {
        return $result[0];
    };

}

function updateToken($id,$link){
    
                

    $token=random_str();


  //  while($link->next_result()) $link->store_result();
    $query="call updateToken($id,\"$token\");";
    //echo $query;
    while(mysqli_next_result($link)){mysqli_store_result($link);};

    //$start = microtime(true);

    $result=mysqli_query($link, $query) or die("Ошибка updateToken ".mysqli_error($link));

    //echo 'Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.|||';
    return $token;

}

function updateRestoreToken($id,$link){
    $token=random_str();

    //$start = microtime(true);
    $query="call updateRestoreToken($id,\"$token\");";
    //echo $query;
    while(mysqli_next_result($link)){mysqli_store_result($link);};
    $result=mysqli_query($link, $query) or die("Ошибка updateToken ".mysqli_error($link));

    //echo 'Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.|||';

    return $token;
}

function echojson($result){
    $rows = mysqli_num_rows($result);
    if ($rows>0){
        echo "{\n\"json\":[\n";
        for($i=0;$i<$rows;$i++){
            if($i<$rows-1){
                echo json_encode(mysqli_fetch_assoc($result)).",";
            }
            else { 
                echo json_encode(mysqli_fetch_assoc($result));
            }
        }
        echo "\n]\n}";
    }
}

function set_names($link, $charset='cp1251'){
    $query="set charset $charset;";
    $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 
    $link->next_result();
}

$conn = mysqli_init();
//$conn->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
//$conn->ssl_set(NULL, NULL, 'root.crt', NULL, NULL);
$conn->real_connect('5.180.139.59', 'user', 'Agihuh48', 'mydb', 3306, NULL, NULL)
    or ("Error ".mysqli_error($conn));
$link=$conn;
     $n;
     $query;


$array=array();

function err_reg_tournament(): string{
	$mess='{
"json":[
{"idTournament":"-1","Date":"2000-00-00","Time":"00:00:00","GameName":"NULL","TournamentName":"NULL","Type":"0","max_players":"0","accepted":"0","accept_flag":"0"}
]
}';
	return $mess;
}

//echo empty($_GET["get_query"])." ".empty($_GET["id"]);

if(!empty($_GET["get_query"]) && !empty($_GET["id"])) {
	echo get_results(1);
}

if(!empty($_POST["query"])){
    if($_POST["type"]=="get"){
        if($_POST["query"]=="getTournaments"){
            $query ="call getTournaments();";
            $result = mysqli_query($link, $query) or die("ErrorÐ° " . mysqli_error($link)); 
            echojson($result);
        }
        if($_POST["query"]=="getReservations"){
            $query="select * from reservation";
            $result = mysqli_query($link, $query) or die("ErrorÐ° " . mysqli_error($link)); 
            echojson($result);
        }

        if($_POST["query"]=="getReservationsById"){
            $query="select * from reservation where idComputer=$_POST[idcomp];";
            $result = mysqli_query($link, $query) or die("ErrorÐ° " . mysqli_error($link)); 
            echojson($result);
        }

        if($_POST["query"]=="getComputers"){
            $query="select * from computers";
            $result = mysqli_query($link, $query) or die("ÐžÑˆÐ¸Ð±ÐºÐ° " . mysqli_error($link)); 
            echojson($result);
        }

        if($_POST["query"]=="getNews"){
            $query="call getNews($_POST[position],$_POST[number]);";
            $result = mysqli_query($link, $query) or die("ÐžÑˆÐ¸Ð±ÐºÐ° " . mysqli_error($link)); 
            echojson($result);
        }
        if($_POST["query"]=="getTeams"){
            $query="call get_teams();";
            $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 
            echojson($result);
        }

        if($_POST["query"]=="getComputerById"){
            $query="call getComputersById($_POST[id]);";
            $result = mysqli_query($link, $query) or die("ÐžÑˆÐ¸Ð±ÐºÐ° " . mysqli_error($link)); 
            echojson($result);
        } 

        if($_POST["query"]=="login"){
            
        	
            $query="call getAuth(\"$_POST[hash]\",\"$_POST[login]\");";
            $result = mysqli_multi_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
            $result = $conn->store_result();
            $res=$result;
            $conn->next_result();
            $result = $conn->store_result();		  
            $res=mysqli_fetch_row($res);
            $res=$res[0];
            

            if($res==1){
                
            	while(mysqli_next_result($conn)){
		      	   mysqli_store_result($conn);
                };

                $query="call getUserIdByLogin(\"$_POST[login]\");";
            	$result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 

            	$res=$result;
            	$res=mysqli_fetch_row($res);
              
                $tokens=updateToken($res[0],$link);                

                $restoretokens=updateRestoreToken($res[0],$link);

            	echo $tokens.$restoretokens."";


            } else {
                echo "-1";
            }

        }

        if($_POST["query"]=="get_user_reservation"){

            $id=getIdByToken($_POST['token'],$link);
            $query="call get_user_reservation('$id')";
            $result = mysqli_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
            echo echojson($result);

        }

        if($_POST["query"]=="get_user_reservation_idf"){

            
            $query="call get_user_reservation_idf('$_POST[idf]')";
            $result = mysqli_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
            echo echojson($result);

        }

        if($_POST["query"]=="get_username"){

            $id=getIdByToken($_POST['token'],$link);
            $query="select Login from users where idUser=$id;";
            $result = mysqli_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
            echo mysqli_fetch_row($result)[0];

        }


        if($_POST["query"]=="get_reservation_by_id_idf"){

            
            $query="call get_reservation_by_id_idf($_POST[compid], '$_POST[idf]');";
            $result = mysqli_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
            echo echojson($result);

        }

        if($_POST["query"]=="get_reservation_by_id_token"){

            $id=getIdByToken($_POST['token'],$link);
            $query="call get_reservation_by_id_token($_POST[compid], $id)";
            $result = mysqli_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
            echo echojson($result);

        }

        if($_POST["query"]=="get_reservation_by_id_token"){

            $id=getIdByToken($_POST['token'],$link);
            $query="call get_reservation_by_id_token($_POST[compid], $id)";
            $result = mysqli_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
            echo echojson($result);

        }



        if($_POST["query"]=="get_reg_tournament"){
        	$id=0;
        	$token=$_POST['token'];
        	if(strlen($token) == 64){
        		$id=getIdByToken($token, $link);
        	} else {
        		$id=-1;
        	}
            $query="call get_reg_tournament($_POST[id_tour], $id)";
            //echo $_POST[id_tour], $id;
        	
            $result = mysqli_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
            $res=$result;
            if(mysqli_num_rows($res)!='0' /*&& mysqli_fetch_row($res)[0]!="0"*/)
            	{//echo mysqli_num_rows($result);
            	echojson($result);}
        	else{
        		echo err_reg_tournament();
        	}

        }

        if($_POST["query"]=="get_reg_tournament_v2"){
            $id=0;
            $token=$_POST['token'];
            if(strlen($token) == 64){
              //  $id=getIdByToken($token, $link);
            } else {
                $id=-1;
            }
            //echo $id;
            if(isset($_POST[t_id])){

                $idt=$_POST[t_id];
            } else if(isset($_POST[id_tour])){
                $idt=$_POST[id_tour];
            }
            $query="call get_reg_tournament_2($idt, $id);";
            //echo $_POST[id_tour].$id;
            
            $result = mysqli_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
            $res=$result;
            if(mysqli_num_rows($res)!='0' /*&& mysqli_fetch_row($res)[0]!="0"*/)
                {//echo mysqli_num_rows($result);
                echojson($result);}
            else{
                echo err_reg_tournament();
            }

        }

        if($_POST["query"]=="restore_login"){
            
            $query="call get_restore_auth(\"$_POST[restoretoken]\");";
            $result = mysqli_multi_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
            $result = $conn->store_result();
            $res=$result;
            $conn->next_result();
            $result = $conn->store_result();          
            $res=mysqli_fetch_row($res);
            $res=$res[0];
            while(mysqli_next_result($conn)){
                mysqli_store_result($conn);
                };
            $token;
            $restore_token;
            if($res!='-1'){
                $token=updateToken($res,$link);
                $restore_token=updateRestoreToken($res,$link);


            
                echo $token.$restore_token."";



            } else {
                echo "-1";
            }
        }
        if($_POST["query"]=="get_user"){
        	$id=getIdByToken($_POST['token'],$link);
            $query="call get_user_by_id($id);";

            $result = mysqli_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
            echojson($result);
        }
        if($_POST["query"]=="get_main_tournaments"){

            $query="call get_main_tournaments()";
            $result = mysqli_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
            echo echojson($result);

        }
        if($_POST["query"]=="get_tournaments_by_date"){
        	if(!empty($_POST["dat_begin"])&&!empty($_POST['dat_end'])){
	            $query="call get_tournaments_by_date('$_POST[dat_begin]','$_POST[dat_end]')";
	            $result = mysqli_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
	            if(mysqli_num_rows($result)==0){
	            	echo empty_tournament();
	            } else {
	            	echo echojson($result);
	        	}
	        }

        }
        if($_POST["query"]=="get_users"){
            if(!empty($_POST["dat_begin"])&&!empty($_POST['dat_end'])){
                $query="call get_tournaments_by_date('$_POST[dat_begin]','$_POST[dat_end]')";
                $result = mysqli_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
                if(mysqli_num_rows($result)==0){
                    echo empty_tournament();
                } else {
                    echo echojson($result);
                }
            }

        }

        if($_POST["query"]=="get_tournament_description"){
            if(!empty($_POST["t_id"])){
                set_names($conn);
                $query="select description from tournament_descriptions where id_tour=$_POST[t_id];";
                $result = mysqli_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
                if(mysqli_num_rows($result)==0){
                    echo '0';
                } else {
                    echo mysqli_fetch_row($result)[0];
                }
            } else {
                echo 0;
            }

        } 
        if($_POST["query"]=="get_tournament_users"){
            if(!empty($_POST["t_id"])){
                set_names($conn);
                $query="call get_tournament_users($_POST[t_id]);";
                $result = mysqli_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
                if(mysqli_num_rows($result)==0){
                    echo '0';
                } else {
                    echo echojson($result);
                }
            } else {
                echo 0;
            }

        } 
        if($_POST["query"]=="get_tournament_results"){
            if(!empty($_POST["t_id"])){
                set_names($conn);
                $query="call get_tournament_results($_POST[t_id]);";
                $result = mysqli_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
                if(mysqli_num_rows($result)==0){
                    echo '0';
                } else {
                    echo echojson($result);
                }
            } else {
                echo 0;
            }

        }
        if($_POST["query"]=="get_test"){
            if(true){
                set_names($conn);
                $query="select idUser,Surname,NAME,SecondName,team,NUMBER,Login,Admin,e_mail FROM users;";
                $result = mysqli_query($conn, $query) or die("Ошибка getAuth " . mysqli_error($conn)); 
                if(mysqli_num_rows($result)==0){
                    echo '0';
                } else {
                    echo echojson($result);
                }
            } else {
                echo 0;
            }

        }

        if($_POST['query']=="get_users"){
            $id=0;
            $token=$_POST['token'];
            if(strlen($token) == 64){
                $id=getIdByToken($token, $link);
            } else {
                $id=-1;
            }
            if($id!=-1){
                $rights=getRightsById($id,$link);
                if($rights == 1){
                    set_names($link);


                $query="call get_users();";
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 
                //$result=mysqli_fetch_row($result)[0];
                echo echojson($result);
                } else {
                    echo 01;
                }
                
            } else {
                echo 02;
            }
        }




    } elseif ($_POST["type"]=="update") {
	
        if($_POST["query"]=="addReservation"){
            if(empty($_POST["hash"])){

                $query="call addReservation($_POST[idcomp],'$_POST[datetime]',1,$_POST[restime]);";
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link));
            } else {
                $iduser=getIdByToken($_POST["hash"],$link);
                $query="call addReservation($_POST[idcomp],'$_POST[datetime]',$iduser,$_POST[restime]);";
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link));
            }
            echo mysqli_fetch_row($result)[0];
        }

        if($_POST["query"]=="addReservation_idf"){
            $sec_code=random_str(4,"0123456789");


            if(empty($_POST["hash"])) {
                
                $query="call add_reservation_idf($_POST[idcomp],'$_POST[datetime]',1,$_POST[restime],'$_POST[idf]','$sec_code',0);";
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link));
            } else {
                $iduser=getIdByToken($_POST["hash"],$link);
                $query="call add_reservation_idf($_POST[idcomp],'$_POST[datetime]',$iduser,$_POST[restime],'$_POST[idf]','$sec_code',1);";
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link));
            }
            
            $response=mysqli_fetch_row($result);
            $response=$response[0]."".$response[1];
            //$response+="LOL"
            echo $response.$sec_code;
        }

        if($_POST["query"]=="addReservation_admin"){
            //$sec_code=random_str(4,"0123456789");
            $iduser=getIdByToken($_POST["token"],$link);
            $rights=0;
            //echo $iduser;
            if($iduser!=-1){
                $rights=getRightsById($iduser,$link);
                if($rights == 1){ 
                    $query="call add_reservation_admin($_POST[idcomp],'$_POST[datetime]',$iduser,$_POST[restime]);";
                    $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link));
                    $response=mysqli_fetch_row($result);
                    $response=$response[0];//."".$response[1];
                    //$response+="LOL"
                    echo $response;

                } else {
                    echo '0';
                }      

            } else {
                echo '0';
            }
            
                
            
            
            $response=mysqli_fetch_row($result);
            $response=$response[0]."".$response[1];
            //$response+="LOL"
            echo $response.$sec_code;
        }



        if($_POST["query"]=="deleteReservation"){
            $iduser=getIdByToken($_POST["hash"],$link);

            $rights=getRightsById($iduser,$link);
            if($rights==1){
                    $query="delete from reservation where idReservation=$_POST[idreservation];";
                    $result = mysqli_query($link, $query) or die("ÐžÑˆÐ¸Ð±ÐºÐ° " . mysqli_error($link));
            }
        }

        if($_POST["query"]=="cancel_reservation_idf"){

                    $query="call cancel_reservation_idf($_POST[id_reservation],'$_POST[idf]');";
                    $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link));
                    $result = mysqli_fetch_row($result);
                    echo $result[0];
            
        }

        if($_POST["query"]=="cancel_reservation_token"){
            $iduser=getIdByToken($_POST["token"],$link);
            
                    $query="call cancel_reservation_token($_POST[id_reservation], $iduser);";
                    $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link));
                    $result = mysqli_fetch_row($result);
                    echo $result[0];
        }

        if($_POST["query"]=="cancel_reservation_admin"){
            $iduser=getIdByToken($_POST["token"],$link);
            
                    $query="call cancel_reservation_admin($_POST[id_reservation], $iduser);";
                    $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link));
                    $result = mysqli_fetch_row($result);
                    echo $result[0];
        }

        if($_POST["query"]=="addNews"){
            $iduser=getIdByToken($_POST["hash"],$link);
            $rights=getRightsById($iduser,$link);
            if($rights==1){
                    $query="call createNews(\"$_POST[header]\",\"$_POST[content]\",\"$iduser\");";
                    $result = mysqli_query($link, $query) or die("ÐžÑˆÐ¸Ð±ÐºÐ° " . mysqli_error($link));
            }
        }

        if($_POST["query"]=="createTournament"){
            if(isset($_POST["hash"])&&isset($_POST["date"])&&isset($_POST["time"])&&isset($_POST["game"])&&isset($_POST["name"])&&isset($_POST["typet"])){
                $iduser=getIdByToken($_POST["hash"],$link);
                $rights=getRightsById($iduser,$link);
                if($rights==1){
                        $query="call createTournament(\"$_POST[date]\",\"$_POST[time]\",\"$_POST[game]\",\"$_POST[name]\",\"$_POST[typet]\");";
                        $result = mysqli_query($link, $query) or die("ÐžÑˆÐ¸Ð±ÐºÐ° " . mysqli_error($link));
                }
            }
        }

        if($_POST["query"]=="createTournament_admin"){
            if(isset($_POST["token"])&&isset($_POST["date"])&&isset($_POST["time"])&&isset($_POST["game"])&&isset($_POST["name"])&&isset($_POST["typet"])){
                $desc='';
                if(empty($_POST[description])){
                    $desc='0';
                } else {
                    $desc=$_POST[description];
                }
                $iduser=getIdByToken($_POST["token"],$link);
                $rights=getRightsById($iduser,$link);
                if($rights==1){
                        $query="call add_tournament_admin(\"$_POST[name]\",\"$_POST[game]\",\"$_POST[date]\",\"$_POST[time]\",\"$_POST[typet]\",'$_POST[max_players]', '$desc');";
                        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
                        echo mysqli_fetch_row($result)[0];
                }
            } else {
                echo '0';
            }
        }


        if($_POST["query"]=="deleteTournament"){
		
            if(isset($_POST["hash"])&&isset($_POST["id"])){
               $iduser=getIdByToken($_POST["hash"],$link);
               $rights=getRightsById($iduser,$link);
                if($rights==1){
                        $query="call deleteTournament(\"$_POST[id]\");";
                        $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link));
                }
            }
        }

        if($_POST["query"]=="registration_old"){
            if(!empty($_POST["login"])&&!empty($_POST["pass_hash"])){

                $query="call registration('$_POST[login]','$_POST[pass_hash]','$_POST[number]',
                '$_POST[surname]','$_POST[name]','$_POST[s_name]');";
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link));

                echo mysqli_fetch_row($result)[0];
            } else {
                echo '0';
            }
            
        }

        if($_POST["query"]=="registration"){
            if(!empty($_POST["login"])&&!empty($_POST["pass_hash"])){
                set_names($link);
                
            	$code=random_str(4,"1234567890QWERTYUIOPASDFGHJKLZXCVBNM");

                $query="call add_reg_request('$_POST[login]','$_POST[pass_hash]','$_POST[number]',
                '$_POST[surname]','$_POST[name]','$_POST[s_name]','$code','$_POST[e_mail]');";
                $res = mysqli_query($link, $query) or die("Error " . mysqli_error($link));
                $a=mysqli_fetch_row($res)[0];
                echo $a;
                //echo $query;

                /*$smtp=new SendMailSmtpClass("maxi7665@gmail.com", "AGIHUH48maximym", "ssl://smtp.gmail.com",465);

				$headers= "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=utf-8\r\n"; // кодировка письма
				$headers .= "From: LEET <maxi7665@gmail.com>\r\n"; 

				 echo $smtp->send($_POST[e_mail], "Код подтверждения LEET",$code, $headers);*/

				 if($a=='1'){
				 	send_accept_mail($code,$_POST['e_mail'],$_POST['name']);
				 }
                
            } else {
                echo '0';
            }
            
        }
        if($_POST["query"]=="accept_registration"){
            if(!empty($_POST["login"])&&!empty($_POST["code"])){

                $query="call accept_reg_request('$_POST[login]', '$_POST[code]');";
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link));

                echo mysqli_fetch_row($result)[0];
            } else {
                echo '0';
            }
            
        }
        if($_POST["query"]=="add_restore_code"){
            if(!empty($_POST["email"])){
            	$code=random_str(4,"1234567890QWERTYUIOPASDFGHJKLZXCVBNM");

                $query="call add_restore_code('$_POST[email]', '$code');";
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link));
                $result=mysqli_fetch_row($result)[0];
                echo $result;
                if($result="1"){
                	send_accept_mail($code,$_POST["email"],"LEET User");
                }
            } else {
                echo '0';
            }
            
        }

        if($_POST["query"]=="valid_restore_code"){
            if(!empty($_POST["code"] && !empty($_POST["email"]))){
            	

                $query="call valid_restore_password('$_POST[code]', '$_POST[email]');";
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link));
                $res=mysqli_fetch_row($result);
                $login="";
                if($res[1] != null){
                	$login=$res[1];
                }
                echo $res[0].$login;
            } else {
                echo '0';
            }
            
        }

        if($_POST["query"]=="restore_password"){
            if(!empty($_POST["code"] && !empty($_POST["email"]) && !empty($_POST["p_hash"]))){

                $query="call restore_password('$_POST[email]', '$_POST[code]','$_POST[p_hash]');";
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link));
                $result=mysqli_fetch_row($result)[0];
                echo $result;
            } else {
                echo '0';
            }
            
        }

        if($_POST["query"]=="accept_user_in_tournament"){
        	$id=0;
        	$token=$_POST['token'];
        	if(strlen($token) == 64){
        		$id=getIdByToken($token, $link);
        	} else {
        		$id=-1;
        	}

        	$query="call accept_user_in_tournament('$_POST[id_tour]', $id);";
            $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 
            if(mysqli_num_rows($result) == 0){
            	echo 0;
            } else {
            	echo mysqli_fetch_row($result)[0];
            }
        }
        if($_POST['query'] == 'cancel_reg_tournament'){
        	$id=0;
        	$token=$_POST['token'];
        	if(strlen($token) == 64){
        		$id=getIdByToken($token, $link);
        	} else {
        		$id=-1;
        	}

        	$query="call cancel_reg_tournament($id,$_POST[tour_id]);";
        	$result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 
            if(mysqli_num_rows($result) == 0){
            	echo 0;
            } else {
            	echo mysqli_fetch_row($result)[0];
            }


        }
        if($_POST['query'] == 'change_email_request'){
            $id=0;
            $token=$_POST['token'];
            if(strlen($token) == 64){
                $id=getIdByToken($token, $link);
            } else {
                $id=-1;
            }
            if($id!=-1){

                $code=random_str(4,"1234567890QWERTYUIOPASDFGHJKLZXCVBNM");

                $query="call change_email_request($id,'$_POST[email]', '$code');";
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 
                $result=mysqli_fetch_row($result)[0];
                echo $result;
                if($result=="1"){
                     send_accept_mail($code,$_POST["email"],"LEET User");
                }
            } else {
                echo 0;
            }

        }
        if($_POST['query'] == 'accept_change_email'){
            $id=0;
            $token=$_POST['token'];
            if(strlen($token) == 64){
                $id=getIdByToken($token, $link);
            } else {
                $id=-1;
            }

            $code=$_POST['code'];
            $query="call accept_change_email('$code',$id);";
            $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 
            $result=mysqli_fetch_row($result)[0];
            echo $result;

        }
        if($_POST['query']== 'change_personal_data'){
            $id=0;
            $token=$_POST['token'];
            if(strlen($token) == 64){
                $id=getIdByToken($token, $link);
            } else {
                $id=-1;
            }
            if($id!=-1){
                $query="set charset cp1251;";
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 
                $link->next_result();


                $query="call change_personal_data($id,'$_POST[name]','$_POST[surname]','$_POST[s_name]','$_POST[phone]');";
                if($_POST['test'] == '1'){
                    echo $query;
                }
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 
                $result=mysqli_fetch_row($result)[0];
                echo $result;
            } else {
                echo 0;
            }

        }

        if($_POST['query']== 'change_personal_data_admin'){
            $id=0;
            $token=$_POST['token'];
            if(strlen($token) == 64){
                $id=getIdByToken($token, $link);
            } else {
                $id=-1;
            }
            if($id!=-1){
                 $rights=getRightsById($id,$link);
                if($rights == 1){
                    set_names($link);


                $query="call change_personal_data_admin('$_POST[id_user]','$_POST[name]','$_POST[surname]','$_POST[s_name]','$_POST[phone]','$_POST[email]');";
                
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 
                $result=mysqli_fetch_row($result)[0];
                echo $result;
                }else{
                    echo 0;
                }
            } else {
                echo 0;
            }

        }

        
        if($_POST['query']== 'change_tournament_description'){
            $id=0;
            $token=$_POST['token'];
            if(strlen($token) == 64){
                $id=getIdByToken($token, $link);
            } else {
                $id=-1;
            }
            if($id!=-1){
                $rights=getRightsById($id,$link);
                if($rights == 1){
                    set_names($link);


                $query="call change_tournament_description($_POST[t_id],'$_POST[description]');";
                
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 
                $result=mysqli_fetch_row($result)[0];
                echo $result;
                } else {
                    echo 0;
                }
                
            } else {
                echo 0;
            }

        }
        
        if($_POST['query']== 'change_tournament_data'){
            $id=0;
            $token=$_POST['token'];
            if(strlen($token) == 64){
                $id=getIdByToken($token, $link);
            } else {
                $id=-1;
            }
            if($id!=-1){
                $rights=getRightsById($id,$link);
                if($rights == 1){
                    set_names($link);

                if(empty($_POST[description])){
                    $desc='0';

                } else {
                    
                       $desc=$_POST[description]; 
                    
                    
                }
                $query="call change_tournament_data($_POST[t_id], '$_POST[date]', '$_POST[time]', '$_POST[game_name]', '$_POST[tour_name]', $_POST[type_t],$_POST[max_players],'$desc');";

                //echo $query;
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 
                $result=mysqli_fetch_row($result)[0];
                echo $result;
                } else {
                    echo 0;
                }
                
            } else {
                echo 0;
            }

        } 
        if($_POST['query']== 'delete_tournament_user'){
            $id=0;
            $token=$_POST['token'];
            if(strlen($token) == 64){
                $id=getIdByToken($token, $link);
            } else {
                $id=-1;
            }
            if($id!=-1){
                $rights=getRightsById($id,$link);
                if($rights == 1){
                    set_names($link);


                $query="call delete_tournament_user($_POST[id_user],$_POST[t_id]);";
                
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 
                $result=mysqli_fetch_row($result)[0];
                echo $result;
                } else {
                    echo 0;
                }
                
            } else {
                echo 0;
            }

        }
        if($_POST['query']== 'add_tournament_result'){
            $id=0;
            $token=$_POST['token'];
            if(strlen($token) == 64){
                $id=getIdByToken($token, $link);
            } else {
                $id=-1;
            }
            if($id!=-1){
                $rights=getRightsById($id,$link);
                if($rights == 1){
                    set_names($link);


                $query="call add_tournament_result($_POST[team1],$_POST[team2],$_POST[t_id],$_POST[stage],$_POST[team1_score],$_POST[team2_score]);";
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 
                $result=mysqli_fetch_row($result)[0];
                echo $result;
                } else {
                    echo 0;
                }
                
            } else {
                echo 0;
            }

        }
        if($_POST['query']== 'change_tournament_result'){
            $id=0;
            $token=$_POST['token'];
            if(strlen($token) == 64){
                $id=getIdByToken($token, $link);
            } else {
                $id=-1;
            }
            if($id!=-1){
                $rights=getRightsById($id,$link);
                if($rights == 1){
                    set_names($link);


                $query="call change_tournament_result($_POST[id_res],$_POST[team1],$_POST[team2],$_POST[t_id],$_POST[stage],$_POST[team1_score],$_POST[team2_score]);";
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 
                $result=mysqli_fetch_row($result)[0];
                echo $result;
                } else {
                    echo 0;
                }
                
            } else {
                echo 0;
            }

        }
        if($_POST['query']== 'delete_tournament_result'){
            $id=0;
            $token=$_POST['token'];
            if(strlen($token) == 64){
                $id=getIdByToken($token, $link);
            } else {
                $id=-1;
            }
            if($id!=-1){
                $rights=getRightsById($id,$link);
                if($rights == 1){
                    set_names($link);


                $query="call delete_tournament_result($_POST[id_res]);";
                $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)); 
                $result=mysqli_fetch_row($result)[0];
                echo $result;
                } else {
                    echo 0;
                }
                
            } else {
                echo 0;
            }

        }
        
}


    }


unset($_POST);
mysqli_close($link);
?>