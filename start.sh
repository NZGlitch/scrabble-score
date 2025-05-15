#!/bin/bash

#docker build -t scrabble-score-image .
#docker run -d -p 8080:80 --name scrabble-score-app scrabble-score-image


docker run -d -p 8080:80 -v $(pwd)/submissions.db:/var/www/html/includes/database.db --name scrabble-score-app scrabble-score-image