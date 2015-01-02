<?php

$cookie = '';

function curlGet($url,$headers) {
	$ch = curl_init();
	curl_setopt_array($ch, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => TRUE,
		CURLOPT_FOLLOWLOCATION => TRUE,
		CURLOPT_HTTPHEADER => $headers,
	));	
	$data = curl_exec($ch);
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE );
	curl_close($ch);
	$result = array('status' => $status, 'data' => $data);
	return $result;		
}	

$inbox = curlGet(
			$url='https://i.instagram.com/api/v1/direct_share/inbox/',
			$headers = array($cookie)
		);

$inbox = json_decode($inbox['data']);

$dms = $inbox->shares;

?>
<html>
<head>
<style>
	body {font-family: helvetica,arial,sans-serif;}
	h1 {font-size:20px; color:#ccc;}
	div.dm {
		float: left;
		width: 90%;
		padding:10px 50px;
		border:1px solid gray;
		margin-bottom:20px;
	}
	a.recipient {
		width:80px;
		height: 80px;
		border-radius: 100px;
		margin:25px 10px;
		float:left;
		text-decoration: none;
		padding:10px;
	}
	div.img {float:left; width:100%; margin: 50px 0 0 0;}
	div.img img {border:5px solid #ccc;}
	div.comments {
		float: left;
		width:100%;
		margin-top:30px;
	}
	div.recipients {float:left; width:100%;}
	a.recipient p {
		position: relative;
		top: 85px;
		text-align: center;
		font-size: 11px;
		font-weight: bold;
	}
</style>
</head>
<body>
<?php foreach($dms as $dm): ?>
	<div class="dm">
		<div class="img">
		<img src="<?=$dm->image_versions[0]->url;?>" />
		<p><strong><?=$dm->caption->user->username;?></strong>: <?=$dm->caption->user->username;?></p>
		</div>

		<div class="recipients">
			<h1>Recipients</h1>
			<?php foreach($dm->recipients as $recipient):?>
			<a href="http://instagram.com/<?=$recipient->user->username;?>" target="_blank" class="recipient" style="background:url('<?=$recipient->user->profile_pic_url;?>'); background-size:cover;">
			<p><?=wordwrap($recipient->user->username, 15,"<br/>", true);?></p></a>
			<?php endforeach;?>
		</div>
		
		<div class="comments">
			<h1>Comments</h1>
			<?php foreach($dm->comments as $comment): ?>
				<p><strong><?=$comment->user->username;?></strong>: <?=$comment->text;?></p>
			<?php endforeach; ?>
		</div>
	</div>
<?php endforeach; ?>
</body>
</html>