# proj3-CMSC433-Scripting
## Description
The Pokemon-2 project was submitted by Group 6 (member count 4) in response to the Project 3 requirements. Hosting the repo files on an XAMPP development server and opening the proj3.html page will ensure the game is correctly set up. The battle sequence of the game needed futher development and integration.<br>
## Setup
Download the repo via zip. <br>
Unzip the files inside your Xampp /opt/lampp/htdocs folder.<br>
Inside htdocs your should see the pokemon2 file.<br>
## Game Startup
In your browser, please enter the following:<br>
    localhost:8080/pokemon2<br>
    Select proj3.html<br>
    <br>
Enjoy!<br>
## Problem shooting
- Permission Denied<br>
    Please consult this web page if you are a mac user:(this page)[https://www.kindacode.com/article/xampp-on-mac-permission-denied/]<br>

    If you are uncomfertable changing the permission on Xampp then I recommend commenting out code in the loginNewUser/register.php.<br>
      Go to loginNewUser/register.php<br>
      Comment out lines 116 - 121.<br>
## Authors
- Michael Alano - database configuration, registration, login, logout, password reset.<br>
- Caleb M. McLaren - database configuration, registration, login, logout, password reset.<br>
- Kaylee Schultz - world destinations, image resources, store, mechanics, world map.<br>
- Cameron Zahnen - battle algorithms, battle animations, battle music, battle ground images..<br>
## Environment
- XAMPP Development Server<br>
## Repo Contents
- pokemon-2 folder: This folder contains the files submitted by Group 6 for project 3, of which I was a member.<br>
    - battle folder: holds the JavaScript to support Pokemon battles at locations around the world map as well as required .png and .mp3 resources for the battle sequences<br>
    - loginNewUser: This folder contains the files required for account registration, login, logout, password reset, and database configuration.<br>
    - play: This folder contains the files required to create and support destinations in the Pokemon-2 world.<br>
    - sourceImg: This folder contains the image resources used throughout most of the project.<br>
    - pokemon.csv: This file is the source file for the Pokemon monster details.<br>
    - proj3.css: This file details most the styling choices for the project.<br>
    - proj3.html: This is the root web page of the pokemon-2 game. Clicking on this page ensures the game starts up correctly.<br>
    - proj3.js: This file details the wallet and pokemon objects.<br>
    - ron.json: experimental detritus from when project development stopped.<br>
- CMSC433 - Project 3 - Pokemon.docx: This document details the project requirements. <br>