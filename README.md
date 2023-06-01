# PHP symfony publisher agent template
### How to run manually (symfony required)
IV='[IV GOES HERE]' KEY='[KEY GOES HERE]' FACEBOOK_APP_SECRET='[FACEBOOK APP SECRET HERE]' symfony server:start

### How to build docker image
docker build -t appcharge/agent-php .

### How to run the container locally
docker run --rm -it -e KEY='[KEY GOES HERE]' -e IV='[IV GOES HERE]' -eFACEBOOK_APP_SECRET='[FACEBOOK APP SECRET HERE]' -p8080:8000 appcharge/agent-php

### Where to get the key
Go to the admin panel, open the integration tab, if you are using signature authentication - copy the key from the primary key field, if you are using encryption, take the key from the primary key field and the IV from the secondary key field.