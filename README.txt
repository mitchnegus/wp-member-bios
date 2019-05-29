=== Member Bios ===
Contributors: mitchnegus
Donate link: 
Tags: member, profile, bio, team
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A plugin for managing a group's member pages, including automatic submission of an image and short bio. 


== Description ==

This is a plugin for managing member pages on a group or team webpage.
It allows users to submit biographical information through a form provided by the plugin, and then uses this information to automatically generate a profile for that member.
These member profiles are then displayed on the site in two locations.
First, the plugin adds a 'Members' page immediately off your site's root page (e.g. at `www.mysite/members`) where all of the members are displayed.
Clicking a member on this page will then take you to the member's individual profile, which includes all of the biographical information that the member provided through the form.
The main 'Member's page includes a link to the form, so that members can easily add themselves.

On the backend, the plugin also provides a site administrator with a variety of customization options.
First and foremost, it adds a 'Members' section (see the "Members" link in the admin area sidebar) where member posts can be edited and customized. 
After a member submits their information through the form, their profile appears in this section as a draft. 
Site administrator approval is then required before the profile is published.
Members can also be added (or removed) manually through this interface, which attempts to be fairly self-explanatory.

A member profile consists of several pieces. The only mandatory component is a name, but an administrator can choose to add any of the following:
* A primary subheader, displayed immediately below the member's name (e.g. the member's field of study, department, division, position, specialty, etc.)
* A secondary subheader, displayed immediately after the primary subheader (e.g. graduation or draft year, office building, website, etc.)
* A member bio
* A member photo (a generic headshot silhouette will be used for members without a thumbnail photo)
* A set of tags for the member (e.g. talents, skills, interests, etc.)
* A position for the member (e.g. member, executive, co-founder, etc.)

For subheader and tag information, the site administrator can choose and customize the descriptions which are used on the new member form and in member profiles. 
These customizations can be adjusted from the plugin's settings menu (found in the sidebar, under "Settings").
On the submission page, potential members will see only fields for subheaders and tags that have descriptions set. Similarly, a member's profile page will only show categories of member info that have set descriptions.
The settings page also includes options for setting the maximum file size of uploaded headshot photos, whether or not the site admin is notified when someone submits a new member form, and whether or not to use your group/organization/institution email address to help filter out spam form submissions.

To see the plugin in action, check out the [Science Policy Group at Berkeley](https://sciencepolicy.berkeley.edu/members) website!

== Installation ==

At the moment, this plugin is not available through WordPress.org.
Instead, you must clone or download the repository manually. Those steps are

1. Clone/upload this repository (with file `member-bios.php` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.


== Frequently Asked Questions ==

= How do I change the order of members on the main 'Members' page? =

The order of members is alphabetical by the slug of the member's individual profile page. 
The default slug is generated when a new member form is submitted by rearranging the new member's name so that the last name is first. 
Though not ideal, these slugs (and hence the ordering) can be changed by editing the page permalinks the same as any other WordPress page.


== Screenshots ==

1. (None yet)

== Changelog ==


= 1.0.0 =
* The first release!


== Upgrade Notice ==

= 1.0 =
(Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.)


== In the works... ==

* Better profile page formatting
* Taxonomy based sorting on the 'Members' page


== Credits ==

This project is based on the [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate) template. 
Many thanks to those developers for helping me get started.
