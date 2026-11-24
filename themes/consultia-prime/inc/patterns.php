<?php
/**
 * Section patterns, and the page content they assemble.
 *
 * Every section in template/index.html, template/about.html,
 * template/services.html and template/contact.html exists here as a
 * block pattern. The theme owns the layout; the pages own the copy.
 * An editor can open any of these pages and change a heading, and
 * the shape survives, because the shape is CSS keyed on the class
 * names this file emits.
 *
 * The registered patterns and the seeded pages are generated from the
 * same functions, so they cannot drift: consultia_prime_home_content()
 * and its three siblings return exactly what is handed to
 * register_block_pattern().
 *
 * Markup notes, all verified against core's own save functions:
 *
 *   - A Group with tagName `section` saves as <section> and takes
 *     `wp-block-group` plus the className: the reference's bare
 *     `section` selector therefore works on these pages.
 *   - A Heading saves as `wp-block-heading` plus its className.
 *   - A Paragraph has `className: false` in core, so it takes its
 *     className alone and never gains `wp-block-paragraph`.
 *   - A Quote saves as <blockquote class="wp-block-quote"> with a
 *     bare <cite> for the attribution, which is what the reference
 *     hand-wrote as a blockquote plus a sibling <p>.
 *   - A Button saves as `wp-block-button` plus its className, with
 *     `wp-block-button__link wp-element-button` on the anchor.
 *
 * One rule runs through all of it: an attribute core reads back out of
 * the saved HTML is never written into the block comment. A button's
 * url and a quote's citation are `source` attributes — the editor
 * recovers both from the href and the <cite> — so a comment carrying
 * them is non-canonical and is dropped the first time the page is
 * re-saved. Only plain attributes (className, anchor, style) belong in
 * the comment. This is checked against the markup core's own bundled
 * themes ship, which is Gutenberg's own serialised output.
 *
 * @package Consultia_Prime
 */

defined( 'ABSPATH' ) || exit;

/**
 * Resolve a page slug to its permalink.
 *
 * The reference links its copy to about/services/contact pages. Those
 * are real pages here, so the link is built from the page rather than
 * typed in, which keeps it correct under any permalink structure and
 * on any host.
 *
 * @param string $slug Page slug.
 * @return string Permalink.
 */
function consultia_prime_url( $slug ) {
	$page = get_page_by_path( $slug );

	if ( $page instanceof WP_Post ) {
		return get_permalink( $page );
	}

	return home_url( '/' . $slug . '/' );
}

/**
 * Block markup for a run of buttons.
 *
 * The reference has exactly two button looks: .btn (an outline) and
 * .btn-solid (filled). They are registered as the block styles
 * `outline` and `solid` so the two are the same object in the editor
 * and in the theme's own markup.
 *
 * @param string $class   Class on the container, e.g. "hero-actions".
 * @param array  $buttons Each item: array( label, url, style ).
 * @return string Block markup.
 */
function consultia_prime_pattern_buttons( $class, $buttons ) {
	$items = '';

	foreach ( $buttons as $button ) {
		list( $label, $url, $style ) = $button;
		$url                            = esc_url( $url );

		$items .= '<!-- wp:button {"className":"' . $style . '"} -->'
			. '<div class="wp-block-button ' . $style . '">'
			. '<a class="wp-block-button__link wp-element-button" href="' . $url . '">' . esc_html( $label ) . '</a>'
			. '</div>'
			. '<!-- /wp:button -->';
	}

	return '<!-- wp:buttons {"className":"' . $class . '"} -->'
		. '<div class="wp-block-buttons ' . $class . '">' . $items . '</div>'
		. '<!-- /wp:buttons -->';
}

/**
 * Block markup for one figure and its caption.
 *
 * The reference puts .num and .mono on the same element, so the
 * Fraunces family from `.stat .num` and the tracking from `.mono` both
 * apply. Both classes are emitted for the same reason.
 *
 * @param string $number Figure, e.g. "60+".
 * @param string $label  Caption.
 * @return string Block markup.
 */
function consultia_prime_pattern_stat( $number, $label ) {
	return '<!-- wp:group {"className":"stat"} -->'
		. '<div class="wp-block-group stat">'
		. '<!-- wp:paragraph {"className":"num mono"} --><p class="num mono">' . esc_html( $number ) . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:paragraph {"className":"label"} --><p class="label">' . esc_html( $label ) . '</p><!-- /wp:paragraph -->'
		. '</div>'
		. '<!-- /wp:group -->';
}

