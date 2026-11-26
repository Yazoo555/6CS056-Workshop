
# **Overview** 

In Workshop 2, we built a Laravel MVC application for managing Courses and Students. 

In this workshop, we will extend that application with authentication and authorization. 

The application will now support: 

- User registration 

- User login and logout 

- Course ownership 

- Authorization of course operations 

- Shared Student entities 

- An administrator role 

- Administrator access to all Courses and Students 

- Authorization implemented using Laravel's Gates 

**Starting Point** : Workshop 2 

**Working Branch** : `Workshop 4` 

Laravel provides authentication and authorization features that can be integrated into an MVC application. In this workshop, authentication answers **"Who are you?"** , while authorization answers **"What are you allowed to do?"** 

_Delete any existing Courses from the database before working on this Workshop_ 

# **1. Create the Workshop 4 Branch** 

Start from the completed Workshop 2 implementation. 

```
git checkout Workshop2
git pull
git checkout -b Workshop4 Workshop2
```

Verify that you are working on the correct branch: 

```
git branch
```

You should see: 

```
* Workshop4
  Workshop2
```

1 / 11 

# **2. Workshop Scenario** 

Imagine that our Training Institute application is used by several staff members. 

Each staff member can: 

- Register an account 

- Log in to the application 

- Create courses 

- View their own courses 

- Edit their own courses 

- Delete their own courses 

- Create students 

- View students 

- Edit students 

- Delete students 

However, **courses belong to the user who created them** . 

Students are different. 

A student belongs to the **training institute** , not to an individual staff member. 

Therefore: 

**Courses are private to their owner, while Students are shared by all registered users.** 

Finally, the institute has an administrator. 

The administrator can: 

- View every course 

- Create courses 

- Edit any course 

- Delete any course 

- View every student 

- Create students 

- Edit any student 

- Delete any student 

This gives us the following authorization model: 

2 / 11 

|**Resource**|**Regular User**|**Administrator**|
|---|---|---|
|Own Courses|Create / Read / Update / Delete|Create / Read / Update / Delete|
|Other Users' Courses|No access|Full access|
|Students|Full access|Full access|
|User Registration|Yes|Yes|
|Login / Logout|Yes|Yes|



# **4. Add Authentication Controller** 

Implement registration and login for the application. 

- Create an empty `AuthController` 

- Add and implement the following methods in the `AuthController` and appropriate views in 

- `/resources/views/auth` folder 

   - `showLogin` : Should return the view for login form. 

   - `login` : Should handle the form submission from the login form 

   - `showRegister` : Should return the view for the register form 

   - `register` : Should handle the register form submission 

   - `logout` : Should handle the logout request (Make sure this is POST) 

Read below to understand how the App's registration, login and logout methods should behave. 

## **Registration Flow** 

A visitor should be able to register with at least: 

- Name 

- Email 

- Password 

- Password confirmation 

Use the `app/models/User` to store the information of the user after register. 

The password must **never be stored as plain text** . (Make sure the `User` model uses appropriate casts) 

After successful registration, the user should be authenticated and redirected to an appropriate page such as the dashboard. 

To login the user, use the `Auth::login($user)` method provided by Laravel's `Auth` facade. 

3 / 11 

To understand how the function works check the Tutorial Slide. 

## **Log In Flow** 

Create a login form containing: 

- Email 

- Password 

The login process should: 

1. Validate the submitted credentials. 

2. Attempt authentication. 

3. Regenerate the session after successful authentication. 

4. Redirect the authenticated user to the application. 

5. Return an appropriate error when the credentials are invalid. (Do not reveal if user exists or not) 

## **Logout Flow** 

Authenticated users must be able to log out. 

After logout: 

The authentication session must be invalidated. 

- The session token should be regenerated. 

Protected pages should no longer be accessible. 

# **5. Add the** **`AuthController` methods to route** 

- Update the `/routes/web.php` file and add appropriate routes for the `AuthController` methods. Use the 

- `Route::get("path", \[Controller::class, "method-name"\])` syntax 

- 

- When adding the routes, make sure the route for `showLogin` method is named `'login'` using the 

- `>name('login')` function 

# **6. Secure the Application's Endpoints** 

Not every page should be publicly accessible. 

Public pages may include: 

```
/
```

4 / 11 

```
 /login
```

```
 /register
```

Application functionality should require authentication: 

```
/courses
/courses/create
/students
/students/create
```

```
# You should secure other courses and students routes as well
```

Use Laravel's authentication middleware to protect these routes. 

For example: 

```
Route::middleware('auth')->group(function () {
    // Protected routes of courses and students & logout
```

```
});
```

```
Route::middleware('guest')->group(function () {
```

```
// Login & Register Routes
```

```
});
```

# **7. Update the** **`navbar` component** 

Open the file `resources/views/components/navbar.blade.php` 

- Use the `@auth` & `@guest` directives to show appropriates routes based on weather user is logged in or logged out 

- Logged out user should see the following links: 

   - Register 

   - Login 

- Logged in user should see the following: 

   - Courses 

Students 

- & Log Out button 

When implementing the logout button make sure, it is a submitting a POST request form. 

5 / 11 

# **8. Add a Role to Users** 

