# deckbuilder_archive_spa_version_vue

This project has been created as part of my portfolio and uses the MIT licsence. Any images and data used that are intellectual property of their respective authors/creators are not used for financial gains. This is a simple portfolio meant to show my personal skills, when it comes to working as a software developer. It is nice to look at and this version will not be hosted on any web browser. It can only be used locally and is meant as an hommage to my favourite part time activity.

**What is this project all about?**
This project combines my previously created backend version with a proper frontend user interface. As of such it also includes a slightly different backend version to the one I have allready created and documented on git: deckbuilder_archive_php_version_only. 
Intended as a part of my portfolio, I chose to create a SPA that combines one of my favourite hobbies, trading cards, with the skills I have acquired since the year 2022, during my studies at the University of Applied Sciences Technikum Wien and beyond that.

**Where are the main differneces between this fullstack version and my php only version?**
Well since Vue itself is used to create Single Page Applications, which contains scripts, that handle all data, like user tokens and cookies on the client side, working with sessions in php, which are exclusively handled on the server side, entailed that certain functions that have been created using these sessions, haven't been working properly. 
The difficulties I have encountered stemmed from me being able to login a user, getting a JWT that is saved by the Firebase library in my Vue project, but being unable to create  or alter decklists, because the server side immidiately destroyed all sessions, after starting them. Which lead to the inability of using several services that the project should have provided. 
As of such it was neccessary to create a backend version with php, that did not automatically fill in values from existing sessions, but instead had to recieve parameters directly/manually via an input in order to properly work the deckbuilder functions.

**Why both versions then, instead of just the SPA version?**
Simple! As a part of testing I have specifically created a regular Javascript and HTML test file that simply accessed the PHP backend and it worked just fine. So if I chose to use regular Javascript and HTML the session handling on the server side would have worked as intended.

**Why not use regular Javscript then?**
For two reasons:
The first one is higher data security. By handling user tokens on the frontend side and not the backend side it is much harder for individuals with malicious intentions to access data they shouldn't have access to like email adresses and passwords. They would need to allready have these informations in order to access them (as far as my understanding goes). Would these be handled by the server side, there could be a risk for someone to just simply display currently active sessions (as far as my understanding goes). By using the SPA version though, the backend would never hold this confidental information, it would only communicate it to the client side.
The second reason is, performance (as far as my understanding goes). Vue has allready predefined code areas, like data, setup, methods, watched, etc. that follow specific scripts when called upon by the CLI. The CLI, even though not quite perfect, follows specific rules, when running the code. Even though when compared to a client like Vite, which only builds the idealtree by accessing components, that are needed for a running the programm, it is still significantly faster/smoother when running Javscript compared to regular Javascript without a framework (as far as my understanding goes).

**What do you mean when you say 'As far as as my understanding goes'?**
I mean, that this is the extent of my understanding and research into the topic. I have done as much due dilligence as possible and IF I have misunderstood any of the intricacies with these frameworks and clients, than I would like to disclose that information upfront. Communication and transparency are very important to me, when it comes to creating high quality products/software. My professionality, so to speak, demands that I properly disclose this information and encourage individuals to do their own research when it comes to these topics. I have been educated in IT Security, but I am far from an expert. I am an azubi. A freshly finished student. I too can make mistakes and I too struggle sometimes with wrapping my head around certain topics, BUT when it comes to things like data privacy and securly browsing the Internet, I feel the incredible need to do it right. And that, for me at least, includes warning individuals about potential risks and properly disclaiming when I am not entirely sure on how things work. What I have learned is that there is no perfect protection for anything on the Internet, but we can get as close as possible, by showing individual responsibility and taking steps ourselves. 
So it is paramount that I clearly communicate that my understanding of the programming language I am working with isn't final. The reason why I assume that this is how it works is, because I have spent 7 weeks trying to figure out, why my backend version was not able to hold a session variable, even after initializing it and recieving confirmation, that logging in has worked. I have consulted the official Vue homepage many times and several other sources, including YouTube as well as ChatGPT, to format the information in a subjectively more understandable way. On top of that I felt confirmed in my assumption when I created a regular Javascript file to test, whether the issue was caused by my PHP project. And as mentioned before, I hadn't encountered the same issue, by using regular Javscript and HTML. 
In fact the web page worked as originally intended, when not using the Vue framework.
So I insetad altered my session handling to use regular associative arrays for the SPA Vue frontend and as a result it worked like envisioned. An issue that took 7 weeks of research and was solved within 2 hours of actually altering the code of my PHP backend.
And that is the full extent of my understanding of the issue I have encountered and I would love further explanations on the inctricacies of why it works like that and not the other way around.

