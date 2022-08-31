## Laravel CRUD API with JWT Auth
Laravel CRUD API application included with Authentication Module & Product Module. It's included with JWT authentication.

### Language & Framework Used:
1. PHP-8
2. Laravel-9

### How to Run:
1. Clone Project - 
2. Create `.env` file & enter database details
3. Go to the terminal run following commands:
    -   `cd {root directory}` 
    -   php artisan migrate
    -   php artisan serve 
4. get site_url e.g http://127.0.0.1:8000 

### API List:
##### Authentication Module
1. Register User API with Token
    Method   : POST 
    Endpoint : /api/auth/register 

    The register method is used to create a user when /api/auth/register route is called. First, user values such as name, email and password are validated through the validation process, and then the user is registered if the user credentials are valid. Then, it generates the JSON Web Token to provide valid access to the user.

2. Login API with Token
    Method   : POST 
    Endpoint : /api/auth/login 

    The login method is used to provide access to the user, and it is triggered when /api/auth/login API is called. It authenticates email and password entered by the user in an email and password field. In response, it generates an authorization token if it finds a user inside the database. Vice versa it displays an error if the user is not found in the database.

3. Authenticated User Profile
    Method   : POST 
    Endpoint : /api/auth/user-profile 

    The userProfile method renders the signed-in user’s data. It works when we place the auth token in the headers to authenticate the Auth request made through the /api/auth/user-profile API.

4. Refresh Token
    Method   : POST 
    Endpoint : /api/auth/refresh

    The refresh method creates a new JSON Web Token in a shorter period, and It is considered a best practice to generate a new token for the secure user authentication system in Laravel. It invalidates the currently logged in user if the JWT token is not new.

5. Logout
    Method   : POST 
    Endpoint : /api/auth/logout

    The logout method is called when /api/auth/logout API is requested, and it clears the passed JWT access token.

##### Product Module

NOTE : Authorization: {{Bearer Token}} is required in header

1. Product List (Get list of all products)           
    Method   : POST 
    Endpoint : /api/products 

2. Create Product (Create New Product )        
    Method   : POST 
    Endpoint : /api/products

3. Edit Product 
    Method   : PUT 
    Endpoint : /api/products/{id} 

4. View Product  (View single product)        
    Method   : GET 
    Endpoint : /api/products/{id} 

5. Delete Product  
    Method   : DELETE 
    Endpoint : /api/products/{id} 



