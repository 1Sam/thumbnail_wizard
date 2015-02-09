<?php
class thumbnail_wizardController extends thumbnail_wizard
{

	/**
	 * Initialization
	 *
	 * @return void
	 */
	function init()
	{

	}

	function triggerGetThumbnail(&$triggerObj) {
		if($triggerObj->variables['thumbnail_url']) return new Object(-1,'thumb : 이미 있네용');

		$triggerObj->add("module_thumbnail_wizard", "thumbnail_wizard");	

		// & 참조로 받아온 $triggerObj 에 thumbnail_url을 추가해 준다. 이렇게 하면 document.getThumbnail() 함수에서 $this에 반영된다.
		// $triggerObj->variables['thumbnail_url'] = '';

		//return new Object(0,'success');

		$document_srl = $triggerObj->variables['document_srl'];
		$width = $triggerObj->variables['width'];
		$height = $triggerObj->variables['height'];
		$thumbnail_type = $triggerObj->variables['thumbnail_type'];
	
		$is_save = TRUE;
	
		// Define thumbnail information
		$thumbnail_path = sprintf('files/thumbnails/%s',getNumberingPath($document_srl, 3));
		$thumbnail_file = sprintf('%s%dx%d.%s.jpg', $thumbnail_path, $width, $height, $thumbnail_type);
		$thumbnail_url  = Context::getRequestUri().$thumbnail_file;

		// 저장된 썸네일이 있으면 바로 주소 리턴
		if(file_exists($thumbnail_file)) {
			//if(filesize($thumbnail_file)<1) return false;
			//else return $thumbnail_url;
			if(filesize($thumbnail_file)>0) {//return $thumbnail_url;
				$oObj = new Object(-1,'썸네일 마법사 : 이미 있는 썸네일 주소 리턴');
				$oObj->variables['thumbnail_url'] = $thumbnail_url;//$source_file;
				$triggerObj->variables['thumbnail_url'] = $thumbnail_url;

				/*$myFile = "testFile_trigger6_".$triggerObj->document_srl.$triggerObj->variables['comment_status'].".txt";
				$fh = fopen($myFile, 'w') or die("can't open file");
				$stringData = $tmp_file.'///'.$video_info->target_src.'//'.print_r($video_info,true).'//'.print_r($oObj,true).'//'.print_r($triggerObj,true);
				fwrite($fh, $stringData);
				fclose($fh);
				return $oObj;*/

				return $oObj;
			}
		}

	
		// content 포함 여부확인
		// 글보기를 한 것만 content 가 포함되어 있음
		$content = $triggerObj->variables['content'];

		if(!$content) {
			/*$oDocumentModel = &getModel('document');
			$oDocument = $oDocumentModel->getDocument($document_srl);
			$content = $oDocument->get('content');*/
			$args->document_srl = $document_srl;
			$output = executeQuery('document.getDocument', $args, '');
			$content = $output->data->content;

			// document.item 쪽에도 content 내용 반환해줌
			$triggerObj->variables['content'] = $content;

		}

		// 컨텐츠에서 동영상 주소 검사
		if($content) {
			$video_info = $this->get_videoinfo($content);
			
			if($video_info->target_src) {

				// $video_info->thumbnail_url 은 실제 주소로 동영상 서버에서 썸네일을 다운받을 수 없게 막은 경우에 이용됨.
				if(!$video_info->thumbnail_url  && ($is_save == TRUE)) {
					// 썸네일 저장
					//$target_src = "http://img.youtube.com/vi/".$youtubeid."/0.jpg"; 
					$tmp_file = sprintf('./files/cache/tmp/%s', md5(rand(111111,999999).$document_srl));
					if(!is_dir('./files/cache/tmp')) FileHandler::makeDir('./files/cache/tmp');
					FileHandler::getRemoteFile($video_info->target_src, $tmp_file);
					// 원격서버에서 소스파일을 tmp 폴더에 복사해 왔다면
					// thumbnails 폴더로 복사후 최종 주소 리턴
					if(file_exists($tmp_file)) {
						//list($_w, $_h, $_t, $_a) = @getimagesize($tmp_file);
						//if($_w>=$width && $_h>=$height)
						//{
						//$source_file = $tmp_file;
						$is_tmp_file = true;
						//}
						$outputz = FileHandler::createImageFile($tmp_file, $thumbnail_file, $width, $height, 'jpg', $thumbnail_type);
						FileHandler::removeFile($tmp_file);

						/*$myFile = "testFile_trigger01_".$triggerObj->document_srl.$triggerObj->variables['comment_status'].".txt";
						$fh = fopen($myFile, 'w') or die("can't open file");
						$stringData = $tmp_file.'///'.$video_info->target_src.'//'.print_r($video_info,true).'//'.print_r($oObj,true).'//'.print_r($triggerObj,true);
						fwrite($fh, $stringData);
						fclose($fh);*/
						$oObj = new Object(-1,'동영상 썸네일생성');
						$oObj->message = "썸네일 생성성공";
						$oObj->variables['thumbnail_url'] = $thumbnail_file;
						$triggerObj->variables['thumbnail_url'] = $thumbnail_file;
					} else {
						$oObj = new Object(-1,'동영상 썸네일생성 실패');
						$oObj->message = "썸네일 생성 실패";

						// 유투브 영상이 짤렸으므로 에러 이미지를 표시함
						$oObj->variables['thumbnail_url'] = './modules/thumbnail_wizard/tpl/images/youtube_blocked.png';//$thumbnail_file;
						$triggerObj->variables['thumbnail_url'] = './modules/thumbnail_wizard/tpl/images/youtube_blocked.png';//$thumbnail_file;
					}
		

					// Create an empty file not to re-generate the thumbnail
					//else FileHandler::writeFile($thumbnail_file, '','w');

					

					/*$myFile = "testFile_trigger4_".$triggerObj->document_srl.$triggerObj->variables['comment_status'].".txt";
					$fh = fopen($myFile, 'w') or die("can't open file");
					$stringData = $tmp_file.'///'.$video_info->target_src.'//'.print_r($video_info,true).'//'.print_r($oObj,true).'//'.print_r($triggerObj,true);
					fwrite($fh, $stringData);
					fclose($fh);
					return $oObj;*/


				// 썸네일을 저장않는다는 옵션이면 실제 주소 리턴
				// 또는 vimeo 의 경우
				} elseif($video_info->thumbnail_url) {

					$oObj = new Object(-1,'동영상 썸네일생성');
					$oObj->message = "썸네일 원본주소만 리턴";
					$oObj->variables['thumbnail_url'] = $video_info->thumbnail_url;
					$triggerObj->variables['thumbnail_url'] = $video_info->thumbnail_url;
				} else {
					$oObj = new Object(-1,'동영상 썸네일생성');
					$oObj->message = "썸네일 오류";
					$oObj->variables['thumbnail_url'] = $video_info->thumbnail_url;
					$triggerObj->variables['thumbnail_url'] = $video_info->thumbnail_url;
					$oObj->video_info = $video_info;


				}
				
				return $oObj;
			} 
		}
	
		//$oObj = new Object(-1,'피카사 썸네일생성');
		//$oObj->variables['thumbnail_url'] = $video_info->target_src;//$source_file;

		/*$myFile = "testFile_trigger2_".$triggerObj->document_srl.$triggerObj->variables['comment_status'].".txt";
		$fh = fopen($myFile, 'w') or die("can't open file");
		$stringData = $video_info->target_src.'//'.print_r($video_info,true).'//'.print_r($oObj,true).'//'.print_r($triggerObj,true);
		fwrite($fh, $stringData);
		fclose($fh);*/

		//return $oObj;

		// 아무런 작업없이 썸네일 마법사 작업 종료
		return new Object(0,'뭔가 이상함');
	}
	