/**
 * The three figures the reference repeats in the hero and the story.
 *
 * @return string Block markup.
 */
function consultia_prime_pattern_stats() {
	$stats = array(
		array(
			'60+',
			__( 'years supporting small and medium-sized enterprises', 'consultia-prime' ),
		),
		array(
			'3',
			__( 'public markets we regularly guide clients onto — Nasdaq, NYSE, OTC Markets', 'consultia-prime' ),
		),
		array(
			'∞',
			__( 'network of accountants, auditors, attorneys and market professionals working alongside us', 'consultia-prime' ),
		),
	);

	$markup = '';
	foreach ( $stats as $stat ) {
		$markup .= consultia_prime_pattern_stat( $stat[0], $stat[1] );
	}

	return $markup;
}

/**
 * Block markup for one service cell.
 *
 * @param string $tag         Index label, e.g. "01 · ORG".
 * @param string $title       Cell heading.
 * @param string $description Cell body.
 * @return string Block markup.
 */
function consultia_prime_pattern_service_card( $tag, $title, $description ) {
	return '<!-- wp:group {"className":"service"} -->'
		. '<div class="wp-block-group service">'
		. '<!-- wp:paragraph {"className":"tag mono"} --><p class="tag mono">' . esc_html( $tag ) . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . esc_html( $title ) . '</h3><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>' . esc_html( $description ) . '</p><!-- /wp:paragraph -->'
		. '</div>'
		. '<!-- /wp:group -->';
}

/**
 * The four service cells, shared by the home page and the services page.
 *
 * @return string Block markup.
 */
function consultia_prime_pattern_service_grid() {
	$services = array(
		array(
			'01 · ORG',
			__( 'Organizational improvement', 'consultia-prime' ),
			__( 'We look at how your company is structured and run, and recommend the changes that let it scale and stand up to outside scrutiny.', 'consultia-prime' ),
		),
		array(
			'02 · ANALYSIS',
			__( 'Business analysis', 'consultia-prime' ),
			__( 'A clear-eyed read of where your business stands today, so decisions about growth, capital, or a public listing are made on solid ground.', 'consultia-prime' ),
		),
		array(
			'03 · SYSTEMS',
			__( 'Accounting information systems', 'consultia-prime' ),
			__( 'We design and refine the systems that produce your numbers, so what you report is accurate, timely, and built to last through an audit.', 'consultia-prime' ),
		),
		array(
			'04 · COMPLIANCE',
			__( 'Financial-reporting compliance', 'consultia-prime' ),
			__( 'We keep your reporting aligned with what regulators and exchanges require — before it becomes a problem, not after.', 'consultia-prime' ),
		),
	);

	$grid = '';
	foreach ( $services as $service ) {
		$grid .= consultia_prime_pattern_service_card( $service[0], $service[1], $service[2] );
	}

	return '<!-- wp:group {"className":"service-grid"} -->'
		. '<div class="wp-block-group service-grid">' . $grid . '</div>'
		. '<!-- /wp:group -->';
}

/**
 * The pull-quote the reference repeats on the home and about pages.
 *
 * @return string Block markup.
 */
function consultia_prime_pattern_overview() {
	return '<!-- wp:group {"tagName":"section","className":"overview"} -->'
		. '<section class="wp-block-group overview">'
		. '<!-- wp:group {"className":"wrap"} --><div class="wp-block-group wrap">'
		. '<!-- wp:quote -->'
		. '<blockquote class="wp-block-quote">'
		. '<!-- wp:paragraph --><p>' . esc_html__( '“Our services include organizational improvement, business analysis, accounting information systems, and financial-reporting compliance — with particular expertise in assisting companies quoted or listed on Nasdaq, the New York Stock Exchange, and OTC Markets.”', 'consultia-prime' ) . '</p><!-- /wp:paragraph -->'
		. '<cite>' . esc_html__( '— How we describe our work', 'consultia-prime' ) . '</cite>'
		. '</blockquote>'
		. '<!-- /wp:quote -->'
		. '</div><!-- /wp:group -->'
		. '</section>'
		. '<!-- /wp:group -->';
}

/**
 * Hero section. The only h1 on the home page.
 *
 * The group carries the `hero` class, which is what
 * consultia_prime_inject_ticker() looks for when it places the ticker
 * directly below the hero.
 *
 * The <em> in the headline is the point of the headline: the reference
 * sets it in Fraunces italic and the accent colour, and
 * `assets/fonts.css` ships the italic cut that makes that a real italic
 * rather than a slanted upright.
 *
 * @return string Block markup.
 */
