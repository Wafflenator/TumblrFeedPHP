<?php
	//cURL Tumblr
	$curl_handle=curl_init();
	curl_setopt($curl_handle,CURLOPT_URL,'http://api.tumblr.com/v2/blog/{USERNAME}.tumblr.com/posts?api_key={API KEY - NO QUOTES}');

	//Stop After 2 Seconds of No Response
	curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);

	//Return Data & Close Connection
	curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	$buffer = curl_exec($curl_handle);
	curl_close($curl_handle);

	//Make JSON Useable
	$tumblrStream = json_decode($buffer);

	//If Tumblr Times Out or No Posts - Fail
	if (empty($buffer))
	{
		echo '<div class="tumblr_post">Tumblr Feed Down - Refresh Page to Retry</div>';
	}
	//Display Tumblr Posts
	else
	{
		//Dig Down to Posts Section of Returned JSON
		$tumblrStream = $tumblrStream->response->posts;
		
		//Print Base JSON for Reference
		//echo '<pre>';
		//print_r($tumblrStream);
		//echo '</pre>';
		
		//Output to HTML
		foreach($tumblrStream as $tumblrPost)
		{
			switch ($tumblrPost->type)
			{
				case 'photo':
					//Declare Variables
					$tumblrPhotoCaption = $tumblrPost->caption;
					$tumblrPhotoSmallURL = $tumblrPost->photos[0]->alt_sizes[2]->url; //photos[#] Signifies displayed photo size (Can be 0=Largest to 5=Smallest)
					$tumblrPhotoFullURL = $tumblrPost->photos[0]->original_size->url;
					$tumblrPhotoDatePosted = $tumblrPost->date;
					
					//HTML
					echo "<div class=\"tumblr_post tumblr_post_photo\">
							<fieldset>
								<legend>Photo</legend>
								<div class=\"tumblr_date_posted\">POSTED: $tumblrPhotoDatePosted</div>
								<br />
								<a href=\"$tumblrPhotoFullURL\"><img src=\"$tumblrPhotoSmallURL\"></a>
								$tumblrPhotoCaption
							</fieldset>
						 </div>
						 <br />";
					break;
				
				case 'text':
					//Declare Variables
					$tumblrTextTitle = $tumblrPost->title;
					$tumblrTextBody = $tumblrPost->body;
					$tumblrTextDatePosted = $tumblrPost->date;

					//HTML
					echo "<div class=\"tumblr_post tumblr_post_text\">
							<fieldset><legend>$tumblrTextTitle</legend>
								<div class=\"tumblr_date_posted\">POSTED: $tumblrPhotoDatePosted</div>
								<br />
								<p>$tumblrTextBody</p>
							</fieldset>
						 </div>
						 <br />";
					break;

				case 'quote':
					//Declare Variables
					$tumblrQuoteText = $tumblrPost->text;
					$tumblrQuoteSource = $tumblrPost->source;
					$tumblrQuoteDatePosted = $tumblrPost->date;

					//HTML
					echo "<div class=\"tumblr_post tumblr_post_quote\">
							<div class=\"tumblr_date_posted\">POSTED: $tumblrQuoteDatePosted</div>
							<br /><br />
							<blockquote><p>$tumblrQuoteText</p><small>$tumblrQuoteSource</small></blockquote>
						  </div>
						  <br />";
					break;

				case 'chat': //THIS IS GOING TO NEED ANOTHER INTERNAL foreach
					//Declare Variables
					$tumblrChatTitle = $tumblrPost->title;
					$tumblrChatBody = $tumblrPost->body;
					$tumblrChatDatePosted = $tumblrPost->date;

					//HTML
					echo "<div class=\"tumblr_post tumblr_post_chat\">
							<fieldset><legend>$tumblrChatTitle</legend>
								<div class=\"tumblr_date_posted\">POSTED: $tumblrChatDatePosted</div>
								<br />
								<p>Chat Post</p>
						  </div>
						  <br />";
					break;

				case 'link':
					//Declare Variables
					$tumblrLinkTitle = $tumblrPost->title;
					$tumblrLinkURL = $tumblrPost->url;
					$tumblrLinkDescription = $tumblrPost->description;
					$tumblrLinkDatePosted = $tumblrPost->date;

					//HTML
					echo "<div class=\"tumblr_post tumblr_post_link\">
							<div class=\"tumblr_date_posted\">POSTED:$tumblrLinkDatePosted</div>
							<br />
						  </div>
						  <br />";
					break;

				case 'video':
					//Declare Variables
					$tumblrVideoCaption = $tumblrPost->caption;
					$tumblrVideoEmbed = $tumblrPost->player[2]->embed_code; //player[#] Signifies Video Player Size (Can be 0=Smallest to 2=Largest)
					$tumblrVideoDatePosted = $tumblrPost->date;

					//HTML
					echo "<div class=\"tumblr_post tumblr_post_video\">
							$tumblrVideoEmbed
							<br />
							$tumblrVideoCaption
						  </div>
						  <br />";
					break;

				case 'audio':
					//Declare Variables
					$tumblrAudioDatePosted = $tumblrPost->date;

					//HTML
					echo "<div class=\"tumblr_post tumblr_post_audio\">
							Audio Post
						  </div>
						  <br />";
					break;
					
				default:
					echo "-Error Retrieving Post-";
					break;
			}
		}
	}
?>