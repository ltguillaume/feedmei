### keyboard_cursor
Use cursor keys for quick tree-style article navigation. Forces the following hotkeys:

Hotkey | Command | Command with opened article
--- | --- | ---
Up/Down | Select article row | Scroll through active article
Left | Jump to top | Deactivate article / focus headlines
Right | Read selected article | Read next article
Enter | Read selected article | Open link in new window
Ctrl+Left/Right | Read previous/next article
(Numpad)0 | Toggle unread

### minimal_hotkeys
Replaces the default keyboard shortcuts with the following set:

Hotkey | Command
--- | ---
[ / ] | Feed previous/next
Left/right | Article previous/next
Up/down | Scroll
Enter | Open link in new window
B | Toggle sidebar
O | Reverse order
P | Toggle published
R | Refresh feed
S | Toggle star
U | Toggle unread
X | Close article
G A\|F\|N\|P\|R\|S\|T | Go to All\|Fresh\|Night mode\|Published\|Read\|Starred\|Tags

### toggle_night_mode
Allow night mode toggle for custom themes (use `yourtheme_night.css` as filename)

### unread_oldest_first
Changes the sort order to _Oldest first_ when the view mode is _Adaptive_ and there are unread articles, otherwise sets sort order back to _Default_. Needs tt-rss commit [e981d52bda](https://git.tt-rss.org/fox/tt-rss/src/e981d52bdabbb0893ac69b29d7690d0bb63fbc14) or later.