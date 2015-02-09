# thumbnail_wizard
[모듈] 썸네일 마법사


http://1sam.kr/index.php?mid=xe_tips&category=54048&document_srl=71145

<p>썸네일 마법사는 document.item.php의 getThumbnail( ); 함수에서 제대로 생성하지 못하는 썸네일을 생성해 줍니다.</p>
<p><br /></p>
<p>썸네일 마법사 모듈을 사용하기 위해서는 아래와 같은 선행작업이 필요합니다.</p>
<p><br /></p>
<p><br /></p>
<p>1. 트리거 추가하기</p>
<p><br /></p>
<p>xe/modules/document/document.item.php 의 getThumbnail(); 함수(777번째 줄)에 다음의 <span style="color: rgb(255, 255, 255); background-color: rgb(0, 0, 255);">파란색으로 된&nbsp;트리거 코드</span>를 삽입합니다.</p>
<p><br /></p>
<p><br /></p>
<blockquote class="q4"><p>&nbsp;function getThumbnail($width = 80, $height = 0, $thumbnail_type = '')<br />&nbsp;{<br />&nbsp;&nbsp;// Return false if the document doesn't exist<br />&nbsp;&nbsp;if(!$this-&gt;document_srl) return;<br />&nbsp;&nbsp;// If not specify its height, create a square<br />&nbsp;&nbsp;if(!$height) $height = $width;</p>
<p><br /></p>
<p><span style="color: rgb(255, 255, 255); background-color: rgb(0, 0, 255);">&nbsp;&nbsp;//* 모듈을 위해 추가된 트리거 시작 *//</span><br /><span style="color: rgb(255, 255, 255); background-color: rgb(0, 0, 255);">&nbsp;&nbsp;//* 1Sam 이 썸네일 마법사 모듈을 위해 추가한 트리거입니다.</span><br /><span style="color: rgb(255, 255, 255); background-color: rgb(0, 0, 255);">&nbsp;&nbsp;$this-&gt;variables['width'] = $width;</span><br /><span style="color: rgb(255, 255, 255); background-color: rgb(0, 0, 255);">&nbsp;&nbsp;$this-&gt;variables['height'] = $height;</span><br /><span style="color: rgb(255, 255, 255); background-color: rgb(0, 0, 255);">&nbsp;&nbsp;$this-&gt;variables['thumbnail_type'] = $thumbnail_type;</span></p>
<span style="color: rgb(255, 255, 255); background-color: rgb(0, 0, 255);">
</span><p><span style="color: rgb(255, 255, 255); background-color: rgb(0, 0, 255);">&nbsp;&nbsp;// trigger 호출 (before)</span><br /><span style="color: rgb(255, 255, 255); background-color: rgb(0, 0, 255);">&nbsp;&nbsp;$output = ModuleHandler::triggerCall('document.getThumbnail', 'before', $this);</span><br /><span style="color: rgb(255, 255, 255); background-color: rgb(0, 0, 255);">&nbsp;&nbsp;if(!$output-&gt;toBool()) return $this-&gt;variables['thumbnail_url'];</span><br /><span style="color: rgb(255, 255, 255); background-color: rgb(0, 0, 255);">&nbsp;&nbsp;//* 모듈을 위해 추가된 트리거 끝 *//</span></p>
<p><br /></p>
<p>&nbsp;&nbsp;// Return false if neither attachement nor image files in the document<br />&nbsp;&nbsp;if(!$this-&gt;get('uploaded_count') &amp;&amp; !preg_match("!&lt;img!is", $this-&gt;get('content'))) return;<br /></p>
</blockquote><p><br /></p>
<p>

</p>
<p><br /></p>
<p>2. 썸네일 마법사 모듈 설치하기</p>
<p><br />
</p>
