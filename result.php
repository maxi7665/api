<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Результат</title>
	<style type="text/css">
	
	div.header{
		background-color: rgb(0,128,128);
		padding: 20px
	}
	div.setka{
		background-color: rgb(100,100,100);
		position: absolute;
		
		

	}
	div.node{
		background-color: grey;
		width:100px;
		height:70px;
		border: 1px;
		position: absolute;

	}
	.table{
		opacity: 100%;
		position: absolute;
		text-align: center;
		width: 100%;
		left: -2000px;


	}
	.table_fact{
		margin:auto;
		text-align:center;
		border: 1px black;
		border-radius: 15px;
	}
	.inner{
		
    	margin: auto;
    	text-align: center;
	}
	.bt{
		appearance: none;
		  border: 0;
		  border-radius: 5px;
		  background: #4676D7;
		  color: #fff;
		  padding: 8px 16px;
		  font-size: 16px;
		  margin:20px;
	}
	.bt:hover {
  background: #1d49aa;
}

	.bt:focus {
	  outline: none;
	  box-shadow: 0 0 0 4px #cbd6ee;
	}

	body{
		background-color: #D8BFD8;
	}
</style>
</head>
<body>
<?php 



/**/function echojson($result){
	$json='';
    $rows = mysqli_num_rows($result);
    if ($rows>0){
        $json.= "{\"json\":[";
        for($i=0;$i<$rows;$i++){
            if($i<$rows-1){
                $json.= json_encode(mysqli_fetch_assoc($result)).",";
            }
            else { 
                $json.=json_encode(mysqli_fetch_assoc($result));
            }
        }
        $json.= "]}";
    }
    return $json;
}

 $match = [
   "id_result" => "2", 
   "id_tour" => "27", 
   "type_t" => "1", 
   "play1" => "2", 
   "play2" => "1", 
   "stage" => "0", 
   "play1_score" => "2", 
   "play2_score" => "3", 
   "DATE" => "2020-04-26", 
   "TIME" => "12:00:00", 
   "GameName" => "new_name", 
   "TournamentName" => "phptou27", 
   "MAX_players" => "35", 
   "team1_name" => "HR", 
   "team2_name" => "NAVI" 
]; 



function parse_match($data) {
	$match=new Match();

	foreach ($data as $key => $value) {
		
	}



}



