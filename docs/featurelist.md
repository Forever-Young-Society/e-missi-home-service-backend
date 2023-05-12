# Featurelist
-------------------------------------------------------------------------------------

### Best Practice

1. **Send Email to Admin on Register of User.**
	- When a user signup from your platform you will receive an email about the user. By this way to provide you the information about each user registered in your platform. As an owner its your right to know if user is a spammer or a valuable lead.

2. **HTML Page Schema.**
	- We do document the database schema in a seprate file ends with `.sql` where we put all the information about the application data model. As big data continues to grow, database objects and schemas are critical to ensure efficiency in day-to-day company operations. If relational models are poorly organized and poorly documented, they will be harder to maintain, posing problems for both its users and the company.

3. **Access Rules for all routes.**
	- By adding this feature we simply restrict the different roles of user to access the resouces. By this way we make sure and secure the information from attackers and sql injection.

4. **HTTPS Method / Verb Filters.**
	- VerbFilter is an action filter that filters by HTTP request methods. It allows to define allowed HTTP request methods for each action and will throw an error when the method is not allowed.

5. **Junk data cleanup**
	- Delete related all is a best practice we follow while deleting an entity or model, it should delete all the related data of that entity. It helps us to make sure to save machine resources.

6. **Remove Commented Code.**
	- While code review we make sure meaningless code should be removed from the application. We keep only those commented code that pass some kind of information to other developers.

7. **Remove Extra Files.**
	- A lot of junk files are added sometimes while devlopment phase like css, js and some media files that is not going to do any job on production, we make sure that all those files and folders are being deleted.

8. **Use ajax validation (no client side validation).**
	- Its a time saving feature that tells the user to put data according to validation as without ajax validation user have to submit the form to check the validation.

9. **Admin settings.**
	- Its an administrator panel with all the acces give to the admin so that, admin can keep an eye over the data flow and report.

10. **Caching**
	- Caching is a technique to save machines resources and peoples time. We make sure over the production server caching is enabled and loads website faster.


### Security and Privacy

1. **Password Strong Validation.**
	- We strongly practice user information and its privacy not to be stolen while developing any application. Password strong validation is one of our application feature, we make sure while inserting password it should be hard to guess by attacker.

2. **Check htaccess Rules.**
	- We make there should be htaccess file in the root file so that one cannot directly look in to our application directory stucture and rules.

3. **Cross-Site Request Forgery (CSRF) protection**
	- A [CSRF](https://en.wikipedia.org/wiki/Cross-site_request_forgery "CSRF")  attack is a kind of attack on a web application, where the attacker's goal is to cause an innocent victim to unknowingly submit a maliciously crafted web request to a website that the victim has privileged access to. We put validation where we track that attack on the runtime and never complete the request.

4. **CORS**
	- Cross-origin resource sharing (CORS) is a browser mechanism which enables controlled access to resources located outside of a given domain. It extends and adds flexibility to the same-origin policy (SOP). However, it also provides potential for cross-domain attacks, if a website's CORS policy is poorly configured and implemented.

5. **Encode echo content (security issue-  hacking).**
	- It's a security feature we provide to prevent from xss attack. One can not put any script on user input data and put off our web application on runtime.

6. **Password Encryption**
	- As other typical application that uses md5(too old) encryption method is not secure to keep our application from attackers. There was a time when using the aforementioned hashing algorithms was sufficient, but modern hardware makes it possible to reverse such hashes very quickly using brute force attacks. In order to provide increased security for user passwords, even in the worst case scenario (your application is breached), you need to use a hashing algorithm that is resilient against brute force attacks. We use best encryption method called `crypt` method with cipher `AES-128-CBC`.

7. **HTTPS**
	- We make sure production server that all requests like http request, media files, asset files should be served over https enabled.

### Seo

1.  **SiteMap.**
	- We provide sitemap to blueprint of your website that help search engines find, crawl and index all of your website’s content. Sitemaps also tell search engines which pages on your site are most important.

2.  **+MOD Rewrite.**
	- Rewriteengine is very important for search engine optimization because the routines of search engines are designed to find well structured and human-readable URL trees.

3. **Meta Tags/SEO module.**
	- META TAGS for SEO module allows to deploy illimited & customized META tags (mainly META Keywords) everywhere on your website or on specific pages/posts/custom post types/product pages depending on your content (for SEO purpose).

### Core Features

1. **Logger.**
	- Logging is essential to understand the behaviour of the application and to debug unexpected issues or for simply tracking events. In the production environment, we can’t debug issues without proper log files as they become the only source of information to debug some intermittent or unexpected errors/exceptions.

2. **Login History.**
	- We put a seprate feature of login histories inside our application. By using this we can track how often user login to our application. Also we can track how often user put the wrong password or if an attacker is trying to login.

3. **Database Backup.**
	- We provide a backup feature in our web application to prevent the unwanted data loss. We have built in support of periodic backup like `daily`, `weekly`, `monthly` and also open to support as per requirement.

4. **Shadow Portlet.**
	- We provide a feature that give admin ability to access data on behalf of its users. By using this admin view look and feel as well as what things are allowed to their users according to their roles.

5. **Email Queue.**
	- Its a core feature of our web application where we provide and access to see the flow of emails and its delivery. It is being access by Admin.

6. **Activities.**
	- Activities is an another essential core feature that keeps the activities of user inside our application.

7. **Setting**
	- Where we put the essential config informations like email, sms gateway etc can be store and can be changed by admin itself.

8. **Installer**
	- By using this feature we make sure one can install the website over server or localserver with a minimal knowledge about server.

9. **Seo**
	- By using this feature our application can generate sitemap dynamically. Also admin can change the meta description and other meta info directly by using seo module.

10. **APIs**
	- Api module is our essential module where we use REST method to develop api's and create mobile application for the web application. Its a swagger enabled module so that no need to use or install other application to consume and test API's.

11. **SMTP**
	- SMTP is another core feature of our web application where one can just plug the module and start using SMTP. There is inbuilt **GUI** config window with one can change the config on the fly. Our smtp module support almost every email hosting provider like **[jiWebHosting](https://jiwebhosting.com/ "jiWebHosting")**, gmail, sendgrid, amazon ses etc. We have inbuilt support of **SSL** and **TLS** support that encrypt all the emails that is going through our smtp module. Email queue is inbuilt so that you can see the flow of outgoing emails. 