function consultia_prime_pattern_hero() {
	return '<!-- wp:group {"tagName":"section","className":"hero","anchor":"top"} -->'
		. '<section id="top" class="wp-block-group hero">'
		. '<!-- wp:group {"className":"wrap"} --><div class="wp-block-group wrap">'
		. '<!-- wp:group {"className":"hero-grid"} --><div class="wp-block-group hero-grid">'
		. '<!-- wp:group --><div class="wp-block-group">'
		. '<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">' . esc_html__( 'Professional Services & Capital-Markets Advisory', 'consultia-prime' ) . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":1} --><h1 class="wp-block-heading">'
		. esc_html__( 'Sixty years of getting companies', 'consultia-prime' )
		. '<em>' . esc_html__( 'market-ready.', 'consultia-prime' ) . '</em>'
		. '</h1><!-- /wp:heading -->'
		. '<!-- wp:paragraph {"className":"lede"} --><p class="lede">' . esc_html__( 'We are a professional services firm supporting small and medium-sized enterprises across multiple countries — with particular depth in the compliance work that gets a company onto Nasdaq, the NYSE, or OTC Markets, and keeps it there.', 'consultia-prime' ) . '</p><!-- /wp:paragraph -->'
		. consultia_prime_pattern_buttons(
			'hero-actions',
			array(
				array( __( 'Start a conversation', 'consultia-prime' ), consultia_prime_url( 'contact' ), 'is-style-solid' ),
				array( __( 'See our services', 'consultia-prime' ), consultia_prime_url( 'services' ), 'is-style-outline' ),
			)
		)
		. '</div><!-- /wp:group -->'
		. '<!-- wp:group {"className":"hero-side"} --><div class="wp-block-group hero-side">'
		. '<!-- wp:group {"className":"stat-row"} --><div class="wp-block-group stat-row">' . consultia_prime_pattern_stats() . '</div><!-- /wp:group -->'
		. '</div><!-- /wp:group -->'
		. '</div><!-- /wp:group -->'
		. '</div><!-- /wp:group -->'
		. '</section>'
		. '<!-- /wp:group -->';
}

/**
 * Services section: the signature rule grid behind an intro.
 *
 * @param bool $with_head Whether to include the .section-head above the grid.
 * @return string Block markup.
 */
function consultia_prime_pattern_services( $with_head = true ) {
	$head = '';

	if ( $with_head ) {
		$head = '<!-- wp:group {"className":"section-head"} --><div class="wp-block-group section-head">'
			. '<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">' . esc_html__( 'What we do', 'consultia-prime' ) . '</p><!-- /wp:paragraph -->'
			. '<!-- wp:heading --><h2 class="wp-block-heading">' . esc_html__( 'Four disciplines, one purpose: get your company examined, compliant, and ready.', 'consultia-prime' ) . '</h2><!-- /wp:heading -->'
			. '<!-- wp:paragraph --><p>'
			. esc_html__( 'Each service stands on its own. Together, they cover the ground a growing company needs to hold — from how it\'s organized internally to how it reports to the outside world.', 'consultia-prime' )
			. ' <a class="section-head__link" href="' . esc_url( consultia_prime_url( 'services' ) ) . '">' . esc_html__( 'See the full breakdown →', 'consultia-prime' ) . '</a>'
			. '</p><!-- /wp:paragraph -->'
			. '</div><!-- /wp:group -->';
	}

	// No className here on purpose. This section is styled by the bare
	// `section` rule the reference sets, so an empty className in the
	// comment would be both redundant and non-canonical: core drops an
	// empty className when it serialises, and the editor would rewrite it.
	return '<!-- wp:group {"tagName":"section","anchor":"services"} -->'
		. '<section id="services" class="wp-block-group">'
		. '<!-- wp:group {"className":"wrap"} --><div class="wp-block-group wrap">'
		. $head
		. consultia_prime_pattern_service_grid()
		. '</div><!-- /wp:group -->'
		. '</section>'
		. '<!-- /wp:group -->';
}

/**
 * Closing call: a heading and two actions on one baseline.
 *
 * Each reference page pairs this section with a different second
 * action, so both are arguments.
 *
 * @param string $label       Second action label.
 * @param string $slug        Page the second action points at.
 * @return string Block markup.
 */
