/**
 * Consultia Prime - navigation toggle.
 *
 * The nav is hidden below 980px and revealed by the toggle button. Progressive
 * enhancement: without this file the button is inert and the markup still
 * renders, so nothing is hidden behind a script that may not run.
 */
( function () {
	'use strict';

	var toggle = document.querySelector( '[data-nav-toggle]' );
	var nav = document.getElementById( 'site-nav' );

	if ( ! toggle || ! nav ) {
		return;
	}

	toggle.addEventListener( 'click', function () {
		var isOpen = nav.classList.toggle( 'is-open' );
		toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
	} );
}() );
