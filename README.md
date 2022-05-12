# NTUST_Web_Security

## Website specification
> http://shorturl.at/cnL68

***

## Some statements before using
> 1. There're 3 main pages - index.php/ login.php/ board.php
> 
> 2. As a beginning user, you must sign up first and you can't use the special words like(" / or and =) or others char will cause SQL injection.
> 
> 3. When you log in to your own page, you have a cute bunny as your default avatar. You can change it by uploading your local image or by image URL. But be careful not to upload some data that are not image files.
> 
> 4. You can also add comments below your user page and add your attachment as well.
> 
> 5. You can see the "BOARD" button at the top-right page to view all the comments not only yours but others. You can delete your post and view every one post on a unique page.

***

## Something can solve in the future
> 1. Insert php.ini in docker and revise upload_max_filesize as 20M at last and post_max_size as 30M --> OKKK
> 
> 2. In /www/upload_data.php and /www/upload_data_web.php, we can add a statement about cookies and sessions then we can redirect the page more suitable as human beings. I designed the page to redirect the index.php consistently and this will let the users log in repeatedly.
> 
> 3. I can analyze the header more correctly that can get the real client IP instead of the virtual IP.


## Important things for author
> 1. Sometimes, the browser will not show the css effect correctly or just show a part of them. Then you can:
    * stop the docker and up again
    * use another browser
    * restart the computer to clean the cache in the register