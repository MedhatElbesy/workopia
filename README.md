# Workopia
 Workopia is a job listing website. It includes a custom Laravel-like router, controller classes, views, a database layer and a project structure using namespaces and PSR-4 autoloading. It highlights how to structure a PHP project without using any frameworks or libraries.

 # Project Structure and Notes
**Custom Laravel-like router** :

Creating a route in routes.php looks like this:
$router->get('/lisings', 'ListingController@index');
This would load the index method in the App/controllers/ListingController.php file.

**Authorization Middleware**:

You can pass in middleware for authorization. This is an array of roles.
If you want the route to be accessible only to logged-in users, you would add the auth role:
$router->get('/listings/create', 'ListingController@create', ['auth']);
If you only want non-logged-in users to access the route, you would add the guest role:
$router->get('/register', 'AuthController@register', ['guest']);

**Public Directory**:

This project has a public directory that contains all of the assets like CSS, JS and images.
This is where the index.php file is located, which is the entry point for the application.

**Framework Directory**:

All of the core files for this project are in the Framework directory

**Namespaces**:

project uses namespaces for all of the classes

**App Directory**:

The App directory contains all of the main application files like controllers, views, etc.






