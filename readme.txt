=== Simplify Menu Usage ===
Contributors: konstk
Donate link: https://www.paypal.me/konstkWP
Tags: menu, menus, navigation, nav, simplify, navigation-management, menu-management, efficient, menu item, optimize-workflow
Requires at least: 5.0
Tested up to: 5.5.1
Requires PHP: 7.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Handle your menus more efficient in the admin dashboard by fast moving, deleting and multi inserting at specific position.

== Description ==

***Have you ever been annoyed by being not able to insert a new item as submenu parent or to delete menu items without opening the menu item panel? A free and easy to use plugin saving you time working in WordPress.***

#### Your benefits

* You can delete menu items in a 1-click process instead of opening the menu item panel.
* You can move the menu items in 1-click process without opening the panel.
* You see via icons on the left sidebar if a page, post, category etc. is already used in the current menu.
* You see the used menu items by hovering over the items in the left sidebar.
* You have the possibility to do quick inserts of menu items, where you can place them at the position you like.
* You can modify the behavior of inserting/moving elements downwards.
* It shows you additionally the original name of a menu item on the outer level like in the opened panel.
* Saves you a lot of time by reducing clicks not needed to work with the menu items in the backend.
* It´s free and easy to use.

#### Different behaviors

Inserting/moving menu items downwards is enriched by two new behaviors. Moving/inserting rightwards, upwards is the same as provided by WordPress.
Leftwards moving/inserting is a special case. The leftwards functionality is nearly the same as provided by WordPress expect the scenario when
the menu item becomes a menu parent and create a submenu by moving one step left.
The different behaviors can be seen in the ***Screenshots section***.

#### Same-level behavior

When choosing this behavior menu items will be inserted/moved after submenus. The default behavior provided by WordPress inserts/moves the menu item into the submenu. 
If you move/insert a menu item and the next menu item is the parent of a submenu it will stay on the same level, but the position is after the submenu.

#### Take-children elements behavior

When choosing this behavior menu items will become new submenu parents when inserted/moved if they would become the parent of the submenu children elements. 
This is true for downwards/leftwards moving/inserting.

#### Quick insert multiple menu items

It can be very handy to insert multiple menu items to a specific menu element to create a submenu.

#### Compatible

The plugin is compatible with a multisite setup.

== Installation ==

Quick and easy installation:

1. Upload the folder `simplify-menu-usage` to your plugin directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. That´s it!

== Frequently Asked Questions ==

= How to get started? =

The plugin provides the functionality for existing menus. If you create a new menu you need to insert at least one menu item and save it. Afterwards the functionality will be applied.

= What does this plugin do? =

This plugin reduces clicks required to delete, move, insert menu items into a menu in the admin dashboard.
It provides info which items are already used and shows the original name on the outer level.

== Screenshots ==

1. This is the view of the modified menu area in the admin dashboard.
2. Shows the original name of the menu item on the outer level.
3. Shows the quick insert buttons on the right side. 
4. Shows inserting menu item leftwards. 
5. Shows quick insert of multiple menu items.
6. Shows movement of submenus at the same level.

== Changelog ==

= 1.0.0 =
* Birth of Simplify Menu Usage: Stable Version

== Future roadmap & wishlist ==

By future updates the plugin´s functionality will be enriched. 
If you have any suggestion please hit me an <a href="mailto:konstk.wp@gmail.com">email</a> 

== Contribute == 

Want to help improve the plugin feel free to submit PRs via Bitbucket <a href="https://bitbucket.org/konstk/simplify-menu-usage/" target="_blank">here</a>.
