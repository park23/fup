<?php
/*
Use cron Luke
*/
set_time_limit(0);
class Diesel{

	public $user;
	public $pass;
	public $post;
	
	public function login(){
		echo "login process start...\n";
		$authArray=array( 
        	'UserName' => $this->user, 
        	'PassWord' => $this->pass, 
        	'Submit' => 'Войти'

      	);

		$result=$this->request('http://diesel.elcat.kg/index.php?act=Login&CODE=01',$authArray,'POST');
		
			
			return true;

		
	}

	public function findDeleteLink(){
			   echo "find link process start..\n";
			$res= $this->request($this->post."&st=9999",false,'GET');
			if(preg_match_all('/\<table\s+class(.*)\s+\<\/table\>/isU',$res,$pages)){
					foreach($pages[0] as $k=>$v){

						if(preg_match('/onmousedown=\"ins\(\''.$this->user.'\'\);\s+return\s+false\"\>\<b\>'.$this->user.'\<\/b\>\<\/a\>/',$v,$out)){
  		                                        echo "you succesfull log in diesel\n";
							if(preg_match('/\<div\s+class=\"postcolor\"\s+id=\'post\-(.*)\'\>\s+up\s+\<\!\-\-IBF\.ATTACHMENT_/isU',$v,$out2)){

							    	if(preg_match('/delete_post\(\'(.*)\'\)\"\>/isU',$v,$result)){
				                                        echo "delete link found\n";
							    		return $result[1];
									}else{
									print_r($result);
									die("link for delete file not found section 1\n");
								}
							}else{
                                                            print_r($v);
								die("link for delete file not found section 2\n");
							}
																					
						}else{
                                                    print_r($v);
								die("link for delete file not found section 3\n");
						}


					}					
			}
		print_r($res);exit;

		return false;     
	 }

	
 
	 public function deleteLink($link){
	                    

	    if($link!=false){
		echo "link delete process start...\n";
 			$res=htmlspecialchars_decode($link);
 			$out=$this->request($res,false,'GET');
			//debug mode
			print_r($out);
					
		}


	}

	public function newUp(){
		echo "new up process start..\n";
		$parse=$this->request($this->post.'&st=99999','','GET',false);
		preg_match('/name=\"f\" value=\"(.*)\"/',$parse,$f);
		preg_match('/name=\"t\" value=\"(.*)\"/',$parse,$t);
		preg_match('/name=\"auth_key\" value=\"(.*)\"/',$parse,$auth_key);
		$action='http://diesel.elcat.kg/index.php';	
		$f=$f[1];
		$t=$t[1];		
		$auth_key=$auth_key[1];

		$upArray=array( 
        	'act'	=> 'Post', 
        	'CODE'	=> '03', 
        	'f'		=> $f,
			't'		=> $t,
			'st'	=> '99999',
			'auth_key' => $auth_key,
			'fast_reply_used'=> 1,
			'Post'	=> 'up',
			'submit' => 'Отправить'
			
      	);

		$result=$this->request($action,$upArray,'POST');
		if(preg_match('/\<div\s+class=\"postcolor\"\s+id=\'post\-(.*)\'\>\s+up\s+\<\!\-\-IBF\.ATTACHMENT_/isU',$result)){
				echo "Mission completed!\n";
	                return $result;
		}else{
			print_r($result);
			die("new up not set(((\n");
		}		
		

	}
			
				
	

	public function request($url,$params='',$method,$close=true){


        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
		if($method=='POST'){
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $params); // post data 
			
		}
		curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch,CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_COOKIEJAR, "cookie.txt");
        curl_setopt($ch,CURLOPT_COOKIEFILE, "cookie.txt");
        curl_setopt($ch,CURLOPT_VERBOSE, false);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
         $result = curl_exec($ch);
		if($close==true){
			curl_close($ch);        
		}
        return $result;
	}


}

$test=new Diesel();
$test->user='';
$test->pass='';
$test->login(); 
$test->post='';
$link=$test->findDeleteLink();
$test->deleteLink($link);
$test->newUp();  
