# FeedMei
A clean and minimal theme for [Tiny Tiny RSS](https://tt-rss.org), loosely inspired by Feedly. Built by making the minimal amount of changes to the default theme.

![Combined mode](SCREENSHOT.png)

## Installation
Just copy all files into the `themes.local` folder of your Tiny Tiny RSS installation.

## Customization Tips

### Maximum width for articles
For optimal reading, the article shouldn't stretch out too far. Add this to `feedmei.css`:
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
The last Tiny Tiny RSS commit this has been fully tested on is __671f4cee65__.