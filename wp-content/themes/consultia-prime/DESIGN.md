# Consultia Prime — Design System

The design constitution for this theme. Every value here is taken from
`assets/styles.css` (a 1:1 port of the static reference in `template/`) and
`assets/fonts.css`. Nothing in this file is aspirational — if a number appears
here it is the number the stylesheet actually ships.

Use it to rebuild this look, extend it, or port it to another project without
reversing the CSS.

---

## 1. Character in one paragraph

A dark, editorial, capital-markets advisory site. Near-black navy ground, a
single olive-green accent, warm off-white for inverted bands, and hairline
dividers instead of shadows. Type does the heavy lifting: a high-contrast
transitional serif (Fraunces) for headings and display, a neutral grotesque
(IBM Plex Sans) for prose, and a monospace (IBM Plex Mono) for every label,
eyebrow, tag and piece of metadata. The monospace/uppercase/wide-tracking
combination is the signature — it is used far more than a typical site would,
and it is what makes the design read as "professional services" rather than
"startup". Motion is minimal: two hover transitions and one looping marquee.

**Design rules that follow from that:**

- **No shadows. No rounded corners** (except the logo plate, `6px`). Separation
  is done with 1px hairlines in `--rule`.
- **No gradients.** Depth comes from three dark navy steps.
- **Everything is uppercase + tracked when it is a label.** If it is a label, it
  is IBM Plex Mono, uppercase, with positive `letter-spacing`.

---

## 2. Color

All tokens live in `:root` in `assets/styles.css`. **Never hardcode a colour
below a token** — change the token, not the rule.

| Token | Value | Role | Reference name |
|---|---|---|---|
| `--bg` | `#0b1826` | Page ground. The dominant colour (~80% of pixels) | `--ink` |
| `--surface` | `#122335` | Raised panels, form fields, service-card hover | `--ink-2` |
| `--surface-2` | `#182c40` | Second raised step (reserved) | `--ink-3` |
| `--paper` | `#eff4f7` | Inverted bands: logo plate, ticker, focus rings | — |
| `--ink` | `#e8ecee` | Primary type on `--bg` | `--text` |
| `--ink-dim` | `#b7c2cc` | Secondary type: ledes, body, descriptions | `--text-dim` |
| `--slate` | `#8fa0ae` | Tertiary: notes, footer meta, timestamps | — |
| `--navy` | `#0a4a7e` | Type on `--paper`; wordmark colour | — |
| `--navy-deep` | `#073055` | Deeper navy (reserved) | — |
| `--accent` | `#7a8d39` | **The accent.** Borders, rules, solid buttons, focus | `--green` |
| `--accent-light` | `#9fac70` | Accent on dark ground: hero `<em>`, tags, hovers | `--green-light` |
| `--blue-light` | `#6396cd` | **Inert** — declared in the reference, never used | `--blue-light` |
| `--rule` | `rgba(122, 141, 57, 0.35)` | Every hairline divider and border | — |
| `--container` | `1180px` | Max content width | `--maxw` |

> **Naming note.** The reference names tokens after the colour they hold
> (`--ink` is the *dark ground*, `--text` is the *light type*). This theme names
> them by role (`--bg`, `--ink`). The **values map one-for-one** — the comment
> beside each token in the stylesheet records the reference name.

### Colour roles worth memorising

- `--rule` is olive at 35% alpha, not grey. Every divider in the design is the
  accent, not a neutral.
- `--accent` on `--bg` is low contrast; that is why `--accent-light` exists.
  Use `--accent` for **fills and borders**, `--accent-light` for **type and
  text on dark**.
- `--paper` appears in only three places: the logo plate, the ticker band, and
  focus outlines. It is the only light surface.

The same eleven colours are mirrored into `theme.json` (`settings.color.palette`)
so they are selectable in the editor.

---

## 3. Typography

### Families

