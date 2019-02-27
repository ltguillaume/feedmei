# FeedMei
A clean and minimal theme for [Tiny Tiny RSS](https://tt-rss.org), loosely inspired by Feedly. Built by making the minimal amount of changes to the default theme.

![Screenshot](SCREENSHOT.png)

## Installation
Just copy all files into the `themes.local` folder of your Tiny Tiny RSS installation.

## Customization Tip
Put some space between the article and the header when scrolling. In `rss/js/Article.js` change:
```
				ctr.scrollTop = e.offsetTop;
```
into
```
				ctr.scrollTop = e.offsetTop - 21;
```

## NOTE
The last Tiny Tiny RSS commit this has been fully tested on is [4e253add8c78bb185911ffdffbb50ef318386a3c](https://git.tt-rss.org/fox/tt-rss/src/4e253add8c78bb185911ffdffbb50ef318386a3c).
