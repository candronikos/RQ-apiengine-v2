![Real Questions Logo](https://realquestions.net.au/profiles/realquestionsau/themes/realquestions/images/blacklogo.png)

# apiengine-v2
This is v2 of the RealQuestions backend operations engine. It is a new build, improving on what v1 was and will sport a new set of technologies.

to learn more see our [wiki] (https://github.com/RealQuestions/apiengine-v2/wiki)

1. Copy env.sample to .env & set the variables

2. The Composer.json files contains all the libraries we use 
" composer update "

* Please note the file system layout will be updated as we decide on one -- consider these baby steps :)

If using the Neo4j Storage only, you can ignore this for now while we fix this:
Copy setup.php.sample to setup.php & set the variables - Run this first 



*IMPORTANT NOTE* please do not use this code on production systems. Not secure or tested. This is pilot code.
Initial code, this is going to change.

These scripts use Cypher queries to connect to neo4j. We are currently in the process of updating them to use gremlin instead. An experimental docker image with the gremlin plugin 7 neo4j, can be found in the dockerimages repository. [neo4k + grelin plugin] (https://github.com/RealQuestions/dockerimages/tree/master/openjdk_neo4j)

