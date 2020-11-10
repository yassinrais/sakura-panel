<p align=center>
  <img src="https://i.imgur.com/Hgh7vem.jpg" >
  <h1 >Sakura <small>v 1.2.1</small></h1>
</p>

<p align="center">
  <a href="https://discord.gg/YGQcNcX">
      <img src="https://discordapp.com/api/guilds/733309225911975957/embed.png" alt="Discord Server"/>
  </a>
  <a href="https://github.com/yassinrais/sakura-core/blob/master/LICENSE">
    <img alt="License: MIT" src="https://img.shields.io/badge/License-MIT-yellow.svg" target="_blank" />
  </a>
</p>

## ⚠ TODO

- UPGRADE PLUGIN ROUTER
- UPGRADE MEMBER MENU
- UPGRADE TO USE MULTI TEMPLATES

## 📗 About

<p>Sakura panel , A script made with PHP ( <a href="https://phalcon.io/" target="_blank">Phalcon FrameWork</a> ) and can run on both apache and nginx or any php service. </p>
<p>The idea of this panel is to make it possible to manage your website, server, bot or anything you want just by adding plugins that allow you to do that without touching the main source code of the panel making it faster more secure and more reliable with a fantastic easy on eyes ui.</p>
<p style="font-style: italic;">This panel idea started as a normal panel for <a href="https://github.com/yassinrais/sakura-core/" target="_blank">Sakura Core</a> but it ended up something even bigger ... </p>

## ⚙️ Installation :

1. First you need to install php v 7.2+ and Phalcon v 4.0.1+ in your _Machine_
2. Then execute `git clone https://github.com/yassinrais/sakura-panel.git` repository into your _machine_
3. Run `composer install` to install php dependencies (vendor)
4. (**For Unix OS**) Run `chmod +x ./sakura-cli.sh` then `./sakura-cli.sh install` With _cli/bash_
   Or if you're (**a Windows OS**) Run `sakura-cli install` with _cmd/powershell_
5. Run `sakura-cli adduser` to add a new user as administrator.
6. Enjoy ! Sakura Panel ♥

## ➕ CLI Usage :

- Install Script `sakura-cli install`
- Add New Admin `sakura-cli adduser`
- Create Plugin `sakura-cli create-plugin`

## 👩🏾‍💻 Setup Local Development Environment Using Docker:

### Installation :

1. You need to have _Git and Docker Desktop_ installed on your _Machine_

2. Execute `git clone https://github.com/yassinrais/sakura-panel.git`

3. Run `cd sakura-panel` to get into the created directory

4. Run `docker build -t lamp .` to create a docker image that includes Ubuntu 18.04 along with a LAMP stack (Apache, Mysql and PHP)

5. Create and start a docker container, It will also link the app and persist the database:

   - `docker run --name sakura-panel -p "80:80" -v ${PWD}:/app -v ${PWD}/mysql:/var/lib/mysql lamp`

   - (**For WINDOWS OS**) `docker run -i -t --name sakura-panel -p "80:80" -v %cd%:/app -v %cd%/mysql:/var/lib/mysql lamp`

6. Run `docker exec -it sakura-panel composer install` to install the dependencies

7. Run `docker exec -it sakura-panel mysql -uroot -e "create database sakura_panel"` to create a new database

8. Run `docker exec -it --user www-data sakura-panel php sakura-cli install` it will run the installation script and help you to configure sakura-panel

9. Run `docker exec -it --user www-data sakura-panel php sakura-cli adduser` to add a new user as an administrator

10. Voila 🎉

### Usage :

- To start your container use `docker start -a sakura-panel`
- To stop your container use `docker stop sakura-panel`
- To create a plugin you should run `docker exec -it --user www-data sakura-panel php sakura-cli create-plugin`
- To add a new user you should run `docker exec -it --user www-data sakura-panel php sakura-cli adduser`
- To get into the container CLI `docker exec -it sakura-panel bash`

**The docker image is based on [docker-lamp](https://github.com/mattrayner/docker-lamp) feel free to have a look at it**

## 👥 Contributors :

- Yassine Rais | Dev | [GitHub](https://github.com/yassinrais) [Website](https://neutrapp.com)
- thisissobhy [GitHub](https://github.com/thisissobhy)

## 📝 License :

Please see the **[LICENSE](LICENSE)** included in this repository for a full copy of the MIT license, which this project is licensed under.

## 📷 ScreenShot(s) :

<img src="https://i.imgur.com/CtrHowg.png" style="wdith: 100%">
<img src="https://i.imgur.com/Yh5qTzF.png" style="width: 100%">
<img src="https://i.imgur.com/ikhXqAv.png" style="width: 100%">
<img src="https://i.imgur.com/I1owzuW.png" style="width: 100%">
<img src="https://i.imgur.com/xhdwP7G.png" style="width: 100%">
<img src="https://i.imgur.com/Zd0llYV.png" style="width: 100%">

<h4>For more screenshots <a href="https://imgur.com/a/Sz0G95m" target="_blank"> click here </a> - hosted in imgur.com !</h4>
