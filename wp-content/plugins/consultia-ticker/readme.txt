=== Consultia Ticker ===
Contributors: consultia
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 8.1
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Reusable decorative markets ticker for the Consultia theme. Embed it anywhere with the [consultia_ticker] shortcode; editors manage the market names under Settings → Consultia Ticker.

== Description ==

The Consultia Ticker plugin exposes the site's scrolling markets ticker as a
reusable, editor-managed artifact. Insert a Shortcode block containing
`[consultia_ticker]` on any page or post, and the ticker renders using only the
Consultia theme's `.ticker` / `.ticker-track` styles (no CSS or JS is shipped
by the plugin).

Market names are stored as a single WordPress option and edited on
Settings → Consultia Ticker (capability: manage_options). One name per line;
leave the list empty to hide the ticker entirely.

= Styling =

The ticker is a decorative strip (`aria-hidden="true"`) whose presentation
comes entirely from the theme stylesheet. Animation is the theme's existing
`@keyframes scroll-ticker` loop with a `prefers-reduced-motion` override; the
plugin repeats the name set 4× server-side so the loop is seamless.

== Installation ==

1. Upload the `consultia-ticker` folder to `/wp-content/plugins/`, or install
   the zip via Plugins → Add New.
2. Activate the plugin through the Plugins screen.
3. Add a Shortcode block with `[consultia_ticker]` to any page or post.
4. Manage the names under Settings → Consultia Ticker.

== Frequently Asked Questions ==

= Why does the ticker disappear when I remove all items? =

That is by design: an empty list renders nothing — no placeholder, no errors.

= Can I change the market names without code? =

Yes. Go to Settings → Consultia Ticker and edit the list; every page using the
shortcode updates immediately.

== Changelog ==

= 1.0.0 =
* Initial release: shortcode, activation default list, Settings screen.