We now need to distinguish between normal users and administrators. 

Add a role to the `users` table. 

For example: 

```
role
```

Use at least two roles: 

```
user
admin
```

The default role for a newly registered user must be: 

```
user
```

A newly registered user must **not** be able to register themselves as an administrator. 

For example, this must not be accepted from the registration form: 

```
POST /register
role=admin
```

The application must determine the role itself. 

_You will need to create a new migration file for the table users and make the change to the schema to add new column for_ _`role`_ 

Use the following command to create migration file for `users` 

```
php artisan make:migration AddRoleToUser --table=users
```

# **9. Seed an Administrator** 

Run the artisan command to create a seeder file: 

```
php artisan make:seeder AdminSeeder
```

Implement the seeder with password hashing: 

6 / 11 

Open `database/seeders/AdminSeeder.php` and update the `run()` method. 

```
<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
classAdminSeederextendsSeeder
{
publicfunctionrun(): void
{
User::updateOrCreate(
[
"name"=>"Admin",
"email"=>"admin@example.com",
"password"=> Hash::make("Admin@123"),
"role"=>"admin"
]);
}
}
```

Add the `AdminSeeder` to `database/seeders/DatabaseSeeder.php` 

```
<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
classDatabaseSeederextendsSeeder
{
use WithoutModelEvents;
publicfunctionrun(): void
{
$this->call([
AdminSeeder::class
```

7 / 11 

```
]);
}
```

```
}
```

Run the following to seed the admin user to the database: 

```
php artisan db:seed
```

_Make sure db is migrated before seeding_ 

# **10. Associate Courses with Users** 

Courses created in Workshop 2 were not associated with a user. 

Modify the Course model and database schema so that every course belongs to a user. 

The database relationship should be: 

```
User 1 ─────────── * Course (One to Many) / (Course belongs to User)
```

A course should contain a foreign key such as: 

```
user_id
```

_To add this foreign key, you need to create a migration file for updating the Course entity and add the schema changes there_ 

Run the following command to create a migration file: 

```
php artisan make:migration AddUserIdForeignKeyInCourse --table=courses
```

After the migration is done, update the Eloquent models with corresponding relationships 

In `User` Model add: 

```
publicfunctioncourses()
{
return$this->hasMany(Course::class);
}
```

In `Course` Model add: 

8 / 11 

```
publicfunctionuser()
{
return$this->belongsTo(User::class);
}
```

# **11. Update the Course Controller** 

## **Creating Course** 

While create use the appropriate relationship to create 

Do not accept user_id from the browser as it is not trusted, only currently authenticated user should determine the owner 

Example: 

```
$request->user()->courses()->create($validated)
```

# **Course List** 

For normal function the course list endpoint should only show the courses created by that user and admin should be able to view all the courses. 

Example: 

```
$request->user()->role==="admin"? Course::latest()->get()
: $request->user()->courses()->latest()->get();
```

# **12. Using gates for access control** 

This workshop walks you through using Laravel `Gates` for centralizing authorization rule 

You can use `policy` for centralizing the Authorization rule as well. (You can research and implement If Interested) 

A Gate provides a central place to define an authorization rule. 

The main rule for Courses is: 

```
Admin → can manage any course
User  → can manage only their own courses
```

Define a Gate named: 

9 / 11 

```
manage-course
```

For example: 

```
Gate::define('manage-course', function (User$user, Course$course) {
return$user->role==='admin'
||$user->id===$course->user_id;
});
```

_Add the above code in the app/Providers/AppServiceProvider.php and you will need the appropriate imports for_ _`Gate` ,_ _`User` , and_ _`Course` ._ 

The important point is that the authorization rule should be defined **once** and reused throughout the application. 

# **13. Authorize Course Operations with the Gate** 

The Course Gate must be checked whenever a user performs an operation on an existing Course. 

This includes: 

```
show
edit
update
destroy
```

For example, in a controller methods use before executing the action: 

```
Gate::authorize('manage-course', $course);
```

# **14. Test the application** 

Take screenshots of each test case: 

   - **Register successfully** — valid name, email, and password creates a new user. 

- **Registration validation** — duplicate email, invalid email, missing fields, and mismatched passwords are 

- rejected. 

   - **Default role** — newly registered users receive the `user` role. 

   - **Login successfully** — valid credentials authenticate the user. 

   - **Invalid login** — wrong email or password is rejected. 

   - **Protected routes** — unauthenticated users cannot access Courses or Students. 

10 / 11 

- **Logout successfully** — after logout, protected pages can no longer be accessed 

- **Session is regenerated** — Session is regenerated after re login 

- **Password security** — passwords are stored hashed, never as plain text. 

- **Owner Isolation** — User A cannot edit / view / delete courses created by user A 

- **Admin Has All Access** — Admin can manage courses of every user 

- **Correct Owner Id Set** — When creating course correct user_id is saved 

# **15. Commit and Push** 

Commit the changes and push to Workshop4 branch 

```
git add .
git commit -m "Commit message"
git push -u origin Workshop4
```

# **16. Create the report of testing** 

Create a word document with screenshot of each test case 

# **17. Submit in MST** 

Submit the document along with the github url on MST. 

11 / 11 

