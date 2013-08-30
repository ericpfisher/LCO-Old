LCO
===

Loaner Checkout Systen

This is a back-of-house system used to manage IT repair loaners rather than using pen and paper.  The system was designed to be a web-interface, accessible from technician terminals.  The system is built using PHP, MySQL, and elements of JavaScript.

A lot of my code was inspired by Jason Lengstorf's "PHP for Absolute Beginners."  If there are any similarities in my PHP/MySQL code that resemble snippets from his book, it's because that book was my launchpad for learning and using PHP on this scale. :)

If you decide to fork this repository, note that my db.inc.php has been git-ignored.  Create your own database PHP file and define the respective variables for a PDO connection. 

Also, any codeblocks not generated by my own fingers will have a comment listing the location I found it. 


Planned updates:

• Major UI changes (learning CSS/JavaScript/jQuery right now!), e.g. merge available loaner count with loaner selection
• Recent checkouts feed
• User password change interface
• Salt passwords
• Truncate older entries (thinking two months...)
• Change labels to icons for easier recognition, e.g. replace "Mac" with an Apple logo
• Make rewrite rules...pending UI changes (if the UI overhaul is too great, might not need to use a Rewrite engine)
• Advanced search (?)
• More useful alerts, e.g. "Loaner X has been out for Y days!"