**So how does it work?**
In genereal the deckbuilder_archive can be compared to any other online web page that has a searchbar/archive/browsing function, a shoppingbasket, as well as a receipt system and user created contents. In a sense it functions exactly the same as any other online shop, hotel or booking site, but it is packaged differently and provides trading card fans in particular with a fun experience in exploring my work.

**There are a lot of files, what for?**
Well since documentation, requirements engineering and project management, also made up a huge part of my studies, I am also somewhat versed in analyzing a projects need, planning that project and communicate its intricacies in a proper and understandable fashion. At least I hope that these additional files will help properly communicate what the project can do and should do, how it was created and so on. For the purpose of clearly understanding the project and as a part of my portfolio, I have created not only requirements documents, but also my own user stories, as well as a kanban board (GitHub is amazing, for having an integrated version) and different paper-prototypes. 
In the hopes of keeping somewhat of an orderly manner, I will split the documentation between the backend and the frontend. I want this project to be easily accesible and setup for anyone who wants to try it out, so I need to find a solution that doesn't force users to run my pet project in a particular way.

**How do I install your project?**
First of all you will need to setup your own server environment. I personally suggest xampp, since it is easy to install and setup, but any server environment you are comfortable with (and that supports PHP) works. 

After the installation you will want to open the xampp control panel and start the Apache server and the MySQL server. Now got to your browser and enter 'localhost/phpmyadmin' into your searchbar.

Then you create a new database named deckbuilder_archive and either simply copy the contents of the deckbuilder_archive_xampp_MySQL_database_contents.pdf file into  your SQL querry or import the included deckbuilder_archive.mysql file into the newly created database.

If, you encounter issues with properly running the code, try creating the tables seperately instead and use the added ALTER TABLE commands to make sure all constraints work properly. Then you can INSERT all the data by copy and pasting the remaining command lines into your SQL querry box. 

In the next step you want to setup your 'pma' user in 'localhost/phpmyadmin' by cklicking the tab on 'User Accounts' and clicking the 'Change Rights' option next to the 'pma' user. The following boxes need to be checked: SELECT, INSERT, UPDATE, DELETE. This will allow you to simply run the project as is, so you don't have to alter the config.php file in order to access the database.

Alternatively you can just simply change the config.php file under the value for 'DBUsername' to your localhost admin. For local testing this shouldn't pose any security issue whatsoever.

And the final configuration you will have to do is to close your browser and stop the MySQL server and then the Apache server. Now click on the 'config' button inside your xampp control panel next to the Apache server and open the httdp.conf file. Use CTRL + F to open up the search function. Enter 'Listen 80'. In here you will now setup a CORS wildcard using an asterics. Post the CORS configuration right below the line of 'Listen 80'. It should look like this:

Listen 80
Header set Access-Control-Allow-Origin "*"
Header set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"
Header set Access-Control-Allow-Headers "X-Requested-With, Content-Type, X-Token-Auth, Authorization"

Save the file dilligently and close it again. Now when you start up your Apache Server again it will use the new CORS configuration and will allow the backend server to communicate with the frontend server of the Vue CLI.

Very important! This is a so-called wildcard, which means that ANY URL that tries to access the backend is capable of doing so. This is a HUGE safety violation and risk since anyone can just write up a script of their own and access the backend side of the server if they know how to. Meaning this is for local production and testing only, DO NOT EMULATE THIS IN ANY PRODUCTION ENVIRONMENT!

IF you choose to, for whatever reason and please keep in mind that I do not condone this, publicly host this project including its backend on the DOM: CHANGE this setting from the asterics '*' to your actual URL that your frontend is using. I am in no way shape or form responsible for your negligance. You have been informed. Keep that in mind!

Next just clone/pull the entire project into your server environment folders (htdocs for xampp as an example) and alter the config.php file, so that it contains the neccessary baseURL and DB-Connection settings you are using, if you haven't given additional rights to your 'pma' database user yet.

Before we proceed further you will now download Visual Studio Code from https://code.visualstudio.com/download. Use the standard installation of the system you are working on. For me that is Windows 11. Simply install the application and start it up and get familiar with the following two options:

The 'File' tab will allow you to open up a project. Do this with the 'Open Folder' option and navigate to your desired project once you have installed it: this should allways be the 'htdocs' directory inside your 'xampp' installation.

The 'Terminal' tab, will give you the option to open up a terminal powershell inside VSC (s.f. Visual Studio Code) allowing you to start your server enironment much more conveniently in comparison to your Windows Powershell for example.

