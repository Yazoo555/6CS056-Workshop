# **Scenario**

We are continuing to build the application for a **training institute**.

In Week 1 we built the first working version of the Training Institute Management System. The application can now:

* Create, view, edit and delete Students

* Create, view, edit and delete Courses

* Validate form input

* Store the information in MySQL

# **Let's Start with Week 2**

Week 2 focuses on organizing the application we built in Week 1\.

At this stage, we are **not adding new features**. The application should behave exactly as it does today, but it will get a navigation bar and a footer.

Instead, we want to focus on the simple question:

### **How can we organize a web application so that we never write the same code twice?**

To answer this question, we need to understand:

1. MVC Architecture

2. Controllers and Resource Routes

3. Layouts and Partials

4. Blade Components

5. The DRY principle (Don't Repeat Yourself)

# **Problem Definition**

The Week 1 application works, but it is hard to maintain.

Every request is handled by a closure inside we will quickly have a huge web.php file.

. If we continue building applications in this format

The other problem is we have to add a navigation bar and footer to the application. This navigation bar and footer are going to be repeated in every page. We want to organize our view so that we can use layouts to structure the  
view properly.

| Problem | Where you can see it in Week 1 |
| :---- | :---- |
| Everything in one file | All Student routes, validation and queries are inside routes/web.php |

| Problem | Where you can see it in Week 1 |
| :---- | :---- |
| Duplicated validation | The create and update routes repeat almost the same rules |
| Hard-coded URLs | Views contain links such as /students/create |
| Repeated page HTML | Every view repeats \<\!DOCTYPE html\> , \<head\> and the error block |
| Duplicated forms | The create and edit forms are almost identical |
| Repeated buttons | Every list page repeats the same delete form |

In Week 2 we **refactor** the application. We keep the same behavior and apply two ideas:

* **MVC:** every piece of code lives in the layer where it belongs.

* **DRY (Don't Repeat Yourself):** every piece of code exists in **one place only**. If it changes, you change it once.

# **Goal & Deliverables**

By the end of Week 2, your application should behave like Week 1, but be organized using MVC and DRY:

and	handle all request logic

contains only a few lines and no business logic

* Named routes are used instead of hard-coded URLs

* Validation rules are written once in each controller

* All pages share one layout with a **navigation bar** and a **footer**

* Reusable **Blade components** for alerts, form fields and delete buttons

* One shared form partial for create and edit

# **Week 2 Checklist**

By the end of the workshop, you should be able to:

* Create controllers using Artisan

* Use	, named routes and route model binding

* Create a layout with a navbar and a footer

* Create reusable Blade components with

* Reuse one form partial for create and edit

* Decide which layer a piece of code belongs to

# **What is MVC?**

**MVC (Model–View–Controller)** splits the application into three parts, each with one job.

| Layer | Responsibility | Location |
| :---- | :---- | :---- |
| **Model** | The data and the database |  app/Models/ |
| **View** | The HTML the user sees |  resources/views/ |
| **Controller** | Receives the request, asks the Model for data, returns a View |  app/Http/Controllers/ |

Routes are not one of the three letters. A route only maps a URL to a controller method.

A request travels like this: **Browser → Route → Controller → Model → Controller → View → Browser**.

# **What is DRY?**

**DRY** means **Don't Repeat Yourself**. If you copy and paste the same code into many files, a small change has to be made in every copy, and you will forget one.

Blade gives us three tools to avoid repetition:

| Tool | Use it for | Example in this workshop |
| :---- | :---- | :---- |
| **Layout** ( @extends ) | The page skeleton shared by all pages |  layouts/app.blade.php |
| **Partial** ( @include ) | A fixed chunk of HTML |  partials/navbar.blade.php |
| **Component** ( \<x-...\> ) | A reusable piece that takes inputs |  \<x-form-input name="email" /\> |

# **Step 1: Prepare the Workshop 2 Branch**

Workshop 2 builds on top of the Workshop 1 code, so first make sure Workshop 1 is complete and committed. Open your WSL terminal and go to your workshop repository:

should say there is nothing to commit. If not, commit your Workshop 1 work first.

Now create the Workshop 2 branch from it:

Start the server and confirm that Student CRUD still works before we change anything:

**Checkpoint:** If something breaks later, you know it was your refactoring and not the original code.

# **Step 2: Create the Student Controller**

A controller is a PHP class. Each public method handles one kind of request. Create it with Artisan:

Open the file and add the imports that we will need:

Every CRUD operation from Week 1 will become one method of this class. Do **not** delete the closure routes yet. The application keeps working while we build the controller.

# **Step 3: Move the Read Logic (index and show)**

Inside the

the location has changed.

class, add these two methods. The code is the same as the Week 1 closures, only

The controller asks the **Model** for data ( itself.

) and hands it to the **View**. It does not build any HTML

# **Step 4: Move the Create Logic (create and store)**

Add two more methods.

shows the empty form and

saves it:

**Note:** We added error. The route name

. In Week 1 a duplicate email passed validation and caused a database is defined in Step 7\.

# **Step 5: Move the Update Logic (edit and update)**

Add two more methods.

shows the form filled with the existing data and

saves the changes:

**Note:** In Week 1 the

class was used without being imported. That is why we added at the top of the controller.

# **Step 6: Move the Delete Logic (destroy)**

Add the last method:

**Checkpoint:** Your

now has seven methods:

,

,

,

,	,

the same.

and destroy . Compare each one with the matching closure in

. The logic should be

# **Step 7: Connect Routes to the Controller**

Now we replace the closures with routes that point to the controller. Open:

### **Delete every Student route from Week 1** (all the

closures for students). Keep the default welcome route.

Make sure the file starts with these imports:

and

Add the Student routes:

typing

gives the route a name, so we can write

.

instead of

### **Watch out:**

as an

.

must be defined **before**

. Also, Week 1 used

. Otherwise Laravel treats the word (singular). From now on all URLs use the plural

Refresh	and test the application. It should behave exactly as before.

# **Step 8: Simplify with Route::resource()**

The seven routes above follow a standard pattern. Laravel can generate all of them with one line. Replace the seven Student routes with:

Check what Laravel created:

| Method | URI | Controller method | Route name |
| :---- | :---- | :---- | :---- |
| GET |  /students |  index |  students.index |
| GET |  /students/create |  create |  students.create |
| POST |  /students |  store |  students.store |
| GET |  /students/{student} |  show |  students.show |
| GET |  /students/{student}/edit |  edit |  students.edit |
| PUT/PATCH |  /students/{student} |  update |  students.update |
| DELETE |  /students/{student} |  destroy |  students.destroy |

The URLs and names are the same as Step 7, so nothing changes in the application. The only difference is that the

parameter is now called	.

# **Step 9: Use Named Routes**

Hard-coded URLs break when a URL changes. Named routes are defined in one place and every link follows them. In Blade, always use route() :

| Instead of | Write |
| :---- | :---- |
|  /students |  {{ route('students.index') }} |
|  /students/create |  {{ route('students.create') }} |
|  /students/{{ \$student-\>id }} |  {{ route('students.show', \$student) }} |
|  /students/{{ \$student-\>id }}/edit |  {{ route('students.edit', \$student) }} |
| form action to save |  {{ route('students.store') }} |
| form action to update |  {{ route('students.update', \$student) }} |
| form action to delete |  {{ route('students.destroy', \$student) }} |

You do not need to edit the old views one by one. All Student views are rewritten in Step 14 using route() .

# **Step 10: Route Model Binding**

Four controller methods start with the same line:

Laravel can do this for you. If a controller parameter is typed as a model and its name matches the route parameter

(	), Laravel finds the record automatically, and returns a 404 page if it does not exist.

Update the four methods in	:

public function show(Student \$student)

{

return view('student.detail', \[ 'student' \=\> \$student,

\]);

}

public function edit(Student \$student)

{

return view('student.edit', \[ 'student' \=\> \$student,

\]);

}

public function update(Request \$request, Student \$student)

{

\$validated \= \$request\-\>validate(\[

'name'	\=\> 'required\|string\|max:255',

'email'	\=\> \[

'required', 'email', 'max:255', Rule::unique('students')\-\>ignore(\$student\-\>id),

\]);

\],

'phone'	\=\> 'required\|string\|max:20', 'address'	\=\> 'nullable\|string\|max:500', 'date\_of\_birth' \=\> 'nullable\|date',

\$student\-\>update(\$validated);

return redirect()

\-\>route('students.show', \$student)

\-\>with('success', 'Student updated successfully\!');

}

public function destroy(Student \$student)

The parameter name in the method (

to see the 404 page.

) must match the route parameter (

). Visit

# **Step 11: Write the Validation Rules Once**

The validation rules are written twice, in

and in

. This is the **DRY** problem from the start of the

workshop. We fix it by moving the rules into **one private method** of the controller.

The only difference between the two cases is the email rule. When updating, the student's own email must be

ignored by the

check. We handle this with an optional

parameter.

Add this method at the bottom of	:

Then use it in

and

. This is the complete	:

namespace App\\Http\\Controllers;

use App\\Models\\Student;

use Illuminate\\Http\\Request; use Illuminate\\Validation\\Rule;

class StudentController extends Controller

{

public function index()

{

return view('student.list', \[ 'students' \=\> Student::all(),

\]);

}

public function create()

{

return view('student.create');

}

public function store(Request \$request)

{

\$student \= Student::create(\$request\-\>validate(\$this-\>rules()));

return redirect()

\-\>route('students.index')

\-\>with('success', "Student {\$student\-\>name} created successfully\!");

}

public function show(Student \$student)

{

return view('student.detail', \['student' \=\> \$student\]);

}

public function edit(Student \$student)

{

return view('student.edit', \['student' \=\> \$student\]);

}

public function update(Request \$request, Student \$student)

{

\$student\-\>update(\$request\-\>validate(\$this-\>rules(\$student)));

return redirect()

\-\>route('students.show', \$student)

\-\>with('success', 'Student updated successfully\!');

}

public function destroy(Student \$student)

{

\$student\-\>delete();

return redirect()

\-\>route('students.index')

\-\>with('success', 'Student deleted successfully\!');

}

private function rules(?Student \$student \= null): array

{

return \[

'name'	\=\> 'required\|string\|max:255',

'email'	\=\> \[

'required', 'email', 'max:255', Rule::unique('students')\-\>ignore(\$student?-\>id),

\],

'phone'	\=\> 'required\|string\|max:20', 'address'	\=\> 'nullable\|string\|max:500', 'date\_of\_birth' \=\> 'nullable\|date',  
\];

}

}

returns only the validated fields, so it can be passed straight to

. If validation fails, Laravel redirects back with the errors and the old input. when creating, so nothing is ignored.

and is

# **Step 12: Give the Model Some Responsibility**

The Model is the right place for rules about the data itself, such as how a date is represented or how an age is calculated.

* The **cast** makes	a date object, so we can write	.

* The **accessor** creates a virtual attribute, so

works even though there is no

column.

Because of the cast, a date input needs	. The views in Step 14 already handle this.

# **Step 13: Component-Based Blade Layouts**

In this step, we replace repeated page markup with **Blade components**. Instead of repating same HTML content in

each page, pages will be composed with component tags such as	and	.

A component can encapsulate markup and accept inputs (props) or page content (a slot). This makes the structure explicit and keeps reusable UI in one place.

| Repeated code | Reusable component | How it is used |
| :---- | ----- | :---- |
| HTML document and page shell |  components/layouts/app.blade.php |  \<x-layouts.app title="Students"\>...  \</x-layouts.app\> |
| Navigation links |  components/navbar.blade.php |  \<x-navbar /\> |
| Footer |  components/footer.blade.php |  \<x-footer /\> |
| Success message |  components/alert.blade.php |  \<x-alert /\> |
| Label, input and validation error |  components/form-input.blade.php |  \<x-form-input ... /\> |
| Label, textarea and validation error |  components/form-textarea.blade.php |  \<x-form-textarea ... /\> |
| Delete form |  components/delete-form.blade.php |  \<x-delete-form ... /\> |
| Shared Student fields |  components/students/form.blade.php |  \<x-students.form ... /\> |

Create these files (folder names must match):

1. ## **Stylesheet**

\* { box-sizing: border-box; }

body {

font-family: Arial, sans-serif; margin: 0;  
min-height: 100vh; display: flex;  
flex-direction: column;

}

.navbar {

background: \#1f2937; padding: 14px 24px; display: flex;  
justify-content: space-between; align-items: center;  
}

.navbar a { color: \#ffffff; text-decoration: none; margin-left: 20px; }

.navbar .brand { font-weight: bold; font-size: 18px; margin-left: 0; }

.navbar a.active { border-bottom: 2px solid \#ffffff; }

.container { flex: 1; width: 100%; max-width: 960px; margin: 0 auto; padding: 24px; }

.footer { background: \#f3f4f6; color: \#555555; text-align: center; padding: 16px; font-size: 14px; }

.btn {

background: \#2563eb; color: \#ffffff; border: 0; border-radius: 4px; padding: 8px 14px; text-decoration: none; cursor: pointer; font-size: 14px;

}

.alert { padding: 10px 14px; margin-bottom: 16px; border-radius: 4px; }

.alert-success { background: \#e6f4ea; color: \#137333; }

table { border-collapse: collapse; width: 100%; margin-top: 16px; }

2. ## **Navigation and footer components**

Blade components are anonymous and are rendered with component tags.

is true on every Student page, so the current link is underlined automatically. Add the Courses link in Step 15\.

3. ## **Reusable UI components**

A component file's name determines its tag. For example,	is rendered with

.

underlying element.

declares named inputs, while

forwards additional HTML attributes to the

4. ## **The layout component**

The layout component owns the HTML document, shared navigation, success alert, and footer. The page-specific

content is supplied through Laravel's default **slot**. The	is a component prop.

A page now wraps its content in \<x-layouts.app title="..."\> ... \</x-layouts.app\> . This replaces the

traditional

pattern.

, @section('title', ...) ,

, and

# **Step 14: Rewrite the Student Views to Use Components**

All Student pages will now use the layout component. The shared Student fields are also made into a component, so create and edit pages do not need to repeat the same fields.

1. ## **Student list**

2. ## **Shared Student form component**

The component expects a	object. The create page passes a new empty model; the edit page passes the

existing model. A colon before an attribute (such as rather than a literal string.

) means the value is a PHP expression

3. ## **Create Student**

   4. ## **Edit Student**

   5. ## **Student details**

## **Checkpoint**

* Confirm that all component files exist under	.

  * Open	; the navbar, footer, and success alert should appear through the layout component.

    * Open the create and edit pages and confirm that the same Student fields render on both.

    * Confirm validation errors and old input values still appear after a failed submission.

**Key idea:** component-based layouts compose a page from reusable components and slots. The layout controls the shared page shell, while each page supplies its own content. Small components encapsulate repeated interface  
elements and receive data through props.

# **Step 15: Apply the Same Pattern to Courses**

Now repeat the process for Courses. Because the reusable pieces already exist, this step is much shorter than the Student work. Let Artisan generate the files:

This creates	with the seven resource methods, already typed with	.

1. ## **Route**

Add the import and the route in	:

2. ## **Model**

   3. ## **Controller**

Replace the generated methods with the code below. The validation rules are written once in the private

method, exactly like the Student controller. The values in of your Week 1 migration.

must match the

values

use Illuminate\\Http\\Request; use Illuminate\\Validation\\Rule;

class CourseController extends Controller

{

public function index()

{

return view('course.list', \['courses' \=\> Course::all()\]);

}

public function create()

{

return view('course.create');

}

public function store(Request \$request)

{

\$course \= Course::create(\$request\-\>validate(\$this-\>rules()));

return redirect()

\-\>route('courses.index')

\-\>with('success', "Course {\$course\-\>name} created successfully\!");

}

public function show(Course \$course)

{

return view('course.detail', \['course' \=\> \$course\]);

}

public function edit(Course \$course)

{

return view('course.edit', \['course' \=\> \$course\]);

}

public function update(Request \$request, Course \$course)

{

\$course\-\>update(\$request\-\>validate(\$this-\>rules()));

4. ## **Views**

Create the four views in delete button are reused.

. They are short because the layout, the form fields and the

\<x-form-input name="name" label="Name" :value="\$course-\>name" /\>

\<x-form-textarea name="description" label="Description" :value="\$course-\>description"

/\>

\<x-form-input name="duration" label="Duration (weeks)" type="number" min="1"

:value="\$course-\>duration" /\>

\<x-form-input name="fee" label="Fee" type="number" step="0.01" min="0"

:value="\$course-\>fee" /\>

\<div class="form-group"\>

\<label for="difficulty"\>Difficulty\</label\>

\<select id="difficulty" name="difficulty"\> @foreach(\['Easy', 'Medium', 'Hard'\] as \$level)  
\<option value="{{ \$level }}" @selected(old('difficulty', \$course-

\>difficulty) \=== \$level)\>

{{ \$level }}

\</option\> @endforeach  
\</select\>

@error('difficulty')

\<p class="error"\>{{ \$message }}\</p\> @enderror  
\</div\>

\<div class="form-group checkbox"\>

\<input type="hidden" name="is\_active" value="0"\>

\<input type="checkbox" id="is\_active" name="is\_active" value="1" @checked(old('is\_active', \$course-\>is\_active))\>  
\<label for="is\_active"\>Active\</label\>

\</div\>

The extra attributes

and

are passed straight to the

through

in the

component. The hidden input is needed because a browser does not send an unchecked checkbox at all: with it, an

unchecked box arrives as	and a checked box arrives as	.

@extends('layouts.app')

@section('title', 'Courses')

@section('content')

\<h1\>Courses\</h1\>

\<a href="{{ route('courses.create') }}" class="btn"\>Create Course\</a\>

\<table\>

\<thead\>

\<tr\>

\<th\>ID\</th\>

\<th\>Name\</th\>

\<th\>Duration\</th\>

\<th\>Fee\</th\>

\<th\>Difficulty\</th\>

\<th\>Active\</th\>

\<th\>Actions\</th\>

\</tr\>

\</thead\>

\<tbody\>

@forelse(\$courses as \$course)

\<tr\>

\<td\>{{ \$course-\>id }}\</td\>

\<td\>{{ \$course-\>name }}\</td\>

\<td\>{{ \$course-\>duration }} weeks\</td\>

\<td\>{{ number\_format(\$course-\>fee, 2\) }}\</td\>

\<td\>{{ \$course-\>difficulty }}\</td\>

\<td\>{{ \$course-\>is\_active ? 'Yes' : 'No' }}\</td\>

\<td\>

\<a href="{{ route('courses.show', \$course) }}"\>View\</a\>

\<a href="{{ route('courses.edit', \$course) }}"\>Edit\</a\>

\<x-delete-form :action="route('courses.destroy', \$course)" /\>

\</td\>

\</tr\> @empty  
\<tr\>

5. ## **Add Courses to the navigation bar**

Because the navbar exists in one file, one edit updates **every page**. Open

and add the second link:

**Checkpoint:** Test list, create, view, edit and delete for Courses, including the validation errors.

# **Step 16: Test the Refactored Application**

Restart the server and go through this list:

| Test | How | Expected result |
| :---- | :---- | :---- |
| Navbar | Click Students and Courses | The current link is underlined on every page |
| Footer | Scroll to the bottom of any page | The footer is visible on every page |
| List | Open /students | Students are shown, or "No students found." |
| Create (valid) | Fill the form correctly | Redirect to the list with a green success message |
| Create (invalid) | Submit an empty form | Errors under each field, old input kept |
| Duplicate email | Create two students with the same email | Validation error, no database exception |
| Edit | Change a name, keep the email | Update succeeds |
| Delete | Click Delete and confirm | Record removed, success message shown |
| Missing record | Open /students/9999 | 404 page |
| Courses | Repeat the same tests on /courses | Same behavior |

**The DRY test:** open	, change the text, and refresh a Student page and a Course

page. Both change, because the footer exists in one place only. Then check the routes:

You should see 14 routes for

and

(7 each).

# **Step 17: Push the Code to GitHub**

Check the project status:

Add the files and commit:

Push the branch:

Use the branch name specified in the **GitHub Guideline** if your repository uses a different naming convention. Verify your code on GitHub.

# **Final Project Structure**

At the end of Workshop 2, your project should contain at least:

| │ | │ | └── footer.blade.php |
| :---- | :---- | :---- |
| │ | ├── | components/ |
| │ | │ | ├── alert.blade.php |
| │ | │ | ├── delete-form.blade.php |
| │ | │ | ├── form-input.blade.php |
| │ | │ | └── form-textarea.blade.php |
| │ | ├── | student/ |
| │ | │ | ├── \_form.blade.php |
| │ | │ | ├── list.blade.php |
| │ | │ | ├── create.blade.php |
| │ | │ | ├── detail.blade.php |
| │ | │ | └── edit.blade.php |
| │ | └── | course/ |
| │ |  | ├── \_form.blade.php |
| │ |  | ├── list.blade.php |
| │ |  | ├── create.blade.php |
| │ |  | ├── detail.blade.php |
| │ |  | └── edit.blade.php |
| │ |  |  |

Your final	should look similar to:

# **What You Have Built**

The application behaves like Week 1, but the request flow is now organized:

# **Workshop 2 Completion Criteria**

You have completed Workshop 2 when:

and	exist with all seven resource methods.

contains no closures for Students or Courses.

* Routes are registered with

* All links and forms use

  .

with route names.

* Route model binding is used in	,	,

* Validation rules are written once in a private	method in each controller.

* The Student model has a

cast and an

accessor.

* All pages extend	and show the navbar and the footer.

* Success messages, form fields and delete buttons use Blade components.

* Create and edit share one	partial for both Students and Courses.

* All Student and Course CRUD operations still work.

* Code has been committed and pushed to GitHub.

# **Common Errors and Fixes**

| Error | Likely cause | Fix |
| :---- | :---- | :---- |
| Route \[students.index\] not defined | Route not registered or not named | Check Route::resource(...) in web.php ; run  php artisan route:list |
|  Route \[courses.index\] not defined (on every page) | The Courses link was added to the navbar before the Course route exists | Add Route::resource('courses', ...) in Step 15.1 first |
|  Target class \[StudentController\] does not exist | Missing import in web.php | Add use App\\Http\\Controllers\\StudentController; |
|  /students/create shows a 404 | Explicit routes: {id} route defined before create | Define /students/create before  /students/{id} |
| Class "Rule" not found | Missing import | Add use Illuminate\\Validation\\Rule; |
| View \[partials.navbar\] not found | File missing or in the wrong folder | Create resources/views/partials/navbar.blade.php |
| Unable to locate a class or view for component \[form-input\] | Component file missing or misspelled | File must be resources/views/components/form-input.blade.php |
| Page looks unstyled |  public/css/app.css missing | Create the file and refresh with Ctrl \+ F5 |
|  419 Page Expired | Missing @csrf in a form | Add @csrf inside the form |
| Add \[name\] to fillable property | Field missing from \$fillable | Add the field to the model |
| Date of birth is empty on the edit form | The input value is not formatted | Use \$student-\>date\_of\_birth?-\>format('Y-m-d') |

| Error | Likely cause | Fix |
| :---- | :---- | :---- |
| Edit always says email already taken | Ignore rule not applied | Use Rule::unique('students')-\>ignore(\$student?-\>id) and pass \$student to rules() in update |
| Old views or components still appear | Cached data | Run php artisan optimize:clear |

