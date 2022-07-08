<?php
$page_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$page_url = explode('ninja', $page_url)[0] . "ninja/";

?>


<!DOCTYPE html> 
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Loading...</title>
	<style>
		*{
			margin: 0;
			padding: 0;
			font-family: sans-serif;
		}
		
		body{
			box-sizing: border-box;
		}
		
		.mainHeader{
			background: #eee;
		}
		
		.mainHeader ul{
			display: inline-flex;
			list-style: none;
		}
		
		.mainHeader li{
			padding: 10px 15px;
			cursor: pointer;
			color: #888;
			font-weight: bold;
		}
		
		.mainHeader li.active_list_item{
			color: #000;
		}
		
	</style>
	<style id="current_subPageCSS"></style>
	<script id="current_subPageJS"></script>
</head>
<body>
	<section class="mainHeader">
		<ul>
			<li class="active_list_item" onclick="changePage(this)">Home</li>
			<li onclick="changePage(this)">Contact</li>
			<li onclick="changePage(this)">About</li>
		</ul>
	</section>
	<section id="current_subPageHTMLContent"></section>
	<script type="text/javascript">
		/*Script To Load Pags internally Without Reloading Page*/
		function changePage(e){
			if(Object.values(e.classList).includes('active_list_item')) return;
			let page = e.innerText.replace(/[^a-zA-Z ]/g, "");
			//Step1
			switchMenu(e);
			//Step2
			cleanBody();
			//Step3
			pageLoader(page);
		}
		
		function cleanBody(){
			document.getElementById('current_subPageHTMLContent').innerHTML = `Loading Content`;
		}
		
		function switchMenu(selected){
			document.getElementsByClassName('active_list_item')[0].classList.remove('active_list_item');
			selected.classList.add('active_list_item');
		}
		
		function pageLoader(page){
			let path = '<?php echo $page_url; ?>sub_page/' + page.split(' ').join('_').toLocaleLowerCase() + '/';
			document.title = page;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if(this.readyState == 4){
					if (this.status == 200) {
						//Load CSS
						var elm = document.getElementById('current_subPageCSS');
						if(elm !== undefined) elm.remove();

						let linkElm = document.createElement('link');
						linkElm.type = 'text/css';
						linkElm.id = 'current_subPageCSS';
						linkElm.setAttribute('rel','stylesheet');
						linkElm.href = path +'style.css';
						document.getElementsByTagName('head')[0].appendChild(linkElm);
						
						linkElm.onload = ()=>{
							//push content
							document.getElementById('current_subPageHTMLContent').innerHTML = this.responseText;
						}

						//Load JS
						var elm2 = document.getElementById('current_subPageJS');
						if(elm2 !== undefined) elm2.remove();

						let linkSCR = document.createElement('script');
						linkSCR.type = 'text/javascript';
						linkSCR.id = 'current_subPageJS';
						linkSCR.setAttribute('rel','stylesheet');
						linkSCR.src = path +'script.js';
						document.getElementsByTagName('head')[0].appendChild(linkSCR);
					}else{
						pageLoadError();
					}
				}
			}
			xhttp.open("GET", path + 'index.php', true);
			xhttp.send();
		}
		
		function pageLoadError(){
			document.getElementById('current_subPageHTMLContent').innerHTML = `Failed To Load Content`;
		}
		
		//load home
		pageLoader('Home');
	</script>
</body>
</html>