function consultia_prime_pattern_cta( $label, $slug ) {
	return '<!-- wp:group {"tagName":"section","className":"cta","anchor":"cta"} -->'
		. '<section id="cta" class="wp-block-group cta">'
		. '<!-- wp:group {"className":"wrap"} --><div class="wp-block-group wrap">'
		. '<!-- wp:group {"className":"cta-inner"} --><div class="wp-block-group cta-inner">'
		. '<!-- wp:heading --><h2 class="wp-block-heading">' . esc_html__( 'Ready to talk about where your company stands?', 'consultia-prime' ) . '</h2><!-- /wp:heading -->'
		. consultia_prime_pattern_buttons(
			'cta-actions',
			array(
				array( __( 'Get in touch', 'consultia-prime' ), consultia_prime_url( 'contact' ), 'is-style-solid' ),
				array( $label, consultia_prime_url( $slug ), 'is-style-outline' ),
			)
		)
		. '</div><!-- /wp:group -->'
		. '</div><!-- /wp:group -->'
		. '</section>'
		. '<!-- /wp:group -->';
}

/**
 * Page hero, the masthead every inner page opens with.
 *
 * @param string $eyebrow Eyebrow label.
 * @param string $title   Page heading.
 * @param string $lede    Standfirst.
 * @return string Block markup.
 */
function consultia_prime_pattern_page_hero( $eyebrow, $title, $lede ) {
	return '<!-- wp:group {"tagName":"section","className":"page-hero"} -->'
		. '<section class="wp-block-group page-hero">'
		. '<!-- wp:group {"className":"wrap"} --><div class="wp-block-group wrap">'
		. '<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">' . esc_html( $eyebrow ) . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":1} --><h1 class="wp-block-heading">' . esc_html( $title ) . '</h1><!-- /wp:heading -->'
		. '<!-- wp:paragraph {"className":"lede"} --><p class="lede">' . esc_html( $lede ) . '</p><!-- /wp:paragraph -->'
		. '</div><!-- /wp:group -->'
		. '</section>'
		. '<!-- /wp:group -->';
}

/**
 * Block markup for one market row.
 *
 * @param string $name Market name.
 * @return string Block markup.
 */
function consultia_prime_pattern_exchange_row( $name ) {
	return '<!-- wp:group {"className":"exchange-row"} -->'
		. '<div class="wp-block-group exchange-row">'
		. '<!-- wp:paragraph {"className":"name"} --><p class="name">' . esc_html( $name ) . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:paragraph {"className":"desc mono"} --><p class="desc mono">' . esc_html__( 'Listing & reporting readiness', 'consultia-prime' ) . '</p><!-- /wp:paragraph -->'
		. '</div>'
		. '<!-- /wp:group -->';
}

/**
 * The story section on the about page.
 *
 * @return string Block markup.
 */
function consultia_prime_pattern_story() {
	$paragraphs = array(
		__( 'For sixty years, Consultia has worked alongside small and medium-sized enterprises as they grow — helping them organize for scale, understand their own numbers, and hold up to the scrutiny that comes with outside capital and public reporting.', 'consultia-prime' ),
		__( 'That work has taken us deep into capital-markets compliance in particular: the ongoing discipline of reporting that keeps a company in good standing once it is quoted or listed, and the preparation that gets a private or lower-tier company ready to make that step in the first place.', 'consultia-prime' ),
		__( 'We have always worked across borders and across disciplines. No single firm carries a company to market alone, and neither do we — which is why our own team is multidisciplinary, and why we\'ve maintained the same professional relationships over decades rather than reassembling them project by project.', 'consultia-prime' ),
	);

	$copy = '';
	foreach ( $paragraphs as $paragraph ) {
		$copy .= '<!-- wp:paragraph --><p>' . esc_html( $paragraph ) . '</p><!-- /wp:paragraph -->';
	}

	return '<!-- wp:group {"tagName":"section","anchor":"story"} -->'
		. '<section id="story" class="wp-block-group">'
		. '<!-- wp:group {"className":"wrap"} --><div class="wp-block-group wrap">'
		. '<!-- wp:group {"className":"section-head"} --><div class="wp-block-group section-head">'
		. '<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">' . esc_html__( 'Our story', 'consultia-prime' ) . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading --><h2 class="wp-block-heading">' . esc_html__( 'Six decades of staying close to the companies we work with.', 'consultia-prime' ) . '</h2><!-- /wp:heading -->'
		. '</div><!-- /wp:group -->'
		. '<!-- wp:group {"className":"story"} --><div class="wp-block-group story">'
		. '<!-- wp:group --><div class="wp-block-group">' . $copy . '</div><!-- /wp:group -->'
		. '<!-- wp:group {"className":"story-figures"} --><div class="wp-block-group story-figures">' . consultia_prime_pattern_stats() . '</div><!-- /wp:group -->'
		. '</div><!-- /wp:group -->'
		. '</div><!-- /wp:group -->'
		. '</section>'
		. '<!-- /wp:group -->';
}

