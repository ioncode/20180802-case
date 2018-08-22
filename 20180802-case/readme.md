# Repository contents

 - simple demo app, based on symfony 4.1 stack with minimum external bundles & modules.

this app developed in Windows X64 env, using PHP 7.2.8 X64 stable build & Mysql 8.0.12


# Registration

![demo registration](https://i.gyazo.com/4a0bd3adfaad1e9186540826875cb952.png)  
[See demo registration on Gyazo](https://gyazo.com/90c8dc79449b0ef266f3f89646deb35e)

# Login 

![demo login](https://i.gyazo.com/2b15048b1bbb4b664b68c590233a7dd2.png)  
[Preview demo login](https://gyazo.com/2d08214ee7a7c329543cc0c1c53e45cb)

#Separated ORM configuration

 [../config/doctrine/](https://github.com/ioncode/20180802-case/tree/master/20180802-case/config/doctrine) folder contains models against Symfony's default Annotation syntax  

# Security 

I think this subject is not covered so good by many other 'scripters', configurations, bundles, examples 

 - firewall configuration stored in [../config/packages/security.yaml](https://github.com/ioncode/20180802-case/blob/master/20180802-case/config/packages/security.yaml)
 
 - guard authentication ([more in docs](http://symfony.com/doc/current/security/guard_authentication.html))

 - If your current provider stores hashes in one of compatible algorithms you can use new [Argon2](https://framework.zend.com/blog/2017-08-17-php72-argon2-hash-password.html) in the same time! Look at my test DB [here](https://gyazo.com/eb90ee97bc5ce4b3c2694a6341388256).    

 - [csrf](https://en.wikipedia.org/wiki/Cross-site_request_forgery) protection

 - token & session auth# management for forms, links and direct requests
 
# Bootstrap 4 ready 

 - look in [/templates](https://github.com/ioncode/20180802-case/tree/master/20180802-case/templates)
 - if you want to change template, please look at /config/packages/twig.yaml 
 

# Webpack Encore   

 - for development build use 'yarn encore dev --watch'

 - for production - 'yarn encore production'
 
 - application frontend codebase /assets/js/app.js 




# Wait for updates! 