| Family | Variable | Stack | Used for |
|---|---|---|---|
| **Fraunces** | `100 900` + `opsz 9–144` | `'Fraunces', serif` | h1–h3, all display type, numbers |
| **IBM Plex Sans** | `100 700` | `'IBM Plex Sans', sans-serif` | Body, ledes, descriptions, form fields |
| **IBM Plex Mono** | 400, 500 | `'IBM Plex Mono', monospace` | Every label, eyebrow, tag, button, note |

Faces are self-hosted in `assets/fonts/`. They are the **exact latin subsets**
Google Fonts returns for the reference's `<link>` — verified byte-identical by
SHA-256, so no third-party request is made and the rendering is the same.

### The Fraunces optical-size axis — important

Fraunces is an **optical-size** font. `font-optical-sizing` is `auto` (the CSS
initial value) and nothing in the theme overrides it, so **`opsz` tracks the
computed `font-size`**. The same word looks materially different at 38px and
60px: display sizes get higher contrast and tighter spacing; text sizes get
sturdier, more open letterforms.

Practical consequence: **anything set in Fraunces renders differently at
different sizes.** This is correct and intended. It also means the block editor
preview will not look like the front end — the editor canvas is a narrow iframe,
so `vw`-based sizes collapse toward their clamp floor and the headings render
in Fraunces' *text* cut. Preview at a desktop viewport before judging the type.

### Fraunces italic is pinned to 400

The reference requests italic 400 only, while the hero `<em>` sits at
`font-weight: 500`. The browser therefore **synthetically emboldens** the 400
italic. Shipping a variable italic would quietly render a real 500 and diverge
from the reference, so the italic face is deliberately declared `font-weight: 400`.
This is a fidelity choice, not an oversight.

### Heading base

```css
h1, h2, h3 {
	font-family: 'Fraunces', serif;
	font-weight: 500;
	letter-spacing: -0.01em;   /* the only negative tracking in the design */
	color: var(--ink);
}
```

`h4` is **not** set by this rule — it is defined by whatever contains it (the
network column and the footer both restyle it). This mirrors the reference.

### Display scale — all fluid, all `clamp()`

| Element | Size | Line-height | Max width |
|---|---|---|---|
| `.hero h1` | `clamp(38px, 5vw, 60px)` | `1.06` | `16ch` |
| `.page-hero h1` / `.page-main h1` | `clamp(34px, 4.4vw, 50px)` | — | `22ch` |
| `.cta h2` | `clamp(30px, 4vw, 46px)` | — | `14ch` |
| `.section-head h2` | `clamp(28px, 3.2vw, 38px)` | — | — |
| `.overview blockquote` | `clamp(24px, 2.6vw, 32px)` | `1.5` | `1000px` |

### Body and label scale — all fixed

| Size | Used by |
|---|---|
| `20px` | contact item links, contact statement |
| `19px` | service `h3` (`lh 1.28`), network list items |
| `18px` | post list links |
| `17.5px` | hero lede |
| `17px` | page-hero lede |
| `16px` | body, `.section-head p` |
| `15.5px` | markets copy, story copy |
| `15px` | form inputs / textareas |
| `14.5px` | markets callout, contact item body |
| `14px` | service body, nav links, network `h3` |
| `13.5px` | stat labels (`lh 1.4`), exchange descriptions, footer links |
| `13px` | ticker items, footer brand copy, network sub-labels, skip link |
| `12.5px` | buttons, hero note, quote `cite`, form note |
| `12px` | eyebrows, nav toggle, footer meta |
| `11.5px` | field labels, contact/meta `.mono` labels |
| `11px` | service tags, footer column headings, timestamps |

`theme.json` exposes five of these to the editor as `small` (14), `medium` (16),
`large` (20), `x-large` (28), `display` (40).

### The label recipe

Any piece of metadata gets all five of these. It is the most recognisable
element of the design:

```css
font-family: 'IBM Plex Mono', monospace;
text-transform: uppercase;
letter-spacing: 0.08em – 0.18em;
font-size: 11px – 12.5px;
color: var(--accent-light);   /* or --ink-dim, or --slate */
```

