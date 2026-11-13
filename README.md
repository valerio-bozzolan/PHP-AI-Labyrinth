# PHP AI Labyrinth 🤖🔀 - Trap F**king evil AI scrapers forever... 🫠 then ban them with fail2ban! 😎

A lightweight, self-hosted **bot trap** that wastes AI crawler resources by generating infinite, realistic-looking fake article pages — each linking to more fake pages.

## How it Works

```
Human visits your site   →  humans never sees the trap (thanks to CSS)
Good faith crawler       →  if they respects robots.txt, they work normally
Evil bot/scraper         →  evil bots ignore robots.txt, so they follow the honeypot link
                         →  landing on a realistic-looking article,
                         →  follows all 6–12 "related article" links
                         →  lands on even more fake articles, with more links, etc.
                         →  ... almost forever, hopefully burning tokens on every page,
                         →  and easily noticeable using tools like fail2ban
```

Mechanisms working together:

1. **The labyrinth** (`www/index.php`) — generates endless fake article pages. A hidden link on your real site is the entrance; humans never see it, compliant crawlers are kept away via `robots.txt`.
2. **The content generator** (`generator.php`) — assembles article text from combinatorial sentence templates. Every page is unique, so crawler deduplication cannot collapse the labyrinth into a handful of repeated pages. Maximum token burn per page.

Key properties:

- Pages are **deterministic** — the same `?p=` ID always generates the same content (no database needed, pure PHP), so responses stay cacheable
- Every page sends `X-Robots-Tag: noindex, nofollow` and `<meta robots="noindex,nofollow">` so the trap never pollutes real search results
- Depth counter prevents log explosion; resets silently after `LABYRINTH_MAX_DEPTH`
- Built-in per-IP rate limiting and log flood protection
- Optional **tarpit delay** slows crawlers down at almost no cost to you

---

## Requirements

- PHP 7.4+
- Any web server (Apache, Nginx, Caddy, Traefik, …)

No database. No dependencies. Drop in three files and go.

## Installation & Setup

### 1. Copy the files

> ⚠️ **Give the entry script an inconspicuous name.** A URL containing `labyrinth` or `trap` tells a smart crawler exactly what it walked into. `articles.php` under `/research/` blends in.

```bash
# Clone the repo with git
git clone https://... /path/to/PHP-AI-Labyrinth

# Copy configuration
cd /path/to/PHP-AI-Labyrinth
cp config.example.php config.php

# Deploy in production, for example linking the 'www' directory as an interesting '/research/' base URL.
sudo ln --symbolic /path/to/PHP-AI-Labyrinth/www  /var/www/html/research
```

Enjoy!

http://localhost/research/

### 2. Adjust `config.php`

At minimum, set your base path and site name:

```php
define('LABYRINTH_BASE_PATH', '/research/');
define('LABYRINTH_SITE_NAME', 'My Website');
```

