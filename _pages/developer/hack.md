---
title:      Hack
permalink:  /developer/hack/
---


HACK
====

To solve problems related to sites hacking is usefull follow some of this suggestions:

- [https://www.wordfence.com/docs/how-to-clean-a-hacked-wordpress-site-using-wordfence/](https://www.wordfence.com/docs/how-to-clean-a-hacked-wordpress-site-using-wordfence/)
- [https://mediatemple.net/community/products/dv/204405434/how-to-uncover-malicious-code-malware-files](https://mediatemple.net/community/products/dv/204405434/how-to-uncover-malicious-code-malware-files)
- [http://php.webtutor.pl/en/2011/05/13/php-code-injection-a-simple-virus-written-in-php-and-carried-in-a-jpeg-image/](http://php.webtutor.pl/en/2011/05/13/php-code-injection-a-simple-virus-written-in-php-and-carried-in-a-jpeg-image/)
- [https://www.google.it/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&uact=8&ved=0ahUKEwitvZDOwv7TAhWqAsAKHdVdBtMQFggnMAA&url=https%3A%2F%2Faw-snap.info%2Farticles%2Fphp-examples.php&usg=AFQjCNHdsNbyLcT2f8hqcxVetdvydA1wFg&sig2=AjJZNbwbkmpsY4W3ail5fg](https://www.google.it/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&uact=8&ved=0ahUKEwitvZDOwv7TAhWqAsAKHdVdBtMQFggnMAA&url=https%3A%2F%2Faw-snap.info%2Farticles%2Fphp-examples.php&usg=AFQjCNHdsNbyLcT2f8hqcxVetdvydA1wFg&sig2=AjJZNbwbkmpsY4W3ail5fg)
- [http://stackoverflow.com/questions/3708246/hacked-what-does-this-piece-of-code-do](http://stackoverflow.com/questions/3708246/hacked-what-does-this-piece-of-code-do)



With `egrep -ril` you can search for:

- `eval *\(` (and found `@eval(`)
- `base64` ( but not secure, since I've found strings like ` $lkbpsopyt='ba'.'se'.'64_deco'.'d'.'e'.''; @eval($lkbpsopyt....`)
- `zip`
- `gzuncompress(`
- `&#x2f;` or `\x2f;` che corrispondono al carattere `\` usato ad esempio in 
- `@include "\x2fvar/\x77ww/t\x69mesc\x61pes/\x77p-co\x6etent\x2fplug\x69ns/r\x6edpst\x32/fav\x69con_\x61ad4a\x30.ico";`
- `http-equiv="refresh" content="1;URL=http://royal-night.info"/`


In general check the files:

- `.htaccess`
- `index.php`



CLEAN DB
--------

Find suspected strings into DB:

````mysql
SELECT * FROM id013_posts WHERE post_content LIKE '%xf2%'
SELECT id,post_content FROM `id013_posts` WHERE post_content LIKE '%<script>%' ;
````


And here a php script to clean the fields: 
>be care, it's no my responsbility if you lost data!

>I suggest to make a backup before proceed with this script

````php
<?php

$servername = "mercurio-mysql";
$username = "root";
$password = "cicciopasticcio";

$dbname = "dbTimescapes";

$table = "id013_posts";
$column = "post_content";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// test one before do for all posts
//$sql = "SELECT id, $column FROM $table WHERE id = 1346";
$sql = "SELECT id, $column FROM $table WHERE id = 1179";
// query all
//$sql = "SELECT id, $column FROM $table";
$result = $conn->query($sql);


// REGEX

// remove scripts with unicode paths
$unicode = 'var *_0x';
// remove suspected function
$func = '\(function.*window\.location=';
// base64 code with eval
$evalbase='var *BDZUFIMGRY';

// start with <script
$startscript='\< *script[^\<]+';

// remove up to <\script>
$endscript = '[^\<]+\< *\/ *script *\>';

// empty script
$emptyscript='\< *script *\> *\< *\/ *script *\>';

$hackRegex = "({$startscript}{$unicode}{$endscript})|({$startscript}{$func}{$endscript})|({$startscript}{$evalbase}{$endscript})|({$emptyscript})";


if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $oldContent = $row["post_content"];

        $newContent = preg_replace(
            "/$hackRegex/",
            '', $oldContent);
        $newContent = $conn->escape_string($newContent);
        echo "$id \n";


        $update = ("UPDATE $table SET $column='$newContent'  WHERE id='$id'");

        $res=$conn->query($update);

        if($res){
            print 'Success! record updated / deleted';
        }else{
            print 'Error : ('. $conn->errno .') '. $conn->error;
        }
    }
} else {
    echo "0 results";
}
$conn->close();

````



### HEADER HACK (exploit)

- [https://exploitbox.io/vuln/WordPress-Exploit-4-6-RCE-CODE-EXEC-CVE-2016-10033.html](https://exploitbox.io/vuln/WordPress-Exploit-4-6-RCE-CODE-EXEC-CVE-2016-10033.html)