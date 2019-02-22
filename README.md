# FeedMei
A clean and minimal theme for [Tiny Tiny RSS](https://tt-rss.org), loosely inspired by Feedly. Built by making the minimal amount of changes to the default theme (which could also mean it will break soon-ish).

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