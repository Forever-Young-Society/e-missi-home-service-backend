These are the points to be included and should be checked before releasing a project
-------------------------------------------------------------------------------------

- SiteMap module should be enable.
- +MOD Rewrite check.
- URL Manager - for components use TUrlManager.
- Logger Module.
- Send Email to Admin on Register of User.
- Meta Tags/SEO module.
- Schema.
- Check Access Rules.
- ADD VERB FILTER IN EVERY ACTION.
- Check isAllowed Function.
- Use deleteRelatedAll in beforeDelete in every model.
- Password Strong Validation.
- Remove Commented Code.
- Remove Extra Files.
- Check htaccess Rules.
- In every folder of root of project except themes and assets.
- www. and without www should be merge.
- Login History.
- Enable csrf.
- Database Backup.
- Encode echo content (security issue-  hacking).
- Download Action With File Name should Not be used.
- Use ajax validation (no client side validation).
- Admin settings.
- Shadow Portlet.
- Email Queue.
- remove any unused file css and js libraries if not required ( eg. remove Slider js library from other pages except index ).
- Caching

- SMTP Modules
- Setting modules
- Send email to admin on every user registration
- Enable cache on config file, and make sure caching added on code
- Caching is a technique to save machines resources and peoples time. We make sure over the production server caching is enabled and loads website faster.
- Debugger
- Check duplicate queries through debugger tools
- Duplicate queries are not allowed otherwise project will not be released to the client if found any duplicate query
- Check excection time, it must not exceed 100ms
- Check readme.md file of each existing modules and update accordingly
- Follow cross loaded rules
- Cross loaded- Before release of the project, get it test by another developer. Mandatory to fix all the cases given by another developer if valid.
- Remove debugger tools from downbar, it's not required.
- Use storage modules in every project, all files will be store in cloud storage(like s3 bucket).
- Use setting modules for storage of variables.
- Docker must required in every new projects those who start from scractch.
- Admin can add more than thousands of data through command line for testing purpose, it is mandatory for all the projects
- DB Schema is required for every project


Only for Inhouse Projects:
------------

- Analytics.
- Internal Ads.