/**
 * Block markup for one network column.
 *
 * @param string $heading Column heading.
 * @param array  $items   Each item: string, or array( text, note ).
 * @return string Block markup.
 */
function consultia_prime_pattern_network_col( $heading, $items ) {
	$list = '';
	foreach ( $items as $item ) {
		if ( is_array( $item ) ) {
			$list .= '<!-- wp:list-item --><li>' . esc_html( $item[0] ) . ' <span>' . esc_html( $item[1] ) . '</span></li><!-- /wp:list-item -->';
		} else {
			$list .= '<!-- wp:list-item --><li>' . esc_html( $item ) . '</li><!-- /wp:list-item -->';
		}
	}

	return '<!-- wp:group {"className":"network-col"} -->'
		. '<div class="wp-block-group network-col">'
		. '<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . esc_html( $heading ) . '</h3><!-- /wp:heading -->'
		. '<!-- wp:list --><ul class="wp-block-list">' . $list . '</ul><!-- /wp:list -->'
		. '</div>'
		. '<!-- /wp:group -->';
}

/**
 * The network section on the about page.
 *
 * @return string Block markup.
 */
function consultia_prime_pattern_network() {
	return '<!-- wp:group {"tagName":"section","anchor":"network"} -->'
		. '<section id="network" class="wp-block-group">'
		. '<!-- wp:group {"className":"wrap"} --><div class="wp-block-group wrap">'
		. '<!-- wp:group {"className":"section-head"} --><div class="wp-block-group section-head">'
		. '<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">' . esc_html__( 'Who\'s at the table', 'consultia-prime' ) . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading --><h2 class="wp-block-heading">' . esc_html__( 'A multidisciplinary team, and a network built over decades.', 'consultia-prime' ) . '</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>' . esc_html__( 'No single firm carries a company to market alone. Our own team is multidisciplinary, and we maintain strong working relationships with the professionals who fill in the rest.', 'consultia-prime' ) . '</p><!-- /wp:paragraph -->'
		. '</div><!-- /wp:group -->'
		. '<!-- wp:group {"className":"network-cols"} --><div class="wp-block-group network-cols">'
		. consultia_prime_pattern_network_col(
			__( 'Inside the firm & our professional network', 'consultia-prime' ),
			array(
				__( 'Accountants', 'consultia-prime' ),
				__( 'Tax advisers', 'consultia-prime' ),
				__( 'Independent PCAOB-registered auditors', 'consultia-prime' ),
				__( 'Securities attorneys', 'consultia-prime' ),
				__( 'Transfer agents', 'consultia-prime' ),
			)
		)
		. consultia_prime_pattern_network_col(
			__( 'Financial-market relationships', 'consultia-prime' ),
			array(
				__( 'Investment banks', 'consultia-prime' ),
				__( 'Broker-dealers', 'consultia-prime' ),
				array(
					__( 'Other financial-market professionals', 'consultia-prime' ),
					__( 'Coordinated support through each stage of a company\'s development and capital-markets strategy.', 'consultia-prime' ),
				),
			)
		)
		. '</div><!-- /wp:group -->'
		. '</div><!-- /wp:group -->'
		. '</section>'
		. '<!-- /wp:group -->';
}

/**
 * The capital-markets section on the services page.
 *
 * @return string Block markup.
 */