Tracking scales with size — smaller text tracks wider:

| Tracking | Applied to |
|---|---|
| `0.18em` | `.eyebrow` (12px) — the widest in the design |
| `0.12em` | `cite` (12.5px), timestamps (11px) |
| `0.1em` | service tags, network `h3`, footer headings, nav toggle |
| `0.08em` | buttons, ticker items, field labels |
| `0.04em` | the bare `.mono` utility |
| `-0.01em` | Fraunces headings only |

---

## 4. Motion

The entire motion budget. Three timings, two easings, one keyframe animation.
**If you are adding a transition, it must come from this table** — that
narrowness is the design.

| What | Property | Duration | Easing |
|---|---|---|---|
| Buttons (`.btn`, core Button block) | `all` | `0.25s` | `ease` |
| Service cards | `background` | `0.25s` | `ease` |
| Nav link colour | `color` | `0.2s` | `ease` |
| Nav link underline | `transform` (`scaleX`) | `0.2s` | `ease` |
| Ticker band | `transform` marquee | `34s` | `linear`, infinite |
| Page anchors | `scroll-behavior: smooth` | — | — |

Hover states that change colour with **no** transition, deliberately — they snap:
`.site-footer .foot-nav a` → `--accent-light`.

**Anchor scrolling** is smooth, with `scroll-padding-top: 100px` on `html` so a
target clears the sticky 84px header. This offset is not in the reference, which
has no anchored links; it is harmless there.

### The two interactive states

**Button** — transparent with a 1px `--accent` border and `--accent-light` type;
on hover/focus the fill inverts to `--accent` and the type becomes `--bg`. Focus
adds a 2px `--paper` outline at 2px offset. Solid variant starts filled and
lightens to `--accent-light` on hover.

```css
transition: all 0.25s ease;   /* deliberately "all", as in the reference */
```

**Nav link** — colour lifts `--ink-dim` → `--ink`, and a 1px `--accent-light`
underline wipes in from the left via `transform: scaleX(0 → 1)` with
`transform-origin: left`. The underline is **persistently on** for
`aria-current='page'`, not only on hover. Focus is a 1px `--accent-light`
outline at 4px offset.

### The ticker marquee

```css
.ticker-track {
	display: flex;
	white-space: nowrap;
	width: max-content;
	animation: scroll-ticker 34s linear infinite;
}
@keyframes scroll-ticker {
	from { transform: translateX(0); }
	to   { transform: translateX(-50%); }
}
```

`translateX(-50%)` is a **seamless loop only because the track's content is
duplicated exactly twice** by the shortcode. The track is `aria-hidden="true"`.
The `●` separator is a `::before` pseudo-element at `6px`, `--accent`, nudged
`top: -2px` to sit on the text's baseline.

### Reduced motion

A single `@media (prefers-reduced-motion: reduce)` block neutralises everything:
`scroll-behavior: auto`, all animation/transition durations to `0.001ms`,
iteration count to `1`, and the ticker to `animation: none; transform: none`.
**Any motion you add must be covered by this block.**

---

## 5. Layout

### Container

`max-width: 1180px`, centred, `padding: 0 32px` (tightens to `20px` under
560px). Applied by the `.wrap` class on every band.

### Vertical rhythm

| Band | Padding | Bottom border |
|---|---|---|
| `.hero` | `120px 0 76px` | 1px `--rule` |
| `.page-hero` | `96px 0 64px` | 1px `--rule` |
| `section` (default) | `104px 0` | 1px `--rule` |
| `.cta` | `100px 0` | — |
| `.site-footer` | `44px 0 40px` | — |

`section:last-of-type` drops its border. Sections are Group blocks rendered as
`<section>`, so the reference's bare `section {}` rule applies unchanged.

### Grid ratios

