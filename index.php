<?php
require_once('simple_html_dom.php');

header('Content-type: text/html; charset=utf-8');

// 目標網址
////$target_url[0] = 'https://itunes.apple.com/tw/genre/dian-ying-dong-zuo-yu-li-xian/id4401?l=zh'; //動作與歷險
////$target_url[1] = 'https://itunes.apple.com/tw/genre/dian-ying-jing-song-pian/id4416?l=zh'; //驚悚片
//$target_url[2] = 'https://itunes.apple.com/tw/genre/dian-ying-ke-huan-yu-qi-huan/id4413?l=zh'; //科幻與奇幻片
//$target_url[3] = 'https://itunes.apple.com/tw/genre/dian-ying-kong-bu-pian/id4408?l=zh'; //恐怖片
//$target_url[4] = 'https://itunes.apple.com/tw/genre/dian-ying-ju-qing-pian/id4406?l=zh'; //劇情片
//$target_url[5] = 'https://itunes.apple.com/tw/genre/dian-ying-xi-ju/id4404?l=zh'; //喜劇
//$target_url[6] = 'https://itunes.apple.com/tw/genre/dian-ying-jing-dian-zuo-pin/id4403?l=zh'; //經典作品


////$target_url[7] = 'https://itunes.apple.com/tw/genre/dian-ying-er-tong-yu-jia-ting/id4410?l=zh'; //兒童與家庭片
////$target_url[8] = 'https://itunes.apple.com/tw/genre/dian-ying-xi-bu-pian/id4418?l=zh'; //西部片
////$target_url[9] = 'https://itunes.apple.com/tw/genre/dian-ying-dou-hui/id4419?l=zh'; //都會
////$target_url[10] = 'https://itunes.apple.com/tw/genre/dian-ying-yun-dong/id4417?l=zh'; //運動

////$target_url[11] = 'https://itunes.apple.com/tw/genre/dian-ying-te-shu-xing-qu-ying/id4415?l=zh'; //特殊興趣影片

////$target_url[12] = 'https://itunes.apple.com/tw/genre/dian-ying-romance/id4412?l=zh'; //Romance

////$target_url[13] = 'https://itunes.apple.com/tw/genre/dian-ying-yin-le-ju/id4411?l=zh'; //音樂劇

////$target_url[14] = 'https://itunes.apple.com/tw/genre/dian-ying-yin-le-zhu-ti-dian/id4424?l=zh'; //音樂主題電影

////$target_url[15] = 'https://itunes.apple.com/tw/genre/dian-ying-du-li-zhi-zuo-ying/id4409?l=zh'; //獨立製作影片


$zzzzzz = 'https://itunes.apple.com/tw/genre/dian-ying-xi-ju/id4404?l=zh'; //喜劇


$abcd = array( 'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','*');  //

$intI = 0;
for($x=1;$x<=5;$x++)
{
	foreach ($abcd as $az)
	{
		$target_url[$intI]= $zzzzzz.'&letter='.$az.'&page='.$x.'#page';
		$intI++;
		//echo $target_url[$x].'<br>';
	}
}


echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';



ob_flush();
flush();
foreach ($target_url as $allUrl)
{
	echo '=========begin read - '.$allUrl.'=========';	
	echo '<br>';	
ob_flush();
flush();
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_URL, $allUrl);
		curl_setopt($curl, CURLOPT_REFERER, $allUrl);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		$html_body = curl_exec($curl);
		curl_close($curl);
		 
		// 建一個 DOM 物件
		 
		$html_obj = new simple_html_dom();
		 
		// 把用 curl 抓回來的資料丟到建物件中
		 
		$html_obj->load($html_body);


		//echo $html_body;



		$dom = new DOMDocument();
		libxml_use_internal_errors(true);
		$dom->loadHTML($html_body);  
		$xpath = new DOMXPath($dom);
		$nodelist = $xpath->query("//ul/li/a"); //放入想要抓取的元素
		//echo $nodelist->length; //抓取資料長度


		foreach ($nodelist as $n){
				
			//echo trim($n->nodeValue)." - ";

			//抓子網址
			$subUrl = $n->getAttribute("href");
		//	echo $subUrl.' - ' ;


			$curl2 = curl_init();
			curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl2, CURLOPT_HEADER, false);
			curl_setopt($curl2, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl2, CURLOPT_URL, $subUrl);
			curl_setopt($curl2, CURLOPT_REFERER, $subUrl);
			curl_setopt($curl2, CURLOPT_RETURNTRANSFER, TRUE);
			$sub_html_body = curl_exec($curl2);
			curl_close($curl2);

				//if($sub_html_body->length >0)
				//{
					$dom2 = new DOMDocument();
					$dom2->loadHTML($sub_html_body);  
					$xpath2 = new DOMXPath($dom2);
					$nodelist2 = $xpath2->query("//ul/li/span[@class='price']"); //放入想要抓取的元素
					//echo $nodelist->length; //抓取資料長度
					if($nodelist2->length > 0)
					{			
							$intMoney = 9999;	
							$money = str_replace('NT$ ','',$nodelist2->item(0)->nodeValue);
							$intMoney = (int)$money;
							//echo ' ';
							//ob_flush();
							//flush();
							
							if($intMoney <100 &&  $intMoney > 1)
							{
								echo '<b><span style="color:#80BFFF">' ;
							}
							
							echo trim($n->nodeValue)." - ";
							

								if($intMoney <100 &&  $intMoney > 1)
								{
									echo '<a href="' ;
								}
								
								echo $subUrl;
								
								if($intMoney <100 &&  $intMoney > 1)
								{
									echo '" target="_blank">'.$subUrl.'</a>' ;
								}
								 
								echo ' - '.$money;	
							
								
							if($intMoney <100 &&  $intMoney > 1)
							{
								echo '</span></b>' ;
							}
							
								echo '<br>';
								ob_flush();
								flush();
					}
				//}

				
		}
	echo '=========finish read - '.$allUrl.'=========';	
	echo '<br>';		
	ob_flush();
	flush();
}


//echo $myHtml2;

/*
foreach ($nodelist as $n){
  echo trim($n->nodeValue)." - ";
 // echo trim($n->getAttribute("href"))."<br/>\n";
 
	$myHtml2 = getMyHtml($n->getAttribute("href"));
	//if($myHtml2->length > 0) echo getMoney($myHtml2);
}
*/





?>