function consultia_prime_pattern_markets() {
	$exchanges = array(
		__( 'Nasdaq', 'consultia-prime' ),
		__( 'NYSE', 'consultia-prime' ),
		__( 'OTC Markets', 'consultia-prime' ),
	);

	$rows = '';
	foreach ( $exchanges as $exchange ) {
		$rows .= consultia_prime_pattern_exchange_row( $exchange );
	}

	return '<!-- wp:group {"tagName":"section","anchor":"markets"} -->'
		. '<section id="markets" class="wp-block-group">'
		. '<!-- wp:group {"className":"wrap"} --><div class="wp-block-group wrap">'
		. '<!-- wp:group {"className":"section-head"} --><div class="wp-block-group section-head">'
		. '<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">' . esc_html__( 'Capital markets', 'consultia-prime' ) . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading --><h2 class="wp-block-heading">' . esc_html__( 'We know what each market asks of a company — because we prepare companies for all three.', 'consultia-prime' ) . '</h2><!-- /wp:heading -->'
		. '</div><!-- /wp:group -->'
		. '<!-- wp:group {"className":"markets"} --><div class="wp-block-group markets">'
		. '<!-- wp:group {"className":"exchange-list"} --><div class="wp-block-group exchange-list">' . $rows . '</div><!-- /wp:group -->'
		. '<!-- wp:group {"className":"markets-copy"} --><div class="wp-block-group markets-copy">'
		. '<!-- wp:paragraph --><p>' . esc_html__( 'We have particular expertise in assisting companies already quoted or listed on Nasdaq, the New York Stock Exchange, and OTC Markets — helping them meet the ongoing reporting and governance standards those markets require.', 'consultia-prime' ) . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:paragraph --><p>' . esc_html__( 'We also guide private and lower-tier public companies through the preparation it takes to qualify for these markets in the first place, so the step up is planned for, not scrambled for.', 'consultia-prime' ) . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:paragraph {"className":"callout"} --><p class="callout"><span class="mono">' . esc_html__( 'Where clients stand when they call us', 'consultia-prime' ) . '</span>' . esc_html__( 'Some are already listed and need to stay compliant. Others are private, or trading lower-tier, and are working toward a listing. We work with both.', 'consultia-prime' ) . '</p><!-- /wp:paragraph -->'
		. '</div><!-- /wp:group -->'
		. '</div><!-- /wp:group -->'
		. '</div><!-- /wp:group -->'
		. '</section>'
		. '<!-- /wp:group -->';
}

/**
 * Block markup for one contact detail.
 *
 * The reference's third detail is a line of prose rather than a value, and
 * the reference sets it apart with a small type treatment. That treatment
 * is the .contact-item__statement class rather than an inline style, since
 * the theme carries no inline CSS.
 *
 * @param string $label     Category label.
 * @param string $value     Value, omitted when $statement is given.
 * @param string $link      Optional href; wraps $value in a link.
 * @param string $note      Optional plain explanation.
 * @param string $statement Optional emphasised prose, set as its own paragraph.
 * @return string Block markup.
 */
function consultia_prime_pattern_contact_item( $label, $value = '', $link = '', $note = '', $statement = '' ) {
	$body = '';

	if ( '' !== $statement ) {
		$body .= '<!-- wp:paragraph {"className":"contact-item__statement"} -->'
			. '<p class="contact-item__statement">' . esc_html( $statement ) . '</p>'
			. '<!-- /wp:paragraph -->';
	} elseif ( '' !== $value ) {
		if ( '' !== $link ) {
			$body .= '<!-- wp:paragraph --><p><a href="' . esc_url( $link ) . '">' . esc_html( $value ) . '</a></p><!-- /wp:paragraph -->';
		} else {
			$body .= '<!-- wp:paragraph --><p>' . esc_html( $value ) . '</p><!-- /wp:paragraph -->';
		}
	}

	if ( '' !== $note ) {
		$body .= '<!-- wp:paragraph --><p>' . esc_html( $note ) . '</p><!-- /wp:paragraph -->';
	}

	return '<!-- wp:group {"className":"contact-item"} -->'
		. '<div class="wp-block-group contact-item">'
		. '<!-- wp:paragraph {"className":"mono"} --><p class="mono">' . esc_html( $label ) . '</p><!-- /wp:paragraph -->'
		. $body
		. '</div>'
		. '<!-- /wp:group -->';
}

/**
 * The contact form.
 *
 * The reference posts to a mailto: address with text/plain, which is
 * reproduced here exactly — including that it hands the message to the
 * visitor's own mail client and delivers nothing by itself. It is
 * marked as a form so WordPress's search excludes it.
 *
 * @return string Block markup.
 */