| Grid | Columns | Gap |
|---|---|---|
| `.hero-grid` | `1.15fr 0.85fr` | `64px` |
| `.markets`, `.contact-grid` | `0.9fr 1.1fr` | `64px` |
| `.network-cols` | `1fr 1fr` | `56px` |
| `.story` | `1fr 1fr` | `64px` |
| `.service-grid` | `repeat(4, 1fr)` | `1px` |
| `.form-row` | `1fr 1fr` | `20px` |

The hero is deliberately **not** symmetrical — the type side gets 15% more width.

### The hairline grid technique

`.service-grid` draws its own dividers rather than styling each cell:

```css
.service-grid {
	display: grid;
	grid-template-columns: repeat(4, 1fr);
	gap: 1px;
	background: var(--rule);   /* the gap shows through as hairlines */
	border: 1px solid var(--rule);
}
.service { background: var(--bg); }   /* cells paint over the background */
```

The 1px gap reveals the olive `--rule` behind it. Hover swaps a cell to
`--surface`, which reads as the hairline lighting up. Reuse this for any
divided grid.

### Measure

- `.section-head` `max-width: 640px`, `margin-bottom: 56px`
- Hero lede `46ch`, page-hero lede `60ch`, section intro `56ch`
- `.entry-content` `62ch`
- Stat labels `22ch`, exchange descriptions `30ch`, `.cta h2` `14ch`

### Breakpoints

Only two, both `max-width`:

- **980px** — every two-column grid collapses to one; the service grid becomes
  2-up; the nav becomes a toggle.
- **560px** — service grid to 1-up, container padding to `20px`, hero and
  page-hero padding tighten, footer stacks.

---

## 6. Components

**Logo plate** — the wordmark sits on a `--paper` chip, because no logo file
exists in the repository. `background: var(--paper)`, `color: var(--navy)`,
`border-radius: 6px`, Fraunces 500. Header variant `7px 14px` / `19px`;
footer variant `6px 12px` / `16px`. Uploading a **Custom Logo** replaces the
wordmark, and the reference's own `height: 38px` (header) / `26px` (footer)
then applies to the image.

**Header** — `position: sticky`, `height: 84px`, `background: rgba(11,24,38,0.92)`
with `backdrop-filter: blur(10px)`, 1px `--rule` bottom border. The translucency
plus blur is the one place alpha is used on a surface.

**Hero decoration** — `.hero::after` is a 520px circle, `border: 1px solid
var(--rule)`, `opacity: 0.5`, offset `right: -120px; top: -160px` so it bleeds
off the corner. `overflow: hidden` on `.hero` clips it. Purely decorative,
never announced.

**Eyebrow** — `.eyebrow::before` is a 22px × 1px `--accent` rule with a `10px`
gap, so the label reads as `— LABEL`. Reuse for any section label.

**Quote / callout** — `.overview blockquote` is Fraunces italic 400 at
`clamp(24px, 2.6vw, 32px)`, with a 2px `--accent` left border and `34px`
padding. The `cite` beneath is a mono uppercase attribution, indented a further
`36px` so it aligns past the border.

**Exchange row** — hairline-divided, `justify-content: space-between`:
Fraunces `22px` name on the left, `13.5px` `--ink-dim` description
`max-width: 30ch` right-aligned. The description drops to left-aligned when the
grid collapses.

**Callout** — a boxed aside inside body copy: 1px `--rule` border,
`22px 24px` padding, with a `.mono` label in `--accent-light` above the text.

**Form fields** — `--surface` fill, 1px `--rule` border, `13px 14px` padding;
on focus the border becomes `--accent` and the outline is removed. Textarea
`min-height: 130px`, `resize: vertical`. Labels are the label recipe at
`11.5px` / `0.08em`.

**Contact form** — a `mailto:` + `text/plain` form, faithful to the reference.
It opens the visitor's mail client; there is no server-side submission. The note
under the button says so.

---

## 7. Porting this to another project

1. **Copy the `:root` block** verbatim from `assets/styles.css`. Rename the
   tokens to whatever convention you prefer — the values are what matter.
