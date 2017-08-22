<?php
	
namespace App\Http\Controllers;


class CrawlerController extends Controller { 

	private $discoveredLinks;
	private $recoveredContent;
	private $curl;
	private $cookie="cookies.txt";

	public function __construct(){
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_HEADER, false);
		curl_setopt($this->curl, CURLOPT_NOBODY, false);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($this->curl, CURLOPT_COOKIE, "cookiename=0");
		curl_setopt($this->curl, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 0);

		curl_setopt ($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt ($this->curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); 
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($this->curl, CURLOPT_TIMEOUT, 5);

		curl_setopt ($this->curl, CURLOPT_COOKIEJAR, $this->cookie); 

		//curl_setopt ($this->curl, CURLOPT_COOKIEFILE, $cookie); 
		//curl_setopt($this->curl, CURLOPT_URL, "http://www.site.com/page/");
		//do stuff with the info with DomDocument() etc
		//$html = curl_exec($this->curl);

		curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "POST");
	}

	public function loginViaPost(
		$url, 
		$username, 
		$fieldUserName, 
		$passwd, 
		$fieldpasswrd)
	{
		echo('Logging in . . . (loginViaPost)');
		$postdata = 
			$fieldUserName.'='.
			$username.'&'.
			$fieldpasswrd.'='.
			$passwd;
		curl_setopt ($this->curl, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt ($this->curl, CURLOPT_POST, 1); 
		curl_setopt($this->curl, CURLOPT_URL, $url);
		$data = curl_exec($this->curl);
		$httpcode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
		echo($httpcode);
	}

	public function close(){
		curl_close($this->curl);
	}

//$elements [{name: $name, type: $type, xPath: $xPath},...
	public function getData($url, $reqElements){
		curl_setopt($this->curl, CURLOPT_URL, $url);
		$data = curl_exec($this->curl);
		$httpcode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
		echo($httpcode);
		$domDoc = new \DOMDocument();
		@$domDoc->loadHTML($data);
		$xpath = new \DOMXPath($domDoc);
		$resElements = array();
		foreach($reqElements as $element) {
			$elements = $xpath->query($element['xPath']);
			unset($content);
			if ($element['type'] == 'img') {
				$content = $elements[0]->attributes->item(0)->value;
			} else {
				$content = $elements[0]->textContent;
			}
			$resElements[$element['name']] = $content;
		}
		return $resElements;
	}

	public function getLinks($url){
		curl_setopt($this->curl, CURLOPT_URL, $url);
		$data = curl_exec($this->curl);
		$httpcode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
		echo($httpcode);
		$domDoc = new \DOMDocument();
		@$domDoc->loadHTML($data);
		return $domDoc->getElementsByTagName('a');
	}

} 