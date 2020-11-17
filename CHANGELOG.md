# ✒ CHANGELOG


#### Version: 1.2.1 > 1.2.2
``Date 17/11/2020 15:12``
* Fix Logout Permissions for Members/Admins Roles
* Fix menuactive undefined variable in Sidebar.volt (Menu active feature disabled temporary)
* Fix Admin Permissions to access to member dashboard


#### Version: 1.2.0-alpha > 1.2.1  
``Date 02/11/2020 15:12``

* Update Project Structure
* Move from NameSpace SakuraPanel to Sakura
* Move to Bootstrap Application
* Create Providers ( Sessions, Cookies, Router, Views, ...) 
* Use phinx for migration generate and run
* Upgrade Router (Create groups admin, auth, member)
* Upgrade ACL ( Using database for permissions & roles ...)
* Autoloader psr - use app/ instead of each folder inside (Controllers,Models..)
* Move Cache,Logs,Uploads to Storage/