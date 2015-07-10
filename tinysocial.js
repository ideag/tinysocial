function tinysocial_pop_center( url, width, height ) {
	var left = ( screen.width / 2 ) - ( width / 2 );
	var top = ( screen.height / 2 ) - ( height / 2 );
	window.open( url, '_blank', 'left='+left+',top='+top+',width='+width+',height='+height+',menubar=no,status=no,scrollbars=no,toolbar=no' );
}

jQuery(function($){
	$('.tinysocial').on( 'click', function(e) {
		e.preventDefault();
		if (typeof(_gaq) !== 'undefined') {
			var network = $(this).attr('data-network');
			var url =  $(this).attr('data-url');
			ga( 'send', 'social', network, 'share', url );
		}
		tinysocial_pop_center( $(this).attr('href'), 655, 350 );
	});	
});
