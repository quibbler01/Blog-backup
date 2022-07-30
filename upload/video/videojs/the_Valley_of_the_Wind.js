document.addEventListener('DOMContentLoaded', function() {
	var p = $("#video").player({
		video: {
			url: {
				hq: {
					en: "风之谷.mp4"
				}
			}
		}
	}, {width: 700});
},false);