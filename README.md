Population Search
=================
A simple tool to search Finnish towns and their population. Project is unfinished on purpose and the intention is for developers to fork the project and complete it.


Hello,

here are some basic features and instructions

1. I have added basic search which is triggered on Ajax and it works with %string% so it gets more results
2. I have limited to 20 results by default and it can be changed on Settings page
3. I have added autcomplete which also works in the same way as the search and is limited to 5 results
4. I have added small behat (BDD) test - more like proof of concept


To run the test you need to run

```
php vendor/bin/behat @"SJWSearchBundle"

```

And you need to run Selenium locally so you can test Ajax call

Testing with selenium:
Download selenium .jar file [here](http://www.seleniumhq.org/download/)

```

java -jar selenium-server-standalone-2.38.0.jar

```

And edit please behat.yml file for the correct base_url, I am not sure how to make it generic


If there are any other questions or issues please ask! :)