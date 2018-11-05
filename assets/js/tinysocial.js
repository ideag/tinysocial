function tinysocial_pop_center( url, width, height ) {
	var left = ( screen.width / 2 ) - ( width / 2 );
	var top = ( screen.height / 2 ) - ( height / 2 );
	window.open( url, '_blank', 'left='+left+',top='+top+',width='+width+',height='+height+',menubar=no,status=no,scrollbars=no,toolbar=no' );
}

jQuery(function($){
	$('.tinysocial').on( 'click', function(e) {
		e.preventDefault();
		var width = 655;
		var height = 350;
		if ($(this).attr('data-height')) {
		    height =  $(this).attr('data-height');
        }
        if ($(this).attr('data-width')) {
            width =  $(this).attr('data-width');
        }
		if (typeof( ga ) !== 'undefined') {
			var network = $(this).attr('data-network');
			var url =  $(this).attr('data-url');
			ga( 'send', 'social', network, 'share', url );
		}
		tinysocial_pop_center( $(this).attr('href'), width, height );
	});
});
