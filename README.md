# NTUST_Web_Security

## Website specification
https://hackmd.io/@splitline/B1Js1ime9

***

## Some statements before using
1. There're 3 main pages - index.php/ login.php/ board.php

2. As a beginning user, you must sign up first and you can't use the special words like(" / or and =) or others char will cause SQL injection.

3. When you log in to your own page, you have a cute bunny as your default avatar. You can change it by uploading your local image or by image URL. But be careful not to upload some data that are not image files.

4. You can also add comments below your user page and add your attachment as well.

5. You can see the "BOARD" button at the top-right page to view all the comments not only yours but others. You can delete your post and view every one post on a unique page.

***

## Something can solve in the future
1. Insert php.ini in docker and revise upload_max_filesize as 20M at last and post_max_size as 30M --> OKKK

2. In /www/upload_data.php and /www/upload_data_web.php, we can add a statement about cookies and sessions then we can redirect the page more suitable as human beings. I designed the page to redirect the index.php consistently and this will let the users log in repeatedly.

3. I can analyze the header more correctly that can get the real client IP instead of the virtual IP.

4. Repair the sign up page bug. If I cancel css class name none, I can bypass recaptcha function.

5. Replace the js alert massage box to much more beautiful. You can browse [this page](https://getbootstrap.com/docs/4.0/components/alerts/#triggers).

6. Verify weak passwaord and email format corret or not.

7. Show the post according to the time order

8. Check the user identify who wants to delete post.(In delete_post.php)

9. Third-party login

10. Create the "revise profile page" including password, username, or email

11. Verify the email that user enter(you can send a verification code to the email)

***

## Important things for author
1. Sometimes, the browser will not show the css effect correctly or just show a part of them. Then you can:
   * stop the docker and up again
   * use another browser
   * restart the computer to clean the cache in the register
   * or just WAIT...

2. How to use .gitignore
   * If you want to ignore all files
   
     ```
     sample_folder/
     ```
     
   * If you want to ignore all files but some of them want to backup. (P.S you must set sample_folder/* at first)
   
     ```
     sample_folder/*
     !sample_folder/s1.jpg
     !sample_folder/s2.pdf
     ```

   * .gitignore will ignore the files that haven't trace in the cache, so if you want to ignore the file that is traced you can execute the command
   
     ```
     git rm -r --cached .
     git add .
     git commit -m "update .gitignore"
     git push
     ```