	// 컨텐츠 내용으로 각 동영상의 종류를 구별하고
	// 그 썸네일을 리턴함
	function get_videoinfo($content) {
		
		$output = new stdClass();

		//$output->content = $content;
		//$output->site // 동영상 사이트
		//$output->id // 동영상 아이디
		//$output->target_src // 실제 위치
		//$output->thumbnail_url // 생성된 썸네일의 위치
		
		
		/*// TED
		if (preg_match('!(((ht|f)tps?:\/\/)|(www.))[a-zA-Z0-9_\-.:#/~}?]+.jpg!', $content, $match)) 
		{
			$target_src = $match[0];

			$output->site = 'ted';
			$output->id = NULL;
			$output->target_src = $target_src;




		// Youtube case 1
		} else*///if(preg_match('~(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu.*?\.(?:be|com)\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)~i', $content, $match)) {



		if(preg_match('/(?:youtube-nocookie\.com\/embed\/|youtube\.com\/watch\?v\=|youtube\.com\/v\/|youtu\.be\/?|youtube\.com\/embed\/).*?([0-9a-zA-Z-_]{11})(?:\/W)?/i',$content, $matches)) {
			//(?:youtube-nocookie\.com\/embed\/|youtube\.com\/watch\?v\=|youtube\.com\/v\/|youtu\.be\/|youtube\.com\/embed\/)(.*)(?:\/W)?
			/*if(strpos($content,"list=") !== false){
					$youtubeid = substr($matches[1][0], 0, 35);
					
					//$youtubeid = $youtubeid.'&amp;';
			} else {
					$youtubeid = substr($matches[1][0], 0, 11);
					//$youtubeid = $youtubeid.'?';
			}*/

			$youtubeid = $matches[1];
			$output->site = 'youtube';
			$output->id = $youtubeid;
			$output->target_src = "http://img.youtube.com/vi/".$youtubeid."/0.jpg";
			
			/*$myFile = "testFile_tudou_".$youtubeid.".txt";
				//http://api.tudou.com/v6/video/info?app_key=d4e477fb03075b75format=json&itemCodes=eNoG-G9OkrQ
			$fh = fopen($myFile, 'w') or die("can't open file");
			$stringData = print_r($matches,true);
			fwrite($fh, $stringData);
			fclose($fh);*/
			

		// Daum 신형
		} elseif (preg_match('/daum.net\/?.*\/([0-9a-zA-Z]{23})(?:\W)?/i', $content, $match)) {
			$Daum_Id = $match[1];

			$output->site = 'daum';
			$output->id = $Daum_Id;
			$output->target_src = "http://i1.daumcdn.net/svc/image/U03/tvpot_thumb/".$Daum_Id."/thumb.png";

		/*// Daum case 1 try to get all Cases 이부분에서 대부분 23자리 아이디를 추출해 냄	
		} elseif (preg_match('~(?:daum\.net/(?:user/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|daum\.net/)([^"&?/ ]{23})~i', $content, $match)) {
			$Daum_Id = $match[1];

			$output->site = 'daum';
			$output->id = $Daum_Id;
			$output->target_src = "http://i1.daumcdn.net/svc/image/U03/tvpot_thumb/".$Daum_Id."/thumb.png";

		// Daum case 2
		} elseif(preg_match('~\/v\/([A-Za-z0-9-_]{23}).+?|html\?vid=([0-9A-Za-z-_]{23})|daum\.net\/vod\/([0-9A-Za-z-_]{23})|swf\?vid=([0-9A-Za-z-_]{23})~si', $content, $match)) {
			for ($i=1; $i<=4; $i++) {
				if (strlen($match[$i])==23) {
					$Daum_Id = $match[$i];
					break;
				}
			}

			$output->site = 'daum';
			$output->id = $Daum_Id;
			$output->target_src = "http://i1.daumcdn.net/svc/image/U03/tvpot_thumb/".$Daum_Id."/thumb.png";*/


		// Dailymotion 
		} elseif(preg_match('/dailymotion\.com\/video\/(.*)/i',$content,$match)) {
			$Dailymotion_id = reset(split('_', $match[1]));
	
			$output->site = 'dailymotion';
			$output->id = $Dailymotion_id;
			$output->target_src = "http://www.dailymotion.com/thumbnail/video/".$Dailymotion_id;

		// Vimeo
		} elseif(preg_match('/vimeo.com\/?.*\/(\d{8,11})(?:\W)?/i', $content, $matches)) {
			$vimeo_id = $matches[1];

			$hash = unserialize($this->get_url_contents("http://vimeo.com/api/v2/video/".$vimeo_id.".php"));
			$target_src = $hash[0]['thumbnail_medium'];

			$output->site = 'vimeo';
			$output->id = $vimeo_id;
			// 동영상 썸네일 주소
			$output->target_src = $target_src;

			// 동영상 서버에서 썸네일 파일을 다운하지 못하도록 막은 경우
			// 아래의 값으로 이미지 실제 주소를 바로 리턴해줘야함
			$output->thumbnail_srl = $output->target_src;

			//!!! 중요
			//return $target_src; // 컨텐츠위젯에서는 제대로 썸네일이 아래의 코드로 저장되지만, 게시판은 묘하게 썸네일 생성이 안되어서 그냥 주소 바로 리턴

		// Tudou
		} elseif(preg_match('/tudou.com\/?.*\/([0-9a-zA-Z]{11})(?:\W)?/i',$content,$match)) {
			$Tudou_id = $match[1];

			// api_key 필수입니다.
			$api_key =  'd4e477fb03075b75'; //http://www.zyjay.com/736.html
			$hash = json_decode($this->get_url_contents("http://api.tudou.com/v6/video/info?app_key=".$api_key."&format=json&itemCodes=".$Tudou_id));
			
			/*$myFile = "testFile_tudou_".strlen($content).".txt";
				//http://api.tudou.com/v6/video/info?app_key=d4e477fb03075b75format=json&itemCodes=eNoG-G9OkrQ
			$fh = fopen($myFile, 'w') or die("can't open file");
			$stringData = print_r($hash->{'results'}[0]->bigPicUrl,true);
			fwrite($fh, $stringData);
			fclose($fh);*/

/*stdClass Object
(
    [results] => Array
        (
            [0] => stdClass Object
                (
                    [itemCode] => jL1NzqAE6j8
                    [vcode] => 
                    [title] => 煎蛋小学堂：假若地球停转
                    [tags] => youtube,煎蛋,煎蛋小学堂,Vsauce
                    [description] => @jandan.net
                    [picUrl] => http://g3.tdimg.com/5417b9ad10d7287804000af694219403/p_2.jpg
                    [totalTime] => 583730
                    [pubDate] => 2014-07-05
                    [ownerId] => 121441540
                    [ownerName] => xiaoxuetang
                    [ownerNickname] => 煎蛋小学堂
                    [ownerPic] => http://u3.tdimg.com/0/61/191/48173541489577008905415334970835219550.jpg
                    [ownerURL] => http://www.tudou.com/home/xiaoxuetang
                    [channelId] => 25
                    [outerPlayerUrl] => http://www.tudou.com/v/jL1NzqAE6j8/v.swf
                    [playUrl] => http://www.tudou.com/programs/view/jL1NzqAE6j8/
                    [mediaType] => 视频
                    [secret] => 
                    [hdType] => 2,3,5
                    [playTimes] => 27416
                    [commentCount] => 19
                    [bigPicUrl] => http://g3.tdimg.com/5417b9ad10d7287804000af694219403/w_2.jpg
                    [alias] => 
                    [downEnable] => 1
                    [location] => 
                    [favorCount] => 1
                    [outerGPlayerUrl] => http://www.tudou.com/programs/view/html5embed.action?code=jL1NzqAE6j8
                    [digCount] => 157
                    [buryCount] => 0
                )

        )

)*/

			$output->site = 'dailymotion';
			$output->id = $Tudou_id;
			$output->target_src = $hash->{'results'}[0]->bigPicUrl;

		}
		



		/*$myFile = "testFile_triggera_".$document_srl.$triggerObj->variables['comment_status'].".txt";
		$fh = fopen($myFile, 'w') or die("can't open file");
		$stringData = print_r($output,true).'//'.print_r($oObj,true).'//'.print_r($triggerObj,true);
		fwrite($fh, $stringData);
		fclose($fh);*/


		return $output;
	}

	function get_url_contents($url){
		$crl = curl_init();
		$timeout = 5;
		curl_setopt ($crl, CURLOPT_URL,$url);
		curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
		$ret = curl_exec($crl);
		curl_close($crl);
		return $ret;
	}

}
/* End of file thumbnail_wizard.controller.php */
/* Location: ./modules/thumbnail_wizard/thumbnail_wizard.controller.php */