function consultia_prime_pattern_form() {
	$address = 'info@consultiausa.com';

	$html  = '<form class="contact-form" action="mailto:' . esc_attr( $address ) . '" method="post" enctype="text/plain">';
	$html .= '<div class="form-row">'
		. '<div class="field"><label for="contact-name">' . esc_html__( 'Name', 'consultia-prime' ) . '</label>'
		. '<input type="text" id="contact-name" name="Name" required=""></div>'
		. '<div class="field"><label for="contact-company">' . esc_html__( 'Company', 'consultia-prime' ) . '</label>'
		. '<input type="text" id="contact-company" name="Company"></div>'
		. '</div>';
	$html .= '<div class="form-row">'
		. '<div class="field"><label for="contact-email">' . esc_html__( 'Email', 'consultia-prime' ) . '</label>'
		. '<input type="email" id="contact-email" name="Email" required=""></div>'
		. '<div class="field"><label for="contact-market">' . esc_html__( 'Market of interest', 'consultia-prime' ) . '</label>'
		. '<input type="text" id="contact-market" name="Market of interest" placeholder="'
		. esc_attr__( 'Nasdaq, NYSE, OTC Markets…', 'consultia-prime' ) . '"></div>'
		. '</div>';
	$html .= '<div class="field"><label for="contact-message">' . esc_html__( 'Message', 'consultia-prime' ) . '</label>'
		. '<textarea id="contact-message" name="Message" required=""></textarea></div>';
	$html .= '<button type="submit" class="btn btn--solid form-submit">' . esc_html__( 'Send message', 'consultia-prime' ) . '</button>';
	$html .= '<p class="form-note">' . sprintf(
		/* translators: %s: contact email address. */
		esc_html__( 'Submitting opens your email client, addressed to %s.', 'consultia-prime' ),
		esc_html( $address )
	) . '</p>';
	$html .= '</form>';

	return '<!-- wp:html {"form":"form"} -->' . "\n" . $html . "\n" . '<!-- /wp:html -->';
}

/**
 * The contact section on the contact page.
 *
 * @return string Block markup.
 */
function consultia_prime_pattern_contact() {
	return '<!-- wp:group {"tagName":"section","anchor":"contact"} -->'
		. '<section id="contact" class="wp-block-group">'
		. '<!-- wp:group {"className":"wrap"} --><div class="wp-block-group wrap">'
		. '<!-- wp:group {"className":"contact-grid"} --><div class="wp-block-group contact-grid">'
		. '<!-- wp:group {"className":"contact-details"} --><div class="wp-block-group contact-details">'
		. consultia_prime_pattern_contact_item(
			__( 'Email', 'consultia-prime' ),
			__( 'info@consultiausa.com', 'consultia-prime' ),
			'mailto:info@consultiausa.com',
			__( 'For general inquiries and new engagements.', 'consultia-prime' )
		)
		. consultia_prime_pattern_contact_item(
			__( 'Phone', 'consultia-prime' ),
			__( '+1 (000) 000-0000', 'consultia-prime' ),
			'tel:+10000000000',
			__( 'Reach us directly to start a conversation.', 'consultia-prime' )
		)
		. consultia_prime_pattern_contact_item(
			__( 'Where we work', 'consultia-prime' ),
			'',
			'',
			'',
			__( 'Supporting small and medium-sized enterprises across multiple countries.', 'consultia-prime' )
		)
		. '</div><!-- /wp:group -->'
		. consultia_prime_pattern_form()
		. '</div><!-- /wp:group -->'
		. '</div><!-- /wp:group -->'
		. '</section>'
		. '<!-- /wp:group -->';
}

/**
 * The home page: the four sections of template/index.html, in order.
 *
 * The ticker is not here. consultia_prime_inject_ticker() places it
 * directly below the hero at render time, so that an editor re-save
 * of this page can neither duplicate it nor lose it.
 *
 * @return string Block markup.
 */
function consultia_prime_home_content() {
	return implode(
		"\n\n",
		array(
			consultia_prime_pattern_hero(),
			consultia_prime_pattern_overview(),
			consultia_prime_pattern_services( true ),
			consultia_prime_pattern_cta( __( 'Learn about us', 'consultia-prime' ), 'about' ),
		)
	);
}

/**
 * The about page: the five sections of template/about.html.
 *
 * @return string Block markup.
 */
function consultia_prime_about_content() {
	return implode(
		"\n\n",
		array(
			consultia_prime_pattern_page_hero(
				__( 'About Consultia', 'consultia-prime' ),
				__( 'Sixty years of professional services, built on one working relationship at a time.', 'consultia-prime' ),
				__( 'We are a professional services firm supporting small and medium-sized enterprises across multiple countries — with particular depth in the compliance work that gets a company onto Nasdaq, the NYSE, or OTC Markets, and keeps it there.', 'consultia-prime' )
			),
			consultia_prime_pattern_overview(),
			consultia_prime_pattern_story(),
			consultia_prime_pattern_network(),
			consultia_prime_pattern_cta( __( 'See our services', 'consultia-prime' ), 'services' ),
		)
	);
}