2. **Copy `assets/fonts.css` and `assets/fonts/`** together. The `@font-face`
   `src` paths are relative to the stylesheet's own directory (`url(fonts/…)`,
   **no `../`**). If you move the file, fix the paths — a stray `../` silently
   404s every face and falls back to generic `serif`.
3. **Copy the reset, the type base and `h1–h3`** from the top of the file.
4. **Take `.btn`, `.eyebrow`, the label recipe and the hairline grid** as your
   starting component set.
5. **Keep the motion table.** Three timings, two easings. Resist adding a fourth.

### WordPress-specific notes

- `.btn` is also the **core Button block**. The reference's two looks are
  registered as block styles `outline` (default) and `solid`, so an
  editor-authored button is identical to a template one. If you add a third
  look, register it in `inc/patterns.php` *and* style
  `.wp-block-button.is-style-<slug> .wp-block-button__link`.
- Core prints `:root :where(…)` layout rules **before** this stylesheet, so
  equal-specificity theme rules win. But `:root :where(h1,h2,h3)` is more
  specific than a bare `h1,h2,h3` — which is why `theme.json` deliberately does
  **not** set heading `lineHeight` or `link` colour, since it would override the
  reference cascade.
- `add_editor_style()` only reaches the editor if
  `add_theme_support( 'editor-styles' )` is also declared. Without both, the
  block editor canvas opens unstyled.
- **Reset caution:** the reference resets margin/padding on `*`. That cannot be
  copied literally — zeroing `ul { padding-left }` on `*` removes what draws a
  core List block's markers. The theme resets an explicit element list instead.
- **No inline `<style>` or `<script>`**, anywhere. A rule belongs in the
  stylesheet; the contact page's one inline-style paragraph was moved to
  `.contact-item__statement` for this reason.

---

## 8. Deliberate deviations from the reference

Only four, each forced by WordPress or by accessibility:

1. **Token names** are role-based (`--bg`, `--ink`) rather than colour-based
   (`--ink`, `--text`). Values are identical, one-for-one.
2. **Mobile navigation.** The reference hides the nav outright under 980px,
   stranding phone visitors. A Menu toggle is shown in its place
   (`.nav-toggle`, driven by `assets/nav.js` via `.is-open`).
3. **Reset scope.** Explicit element list instead of `*`, to keep core List
   block markers working.
4. **Header/footer selectors.** Styled as `.site-header` / `.site-footer`
   rather than the bare `header` / `footer` elements, because the page body is
   block content and an element selector would match any header an editor drops
   into a page.

Everything marked **"Beyond the reference"** in the stylesheet — `index.php`,
`single.php`, `404.php` — has no counterpart in the reference. It reuses the
`.page-hero` masthead, the hairline rows and the type, so those three templates
look native without inventing new values.

---

## 9. Gotchas

- **`--blue-light` (`#6396cd`) is inert.** It exists in the reference and is
  never referenced. Don't reach for it expecting a blue.
- **Fraunces at different sizes looks different.** That is the optical-size
  axis working, not a bug. Judge the type at desktop width.
- **Editor ≠ front end.** The editor canvas is a narrow iframe, so `vw` sizes
  collapse to their clamp floors and the headings render smaller. Fonts are
  correctly delivered to the editor; the size is not.
- **The ticker is invisible in the block editor.** It is injected at render time
  and is deliberately not stored in page content, so that an editor re-save can
  neither duplicate nor drop it. Expected.
- **The ticker loop depends on duplicated content.** If you change the shortcode,
  keep the content duplicated exactly twice or the `translateX(-50%)` loop will
  jump.
- **`--container` is the only width token.** Everything else is a literal
  `ch`/`px` measure.
- **Straight apostrophes are deliberate.** The copy uses `'` (U+0027), and
  `esc_html()` encodes it to `&#039;`, which `wptexturize` skips — so the page
  renders byte-identical to the reference. Do not "fix" these to curly quotes.
