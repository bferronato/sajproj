function enableSubMenus(){
	var lis = document.getElementsByTagName('li');
	for (var i = 0, li; li = lis[i]; i++){
	 var link = li.getElementsByTagName('a')[0];
	 if (link){
		link.onfocus = function(){
		 var ul = this.parentNode.getElementsByTagName('ul')[0];
		 if (ul)
		 ul.style.display = 'block';
		}
		var ul = link.parentNode.getElementsByTagName('ul')[0];
		if (ul){
		 var ullinks = ul.getElementsByTagName('a');
		 var ullinksqty = ullinks.length;
		 var lastItem = ullinks[ullinksqty - 1];
		 if (lastItem){
			lastItem.onblur = function(){
			 this.parentNode.parentNode.style.display = '';
			}
		 }
		}
	 }
	}
}
window.onload = enableSubMenus;