$conn = mysqli_init();
//$conn->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
//$conn->ssl_set(NULL, NULL, 'root.crt', NULL, NULL);
$conn->real_connect('5.180.139.59', 'user', 'Agihuh48', 'mydb', 3306, NULL, NULL)
    or ("Error ".mysqli_error($conn));
    if(isset($_GET['id'])){
    $query='call get_tournament_results('.$_GET['id'].');';
    //echo($query);
	}
	$flag=0;
     $result = mysqli_query($conn, $query) or die("ErrorÐ° " . mysqli_error($conn)); 
     //echojson($result);
     if(mysqli_num_rows($result)!=0){
     	$i=0;
     	for($data=[];$row=mysqli_fetch_assoc($result);$data[$i++]=$row) ;//get the whole response to ^2 array
     	set_header($data);
     	//draw();
     	$flag=1;
     } else {
     	$i=0;
     	for($data=[];$row=mysqli_fetch_assoc($result);$data[$i++]=$row) ;//get the whole response to ^2 array
     	set_err_header($data);
     }
     $forjson=$result;

     

    
    $conn->next_result();
    $jso=mysqli_query($conn, $query) or die(mysqli_error($conn));
    $jso = echojson($jso);

    ?>





    <?php
    $result='';
    $result.='<table class="table_fact" border="1px"> <tr>';
    $result.='<th>Стадия</th><th>Команда 1</th><th>Счет</th><th>Команда 2</th>';
	foreach($data as $key => $elem) {
        break;

        foreach ($elem as $key => $value) {
        	$result.='<th>'.$key.'</th>';
        
        }
        $result.='</tr>';
        break;
    }
    foreach($data as $key => $elem) {
        $result.='<tr>';
        //echo $key;
        $a=intval($data[$key]['stage']);
        if($a==0){
        	$s='Финал';
        } else{
        	$s='1/'.($a)*2;
        }
        $result.='<td>'.$s.'</td>';
        $result.='<td>'.$data[$key]['team1_name'].'</td>';
        $result.='<td>'.$data[$key]['play1_score'].":".$data[$key]['play2_score'].'</td>';
        $result.='<td>'.$data[$key]['team2_name'].'</td>';
        foreach ($elem as $key => $value) {
        	//$result.='<td>'.$value.'</td>';

        
        }
        


        $result.='</tr>';
    }


    function set_header($data){
	    echo '<div class="header" width="100%" align="center" background-color="blue" height="300px">';
	    echo '<h1>Результаты турнира "'.$data[0]['TournamentName'].'"</h1>';
	    echo '<h3>Дата: '.$data[0]['DATE'].' '.$data[0]['TIME'].'</h3>';
	    $type='<h3>Тип турнира: ';
	    if($data[0]['type_t']=='1'){
	    	$type.='Командный';
	    } else if($data[0]['type_t']=='0'){
	    	$type.='Одиночный';
	    }
	    $type.='</h3>';
	    echo $type;
	    echo '<h3>Количество участников: '.$data[0]['MAX_players'].'</h3>';
	    echo '<h3>Дисциплина: '.$data[0]['GameName'].'</h3>';
	    echo '<div align="left">';

		echo '<button class="bt" onclick="change1();" id="bt1">Таблица</button><button class="bt" onclick="change2();" id="bt2">Сетка</button>';

	    echo '</div>';
	    echo '</div>';
	    echo "\n";
	}

	function set_err_header(){
		echo '<div class="header" width="100%" align="center" background-color="blue" height="300px">';
	    echo '<h1>Ошибка!</h1>';
	    echo '<h3>Турнир не найден (</h3>';
	    
	    echo '</div>';
	    echo "\n";
	}


    /*$table='<table width="100px" border="1px">';
    for ($i=0;$i<4;$i++){
    	$table.='<tr>';
    	for($j=4-$i;$j<4;$j++){
    		$table.='<td>f</td>';
    	}
    	$table.='</tr>';
    }*/
    //echo count($data)."Длина";
    if($flag==1)
    echo '<div class="table" width="100%" >'.$result.'</table></div>'."\n";

    //echo $table;



?>

<script type="text/javascript">

    	bt=document.getElementById("bt1");
    	//bt.onclick='change1();';
    	function change1(){
    		table=document.querySelector('div.table');
    		setka=document.querySelector('div.setka');
    		//console.dir(table.style);
    		table.style.left='0px';
    		setka.style.left='-2000px';
    		console.log('change');
    		//alert('a');

    	}
    	function change2(){
    		setka=document.querySelector('div.setka');
    		table=document.querySelector('div.table');
    		//console.dir(table.style);
    		table.style.left='-2000px';
    		setka.style.left='0px';
    		console.log('change');
    		//alert('a');

    	}

    </script>

