### libpaging2 ###

# Purpose

This application generates a set of paging lists for Multnomah County Library (MCL) branches. While
it is configured for MCL, it could easily be configured for any other library utilizing the SirsiDynix
Symphony integrated library system.

The application has three primary purposes: 
1. To provide simple, clear, paging lists for MCL branches, which are accessible via either web or email.
2. To support breakdown of the Central Library's list into multiple lists, based on shelving location.
3. To demonstrate the capabilities of the <a href="https://github.com/multnomah-county-it/libilsws" 
alt="LibILSWS GitHub Repository">LibILSWS</a> code library, which the application utilizes to communicate 
with the SirsiDynix system.

# Features

The paging application produces a web interface to title and item paging lists for each branch of a
multi-branch system. In addition, the application provides live links into the Symphony catalog so 
that staff may conveniently and quickly look up additional information about listed items.

The Central page provides Central Library staff the option of organizing their paging items into 
multiple, separate lists, based on shelving location. This is helpful, given the large size and 
multi-floor design of the bulding. The Central page is configured via a YAML file that filters items 
into groups based on Symphony's current location and call number. Regular expressions are used to define 
the call number filters.

Because retrieving the paging list data from Symphony is a fairly slow process--it takes about 20 
seconds on Multnomah County Library's system--the application comes with a list generation utility, 
`bin/gen_lists.php`, which is designed to be run by root from a cron job. For example, to generate 
the lists every morning at seven a.m., you would add the following line to your crontab:
```
0 7 * * * /usr/bin/php /var/www/html/paging/bin/gen_lists.php /var/www/html/paging
```
This utility also emails the lists to users at each branch configured in the YAML configuration file.

The paging lists may also be updated during the day by pressing the button at the bottom of the branch 
list.

# Demonstration

The Multnomah County Library implementation of this application may be accessed at 
<a href="https://lib-paging.multcolib.org/paging/">https://lib-paging.multcolib.org/paging/</a>.