/**
 * The services page: the four sections of template/services.html.
 *
 * @return string Block markup.
 */
function consultia_prime_services_content() {
	return implode(
		"\n\n",
		array(
			consultia_prime_pattern_page_hero(
				__( 'What we do', 'consultia-prime' ),
				__( 'Four disciplines, one purpose: get your company examined, compliant, and ready.', 'consultia-prime' ),
				__( 'Each service stands on its own. Together, they cover the ground a growing company needs to hold — from how it\'s organized internally to how it reports to the outside world.', 'consultia-prime' )
			),
			consultia_prime_pattern_services( false ),
			consultia_prime_pattern_markets(),
			consultia_prime_pattern_cta( __( 'Learn about us', 'consultia-prime' ), 'about' ),
		)
	);
}

/**
 * The contact page: the two sections of template/contact.html.
 *
 * @return string Block markup.
 */
function consultia_prime_contact_content() {
	return implode(
		"\n\n",
		array(
			consultia_prime_pattern_page_hero(
				__( 'Contact', 'consultia-prime' ),
				__( 'Ready to talk about where your company stands?', 'consultia-prime' ),
				__( 'Some clients are already listed and need to stay compliant. Others are private, or trading lower-tier, and are working toward a listing. Tell us where you are, and we\'ll take it from there.', 'consultia-prime' )
			),
			consultia_prime_pattern_contact(),
		)
	);
}

/**
 * Every page this theme seeds, keyed by slug.
 *
 * The home page is included so that consultia_prime_seed_pages() can
 * re-seed it without the caller naming it.
 *
 * @return array Slug => array( title, content ).
 */
function consultia_prime_pages() {
	return array(
		'home'     => array( __( 'Home', 'consultia-prime' ), consultia_prime_home_content() ),
		'about'    => array( __( 'About', 'consultia-prime' ), consultia_prime_about_content() ),
		'services' => array( __( 'Services', 'consultia-prime' ), consultia_prime_services_content() ),
		'contact'  => array( __( 'Contact', 'consultia-prime' ), consultia_prime_contact_content() ),
	);
}

/**
 * Register the section patterns and the two button styles.
 *
 * `outline` overrides the style core registers under the same name. The
 * reference has two button looks and no more, so the list is trimmed to
 * those two rather than leaving core's own styles alongside them.
 */
function consultia_prime_register_patterns() {
	register_block_pattern_category(
		'consultia-prime',
		array( 'label' => __( 'Consultia Prime', 'consultia-prime' ) )
	);

	$patterns = array(
		'hero'     => array( __( 'Hero', 'consultia-prime' ), consultia_prime_pattern_hero() ),
		'overview' => array( __( 'Overview', 'consultia-prime' ), consultia_prime_pattern_overview() ),
		'services' => array( __( 'Services', 'consultia-prime' ), consultia_prime_pattern_services( true ) ),
		'cta'      => array( __( 'Closing call', 'consultia-prime' ), consultia_prime_pattern_cta( __( 'Learn about us', 'consultia-prime' ), 'about' ) ),
		'page-hero' => array( __( 'Page hero', 'consultia-prime' ), consultia_prime_pattern_page_hero( __( 'Page', 'consultia-prime' ), __( 'Page heading', 'consultia-prime' ), __( 'A standfirst that sets out what the page covers.', 'consultia-prime' ) ) ),
		'story'    => array( __( 'Story', 'consultia-prime' ), consultia_prime_pattern_story() ),
		'network'  => array( __( 'Network', 'consultia-prime' ), consultia_prime_pattern_network() ),
		'markets'  => array( __( 'Capital markets', 'consultia-prime' ), consultia_prime_pattern_markets() ),
		'contact'  => array( __( 'Contact', 'consultia-prime' ), consultia_prime_pattern_contact() ),
	);

	foreach ( $patterns as $slug => $pattern ) {
		register_block_pattern(
			'consultia-prime/' . $slug,
			array(
				'title'      => $pattern[0],
				'categories' => array( 'consultia-prime' ),
				'content'    => $pattern[1],
			)
		);
	}

	// The reference's .btn, which is also the default look of the block.
	register_block_style(
		'core/button',
		array(
			'name'  => 'outline',
			'label' => __( 'Outline', 'consultia-prime' ),
		)
	);

	// The reference's .btn-solid.
	register_block_style(
		'core/button',
		array(
			'name'  => 'solid',
			'label' => __( 'Solid', 'consultia-prime' ),
		)
	);
}
add_action( 'init', 'consultia_prime_register_patterns' );
