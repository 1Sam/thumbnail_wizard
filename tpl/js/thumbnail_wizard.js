function removethumbnails(){
	if(confirm("썸네일 폴더를 삭제하시겠습니까?")){
	
		/*var params = {
			target_srl : target_srl,
			cur_mid    : current_mid,
			mid        : current_mid
		
		};*/
		exec_xml('thumbnail_wizard','procThumbnail_wizardAdminRemoveThumbnails','', completeAdminRemoveThumbnails);
	}	
}

function completeAdminRemoveThumbnails(ret_obj) {
    var error = ret_obj['error'];
    var message = ret_obj['message'];
 
    alert('썸네일 폴더 삭제 결과 : '+message);
 
    var url = current_url.setQuery('act','dispThumbnail_wizardAdminIndex');
    location.href = url;
}



/*jQuery("#test").bind(".test", function (e, data) {
    return data.instance.toggle_node(data.node);
});

jQuery('#tree_container_id').jstree('open_all');*/
