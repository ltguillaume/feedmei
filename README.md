# FeedMei
A clean and minimal theme for [Tiny Tiny RSS](https://tt-rss.org), loosely inspired by Feedly. Built by making the minimal amount of changes to the default theme.

![Combined Mode](SCREENSHOT.png)

![Combined Night Mode](SCREENSHOT2.png)

## Installation
Just copy the files from `themes.local` to the `themes.local` folder of your Tiny Tiny RSS installation.

## Customization Tips

### Maximum width for articles
For optimal reading, the article shouldn't stretch out too far. Add this to `feedmei.css`, or use `feedmei+.css`, which includes more of my personal preferences:
```
div.cdm.expanded {
	width: calc(100% - 5em);
	max-width: 900px;
	margin: 1.5em auto;
}
```

### Align article top
Put some space between the article and the header when scrolling. In `rss/js/Article.js` change:
```
				ctr.scrollTop = e.offsetTop;
```
into
```
				ctr.scrollTop = e.offsetTop - 21;
```

## NOTE
[This is](https://git.tt-rss.org/fox/tt-rss/commit/656475ec78b2139ce43f547968e1d73143bd5c26) the last Tiny Tiny RSS commit the theme has been fully tested on.