We are not done though. You will need to perform two more steps for the project to actually work. The first one is open up your commandshell or VSC terminal and navigate into the 'ui' directory with 'cd ui'. Here you will have to use 'npm i' or 'npm install' to download your node_modules.

The .gitignore file is configured in a way, so that it doesn't push 'node_modules' into GitHub, simply due to package sizes. This file should also not be tempered with. I personally do not know if there are any advantages by making changes to the .gitignore file, but as it stands there is no need for it either. Everything works perfectly fine this way, even if it is a bit tedious.

If you do not have the Node Package Manager yet, simply go to https://nodejs.org/en/download/package-manager and use the pre-built installer for Node.js. Once that is done you should be able to run the 'npm i' command in your directory. Doing this will install all neccessary packages that are listed in the package.json file automatically. This might take a minute or two.

Now in the second step and this is where it gets tedious, you will need to change ALL the files in the 'ui' folder (that aren't in the 'node_modules' directory or are images) from CRLF to LF. This will change the files from windows-mode to unix-mode. Since Vue comes with and needs a Linter and a Formatter to properly work, I chose ESLint + Prettier. This is important. Prettier requires the user to change to unix-mode in order to work properly. If you do not want to do this manually in VSC, you can use your command shell to switch into the 'ui' directory and run 'npx eslint --fix' followed by 'npx prettier --write'. If this option does not work for you, you will need to this manually for EVERY FILE!
For that open VSC and you will notice the blue bar on the bottom of the application. It should sit just above your quick-access-bar on Windows systems and you will find a couple of options. From the right to the left that should be:

*A bell icon
*Prettier
*The copilot icon
*A key icon
*Markdown
*CRLF
*...and so on

The ONLY thing you need to click is the CRLF (windows mode). If you do an option will pop-up from the top of VSC prompting you to choose between LF (unix-mode; the top option) and CRLF. Choose LF. Do this for every file and save the file immidiately after. In some cases you will still get your code highlighted in some way shape or form, by Prettier. In this case either open a terminal in VSC and navigate to the 'Problems' tab or hover over one of the underlined code snippets and click on the option 'show quick-fixes'. Regardless of which way you choose to do so, click the option 'fix all auto-fixable problems'. This will prompt Prettier to auto-format your code and bring it in-line with the document file. In my personal experience: This is much cleaner than manually structuring my code, which is why I use Prettier to begin with.

Now for the last step. In order to run the application you will need to proceed in this exact order:
1. start the xampp control panel.
2. press 'Start' next to the Apache server option.
3. press 'Start' next to the MySQL server option.
4. Go back into VSC and open up a new terminal if you have closed your previous commandshell (also possible from the regular Windows Powershell).
5. Type 'cd ui' (if you are still in Windows Powershell, you should still be in this directory given you didn't leave it or close the shell).
6. Type 'npm run serve' (if you followed my steps dilligently it should now build a tree and present you with several options).
7. Hold down the CTRL button now and leftcklick the URL next to 'Local:' (the top option of the two), you will now be redirect you into your browser.

You are now able to browse the Web Page of the deckbuilder_archive. Once you are finished close the browser window, go back into your console and press CTRL + c. This will prompt with Y/N. Enter 'y' into the console and press 'Enter'.
The Vue Server is now shut down properly. After that open up the xampp-control panel and stop the MySQL database before stopping the Apache server.
Be mindful when opening and closing the project! I have irrepairably destroy more than one server environment and had to completely reinstall and setup xampp again, by not respecting this step-by-step process. I do not know why, but this is the only way I have encountered 0 issues with my environment.


**How do I use your deckbuilder_archive?**
Well you are now able to browse the deckbuilder_archive. You will first start on the cover page and can work yourself through the different links and options available in the navbar and the footer, but you will also find certain calls-to-action on a variety of the pages and you will ideally be prompet by a pop-up upon entering the web page, that will ask for your consent, when it comes down to data privacy. Outside of several different links leading to different services on the page, you can also create an account and by extension login to that account, logout of it and change your account data or delete your data entirely. 
While logged-in you will also notice that the options inside the 'Account' dropdown menu will change based on your logged-in-status. This will allow you to then create a decklist, enter the sandbox, switch between your decklists and settings, and update or delete any of the information presented there.
You can also browse through the About Page and Imprint Page to get a feeling for how they look and work. Using the links in the footer for example will direct you towards their respective paragraphs on the Imprint Page, containing what an Imprint Page should legally contain, to my knowledge. The information presented in the About and Imprint Pages represent my stance towards the usage of the project as well as my work on the project. 