Optionally replace the German topics, authors and word pools with content in your language and niche — the more convincing the content matches your site, the more effective the trap (see [Content Generator](#content-generator)).

### 3. Add a hidden entry link to your site

Bots follow links — humans never see this one:

```html
<!-- In your footer or any page -->
<a href="/research/" style="display:none" tabindex="-1" aria-hidden="true">uhm</a>
```

**Why not just block bots?** Well, before blocking bots, you must identify bots :P So... this is a gooooood way to identify bots, as well as keeping them engaged somewhere else...

### 4. Set up `robots.txt`

Three rules make the trap work (see [`robots.txt.example`](robots.txt.example) for a complete file):

2. **Disallow a honeypot path** (e.g. `/trap/`) that nothing legitimate ever links to. Any request to it is by definition a robots.txt violation — route it into the labyrinth (see [Bot Monitor](#bot-monitor-catch-robotstxt-violators-optional)).
3. **Tell AI training crawlers to stay off entirely.** Compliant ones (most large operators) will honor this and leave. The ones that ignore it walk straight into your traps.

```
User-agent: *
Disallow: /research/
```

### 5. Configure your web server (optional: clean URLs)

**Apache** — in `.htaccess` or VHost:
```apache
Alias /research/ /path/to/PHP-AI-Labyrinth/www
```

## Content Generator

Static bait text has a weakness: crawlers deduplicate. If every labyrinth page is a permutation of the same dozen paragraphs, a scraper's dedup layer collapses the whole labyrinth into ~12 paragraphs' worth of tokens — trap defused.

`generator.php` fixes this. It assembles grammatically correct German sentences from independent word pools (subjects × predicates × adverbs × conditions × sentence templates), yielding **billions of distinct paragraphs**. Each page mixes ~80 % generated text with an occasional hand-written paragraph and bullet list, plus varied article titles and deterministic publication dates.

Everything runs off a single `mt_srand()` seed derived from the page ID:

- same page ID → byte-identical page, every time (cache-friendly, no database)
- different page ID → genuinely different text (dedup-proof, maximum token burn)

**Customizing / translating:** edit the word pools at the top of `generator.php` (subjects, predicates, adverbs, conditions, intros, list items) and the pools in `config.php` (topics, authors, subheadings, month names). Keep the predicate structure `[verb, object]` intact — it is what keeps both main-clause and subordinate-clause word order grammatical.

---

## WordPress Integration

PHP AI Labyrinth integrates into WordPress without any plugin. Just follow the previous instructions, plus, add an hidden link pointing to the honeypot, manually editing your sidebar, or footer.

## Potential Conflicts

| Situation | Problem | Solution |
|---|---|---|
| **Security plugins** (Wordfence, iThemes) | Plugin scans the labyrinth path and fires alerts | Whitelist the trap path in the plugin settings, or use a less obvious path name |
| **Caching plugins / CDN** | Cached pages always return the same page — bots loop through only 1–2 pages | Exclude the trap path from cache (e.g. W3 Total Cache: "Never cache pages: `/research/.*`") |
| **`robots.txt` plugins** | Plugins like Yoast SEO may auto-disallow non-WP paths | Remove the labyrinth path from the plugin's disallow list |
| **WAF / Cloudflare Firewall** | Your own WAF rules block trap traffic before it reaches PHP | Add the trap path to WAF bypass rules (`uri contains /research/ → skip`) |
| **Rate limiting** | Your own rate limiting blocks aggressive bots before they go deep | Exclude the trap path from external rate limiting — you *want* bots to send many requests (the built-in limiter still caps genuine DoS) |
| **PHP `open_basedir`** | Restrictive `open_basedir` settings can block `require_once` for `config.php` | Place all three files in the same directory, or adjust `open_basedir` |

---

## Configuration Reference

All settings live in `config.php`:

| Constant | Default | Description |
|---|---|---|
| `LABYRINTH_MAX_DEPTH` | `100` | Depth cap before silent reset |
| `LABYRINTH_LINKS_MIN` / `_MAX` | `6` / `12` | Outgoing links per page |
| `LABYRINTH_PARAGRAPHS_MIN` / `_MAX` | `4` / `8` | Paragraphs per page |
| `LABYRINTH_SITE_NAME` | `'Fachredaktion'` | Shown in footer |
| `LABYRINTH_BASE_PATH` | `'/research/articles.php'` | Self-referencing URL path |
| `LABYRINTH_LOG_VISITS` | `true` | Log bot visits via `error_log()` |
| `LABYRINTH_DELAY_MIN_MS` / `_MAX_MS` | `0` / `0` | Tarpit delay per page in ms (see below) |
| `LABYRINTH_RATE_LIMIT_ENABLED` | `true` | Per-IP rate limiting on/off |
| `LABYRINTH_RATE_LIMIT_MAX` | `60` | Max requests per IP per window |
| `LABYRINTH_RATE_LIMIT_WINDOW` | `60` | Rate-limit window in seconds |
| `LABYRINTH_PAGE_ID_MAX_LENGTH` | `64` | Max length of the `?p=` parameter |
| `LABYRINTH_LOG_MAX_PER_WINDOW` | `5` | Max log lines per IP per minute |

Arrays for topics, paragraphs, authors, subheadings and month names are in the same file; the sentence-generator word pools live in `generator.php`. All can be freely extended.

### Tarpit delay

Setting `LABYRINTH_DELAY_MIN_MS` / `LABYRINTH_DELAY_MAX_MS` (e.g. `250` / `1500`) makes every labyrinth page sleep for a deterministic, per-page amount of time before responding. It costs the crawler wall-clock time and costs you almost nothing — **but** each sleeping request occupies a PHP-FPM worker. Only enable it if your worker pool has headroom, and rely on the built-in rate limiter (values are hard-capped at 5000 ms as a safety net). Default: off.

## Monitoring

Just inspect your webserver logs to look at visitors of your configured honeypot path, like `/research/`.

---

## License

MIT — see [LICENSE](LICENSE)

## Credits

Concept inspired by [Cloudflare AI Labyrinth](https://blog.cloudflare.com/ai-labyrinth/) (2025).
PHP implementation by [Philipp Gabriel](https://github.com/PhilGabriel).
KISS fork by [Valerio Bozzolan](https://gitlab.com/valerio.bozzolan/).
