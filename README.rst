*****************************
Multi-channel contexts: WURFL
*****************************
Provides context filters based on user device properties.

Allows you to show pages and content elements specifically
for mobile devices, tablet or for different screen sizes.

It has the following device detection features:

- Mobile devices yes/no
- Wireless/tablet/phone/smart TV yes/no
- Screen size range
- Device brand name
- Browser used


============
Installation
============

Dependencies
============
- Contexts TYPO3 extension
- MySQL5 (the PHP MySQLi extension is required).

Setup
=====
- Activate extension
- Optional: Update the URL to the compressed WURFL archive
  - Currently used: http://sourceforge.net/projects/wurfl/files/WURFL/2.3.3/wurfl-2.3.3.zip/download
- Go to "Admin tools" => "WURFL" and perform initial setup

---------------
Alternative way
---------------
- Create backend user ``_cli_contexts_wurfl`` (for command line access)
- Import WURFL database::

    $ php ./typo3/cli_dispatch.phpsh contexts_wurfl import --type=remote

- Setup a scheduler task to regularly update the WURFL database.
  It's sufficient to do this once a month or once a week, since it gets updated
  only a couple of times a year.


=====
Usage
=====

Context creation
================
- Go to the TYPO3 web list view, root page (ID 0)
- Create a new database record
- Record type: Context
- Give it a title, e.g. ``Type: tablet``
- Context type: ``Device properties``
- Check the "Tablet" check box.
- Save

You now have a context that's active whenever a visitor views
your page on a tablet.


=====
WURFL
=====
WURFL, the Wireless Universal Resource FiLe, is a Device Description Repository
(DDR), i.e. a software component that maps HTTP Request headers to the profile
of the HTTP client (Desktop, Mobile Device, Tablet, etc.) that issued the
request.

See http://wurfl.sourceforge.net/ for more information.

Admintool
=========
The WURFL contexts extension comes with an additional backend module located
at ``Admin tools`` => ``WURFL``.

The module allows you to easily update your existing WURFL database, clearing
and rebuilding the WURFL cache and to perform test queries against the database.


Remote database update
======================
You may either use the WURFL admintool or a scheduler task to update the WURFL
database. Alternatively you could also use the CLI to fetch new data from the WURLF
website.

See the setup section.


Local database update
=====================
1. Download a local version of the WURFL repository file.
2. Store it in ``/typo3temp/contexts_wurfl/wurfl.xml``.
3. Call the ``contexts_wurl`` CLI task then::

   $ php ./typo3/cli_dispatch.phpsh contexts_wurfl import --type=local


API update
==========
When updating the WURFL PHP API, it is important to keep our version of
``Library/wurfl-dbapi-1.4.4.0/TeraWurflConfig.php``.
It contains the connection to TYPO3.
