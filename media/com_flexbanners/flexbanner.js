
var $j = jQuery.noConflict();
(function($) {
	$(document).ready(function() {
		$("#jform_type").on("change", function(a, params) {

			var v = typeof (params) !== "object" ? $("#jform_type").val() : params.selected;

			var image = $("#image");
			var linkurl = $("#linkurl");
			var flash = $("#flash");
			var custom = $("#custom");
			var cloud = $("#cloud_image");

			switch (v) {
			case "0":
				// Image
				image.show();
				linkurl.show();
				flash.hide();
				custom.hide();
				cloud.hide();
				break;
			case "1":
				// Flash
				image.hide();
				linkurl.show();
				flash.show();
				custom.hide();
				cloud.hide();
				break;
			case "2":
				// Custom
				image.hide();
				linkurl.hide();
				flash.hide();
				custom.show();
				cloud.hide();
				break;
			case "3":
				// Cloud
				image.hide();
				linkurl.show();
				flash.hide();
				custom.hide();
				cloud.show();
				break;

			}
		}).trigger("change");
	});
})(jQuery);

