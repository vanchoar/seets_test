Testcase
We'd like to see how you handle this testcase. It uses databases, encryption,
date functions and some AJAX handling in jQuery. Please write everything in
OOP. You can leave the html/css plain. A little styling is a nice to have.

Year

Create a simple form with an input year and use the method POST.
Count the filled in year backwards, and show which year is a primenumber.
Execute it until you have 30 prime years. Also tell on which day christmas is
(monday, tuesday etc.).

Encryption in database

Create a simple table in MySQL with 3 columns (id, year, day).
Encrypt the day, where christmas is on, using an encryption of your liking. Save
it to the table with prepared statements. If it's already present, you won't have
to insert it again.

When it's done, return all of the (decrypted) rows in a simple html table and
return the output in javascript the ajax function of jQuery.
This gives you the following:

1. Input data:
1. Input year
2. Send form
1. Send data to server
2. Insert encrypted data into database
2. Output data:
1. Decrypt data
2. Output using JSON object
3. Show a table with the handled data
When you're done, please email a zip with the necessary files.
