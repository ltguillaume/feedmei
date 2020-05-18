### keyboard_cursor
Use cursor keys for a tree-style article navigation. This makes browsing through your articles a lot faster. Forces the following hotkeys:

Hotkey | Command | Command with opened article
--- | --- | ---
Up/Down | Select article row | Scroll through active article
Left | Jump to top | Deactivate article / focus headlines
Right | Read selected article | Read next article
Enter | Read selected article | Open link in new window
Ctrl+Left/Right | Read previous/next article
Insert/(Numpad)0 | Toggle unread

### minimal_hotkeys
_Replace_ the default keyboard shortcuts with the following intuitive set:

Hotkey | Command
--- | ---
[ / ] | Feed previous/next
Left/right | Artcle previous/next
Up/down | Scroll
Enter | Open link in new window
b | Toggle sidebar
o | Reverse order
p | Toggle published
r | Refresh feed
s | Toggle star
u | Toggle unread
x | Close article
g a\|f\|n\|p\|r\|s\|t | Go to All\|Fresh\|Night mode\|Published\|Read\|Starred\|Tags

### quick_unsubscribe
Speed up your cleaning rage with these hotkeys. Hotkeys to speed up the process of going through many feeds and quickly unsubscribe or mark all articles as read.
Hotkey | Command
--- | ---
M | Mark all articles in feed as read _and_ open next feed
u | Unsubscribe from feed _and_ open next feed

### toggle_night_mode
Allow night modes for custom themes (use `yourtheme_night.css` as filename). Reintroduces `a N` hotkey to toggle between day and night themes.

### unread_oldest_first
Change the sort order to _Oldest first_ when the view mode is _Adaptive_ and there are unread articles. Otherwise, set the sort order back to _Default_. Needs tt-rss commit [e981d52bda](https://git.tt-rss.org/fox/tt-rss/src/e981d52bdabbb0893ac69b29d7690d0bb63fbc14) or later.