<div class="setka" width="100%"></div>
	


	<script type="text/javascript">
		function getStyleString(stage=1,inrow=0){
			var width=document.body.clientWidth/6;
			var specwidth=width;
			if(stage>3){
				specwidth=width*0.6;
			} else if(stage == 1){
				specwidth=width*1.5;
			}
			return a="position:absolute;top:"+(20+(stage-1)*(Math.round(width*0.5)+20))+"px;left:"+Math.round(document.body.clientWidth/Math.pow(2,(stage))-specwidth/2+document.body.clientWidth/Math.pow(2,stage-1)*inrow)+"px;width:"+Math.round(specwidth)+"px;height:"+Math.round(specwidth*0.5)+"px;background-color: #AFEEEE;border: 1px solid black;border-radius: 5px; display: flex;";

		}
		function draw(result){
			var articleDiv = document.querySelector("div.setka");
			
			row=1;
			for(i=0;i<result.length-1;i++){
				inc=0;
				for(j=0;j<row;j++){
					
					elm=document.createElement("div");
					elm.style=getStyleString(i+1,inc++);
					elm.innerHTML="<div class='inner'>Матч отсутствует</div>";
					elm.id=''+i+inc;
					articleDiv.appendChild(elm);
					//alert(i+" "+j);
				}
				row*=2;
			}
		}
		

		function find_by_team_id(result,id,stage){
			
			for(z=0;z<result.length;z++){
				console.log(result[z]['play1']+" "+id+" "+result[z]['play2']+" "+String(id) +' '+ result[z]['stage'] +" "+ String(stage));

				if((result[z]['play1']==String(id) || result[z]['play2']==String(id)) && result[z]['stage'] == String(stage) ){

					return z;
					
				}
			}
			return -1;
		}

		function get_block_text(result,stage){
			//alert(result);
			stage++;
			return "<div class='inner'>"+result['team1_name']+' '+result['play1_score']+' : '+result['play2_score']+' '+result['team2_name']+"<br>1/"+(stage*2)+" финала</div>";
		}

		function set_results(result){
			elm=document.getElementById('01');
			elm.setAttribute('play1',result[0]['play1']);
			elm.setAttribute('play2',result[0]['play2']);
						if(result[0]['stage'] =='0'){
				elm.innerHTML="<div class='inner'>"+result[0]['team1_name']+' '+result[0]['play1_score']+' : '+result[0]['play2_score']+' '+result[0]['team2_name']+"<br>Финал</div>";
			} else {
				return 0;
			}
			//alert(''+i+inc);
			row=1;
			for(i=0;i<result.length-1;i++){
				inc=0;
				for(j=0;j<row;j++){
					//onsole.log(i,j);
					elm=document.getElementById(String(''+i+(j+1)));
					//alert(elm+''+String(''+i+(j+1))+' '+i);
					//left
					arr1=find_by_team_id(result,elm.getAttribute('play1'),1+i);//прищепочка пришла
					//right
					arr2=find_by_team_id(result,elm.getAttribute('play2'),1+i);

					console.log(arr1+' '+arr2);
					//console.log(i,j);
					if(i!=3){//if not 1/8 yet
						if(arr1!=-1){
							block1=document.getElementById(String(''+(i+1)+((j+1)*2-1)));
							block1.innerHTML=get_block_text(result[arr1],i);
							console.log("setAttribute "+result[arr1]['play1']);
							block1.setAttribute('play1',result[arr1]['play1']);
							block1.setAttribute('play2',result[arr1]['play2']);

						}
						if(arr2!=-1){
							block1=document.getElementById(String(''+(i+1)+((j+1)*2)));
							block1.innerHTML=get_block_text(result[arr2],i);
							console.log("setAttribute "+result[arr2]['play1']);
							block1.setAttribute('play1',result[arr2]['play1']);
							block1.setAttribute('play2',result[arr2]['play2']);

						}
					}

					if(i!=0 || j!=0){
						//elm.innerHTML='test';
					}
					
				}
				row*=2;

			}
			return 1;
		}
    	//let str = '{"title":"Conference","date":"2017-11-30T12:00:00.000Z"}';
    	//var res=JSON.parse(str );
    	var res=JSON.parse('<?php echo $jso; ?> ');
    	var result=res['json'];
    	if(result!=null ){
    		if( result[0]['play1']!=null){
    			draw(result);
    			state=set_results(result);
    			console.log("State: "+ state);
    			if(state!=1){
    				//todo
    			}	
    		} else{
    			document.querySelector('div.setka').innerHTML='Матчи не найдены! Пожалуйста, зайдите позже';
    		}
    		
    	}
    	//alert(res['json'][0]['GameName']);



	</script>

</body>
</html>